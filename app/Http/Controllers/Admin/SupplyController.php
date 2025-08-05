<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supply;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SupplyController extends Controller
{
    public function index(Request $request)
    {

        $notifications = Notification::orderBy('created_at', 'desc')
        ->take(20)
        ->get();

        $query = Supply::query();

        if ($request->has('search') && $request->input('search') !== '') {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('unit', 'like', '%' . $search . '%');
            });
        }

        // Sorting functionality
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
                    // Default sorting (if needed)
                    $query->orderBy('name', 'asc');
                    break;
            }
        }
 
        // Paginate the results
        $supplies = $query->paginate(5);

        return view('admin.supplies.index', compact('supplies', 'notifications'));
    }

    public function create()
    {
        return view('admin.supplies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'image' => 'required|nullable|image|mimes:jpeg,png,jpg,gif|max:20048',
        ]);
    
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        } else {
            $imageName = null;
        }
    
        $supply = Supply::create(array_merge($request->all(), ['image' => $imageName]));
    
        Notification::create([
            'message' => 'New supply "' . $supply->name . '" has been added with quantity: ' . $supply->quantity,
        ]);
    
        return redirect()->route('admin.supplies.index')->with('success', 'Successfully Created New Supply.');
    }
    
    public function update(Request $request, Supply $supply)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20048',
        ]);
    
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
    
            if ($supply->image) {
                unlink(public_path('images') . '/' . $supply->image);
            }
        } else {
            $imageName = $supply->image;
        }
        $supply->update(array_merge($request->all(), ['image' => $imageName]));
        Notification::create([
            'message' => 'Supply "' . $supply->name . '" has been updated with new quantity: ' . $supply->quantity,
        ]);
    
        return redirect()->route('admin.supplies.index')->with('update', 'Supply Updated Successfully .');
    }
    public function destroy($id, Request $request)
    {
        $supply = Supply::findOrFail($id);
    
        // Check if the supply is involved in any transaction
        $isUsed = DB::table('supply_requests')
            ->where('supply_id', $supply->id)
            ->whereIn('status', ['completed', 'pending', 'requested'])
            ->exists();
    
        if ($isUsed && !$request->has('force')) {
            session()->flash('supply_id', $id);
            return redirect()->route('admin.supplies.index')->with('error', 'This supply is currently in use and cannot be deleted.');
        }
    
        // If forced deletion is requested, remove all transactions & delete permanently
        if ($request->has('force')) {
            DB::table('supply_requests')->where('supply_id', $supply->id)->delete(); // Remove from transactions
    
            if ($supply->image) {
                $imagePath = public_path('images') . '/' . $supply->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image permanently
                }
            }
    
            $supply->delete(); // Hard delete from database
            return redirect()->route('admin.supplies.index')->with('delete', 'Supply and related transactions were permanently deleted.');
        }
    
        // Normal deletion (soft delete)
        session()->put('deleted_supply', [
            'id' => $supply->id,
            'name' => $supply->name,
            'quantity' => $supply->quantity,
            'unit' => $supply->unit,
            'image' => $supply->image,
        ]);
    
        // Move image to backup folder
        if ($supply->image) {
            $imagePath = public_path('images') . '/' . $supply->image;
            $backupImagePath = public_path('deleted_images') . '/' . $supply->image;
    
            if (file_exists($imagePath)) {
                if (!file_exists(public_path('deleted_images'))) {
                    mkdir(public_path('deleted_images'), 0777, true);
                }
                rename($imagePath, $backupImagePath);
            }
        }
    
        $supply->delete();
    
        return redirect()->route('admin.supplies.index')
            ->with('delete', 'Supply deleted successfully. <a href="' . route('admin.supplies.restore') . '" id="undo-delete">Undo</a>');
    }
    
    public function forceDelete($id)
    {
        $supply = Supply::findOrFail($id);
    
        if ($supply->image) {
            $imagePath = public_path('images') . '/' . $supply->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    
        $supply->delete();
    
        return redirect()->route('admin.supplies.index')->with([
            'delete' => 'Supply was permanently deleted.',
            'force_deleted' => true
        ]);
    }
    

public function restore()
{
    $deletedSupply = session()->get('deleted_supply');

    if (!$deletedSupply) {
        return redirect()->route('admin.supplies.index')->with('error', 'No supply to restore.');
    }

    if ($deletedSupply['image']) {
        $imagePath = public_path('images') . '/' . $deletedSupply['image'];
        $backupImagePath = public_path('deleted_images') . '/' . $deletedSupply['image'];

        if (!file_exists($imagePath) && file_exists($backupImagePath)) {
            copy($backupImagePath, $imagePath);
        }
    }

    Supply::create([
        'id' => $deletedSupply['id'],
        'name' => $deletedSupply['name'],
        'quantity' => $deletedSupply['quantity'],
        'unit' => $deletedSupply['unit'],
        'image' => $deletedSupply['image'],
    ]);

    session()->forget('deleted_supply');

    return redirect()->route('admin.supplies.index')->with('success', 'Supply restored successfully.');
}
}