<?php
namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Notification; // Import the ImageAnalysis model
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function generateReport(Request $request)
    {
        $farmer = Auth::guard('farmer')->user();
        $notifications = Notification::whereIn('type', ['supply', 'borrow', 'plantDiseasereport']) // Filter by type
        ->where('farmer_id', $farmer->id) // Filter by the authenticated farmer's ID
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get(); 
        // Extract data from the request
        $data = [
            'prediction' => $request->input('prediction'),
            'confidence' => $request->input('confidence'),
            'description' => $request->input('description'),
            'solution' => $request->input('solution'),
            'impact' => $request->input('impact'),

        ];

        // Generate the PDF
        $pdf = Pdf::loadView('farmer.pdf.report', $data);

        // Create a filename with a timestamp
        $fileName = 'plant-disease-report-' . Carbon::now()->format('Y-m-d-H-i-s') . '.pdf';

        // Save the PDF to storage
        $filePath = storage_path('app/reports/' . $fileName);
        $pdf->save($filePath);

        // Optionally, send to admin
        if ($request->has('sendToAdmin') && $request->input('sendToAdmin') == '1') {
            // Implement sending PDF to admin logic here
            // You might want to use Mail or other services
        }

        Notification::create([
            'farmer_id' => $farmer->id,
            'message' => 'Your Pdf report' . $fileName . ' has been generated and sent to admin.',
            'type' => 'plantDiseasereport',  // Mark the notification type as borrow

        ]);
        // Return the PDF for download
        return $pdf->download($fileName);
    }
}
