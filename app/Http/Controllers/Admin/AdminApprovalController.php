<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminApprovalController extends Controller
{
    public function index(Request $request)
    {
        
        $supplyRequests = SupplyRequest::with('supply', 'farmer')
            ->where('is_released', 'No') 
            ->where('status', '!=', 'rejected'); 
    
        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $supplyRequests->where(function ($query) use ($search) {
                $query->where('requesting_number', 'LIKE', "%$search%")
                    ->orWhere('id', 'LIKE', "%$search%")
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhere('quantity', 'LIKE', "%$search%")
                    ->orWhereHas('supply', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('farmer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }
    
        // Sorting functionality
        if ($request->has('sort') && $request->has('order')) {
            $supplyRequests->orderBy($request->get('sort'), $request->get('order'));
        }
    
        // Pagination
        $supplyRequests = $supplyRequests->paginate(10);
    
        // Fetch notifications
        $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();
    
        // Return view with supply requests and notifications
        return view('admin.approval.index', compact('supplyRequests', 'notifications', 'request'));
    }
    

    public function approveRequest($id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->status = 'approved';
        $supplyRequest->save();

        Notification::create([
            'message' => ' Ang Request ID ' . $supplyRequest->requesting_number. ' ay na aprubahan at maari mo nang makuha.',
            'type' => 'approval',
            'farmer_id' => $supplyRequest->farmer_id,

        ]);

        return redirect()->route('admin.admin.approval.index')->with('success', 'Requested Supplies Successfully Approved!');
    }

    public function rejectRequest($id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->status = 'rejected';
        $supplyRequest->is_released = 'No'; // Ensure rejected requests are not marked as released
        $supplyRequest->save();
    
        Notification::create([
            'message' => 'Ang Request ID ' . $supplyRequest->requesting_number. ' ay hindi na aprubahan sa mga kadahilanan kulang o hindi na sapat ang bilang ng supplies.',
            'type' => 'rejection',
            'farmer_id' => $supplyRequest->farmer_id,]);
    
        return redirect()->route('admin.admin.approval.index')->with('delete', 'Requested Supplies is Rejected!');
    }
    

    public function markAsReleased($id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->is_released = 'Yes';  
        $supplyRequest->save();

        Notification::create([
            'message' => 'Ang Request ID. ' . $supplyRequest->requesting_number. ' ay naibigay na, at matatanggal sa Approval page.',
            'type' => 'release',
            'farmer_id' => $supplyRequest->farmer_id,
        ]); 

        return redirect()->route('admin.admin.approval.index')->with('update', 'Requested Supplies is Successfully Released');
    }

    public function historyRecords(Request $request)
    {
        // Query to fetch supply requests with related models
        $releasedRequests = SupplyRequest::with('supply', 'farmer')
            ->where(function ($query) {
                $query->whereIn('status', ['approved', 'rejected'])
                      ->orWhere('is_released', 'Yes'); // Include only released requests
            });

        // Apply search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $releasedRequests->where(function ($query) use ($search) {
                $query->where('requesting_number', 'LIKE', "%$search%")
                    ->orWhere('id', 'LIKE', "%$search%")
                    ->orWhere('status', 'LIKE', "%$search%")
                    ->orWhere('is_released', 'LIKE', "%$search%")
                    ->orWhere('quantity', 'LIKE', "%$search%")
                    ->orWhereHas('supply', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('farmer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        // Apply sorting
        if ($request->has('sort') && $request->has('order')) {
            $releasedRequests->orderBy($request->get('sort'), $request->get('order'));
        }

        // Pagination
        $releasedRequests = $releasedRequests->paginate(10);

        // Fetch notifications
        $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();

        return view('admin.history-records', compact('releasedRequests', 'notifications', 'request'));
    }
}
