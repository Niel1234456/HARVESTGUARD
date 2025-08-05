<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Supply;
use App\Models\SupplyRequest;
use App\Models\Equipment;
use App\Models\BorrowRequest;
use App\Models\Pdf as PdfModel;
use App\Models\News;
use App\Models\Event;
use App\Models\Notification;



class FarmerDashboardController extends Controller
{
    public function index()
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();

        // Get recent news and events
        $recentNews = News::orderBy('created_at', 'desc')
            ->take(3)
            ->get(['title', 'image']);
    
        $recentEvents = Event::orderBy('created_at', 'desc')
            ->take(3)
            ->get(['title', 'description', 'start', 'end', 'start_time', 'end_time']);
         
    $supplyRequests = SupplyRequest::where('farmer_id', $farmer->id)
        ->with('supply:id,name') 
        ->orderBy('created_at', 'desc')
        ->get(['supply_id', 'quantity', 'status']); 
    $totalSupplyRequests = $supplyRequests->sum('quantity');

    $borrowRequests = BorrowRequest::where('farmer_id', $farmer->id)
        ->with('equipment:id,name') 
        ->orderBy('created_at', 'desc')
        ->get(['equipment_id', 'quantity', 'status']); 
    $totalBorrowRequests = $borrowRequests->sum('quantity'); 

    $supplyRequests = SupplyRequest::where('farmer_id', $farmer->id)
    ->with('supply:id,name') 
    ->orderBy('created_at', 'desc') 
    ->take(2) 
    ->get(['supply_id', 'quantity', 'status']);

$borrowRequests = BorrowRequest::where('farmer_id', $farmer->id)
    ->with('equipment:id,name') 
    ->orderBy('created_at', 'desc') 
    ->take(2) 
    ->get(['equipment_id', 'quantity', 'status']);


    return view('farmer.dashboard', compact('notifications', 'recentNews', 'recentEvents', 'supplyRequests', 'borrowRequests', 'totalSupplyRequests', 'totalBorrowRequests'));

    }
    

    public function supplies()
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();
    
        $supplies = Supply::paginate(7); 
    
        return view('farmer.supplies', compact('notifications', 'supplies'));
    }

    public function showRequestForm()
    {$farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();  
        $supplies = Supply::all();
        return view('farmer.supply-request-form', compact('notifications', 'supplies'));
    }
    

    public function sendRequest(Request $request)
    {
        $request->validate([
            'supply_id' => 'required|exists:supplies,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255',
        ]);
    
        $supply = Supply::findOrFail($request->input('supply_id'));
    
        if ($supply->quantity < $request->input('quantity')) {
            return redirect()->back()->with('error', 'Not enough supply available.');
        }
    
        $supplyRequest = SupplyRequest::create([
            'supply_id' => $request->input('supply_id'),
            'quantity' => $request->input('quantity'),
            'farmer_id' => Auth::id(),
            'description' => $request->input('description'),
            'status' => 'pending',
            'is_released' => 'No',
            'requesting_number' => uniqid('REQ-'), // Add unique requesting number
        ]);
    
        $supply->quantity -= $request->input('quantity');
        $supply->save();
    
        Notification::create([
            'farmer_id' => Auth::id(), 
            'title' => 'New Supply Request',
            'message' => 'Ang Request ID no. ' . $supplyRequest->requesting_number . ' ni Farmer ' . Auth::user()->name . 
            ' ay ' . $request->input('quantity') . ' units na ' . $supply->name . '.',
            'is_read' => false,
            'type' => 'supply', 

        ]); 
    
        return redirect()->route('farmer.supplies')->with('success', 'Matagumpay na naisumite ang kahilingan! hintayin ang pag update ng Admin.');
    }
    

   
    public function equipment()
    {$farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();  
        
        $equipment = Equipment::paginate(6);
        return view('farmer.equipment', compact('notifications', 'equipment'));
    }

    public function showBorrowForm()
    {$farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();  
        $equipment = Equipment::all();
        return view('farmer.borrow-request-form', compact('notifications', 'equipment'));
    }
    

   public function store(Request $request)
   {

    $request->validate([
        'equipment_id' => 'required|exists:equipment,id',
        'quantity' => 'required|integer|min:1',
        'description' => 'nullable|string|max:255',
        'return_date' => 'nullable|date|after_or_equal:today',
    ]);

       $equipment = Equipment::findOrFail($request->equipment_id);
       if ($equipment->quantity < $request->quantity) {
           return back()->with('error', 'Not enough equipment available.');
       }

       $borrowRequest = BorrowRequest::create([
        'farmer_id' => Auth::id(),
        'equipment_id' => $request->equipment_id,
        'quantity' => $request->quantity,
        'description' => $request->description,
        'return_date' => $request->return_date,
        'status' => 'pending', 
        'borrow_number' => uniqid('BR-'), 

    ]);

        $equipment->quantity -= $request->quantity;
        $equipment->save();

        Notification::create([
            'farmer_id' => Auth::id(), 
            'title' => 'New Borrow Request',
            'message' => 'Ang Borrow ID no. ' . $borrowRequest->borrow_number . ' ni Farmer ' . Auth::user()->name . 
                ' ay ' . $request->quantity . ' units na ' . $equipment->name . '.',
            'is_read' => false,
            'type' => 'borrow', 

        ]);


        return redirect()->back()->with([
            'success' => 'Matagumpay na naisumite ang paghiram ng gamit! Hintayin ang pag-update ng Admin.',
            'borrowRequest' => [
                'equipment_name' => $equipment->name,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'return_date' => $request->return_date,
                'borrow_number' => $borrowRequest->borrow_number,
            ]
        ]);
        }

    public function showUpdateForm()
    {$farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();  
        return view('farmer.update', compact('notifications'));
    }

}
