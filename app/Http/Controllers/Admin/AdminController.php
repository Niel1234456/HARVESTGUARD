<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Supply;
use App\Models\Farmer;
use App\Models\Note;
use App\Models\Admin;
use App\Models\Event;
use App\Models\BorrowRequest;
use App\Models\SupplyRequest;
use App\Models\News;
use App\Models\Notification;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSupplies = Supply::sum('quantity');

        $totalEquipment = Equipment::sum('quantity');

        $recentFarmers = Farmer::orderBy('created_at', 'desc')
                                ->take(4)
                                ->get(['first_name', 'profile_picture']);

        $recentNews = News::orderBy('created_at', 'desc')
                          ->take(3)
                          ->get(['title', 'image']);


        $recentEvents = Event::orderBy('created_at', 'desc')
                             ->take(3)
                             ->get(['title', 'description', 'start', 'end', 'start_time', 'end_time']);


        $notifications = Notification::orderBy('created_at', 'desc', 'approval')
            ->take(20)
            ->get();

        return view('dashboard', [
            'notifications' => $notifications, 
            'totalSupplies' => $totalSupplies,
            'totalEquipment' => $totalEquipment,
            'recentFarmers' => $recentFarmers,
            'recentNews' => $recentNews,
            'recentEvents' => $recentEvents 
        ]);
    }

public function index(Request $request)
{
    $query = Farmer::with(['supplyRequests', 'borrowRequests']);

    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', '%' . $search . '%')
              ->orWhere('birth_date', 'like', '%' . $search . '%')
              ->orWhere('phone', 'like', '%' . $search . '%');
        });
    }

    if ($request->has('sort_by')) {
        $sortOption = $request->input('sort_by');
        switch ($sortOption) {
            case 'first_name_asc':
                $query->orderBy('first_name', 'asc');
                break;
            case 'birth_date_desc':
                $query->orderBy('birth_date', 'desc');
                break;
            case 'phone_desc':
                $query->orderBy('phone', 'desc');
                break;
            default:
                $query->orderBy('first_name', 'asc'); 
                break;
        }
    } else {
        $query->orderBy('first_name', 'asc'); 
    }
    $notifications = Notification::orderBy('created_at', 'desc', 'approval')->take(20)->get();

    $registeredFarmers = $query->paginate(10); 

    return view('admin.farmers', compact('registeredFarmers', 'notifications'));

  $registeredFarmers = Farmer::with('supplyRequests')->get();
  $registeredFarmers = Farmer::with('borrowRequests')->get();
  return view('admin.farmers', compact('registeredFarmers'));
        
  $farmers = Farmer::all();
  return view('admin.dashboard', compact('farmers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
        ]);
        Farmer::create([
            'first_name' => $request->first_name,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
        ]);

        return redirect()->route(' admin.admin.store') ->with('success', 'Farmer added successfully.');
    }

    public function deleteFarmer($id)
    {
        $farmer = Farmer::find($id);
        if (!$farmer) {
            return redirect()->back()->with('error', 'Farmer not found.');
        }
    
        $farmer->delete();
        return redirect()->back()->with('success', 'Farmer deleted successfully.');
    }
    
    public function viewReport($id)
    {
        $farmer = Farmer::findOrFail($id);
    
        // Fetch reports related to this farmer
        $pdfFiles = Storage::files("reports/farmer_{$id}");
    
        $fileDetails = [];
        foreach ($pdfFiles as $file) {
            $filePath = storage_path('app/' . $file);
            $fileDetails[] = [
                'name' => basename($file),
                'created_at' => filemtime($filePath),
            ];
        }
    
        $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();
    
        return view('admin.admin.view_report', [
            'farmer' => $farmer,
            'fileDetails' => $fileDetails,
            'notifications' => $notifications
        ]);
    }
    
        public function listGeneratedReports(Request $request)
        {
            $pdfFiles = Storage::files('reports');
        
            $fileDetails = [];
            foreach ($pdfFiles as $file) {
                $filePath = storage_path('app/' . $file);
                $fileDetails[] = [
                    'name' => basename($file),
                    'created_at' => filemtime($filePath),
                ];
            }
        
            // Search
            if ($request->has('search')) {
                $search = $request->get('search');
                $fileDetails = array_filter($fileDetails, function ($file) use ($search) {
                    return stripos($file['name'], $search) !== false;
                });
            }
        
            // Sorting
            if ($request->has('sort') && $request->has('order')) {
                usort($fileDetails, function ($a, $b) use ($request) {
                    $sortBy = $request->get('sort');
                    $order = $request->get('order');
                    if ($order === 'asc') {
                        return strcmp($a[$sortBy], $b[$sortBy]);
                    } else {
                        return strcmp($b[$sortBy], $a[$sortBy]);
                    }
                });
            }
        
            // Pagination logic
            $perPage = 10; // Number of items per page
            $page = $request->get('page', 1);
            $total = count($fileDetails);
        
            $fileDetails = array_slice($fileDetails, ($page - 1) * $perPage, $perPage);
        
            $fileDetailsPaginator = new LengthAwarePaginator(
                $fileDetails,
                $total,
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        
            $notifications = Notification::orderBy('created_at', 'desc', 'approval')->take(20)->get();
        
            return view('admin.pdf_list', [
                'fileDetails' => $fileDetailsPaginator,
                'notifications' => $notifications,
            ]);
        }
     public function downloadReport($fileName)
        {
            $notifications = Notification::orderBy('created_at', 'desc', 'approval')->take(20)->get();
                if (Storage::exists('reports/' . $fileName)) {
                return response()->download(storage_path('app/reports/' . $fileName));
            } else {
                abort(404, 'File not found.');
            }
        }
    
        public function deleteReport($fileName)
        {
            if (Storage::exists('reports/' . $fileName)) {
                Storage::delete('reports/' . $fileName);
                Notification::create([
                    'message' => 'Report "' . $fileName . '" has been deleted.',
                ]);
             return redirect()->route('admin.listGeneratedReports')->with('success', 'Report deleted successfully.');
            } else {
                abort(404, 'File not found.');
            }
        }
    }
