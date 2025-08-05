<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyRequest;
use App\Models\BorrowRequest;
use App\Models\Farmer;
use App\Models\Supply;
use App\Models\Equipment;
use App\Models\ImageAnalysis;
use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class InsightController extends Controller
{
    public function index()
    {
        $data = $this->fetchInsightData();
        
        $notifications = Notification::orderBy('created_at', 'desc')
            ->take(20)
            ->get();
    
            return view('admin.insight.index', array_merge($data, [
                'notifications' => $notifications,
                'supplyPercentage' => $data['supplyStats']['available'] / $data['supplyStats']['total'] * 100,
                'equipmentPercentage' => $data['equipmentStats']['available'] / $data['equipmentStats']['total'] * 100
            ]));    }
    

    public function fetchInsightData()
    {
        $supplyRequests = SupplyRequest::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
            ->groupBy('week')
            ->get();
        
        $borrowRequests = BorrowRequest::selectRaw('WEEK(created_at) as week, COUNT(*) as count')
            ->groupBy('week')
            ->get();

        $totalSupplies = Supply::sum('quantity');
        $totalRequestedSupplies = SupplyRequest::sum('quantity');
        $availableSupplies = $totalSupplies - $totalRequestedSupplies;
        $supplyPercentage = $totalSupplies > 0 ? ($availableSupplies / $totalSupplies) * 100 : 0;

        $totalEquipment = Equipment::sum('quantity');
        $totalBorrowedEquipment = BorrowRequest::sum('quantity');
        $availableEquipment = $totalEquipment - $totalBorrowedEquipment;
        $equipmentPercentage = $totalEquipment > 0 ? ($availableEquipment / $totalEquipment) * 100 : 0;

        $supplyStats = [
            'total' => $totalSupplies,
            'requested' => $totalRequestedSupplies,
            'available' => max(0, $availableSupplies),
        ];
        if ($supplyPercentage <= 20) {
            Notification::create([
                'message' => 'Available supplies have dropped below 20%.',
            ]);
        }
    
        if ($equipmentPercentage <= 20) {
            Notification::create([
                'message' => 'Available equipment has dropped below 20%.',
            ]);
        }

        $equipmentStats = [
            'total' => $totalEquipment,
            'borrowed' => $totalBorrowedEquipment,
            'available' => max(0, $availableEquipment),
        ];


        $farmersPerMonth = Farmer::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->get();
        
        $farmerActivity = Farmer::withCount(['borrowRequests', 'supplyRequests'])->get();

        $borrowerNames = $farmerActivity->pluck('name');
        $borrowerCounts = $farmerActivity->pluck('borrow_requests_count');
        $supplyerCounts = $farmerActivity->pluck('supply_requests_count');
        
        $mostRequestedSupply = SupplyRequest::selectRaw('supply_id, COUNT(*) as count')
            ->groupBy('supply_id')
            ->orderByRaw('COUNT(*) DESC')
            ->with('supply') 
            ->first();

        $leastRequestedSupply = SupplyRequest::selectRaw('supply_id, COUNT(*) as count')
            ->groupBy('supply_id')
            ->orderByRaw('COUNT(*) ASC')
            ->with('supply') 
            ->first();

        $mostBorrowedEquipment = BorrowRequest::selectRaw('equipment_id, COUNT(*) as count')
            ->groupBy('equipment_id')
            ->orderByRaw('COUNT(*) DESC')
            ->with('equipment') 
            ->first();

        $leastBorrowedEquipment = BorrowRequest::selectRaw('equipment_id, COUNT(*) as count')
            ->groupBy('equipment_id')
            ->orderByRaw('COUNT(*) ASC')
            ->with('equipment') 
            ->first();

        $mostRequestedSupplyPercentage = $totalRequestedSupplies > 0 ? ($mostRequestedSupply->count / $totalRequestedSupplies) * 100 : 0;
        $leastRequestedSupplyPercentage = $totalRequestedSupplies > 0 ? ($leastRequestedSupply->count / $totalRequestedSupplies) * 100 : 0;

        $mostBorrowedEquipmentPercentage = $totalBorrowedEquipment > 0 ? ($mostBorrowedEquipment->count / $totalBorrowedEquipment) * 100 : 0;
        $leastBorrowedEquipmentPercentage = $totalBorrowedEquipment > 0 ? ($leastBorrowedEquipment->count / $totalBorrowedEquipment) * 100 : 0;

        $totalImagesAnalyzed = ImageAnalysis::count();
        $mostCommonDiseases = ImageAnalysis::select('disease_name', DB::raw('count(*) as total'))
            ->groupBy('disease_name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $diseaseDistribution = ImageAnalysis::select('disease_name', DB::raw('count(*) as count'))
            ->groupBy('disease_name')
            ->get();

        $averageConfidenceLevels = ImageAnalysis::select('disease_name', DB::raw('AVG(average_confidence) as avg_confidence'))
            ->groupBy('disease_name')
            ->get();

        $healthyCount = ImageAnalysis::where('disease_name', 'Healthy')->count();
        $diseasedCount = $totalImagesAnalyzed - $healthyCount;

        $supplyRequestCounts = SupplyRequest::select('supply_id', DB::raw('COUNT(*) as count'))
            ->groupBy('supply_id')
            ->with('supply')
            ->get();

        $borrowRequestCounts = BorrowRequest::select('equipment_id', DB::raw('COUNT(*) as count'))
            ->groupBy('equipment_id')
            ->with('equipment')
            ->get();

        $combinedLabels = $supplyRequestCounts->pluck('supply.name')
            ->merge($borrowRequestCounts->pluck('equipment.name'))
            ->unique()
            ->values();

        $combinedSupplyData = $supplyRequestCounts->mapWithKeys(function ($request) {
            return [$request->supply->name => $request->count];
        });

        $combinedBorrowData = $borrowRequestCounts->mapWithKeys(function ($request) {
            return [$request->equipment->name => $request->count];
        });

        $combinedData = [
            'supplyRequests' => $combinedLabels->map(fn($label) => $combinedSupplyData[$label] ?? 0),
            'borrowRequests' => $combinedLabels->map(fn($label) => $combinedBorrowData[$label] ?? 0),
        ];

        return [
            'supplyRequests' => $supplyRequests,
            'borrowRequests' => $borrowRequests,
            'supplyStats' => $supplyStats,
            'equipmentStats' => $equipmentStats,
            'farmersPerMonth' => $farmersPerMonth,
            'farmerActivity' => $farmerActivity,
            'mostRequestedSupply' => [
                'name' => $mostRequestedSupply->supply->name,
                'count' => $mostRequestedSupply->count,
                'percentage' => number_format($mostRequestedSupplyPercentage, 2)
            ],
            'leastRequestedSupply' => [
                'name' => $leastRequestedSupply->supply->name,
                'count' => $leastRequestedSupply->count,
                'percentage' => number_format($leastRequestedSupplyPercentage, 2)
            ],
            'mostBorrowedEquipment' => [
                'name' => $mostBorrowedEquipment->equipment->name,
                'count' => $mostBorrowedEquipment->count,
                'percentage' => number_format($mostBorrowedEquipmentPercentage, 2)
            ],
            'leastBorrowedEquipment' => [
                'name' => $leastBorrowedEquipment->equipment->name,
                'count' => $leastBorrowedEquipment->count,
                'percentage' => number_format($leastBorrowedEquipmentPercentage, 2)
            ],
            'totalImagesAnalyzed' => $totalImagesAnalyzed,
            'mostCommonDiseases' => $mostCommonDiseases,
            'diseaseDistribution' => $diseaseDistribution,
            'averageConfidenceLevels' => $averageConfidenceLevels,
            'healthyCount' => $healthyCount,
            'diseasedCount' => $diseasedCount,
            'supplyRequestCounts' => $supplyRequestCounts,
            'borrowRequestCounts' => $borrowRequestCounts,
            'combinedLabels' => $combinedLabels,
            'combinedData' => $combinedData,
        ];
    }

    public function createReport()
    {
        $data = $this->fetchInsightData();

        $pdf = PDF::loadView('admin.insight.report', $data);
        $fileName = 'insight_report_' . now()->format('Ymd_His') . '.pdf';
        $filePath = 'public/pdfs/' . $fileName;

        Storage::put($filePath, $pdf->output());
        Notification::create([
            'message' => 'A new insight report "' . $fileName . '" has been created.',
        ]);
    Notification::create([
        'message' => 'A new insight report "' . $fileName . '" has been created.',
    ]);
        return $pdf->download('insight_report.pdf');
    }

    public function listReports(Request $request) 
    {
        $pdfFiles = collect(Storage::files('public/pdfs'))
            ->filter(function ($file) {
                return str_ends_with($file, '.pdf');
            })
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'url' => Storage::url($file),
                    'created_at' => Storage::lastModified($file),
                ];
            });
    
        if ($request->has('search')) {
            $search = $request->get('search');
            $pdfFiles = $pdfFiles->filter(function ($file) use ($search) {
                return str_contains(strtolower($file['name']), strtolower($search));
            });
        }
    
        if ($request->has('sort') && $request->has('order')) {
            $sortBy = $request->get('sort');
            $order = $request->get('order');
    
            $pdfFiles = $pdfFiles->sortBy(function ($file) use ($sortBy) {
                return $file[$sortBy];
            }, SORT_REGULAR, $order === 'desc');
        }
    $perPage = 10; 
    $page = $request->get('page', 1);
    $total = $pdfFiles->count();
    $pdfFiles = $pdfFiles->slice(($page - 1) * $perPage, $perPage)->values();

    $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();

    return view('admin.insight.list_reports', [
        'pdfFiles' => new \Illuminate\Pagination\LengthAwarePaginator(
            $pdfFiles,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        ),
        'notifications' => $notifications
    ]);
    }
     


    public function deleteReport($fileName)
    {
        $filePath = 'public/pdfs/' . $fileName;
    
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
                Notification::create([
                'message' => 'The report "' . $fileName . '" has been deleted.',
            ]);
    
            return redirect()->back()->with('success', 'File deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'File not found.');
    }
    
}
