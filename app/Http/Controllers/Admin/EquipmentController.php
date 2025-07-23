<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Add this at the top

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $query = Equipment::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('unit', 'LIKE', "%{$search}%");
        }

        // Sort functionality
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'quantity_asc':
                    $query->orderBy('quantity', 'asc');
                    break;
                case 'quantity_desc':
                    $query->orderBy('quantity', 'desc');
                    break;
                case 'unit_asc':
                    $query->orderBy('unit', 'asc');
                    break;
                case 'unit_desc':
                    $query->orderBy('unit', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        $equipments = $query->paginate(5);

        return view('admin.equipment.index', compact('equipments', 'notifications'));
    }

    public function create()
    {
        return view('admin.equipment.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            $imageName = null;
        }

        $equipment = Equipment::create(array_merge($request->all(), ['image' => $imageName]));

        Notification::create([
            'message' => 'New equipment "' . $equipment->name . '" has been added with quantity: ' . $equipment->quantity,
        ]);

        return redirect()->route('admin.equipment.index')->with('success', 'Successfully Created New Equipment.');
        
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        return view('admin.equipment.edit', compact('equipment'));
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:20048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            // Delete the old image
            if ($equipment->image) {
                unlink(public_path('images') . '/' . $equipment->image);
            }
        } else {
            $imageName = $equipment->image;
        }

        $equipment->update(array_merge($request->all(), ['image' => $imageName]));

        Notification::create([
            'message' => 'Equipment "' . $equipment->name . '" has been updated with new quantity: ' . $equipment->quantity,
        ]);

        return redirect()->route('admin.equipment.index')->with('update', 'Equipment Updated successfully .');
    }

    public function destroy($id, Request $request)
    {
        $equipment = Equipment::findOrFail($id);
    
        // Check if the equipment is involved in any transaction
        $isUsed = DB::table('borrow_requests')
            ->where('equipment_id', $equipment->id)
            ->whereIn('status', ['borrowed', 'pending'])
            ->exists();
    
        if ($isUsed && !$request->has('force')) {
            session()->flash('equipment_id', $id);
            return redirect()->route('admin.equipment.index')->with('error', 'This equipment is currently in use and cannot be deleted.');
        }
    
        // If forced deletion is requested, remove all related transactions & delete permanently
        if ($request->has('force')) {
            DB::table('borrow_requests')->where('equipment_id', $equipment->id)->delete(); // Remove related transactions
    
            if ($equipment->image) {
                $imagePath = public_path('images') . '/' . $equipment->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image permanently
                }
            }
    
            $equipment->delete(); // Hard delete from database
            return redirect()->route('admin.equipment.index')->with('delete', 'Equipment and related transactions were permanently deleted.');
        }
    
        // Normal deletion (soft delete)
        session()->put('deleted_equipment', [
            'id' => $equipment->id,
            'name' => $equipment->name,
            'quantity' => $equipment->quantity,
            'unit' => $equipment->unit,
            'image' => $equipment->image,
        ]);
    
        // Move image to backup folder
        if ($equipment->image) {
            $imagePath = public_path('images') . '/' . $equipment->image;
            $backupImagePath = public_path('deleted_images') . '/' . $equipment->image;
    
            if (file_exists($imagePath)) {
                if (!file_exists(public_path('deleted_images'))) {
                    mkdir(public_path('deleted_images'), 0777, true);
                }
                rename($imagePath, $backupImagePath);
            }
        }
    
        // Soft delete the equipment
        $equipment->delete();
    
        return redirect()->route('admin.equipment.index')
            ->with('delete', 'Equipment deleted successfully. <a href="' . route('admin.equipment.restore') . '" id="undo-delete">Undo</a>');
    }
    
    public function forceDelete($id)
    {
        $equipment = Equipment::findOrFail($id);
    
        // Remove permanently
        if ($equipment->image) {
            $imagePath = public_path('images') . '/' . $equipment->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        $equipment->delete();
    
        return redirect()->route('admin.equipment.index')->with([
            'delete' => 'Equipment was permanently deleted.',
            'force_deleted' => true // Used to show only the success message, no undo
        ]);
    }
    
    
    public function restore()
    {
        $deletedEquipment = session()->get('deleted_equipment');
    
        if (!$deletedEquipment) {
            return redirect()->route('admin.equipment.index')->with('error', 'No equipment to restore.');
        }
    
        // Restore the image from backup if needed
        if (!empty($deletedEquipment['image'])) {
            $imagePath = public_path('images') . '/' . $deletedEquipment['image'];
            $backupImagePath = public_path('deleted_images') . '/' . $deletedEquipment['image'];
    
            if (!file_exists($imagePath) && file_exists($backupImagePath)) {
                copy($backupImagePath, $imagePath);
            }
        }
    
        // Restore the equipment
        Equipment::create([
            'id' => $deletedEquipment['id'],
            'name' => $deletedEquipment['name'],
            'quantity' => $deletedEquipment['quantity'],
            'unit' => $deletedEquipment['unit'],
            'image' => $deletedEquipment['image'],
        ]);
    
        // Clear session after restoring
        session()->forget('deleted_equipment');
    
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment restored successfully.');
    }
    
}    
