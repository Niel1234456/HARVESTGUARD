<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExistingFarmer;
use App\Models\Farmer;
use App\Models\Notification;
use Illuminate\Http\Request;

class ExistingFarmerController extends Controller
{
    public function index(Request $request)
    {
        
        $notifications = Notification::orderBy('created_at', 'desc')
        ->take(20)
        ->get();

        $query = ExistingFarmer::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('middle_initial', 'LIKE', "%{$search}%");
        }

        // Sort functionality
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'name_asc':
                    $query->orderBy('firstname', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('firstname', 'desc');
                    break;
                case 'middle_initial_asc':
                    $query->orderBy('middle_initial', 'asc');
                    break;
                case 'middle_initial_desc':
                    $query->orderBy('middle_initial', 'desc');
                    break;
                case 'age_asc':
                    $query->orderBy('age', 'asc');
                    break;
                case 'age_desc':
                    $query->orderBy('age', 'desc');
                    break;
                case 'email_asc':
                    $query->orderBy('email', 'asc');
                    break;
                case 'email_desc':
                    $query->orderBy('email', 'desc');
                    break;
                default:
                    // Default sorting (optional)
                    $query->orderBy('firstname', 'asc');
                    break;
            }
        }

        $existingFarmers = $query->paginate(10); // Adjust pagination as needed

        return view('admin.existingFarmers.index', compact('existingFarmers', 'notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:255',
            'age' => 'required|integer',
            'birthday' => 'required|date',
            'email' => 'required|email|max:255|unique:existing_farmers,email',
            'phone_number' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
        ]);

        ExistingFarmer::create($request->all());

        return redirect()->route('admin.existingFarmers.index')->with('success', 'Existing farmer added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:255',
            'age' => 'required|integer',
            'birthday' => 'required|date',
            'email' => 'required|email|max:255|unique:existing_farmers,email,' . $id,
            'phone_number' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
        ]);

        $existingFarmer = ExistingFarmer::findOrFail($id);
        $existingFarmer->update($request->all());

        return redirect()->route('admin.existingFarmers.index')->with('success', 'Existing farmer updated successfully.');
    }

    public function transferToFarmers($id)
    {
        $existingFarmer = ExistingFarmer::findOrFail($id);

        // Create the new Farmer record
        Farmer::create([
            'firstname' => $existingFarmer->firstname,
            'lastname' => $existingFarmer->lastname,
            'middle_initial' => $existingFarmer->middle_initial,
            'age' => $existingFarmer->age,
            'birthday' => $existingFarmer->birthday,
            'email' => $existingFarmer->email,
            'phone_number' => $existingFarmer->phone_number,
            'address_1' => $existingFarmer->address_1,
            'address_2' => $existingFarmer->address_2,
        ]);

        // Optionally delete the existing farmer record
        $existingFarmer->delete();

        return redirect()->route('admin.existingFarmers.index')->with('success', 'Farmer transferred successfully.');
    }

    public function destroy($id)
    {
        $existingFarmer = ExistingFarmer::findOrFail($id);
        $existingFarmer->delete();

        return redirect()->route('admin.existingFarmers.index')->with('success', 'Existing farmer deleted successfully.');
    }
}
