<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Http\Request; 
use App\Models\Notification; 


class AdminEquipmentApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = BorrowRequest::with('equipment', 'farmer');
        
        $query->where('is_returned', 'No')
              ->where('status', '!=', 'rejected');  

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('borrow_number', 'LIKE', "%$search%")
                ->orWhere('id', 'LIKE', "%$search%") 
                ->orWhere('status', 'LIKE', "%$search%") 
                ->orWhere('is_released', 'LIKE', "%$search%") 
                ->orWhere('quantity', 'LIKE', "%$search%") 
                ->orWhere('is_returned', 'LIKE', "%$search%") 
                ->orWhereHas('equipment', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('farmer', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
        }

        if ($request->has('sort') && $request->has('order')) {
            $query->orderBy($request->get('sort'), $request->get('order'));
        }

        $borrowRequests = $query->paginate(10);
        
        $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();
        
        return view('admin.equipment-approval.index', compact('borrowRequests', 'notifications', 'request'));
    }
    
    public function approveRequest($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);
        $borrowRequest->status = 'approved';
        $borrowRequest->save();
 
        Notification::create([
            'message' => 'Ang Borrow ID. ' . $borrowRequest->borrow_number. ' ay na aprubahan at maari mo nang makuha.',
            'type' => 'approval',
            'farmer_id' => $borrowRequest->farmer_id,
        ]);
    
        return redirect()->route('admin.admin.equipment.approval.index')->with('success', 'Borrowed Equipment Successfully Approved!');
    }
    public function rejectRequest($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);
        $borrowRequest->status = 'rejected';
        $borrowRequest->save();
        
        Notification::create([
            'message' => 'Ang Borrow ID.' . $borrowRequest->borrow_number. ' ay hindi na aprubahan sa mga kadahilanan kulang o hindi na sapat ang bilang ng kagamitan.',
            'type' => 'rejection',
            'farmer_id' => $borrowRequest->farmer_id,
        ]);
        
        return redirect()->route('admin.admin.equipment.approval.index')->with('delete', 'Borrowed Equipment is Rejected!');
    }
    
    
    public function markAsReleased($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);
    
        $borrowRequest->is_released = 'Yes';
        $borrowRequest->save();
    
        Notification::create([
            'message' => 'Ang Borrow ID. ' . $borrowRequest->borrow_number. ' ay naibigay na, at matatanggal sa Approval page',
            'type' => 'release',
            'farmer_id' => $borrowRequest->farmer_id,
        ]);
    
        return redirect()->route('admin.admin.equipment.approval.index')->with('update', 'Borrowed Equipment is Successfully Released');
    }
    
    public function returnEquipment($id)
    {
        $borrowRequest = BorrowRequest::findOrFail($id);
        
        $borrowRequest->is_returned = 'Yes'; 
        $borrowRequest->returned_at = now(); 
        $borrowRequest->save();
        
        $equipment = $borrowRequest->equipment;
        $equipment->quantity += $borrowRequest->quantity;
        $equipment->save();

        Notification::create([
            'message' => 'Ang Borrow ID. ' . $borrowRequest->borrow_number. ' na gamit ay naibalik na sa opisina ng city agriculture office',
            'type' => 'return',
            'farmer_id' => $borrowRequest->farmer_id,
         ]);
    
        return redirect()->route('admin.admin.equipment.approval.index')->with('return', 'Borrowed Equipment is Successfully Returned!');;
    } 

    public function historyRecords(Request $request)
    {
        $query = BorrowRequest::with('equipment', 'farmer')
            ->where(function ($q) {
                $q->where('is_returned', 'Yes')
                  ->orWhere('status', 'rejected'); 
            });

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('borrow_number', 'LIKE', "%$search%")
                    ->orWhere('id', 'LIKE', "%$search%") 
                    ->orWhere('status', 'LIKE', "%$search%") 
                    ->orWhere('is_released', 'LIKE', "%$search%") 
                    ->orWhere('quantity', 'LIKE', "%$search%") 
                    ->orWhere('is_returned', 'LIKE', "%$search%") 
                    ->orWhereHas('equipment', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    })
                    ->orWhereHas('farmer', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%$search%");
                    });
            });
        }

        if ($request->has('sort') && $request->has('order')) {
            $query->orderBy($request->get('sort'), $request->get('order'));
        }
    
        $returnedBorrowRequests = $query->paginate(10);
    
        $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();
    
        return view('admin.history-records-borrowed', compact('returnedBorrowRequests', 'notifications', 'request'));
    }
    
    
}  