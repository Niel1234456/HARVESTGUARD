<?php

namespace App\Http\Controllers\Farmer;

use Illuminate\Http\Request;
use App\Models\SupplyRequest;
use App\Models\BorrowRequest;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $farmer = Auth::guard('farmer')->user();
    
        $notifications = Notification::where('farmer_id', $farmer->id)
            ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    
        $sort = $request->get('sort', 'created_at'); 
        $order = $request->get('order', 'desc'); 
    
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'desc';
        }
    
        $search = $request->input('search');
    
        $supplyRequests = SupplyRequest::where('farmer_id', $farmer->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('requesting_number', 'LIKE', "%$search%")
                        ->orWhere('status', 'LIKE', "%$search%")
                        ->orWhere('quantity', 'LIKE', "%$search%")
                        ->orWhereHas('supply', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%");
                        });
                });
            })
            ->orderBy($sort, $order)
            ->with('supply:id,name')
            ->paginate(5);
    
        $borrowRequests = BorrowRequest::where('farmer_id', $farmer->id)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('borrow_number', 'LIKE', "%$search%")
                        ->orWhere('status', 'LIKE', "%$search%")
                        ->orWhere('quantity', 'LIKE', "%$search%")
                        ->orWhere('is_released', 'LIKE', "%$search%")
                        ->orWhere('is_returned', 'LIKE', "%$search%")
                        ->orWhereHas('equipment', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%");
                        });
                });
            })
            ->orderBy($sort, $order)
            ->with('equipment:id,name')
            ->paginate(5);
    
        $totalSupplyRequests = SupplyRequest::where('farmer_id', $farmer->id)->sum('quantity');
        $totalBorrowRequests = BorrowRequest::where('farmer_id', $farmer->id)->sum('quantity');
            return view('farmer.request', compact(
            'supplyRequests',
            'borrowRequests',
            'totalSupplyRequests',
            'totalBorrowRequests',
            'notifications',
            'sort',
            'order'
        ));

    }
    public function deleteSupplyRequest($id)
    {
        $request = SupplyRequest::findOrFail($id);
            $deletedRequests = session()->get('deleted_supply_requests', []);
        $deletedRequests[$id] = [
            'id' => $request->id,
            'farmer_id' => $request->farmer_id,
            'requesting_number' => $request->requesting_number ?? 'N/A', // Ensure key exists
            'status' => $request->status ?? 'Unknown',
            'quantity' => $request->quantity ?? 0,
            'supply_id' => $request->supply_id ?? null,
        ];
    
        session()->put('deleted_supply_requests', $deletedRequests);
    
        if ($request->supply) {
            $request->supply->increment('quantity', $request->quantity);
        }
    
        $request->delete();
    
        return redirect()->back()->with('success', 'Ang pag-delete ng Requested Supplies ay Matagumpay.');
    }
    
    
    public function undoDeleteSupplyRequest($id)
    {
        $deletedRequests = session()->get('deleted_supply_requests', []);
    
        if (!isset($deletedRequests[$id])) {
            return redirect()->back()->with('error', 'Walang nahanap na request upang ibalik.');
        }
    
        $restoredRequest = new SupplyRequest($deletedRequests[$id]);
        $restoredRequest->save();
    
        if ($restoredRequest->supply) {
            $restoredRequest->supply->decrement('quantity', $restoredRequest->quantity);
        }
    
        unset($deletedRequests[$id]);
        session()->put('deleted_supply_requests', $deletedRequests);
    
        return redirect()->back()->with('success', 'Matagumpay na naibalik ang request.');
    }
    
    public function permanentlyDeleteSupplyRequest($id)
    {
        $deletedRequests = session()->get('deleted_supply_requests', []);
    
        if (!isset($deletedRequests[$id])) {
            return redirect()->back()->with('error', 'Walang nahanap na request upang tanggalin.');
        }
    
        unset($deletedRequests[$id]);
        session()->put('deleted_supply_requests', $deletedRequests);
    
        return redirect()->back()->with('success', 'Matagumpay na tinanggal nang permanente ang request.');
    }
    

    public function deleteBorrowRequest($id)
    {
        $request = BorrowRequest::findOrFail($id);
    
        // Return quantity back to the equipment
        if ($request->equipment) {
            $request->equipment->increment('quantity', $request->quantity);
        }
    
        $request->delete();
    
        return redirect()->back()->with('success', 'Ang pag-delete ng Borrowed Equipment ay Matagumpay');
    }
} 