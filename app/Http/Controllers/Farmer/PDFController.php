<?php
namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Notification; 
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function generateReport(Request $request)
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::whereIn('type', ['supply', 'borrow', 'plantDiseasereport']) 
        ->where('farmer_id', $farmer->id) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get(); 
        $data = [
            'prediction' => $request->input('prediction'),
            'confidence' => $request->input('confidence'),
            'description' => $request->input('description'),
            'solution' => $request->input('solution'),
            'impact' => $request->input('impact'),

        ];

        $pdf = Pdf::loadView('farmer.pdf.report', $data);

        $fileName = 'plant-disease-report-' . Carbon::now()->format('Y-m-d-H-i-s') . '.pdf';

        $filePath = storage_path('app/reports/' . $fileName);
        $pdf->save($filePath);

        if ($request->has('sendToAdmin') && $request->input('sendToAdmin') == '1') {
        }

        Notification::create([
            'farmer_id' => $farmer->id,
            'message' => 'Your Pdf report' . $fileName . ' has been generated and sent to admin.',
            'type' => 'plantDiseasereport', 

        ]);
        return $pdf->download($fileName);
    }
}
