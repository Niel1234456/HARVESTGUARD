<?php

namespace App\Http\Controllers\Farmer;

use Illuminate\Http\Request;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use App\Http\Controllers\Controller;

class ImageRecognitionController extends Controller
{
    public function showUpdateForm()
    {
        return view('farmer.update');
    }

    public function recognize(Request $request)
    {
        $image = $request->file('image');

        if (!$image->isValid()) {
            return back()->with('error', 'Invalid image file.');
        }

        // Assuming the Google Cloud Vision API key JSON file path is stored in the environment variable GOOGLE_CLOUD_VISION_API_KEY
        $imageAnnotator = new ImageAnnotatorClient(['credentials' => json_decode(file_get_contents(env('GOOGLE_CLOUD_VISION_API_KEY')), true)]);
        $imageContent = file_get_contents($image);
        $response = $imageAnnotator->labelDetection($imageContent);
        $labels = $response->getLabelAnnotations();

        $descriptions = [];
        foreach ($labels as $label) {
            $descriptions[] = $label->getDescription();
        }

        return view('farmer.update', ['result' => implode(', ', $descriptions)]);
    }
}
