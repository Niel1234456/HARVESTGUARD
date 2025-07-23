<?php

namespace App\Http\Controllers\Farmer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ImageAnalysis; // Import the ImageAnalysis model
use Illuminate\Support\Facades\Http; // To make HTTP requests
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Notification; // Import the ImageAnalysis model
use Illuminate\Support\Facades\Auth;

class ImageAnalysisController extends Controller
{   

    public function showForm()
    {$farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();
 
        return view('image_analysis.form', compact('notifications') );
    }

    public function analyze(Request $request)
    {$farmer = Auth::guard('farmer')->user();
        $notifications = Notification::where('farmer_id', $farmer->id)
        ->whereIn('type', ['supply', 'borrow', 'plantDiseasereport', 'approval', 'rejection', 'release', 'return']) 
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();
        // Validate the request based on the presence of an image file or base64 data
        $request->validate([
            'image' => 'nullable|image|max:20048',
            'capturedImage' => 'nullable|string'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            // Handle uploaded image
            $image = $request->file('image');
            $imagePath = $image->getPathname();
        } elseif ($request->filled('capturedImage')) {
            // Handle captured image (base64)
            $capturedImage = $request->input('capturedImage');
            $imagePath = $this->handleBase64Image($capturedImage);
        } else {
            return redirect()->back()->withErrors(['No image provided']);
        }

        // Send the image to the Flask API for disease prediction
        $response = $this->sendToFlaskAPI($imagePath);

        if ($response->successful()) {
            $data = $response->json();
    // Check if the API detected a non-plant image
    if (isset($data['error']) && $data['error'] == 'Not a plant image') {
        return redirect()->back()->withErrors(['image' => 'The uploaded image does not appear to be a plant. Please upload a valid plant image.']);
    }
            // Check if 'prediction' and 'confidence' keys exist in the response
            if (isset($data['prediction']) && isset($data['confidence'])) {
                $predictionIndex = $data['prediction'];
                $confidence = $data['confidence'];

                // Define the mapping of prediction indices to disease names and descriptions
                $diseaseMapping = [
                    0 => ['name' => 'Bacterial Spot', 'description' => 'Bacterial Spot causes dark, sunken lesions on leaves, stems, and fruit.'],
                    1 => ['name' => 'Early Blight', 'description' => 'Early Blight presents as small, dark spots on leaves, often surrounded by yellow halos.'],
                    2 => ['name' => 'Late Blight', 'description' => 'Late Blight causes large, irregular brown patches on leaves and fruits.'],
                    3 => ['name' => 'Leaf Mold', 'description' => 'Leaf Mold causes a gray mold to form on the upper surfaces of leaves.'],
                    4 => ['name' => 'Septoria Leaf Spot', 'description' => 'Septoria Leaf Spot leads to small, round, dark spots on leaves.'],
                    5 => ['name' => 'Spider Mites', 'description' => 'Spider Mites create tiny yellow or white spots on leaves.'],
                    6 => ['name' => 'Target Spot', 'description' => 'Target Spot causes concentric rings of necrosis on leaves.'],
                    7 => ['name' => 'Tomato Mosaic Virus', 'description' => 'Tomato Mosaic Virus causes mottled, distorted leaves with yellow patches.'],
                    8 => ['name' => 'Tomato Yellow Leaf Curl Virus', 'description' => 'Tomato Yellow Leaf Curl Virus results in yellowing and curling of the leaves.'],
                    9 => ['name' => 'Anthracnose', 'description' => 'Anthracnose leads to dark, sunken lesions on leaves, stems, flowers, or fruits.'],
                    10 => ['name' => 'Powdery Mildew', 'description' => 'Powdery Mildew appears as white, powdery spots on leaves and stems.'],
                    11 => ['name' => 'Downy Mildew', 'description' => 'Downy Mildew causes yellow or white patches on the upper leaf surface and gray mold underneath.'],
                    12 => ['name' => 'Root Rot', 'description' => 'Root Rot leads to the browning and wilting of leaves, often caused by overly wet soil.'],
                    13 => ['name' => 'Rust', 'description' => 'Rust diseases cause small, orange or brown pustules on the undersides of leaves.'],
                    14 => ['name' => 'Verticillium Wilt', 'description' => 'Verticillium Wilt causes leaves to yellow and wilt, starting from the base of the plant.'],
                    15 => ['name' => 'Fusarium Wilt', 'description' => 'Fusarium Wilt leads to wilting and yellowing of leaves, often one-sided.'],
                    16 => ['name' => 'Scab', 'description' => 'Scab causes rough, scabby spots on fruit, tubers, or leaves.'],
                    17 => ['name' => 'Clubroot', 'description' => 'Clubroot causes swelling and distortion of roots, leading to stunted growth and wilting.'],
                    18 => ['name' => 'Canker', 'description' => 'Canker results in sunken, dead areas on stems, branches, or trunks.'],
                    19 => ['name' => 'Black Spot', 'description' => 'Black Spot causes black spots on leaves, leading to yellowing and premature leaf drop.'],
                    20 => ['name' => 'Gray Mold', 'description' => 'Gray Mold forms fuzzy, gray spores on affected plant parts, especially in humid conditions.'],
                    21 => ['name' => 'Bacterial Wilt', 'description' => 'Bacterial Wilt causes sudden wilting and collapse of the plant, often starting with the lower leaves.'],
                    22 => ['name' => 'Mosaic Virus', 'description' => 'Mosaic Virus causes mottled, yellowed leaves with irregular patterns.'],
                    23 => ['name' => 'Botrytis Blight', 'description' => 'Botrytis Blight results in gray mold on flowers, leaves, and stems, often causing tissue death.'],
                    24 => ['name' => 'Crown Gall', 'description' => 'Crown Gall causes round, tumor-like swellings at the base of the plant or on roots.'],
                    25 => ['name' => 'Phytophthora Blight', 'description' => 'Phytophthora Blight causes water-soaked lesions on stems, roots, and leaves, leading to plant collapse.'],
                    26 => ['name' => 'Fire Blight', 'description' => 'Fire Blight causes blackened, shriveled leaves and branches, as if scorched by fire.'],
                    27 => ['name' => 'Alternaria Leaf Spot', 'description' => 'Alternaria Leaf Spot leads to dark, concentric rings on leaves, sometimes with a yellow halo.'],
                    28 => ['name' => 'Southern Blight', 'description' => 'Southern Blight causes a white, moldy growth at the soil line, leading to wilting and death of the plant.'],
                    29 => ['name' => 'Nematodes', 'description' => 'Nematodes cause galls on roots, stunted growth, and yellowing of leaves.'],
                    30 => ['name' => 'Bacterial Soft Rot', 'description' => 'Bacterial Soft Rot leads to mushy, foul-smelling decay of plant tissues, especially in storage organs.'],
                    31 => ['name' => 'Sooty Mold', 'description' => 'Sooty Mold appears as a black, powdery growth on leaves and stems, often following insect infestations.'],
                    32 => ['name' => 'Black Rot', 'description' => 'Black Rot causes dark, sunken spots on leaves, stems, and fruit, often leading to tissue death.'],
                    33 => ['name' => 'White Mold', 'description' => 'White Mold forms white, cottony growth on affected plant parts, leading to wilting and tissue death.'],
                    34 => ['name' => 'Pythium Root Rot', 'description' => 'Pythium Root Rot causes water-soaked, mushy roots, leading to wilting and plant collapse.'],
                    35 => ['name' => 'Angular Leaf Spot', 'description' => 'Angular Leaf Spot causes angular, water-soaked lesions on leaves, often with a yellow border.'],
                    36 => ['name' => 'Bacterial Canker', 'description' => 'Bacterial Canker results in sunken, dead areas on stems, branches, or trunks, often with a sticky exudate.'],
                    37 => ['name' => 'Leaf Curl', 'description' => 'Leaf Curl causes leaves to curl and distort, often with a reddish or yellowish tint.'],
                    38 => ['name' => 'Charcoal Rot', 'description' => 'Charcoal Rot leads to blackened roots and stems, often with a charcoal-like appearance.'],
                    39 => ['name' => 'Cercospora Leaf Spot', 'description' => 'Cercospora Leaf Spot causes small, circular spots on leaves, often with a gray center and dark border.'],
                    40 => ['name' => 'Cucumber Mosaic Virus', 'description' => 'Cucumber Mosaic Virus causes mottled, distorted leaves with yellow patches, often leading to stunted growth.'],
                    41 => ['name' => 'Root Knot Nematodes', 'description' => 'Root Knot Nematodes cause galls on roots, leading to stunted growth and yellowing of leaves.'],
                    42 => ['name' => 'Citrus Canker', 'description' => 'Citrus Canker causes raised, corky lesions on leaves, stems, and fruit, often surrounded by a yellow halo.'],
                    43 => ['name' => 'Peach Leaf Curl', 'description' => 'Peach Leaf Curl causes leaves to curl and blister, often with a reddish or yellowish tint.'],
                    44 => ['name' => 'Phytophthora Root Rot', 'description' => 'Phytophthora Root Rot causes water-soaked, mushy roots, leading to wilting and plant collapse.'],
                    45 => ['name' => 'Sclerotinia Blight', 'description' => 'Sclerotinia Blight causes white, cottony growth on affected plant parts, leading to wilting and tissue death.'],
                    46 => ['name' => 'Fusarium Crown Rot', 'description' => 'Fusarium Crown Rot leads to browning and wilting of leaves, often starting from the base of the plant.'],
                    47 => ['name' => 'Powdery Scab', 'description' => 'Powdery Scab causes powdery, scabby spots on tubers or leaves.'],
                    48 => ['name' => 'Soybean Cyst Nematode', 'description' => 'Soybean Cyst Nematode causes stunted growth, yellowing of leaves, and reduced yields.'],
                    49 => ['name' => 'Phyllosticta Leaf Spot', 'description' => 'Phyllosticta Leaf Spot leads to small, circular spots on leaves, often with a gray center and dark border.'],
                    50 => ['name' => 'Rice Blast', 'description' => 'Rice Blast causes elliptical lesions on leaves, often with a gray center and dark border.'],
                    51 => ['name' => 'Pink Rot', 'description' => 'Pink Rot causes pink, water-soaked lesions on tubers or roots, leading to decay.'],
                    52 => ['name' => 'Sugarcane Smut', 'description' => 'Sugarcane Smut causes long, black whip-like structures to form on affected plants.'],
                    53 => ['name' => 'Corn Smut', 'description' => 'Corn Smut forms large, grayish galls on ears, leaves, and stems of corn.'],
                    54 => ['name' => 'Tomato Hornworm', 'description' => 'Tomato Hornworm is a large, green caterpillar that can defoliate tomato plants.'],
                    55 => ['name' => 'Leafhoppers', 'description' => 'Leafhoppers suck sap from plants, causing stippling, leaf curling, and yellowing.'],
                    56 => ['name' => 'Aphids', 'description' => 'Aphids are small, soft-bodied insects that suck sap from plants, causing curling and yellowing of leaves.'],
                    57 => ['name' => 'Whiteflies', 'description' => 'Whiteflies are small, white insects that feed on the undersides of leaves, causing yellowing and wilting.'],
                    58 => ['name' => 'Scale Insects', 'description' => 'Scale Insects are small, oval insects that suck sap from plants, often leading to stunted growth and yellowing of leaves.'],
                    59 => ['name' => 'Mealybugs', 'description' => 'Mealybugs are small, white, cottony insects that suck sap from plants, causing leaf drop and stunted growth.'],
                    60 => ['name' => 'Thrips', 'description' => 'Thrips are tiny, slender insects that feed on plant tissues, causing silvery streaks and stippling on leaves.'],
                    61 => ['name' => 'Leafminers', 'description' => 'Leafminers burrow into leaves, creating winding tunnels that can lead to leaf drop.'],
                    62 => ['name' => 'Japanese Beetles', 'description' => 'Japanese Beetles are metallic green and bronze beetles that feed on a wide range of plants, skeletonizing leaves.'],
                    63 => ['name' => 'Cutworms', 'description' => 'Cutworms are caterpillars that chew through plant stems at the soil line, causing wilting and collapse.'],
                    64 => ['name' => 'Root Maggots', 'description' => 'Root Maggots are larvae that feed on roots, leading to stunted growth and yellowing of leaves.'],
                    65 => ['name' => 'Colorado Potato Beetle', 'description' => 'Colorado Potato Beetles are yellow and black-striped beetles that feed on potato leaves, defoliating plants.'],
                    66 => ['name' => 'Squash Vine Borer', 'description' => 'Squash Vine Borers are larvae that burrow into the stems of squash plants, causing wilting and plant death.'],
                    67 => ['name' => 'Cabbage Looper', 'description' => 'Cabbage Loopers are caterpillars that chew large holes in cabbage leaves, leading to reduced yields.'],
                    68 => ['name' => 'Flea Beetles', 'description' => 'Flea Beetles are small, jumping beetles that chew tiny holes in leaves, leading to stunted growth.'],
                    69 => ['name' => 'Blister Beetles', 'description' => 'Blister Beetles are large, black beetles that feed on a wide range of plants, causing defoliation.'],
                    70 => ['name' => 'Corn Earworm', 'description' => 'Corn Earworms are caterpillars that feed on the kernels of corn, reducing yield and quality.'],
                    71 => ['name' => 'Armyworms', 'description' => 'Armyworms are caterpillars that feed on a wide range of plants, causing significant defoliation.'],
                    72 => ['name' => 'Stink Bugs', 'description' => 'Stink Bugs are shield-shaped insects that suck sap from plants, causing discolored and misshapen fruit.'],
                    73 => ['name' => 'Chinch Bugs', 'description' => 'Chinch Bugs are small, black and white insects that suck sap from grasses, causing yellowing and browning of turf.'],
                    74 => ['name' => 'European Corn Borer', 'description' => 'European Corn Borers are caterpillars that burrow into corn stalks, causing them to weaken and break.'],
                    75 => ['name' => 'Spider Mites', 'description' => 'Spider Mites are tiny, spider-like pests that suck sap from plants, causing stippling and leaf drop.'],
                    76 => ['name' => 'Wireworms', 'description' => 'Wireworms are larvae that feed on the roots of crops, leading to stunted growth and reduced yields.'],
                    77 => ['name' => 'Grubs', 'description' => 'Grubs are larvae that feed on grass roots, causing patches of dead turf.'],
                    78 => ['name' => 'European Chafer', 'description' => 'European Chafers are beetle larvae that feed on grass roots, causing patches of dead turf.'],
                    79 => ['name' => 'Cabbage Root Maggot', 'description' => 'Cabbage Root Maggots are larvae that feed on the roots of cabbage plants, leading to stunted growth and wilting.'],
                    80 => ['name' => 'Cabbage White Butterfly', 'description' => 'Cabbage White Butterflies lay eggs that hatch into caterpillars that feed on cabbage leaves.'],
                    81 => ['name' => 'Brown Marmorated Stink Bug', 'description' => 'Brown Marmorated Stink Bugs are shield-shaped insects that feed on a wide range of plants, causing discolored and misshapen fruit.'],
                    82 => ['name' => 'Citrus Leaf Miner', 'description' => 'Citrus Leaf Miners are larvae that burrow into citrus leaves, causing winding tunnels and leaf distortion.'],
                    83 => ['name' => 'Root Weevils', 'description' => 'Root Weevils are larvae that feed on the roots of a wide range of plants, leading to stunted growth and yellowing of leaves.'],
                    84 => ['name' => 'Grape Phylloxera', 'description' => 'Grape Phylloxera are small, aphid-like insects that feed on grape roots, causing galls and reducing vine vigor.'],
                    85 => ['name' => 'Soybean Aphid', 'description' => 'Soybean Aphids are small, soft-bodied insects that suck sap from soybean plants, leading to stunted growth and yellowing of leaves.'],
                    86 => ['name' => 'Two-Spotted Spider Mite', 'description' => 'Two-Spotted Spider Mites are tiny, spider-like pests that suck sap from plants, causing stippling and leaf drop.'],
                    87 => ['name' => 'Apple Maggot', 'description' => 'Apple Maggots are larvae that tunnel through apple flesh, causing misshapen and rotting fruit.'],
                    88 => ['name' => 'Codling Moth', 'description' => 'Codling Moths are larvae that burrow into apples, pears, and other fruit, causing them to rot.'],
                    89 => ['name' => 'Plum Curculio', 'description' => 'Plum Curculios are beetles that lay eggs in fruit, causing the fruit to drop prematurely.'],
                    90 => ['name' => 'San Jose Scale', 'description' => 'San Jose Scales are small, circular insects that suck sap from fruit trees, causing stunted growth and yellowing of leaves.'],
                    91 => ['name' => 'Citrus Thrips', 'description' => 'Citrus Thrips are tiny, slender insects that feed on citrus leaves and fruit, causing scarring and distortion.'],
                    92 => ['name' => 'Western Flower Thrips', 'description' => 'Western Flower Thrips are tiny, slender insects that feed on a wide range of plants, causing silvery streaks and stippling on leaves.'],
                    93 => ['name' => 'Psyllids', 'description' => 'Psyllids are small, jumping insects that suck sap from plants, causing curling and yellowing of leaves.'],
                    94 => ['name' => 'Mealybugs', 'description' => 'Mealybugs are small, white, cottony insects that suck sap from plants, causing leaf drop and stunted growth.'],
                    95 => ['name' => 'Scale Insects', 'description' => 'Scale Insects are small, oval insects that suck sap from plants, often leading to stunted growth and yellowing of leaves.'],
                    96 => ['name' => 'Whiteflies', 'description' => 'Whiteflies are small, white insects that feed on the undersides of leaves, causing yellowing and wilting.'],
                    97 => ['name' => 'Aphids', 'description' => 'Aphids are small, soft-bodied insects that suck sap from plants, causing curling and yellowing of leaves.'],
                    98 => ['name' => 'Leafhoppers', 'description' => 'Leafhoppers suck sap from plants, causing stippling, leaf curling, and yellowing.'],
                    99 => ['name' => 'Tomato Hornworm', 'description' => 'Tomato Hornworm is a large, green caterpillar that can defoliate tomato plants.'],
                ];

                $potentialImpactMapping = [
                    0 => ['impact' => 'Moderate: Madilim, malalim na mga sugat sa mga dahon, tangkay, at prutas, na nagpapababa ng kalusugan ng halaman at kalidad ng prutas. Maaaring makaapekto sa ibang mga halaman tulad ng sili at talong, na nagdudulot ng katulad na mga sintomas.'],
                    1 => ['impact' => 'Moderate: Maliit, madilim na mga tuldok sa mga dahon, na nagdudulot ng maagang pagbagsak ng mga dahon at nagpapababa ng lakas ng halaman. Maaaring kumalat sa ibang mga solanaceous na tanim tulad ng sili, na nagpapahina sa kanilang paglago.'],
                    2 => ['impact' => 'Severe: Malalaki, hindi pantay na mga brown na mantsa sa mga dahon at prutas, na nagdudulot ng pagbagsak ng halaman at pagkawala ng ani. Ang ibang mga tanim sa pamilya ng nightshade tulad ng patatas at sili ay maaari ring maapektuhan, na nagdudulot ng malaking pinsala.'],
                    3 => ['impact' => 'Moderate: Gray mold sa mga dahon, na maaaring magpahina sa halaman at magpababa ng photosynthesis. Maaaring kumalat ito sa ibang mga tanim tulad ng pipino at letsugas, na nagpapababa sa kanilang vitality.'],
                    4 => ['impact' => 'Moderate: Maliit, bilog na madilim na mga tuldok sa mga dahon, na nagdudulot ng maagang pagbagsak ng mga dahon at pagpapababa ng ani. Maaaring magdulot ng impeksyon sa ibang mga tanim tulad ng sili at beans, na nagpapababa ng pangkalahatang kalusugan at productivity.'],
                    5 => ['impact' => 'Moderate: Maliit na dilaw o puting mga tuldok sa mga dahon, na nagdudulot ng pagbagsak ng photosynthesis at stress sa halaman. Maaaring makaranas ng katulad na mga sintomas ang ibang mga tanim tulad ng pipino, strawberry, at beans.'],
                    6 => ['impact' => 'Moderate: Concentric rings ng necrosis sa mga dahon, na nagpapahina sa halaman at nagpapababa ng potensyal ng paglago. Ang ibang mga solanaceous na tanim at ilang mga leafy vegetables ay maaari ring maapektuhan, na nagpapakita ng katulad na pinsala.'],
                    7 => ['impact' => 'Severe: Mottled, baluktot na mga dahon na may mga dilaw na patches, na nagdudulot ng pagpapababa ng paglago at ani. Maaari rin itong kumalat sa ibang mga tanim tulad ng sili at pipino, na nagpapahina sa kanilang paglago.'],
                    8 => ['impact' => 'Severe: Pagdilaw at pag-ikot ng mga dahon, na karaniwang nagdudulot ng stunted growth at pagkawala ng ani. Maaaring maapektuhan din ang ibang mga tanim tulad ng sili at tabako, na nagdudulot ng katulad na mga sintomas.'],
                    9 => ['impact' => 'Severe: Madilim, malalim na mga sugat sa mga dahon, tangkay, bulaklak, o prutas, na nagdudulot ng pagkamatay ng tissue at pagkawala ng ani. Maaaring maapektuhan din ang ibang mga pananim tulad ng beans, sili, at kamatis, na nagdudulot ng malawakang pagkawala ng ani.'],
                    10 => ['impact' => 'Moderate: Puting, powdery na mga tuldok sa mga dahon at tangkay, na nagpapababa ng photosynthesis at vitality ng halaman. Maaari ring maapektohan ang ibang mga tanim tulad ng pipino, melon, at sili, na nagpapahina sa kanilang kalusugan.'],
                    11 => ['impact' => 'Moderate: Dilaw o puting mga patches sa mga dahon na may gray mold sa ilalim, na nagpapahina sa halaman at nagpapababa ng paglago. Ang ibang mga pananim tulad ng pipino, gisantes, at letsugas ay maaari ring magpakita ng mga senyales ng pinsala.'],
                    12 => ['impact' => 'Severe: Pagkabaluktot at wilting ng mga dahon, karaniwan dahil sa labis na basa ng lupa, na nagdudulot ng pagkamatay ng halaman. Maaari ring maapektohan ang ibang mga pananim tulad ng pipino, kamatis, at sili, na nagdudulot ng malawakang pag collapse ng mga panamin.'],
                    13 => ['impact' => 'Moderate: Kahel o brown na mga pustules sa mga dahon, na nagdudulot ng pagbagsak ng kalusugan ng halaman at maagang pagbagsak ng mga dahon. Maaaring maapektuhan ang ibang mga pananim tulad ng strawberry, beans, at sili, na nagdudulot ng pagbagsak ng ani.'],
                    14 => ['impact' => 'Severe: Pagdilaw at wilting ng mga dahon, na karaniwang nagdudulot ng pagbagsak ng halaman, lalo na sa mga matandang halaman. Maaaring maapektuhan ang ibang mga tanim tulad ng sili, kamatis, at talong, na nagdudulot ng malawakang pagkaubos.'],
                    15 => ['impact' => 'Severe: Wilting at pagdilaw ng mga dahon, kadalasang isang panig, na nagdudulot ng pagkamatay ng halaman. Maaaring maapektuhan ang ibang mga pananim tulad ng beans, kamatis, at sili, na nagdudulot ng matinding pagkawala ng ani.'],
                    16 => ['impact' => 'Moderate: Magaspang, scabby na mga tuldok sa prutas, tubers, o mga dahon, na nagpapababa ng kalidad ng ani. Maaaring mag-develop ng katulad na mga sugat ang ibang mga tanim tulad ng patatas, pipino, at mansanas.'],
                    17 => ['impact' => 'Severe: Pamamaga at baluktot ng mga ugat, na nagdudulot ng stunted growth, wilting, at pagkawala ng ani. Maaaring maapektuhan din ang ibang mga cruciferous na tanim, kabilang ang repolyo, cauliflower, at broccoli.'],
                    18 => ['impact' => 'Moderate: Sunken, patay na mga bahagi sa mga tangkay, sanga, o puno, na nagpapababa ng vitality ng halaman at nagdudulot ng dieback. Maaaring maapektuhan ang ibang mga tanim tulad ng mga punong prutas at mga shrubs, na nagdudulot ng katulad na pinsala.'],
                    19 => ['impact' => 'Moderate: Itim na mga tuldok sa mga dahon na nagdudulot ng pagdilaw at maagang pagbagsak ng mga dahon, na nagpapahina sa halaman. Maaaring kumalat ang sakit na ito sa ibang mga ornamental at prutas na halaman tulad ng mga rosas at strawberry.'],
                    20 => ['impact' => 'Severe: Malalambot na gray na spores sa mga bahagi ng halaman, na nagdudulot ng pagkamatay ng tissue at madalas na kumakalat sa mga kondisyong mamasa-masa. Ang ibang mga tanim, lalo na sa mga greenhouse tulad ng pipino at sili, ay maaari ring malubhang maapektuhan.'],
                    21 => ['impact' => 'Severe: Biglaang wilting at pagbagsak ng halaman, karaniwang nagsisimula sa mga ibabang dahon, na nagdudulot ng pagkamatay ng halaman. Maaaring maapektuhan din ang ibang mga pananim tulad ng kamatis at sili.'],
                    22 => ['impact' => 'Moderate: Mottled, dilaw na mga dahon na may hindi pantay na mga pattern, na nagpapababa ng paglago ng halaman at ani. Maaari ring maapektuhan ang ibang mga tanim tulad ng pipino, sili, at letsugas.'],
                    23 => ['impact' => 'Severe: Gray mold sa mga bulaklak, dahon, at tangkay, na nagdudulot ng pagkamatay ng tissue at malaking pagkawala ng ani. Ang ibang mga pananim tulad ng letsugas, pipino, at beans ay maaari ring makaranas ng katulad na pinsala.'],
                    24 => ['impact' => 'Moderate: Bilog, tumor-like na pamamaga sa base ng halaman o mga ugat, na nagdudulot ng stunted growth at pagpapababa ng lakas. Ang ibang mga tanim tulad ng mga rosas at mga punong prutas ay maaari ring mag-develop ng katulad na sintomas.'],
                    25 => ['impact' => 'Severe: Water-soaked na mga sugat sa mga tangkay, ugat, at mga dahon, na nagdudulot ng pagbagsak ng halaman at pagkamatay. Ang blight na ito ay maaaring makaapekto sa ibang mga pananim tulad ng kamatis, patatas, at sili.'],
                    26 => ['impact' => 'Severe: Itim na, shriveled na mga dahon at sanga, na nagdudulot ng pagkamatay ng halaman at malubhang pagkawala ng ani. Ang ibang mga prutas na tanim, kabilang ang mansanas at peras, ay maaari ring maapektuhan ng malubhang pinsala.'],
                    27 => ['impact' => 'Moderate: Madilim na concentric rings sa mga dahon, minsan may dilaw na halo, na nagpapababa ng lakas ng halaman. Ang ibang mga pananim tulad ng patatas at kamatis ay maaaring magpakita ng katulad na mga sintomas.'],
                    28 => ['impact' => 'Severe: Puti, Mold na lumalago sa linya ng lupa, na nagdudulot ng wilting at pagkamatay ng halaman. Maaari ring maapektuhan ang ibang mga pananim tulad ng kamatis, beans, at sili, na nagdudulot ng pagkahulog ng halaman.'],
                    29 => ['impact' => 'Moderate: Galls sa mga ugat, stunted growth, at pagdilaw ng mga dahon, na nagdudulot ng pagbagsak ng productivity ng ani. Maaari itong kumalat sa ibang mga pananim tulad ng kamatis, carrot, at beans.'],
                    30 => ['impact' => 'Severe: Malambot, mabahong pagkabulok ng mga tissue ng halaman, lalo na sa mga storage organs, na nagdudulot ng pagkawala ng ani. Maaari itong makaapekto sa ibat ibang mga tanim, kabilang ang patatas at sibuyas.'],
                    31 => ['impact' => 'Moderate: Itim, powdery na lumalago sa mga dahon at tangkay, madalas na sumusunod sa mga insect infestation, na nakakaapekto sa kalusugan ng halaman. Ang sakit na ito ay maaari ring kumalat sa ibang mga tanim tulad ng pipino at beans.'],
                    32 => ['impact' => 'Severe: Madilim, malalim na mga tuldok sa mga dahon, tangkay, at prutas, na nagdudulot ng pagkamatay ng tissue at malubhang pagkawala ng ani. Ang ibang mga pananim tulad ng kamatis at sili ay maaari ring maapektohan.'],
                    33 => ['impact' => 'Severe: Puti, cottony na paglago sa mga naapektuhang bahagi ng halaman, na nagdudulot ng wilting at pagkamatay ng tissue. Maaari itong makaapekto sa ibat ibang mga tanim tulad ng beans, pipino, at kamatis, na nagdudulot ng malubhang pinsala.'],
                    34 => ['impact' => 'Severe: Water-soaked, malambot na mga ugat, na nagdudulot ng wilting at pagkahulog ng halaman, lalo na sa mga mamasa-masang kondisyon. Maaari itong makaapekto sa ibang mga tanim tulad ng pipino, kamatis, at sili.'],
                    35 => ['impact' => 'Moderate: Angular, water-soaked na mga sugat sa mga dahon na may dilaw na hangganan, na nagpapahina sa halaman at nagpapababa ng ani. Maaari ring makaapekto sa ibang mga tanim tulad ng beans at sili.'],
                    36 => ['impact' => 'Moderate: Sunken, patay na mga bahagi sa mga tangkay na may malagkit na exudate, na nagpapababa ng vitality ng halaman at nagdudulot ng dieback. Maaari ring kumalat ang sakit na ito sa ibang mga tanim tulad ng mga punong prutas at mga shrubs.'],
                    37 => ['impact' => 'Moderate: Pag-ikot at baluktot ng mga dahon na may reddish o yellowish na tint, na nagpapahina sa halaman. Maaari itong makaapekto sa ibat ibang mga pananim tulad ng mga peach, kamatis, at sili.'],
                    38 => ['impact' => 'Severe: Itim na mga ugat at tangkay na may charcoal-like na hitsura, na nagdudulot ng stunted growth at pagkamatay ng halaman. Ang ibang mga pananim tulad ng mais, beans, at kamatis ay maaari ring magdanas ng sakit na ito.'],
                    39 => ['impact' => 'Moderate: Maliit, bilog na mga tuldok na may gray na gitna at madilim na hangganan, na nagpapababa ng photosynthesis at nagpapahina sa halaman. Ang sakit na ito ay maaaring kumalat sa ibang mga pananim tulad ng kamatis at patatas.'],
                    40 => ['impact' => 'Severe: Eliptikal na mga sugat sa mga dahon na may gray na gitna at madilim na hangganan, na nagdudulot ng pagbagsak ng ani at kalusugan ng halaman. Maaari itong makaapekto sa ibang mga pananim tulad ng palay, millet, at sorghum.'],
                    41 => ['impact' => 'Moderate: Pagdilaw ng mga dahon at tangkay na may itim na mga ugat, na nagdudulot ng pagbagsak ng lakas ng halaman. Maaaring makaapekto din ang sakit na ito sa mga pananim tulad ng pipino, melon, at kalabasa.'],
                    42 => ['impact' => 'Severe: pagkabulok ng ugat, na nagdudulot ng wilting at pagkamatay ng halaman, partikular na sa mga mamasa-masang lupa. Maaari ring maapektuhan ang ibang mga pananim tulad ng patatas, kamatis, at sili.'],
                    43 => ['impact' => 'Moderate: Mga streak ng dilaw o pula sa mga dahon, na nagpapababa ng paglago ng halaman. Ang sakit na ito ay maaaring makaapekto sa ibat ibang tanim tulad ng letsugas, beans, at pipino.'],
                    44 => ['impact' => 'Severe: Malalang leaf curl at stunted growth, na nagdudulot ng mahinang ani. Maaari ring maapektuhan ang ibang mga pananim tulad ng kamatis at sili.'],
                    45 => ['impact' => 'Moderate: Yellowish streaks sa mga dahon na may deformed na tissue, na nagpapahina sa halaman at nagpapababa ng photosynthesis. Maaari itong makaapekto sa ibat ibang mga pananim tulad ng repolyo, spinach, at letsugas.'],
                    46 => ['impact' => 'Severe: Kumpletong pagkawala ng mga dahon at dieback ng mga sanga, na nagdudulot ng pagkabagsak ng halaman. Ang ibang mga pananim tulad ng beans at strawberry ay maaari ring makaranas ng malubhang pinsala mula sa sakit na ito.'],
                    47 => ['impact' => 'Moderate: Brown na mga tuldok sa mga dahon, na nagdudulot ng maagang pagbagsak ng mga dahon at pagpapababa ng potensyal ng ani. Ang ibang mga pananim tulad ng pipino, melon, at kalabasa ay maaari ring magdanas ng katulad na mga sintomas.'],
                    48 => ['impact' => 'Severe: Tissue necrosis sa mga tangkay at dahon, na nagdudulot ng mabilis na pagbagsak ng halaman. Maaari itong makaapekto sa ibang mga tanim tulad ng kamatis, sili, at pipino.'],
                    49 => ['impact' => 'Moderate: Itim na streaks at mga sugat sa mga dahon, na nagpapababa ng photosynthesis at stress ng halaman. Maaaring magpakita ng katulad na pinsala ang ibang mga pananim tulad ng patatas, kamatis, at sili.'],
                    50 => ['impact' => 'Severe: Baluktot, dilaw na mga dahon na may itim na mga tip, na nagpapababa ng kalusugan ng halaman at ani. Ang ibang mga tanim tulad ng pipino, kamatis, at letsugas ay maaari ring magdanas ng katulad na mga sintomas.'],
                    51 => ['impact' => 'Moderate: Pink, water-soaked na mga sugat sa mga tuber o ugat, na nagdudulot ng pagkabulok. Maaari itong makaapekto sa mga pananim tulad ng patatas, kamatis, at karot.'],
                    52 => ['impact' => 'Severe: Mahahabang, itim na whip-like na mga structure na nabubuo sa mga naapektuhang halaman, na nagpapababa ng paglago ng halaman at nakakaapekto sa mga sugarcane crops.'],
                    53 => ['impact' => 'Moderate: Grayish galls sa mga tainga, dahon, at tangkay ng mais, na nagdudulot ng pagbagsak ng ani at kalidad. Ang ibang mga pananim tulad ng trigo at barley ay maaari ring maapektuhan.'],
                    54 => ['impact' => 'Severe: Mga berdeng caterpillar na nagdudulot ng defoliation sa mga halaman ng kamatis, na nagdudulot ng pagbagsak ng ani at pagkamatay ng halaman. Ang ibang mga pananim tulad ng sili at talong ay maaari ring maapektohan.'],
                    55 => ['impact' => 'Moderate: Leaf stippling, curling, at pagdilaw na dulot ng sap-sucking, na nagpapababa ng lakas ng halaman. Maaari itong makaapekto sa mga pananim tulad ng beans, kamatis, at letsugas.'],
                    56 => ['impact' => 'Moderate: Pagkukulot at pagdidilaw ng mga dahon dahil sa pagsipsip ng katas, na humahantong sa pagbawas sa kalusugan ng halaman. Maaari itong makaapekto sa mga pananim tulad ng mga pipino, melon, at sili.'], 
                    57 => ['impact' => 'Moderate: Pagdidilaw at pagkalanta ng mga dahon dahil sa pagsipsip ng katas ng mga halaman. Ang ibang mga pananim tulad ng talong, patatas, at mga pipino ay maaaring magpakita ng mga katulad na sintomas.'], 
                    58 => ['impact' => 'Moderate: Pagtigil sa paglaki at pagdidilaw ng mga dahon na dulot ng pagsipsip ng dagta, na nakakaapekto sa malawak na hanay ng mga halaman tulad ng sili, kamatis, at strawberry.'], 
                    59 => ['impact' => 'Moderate: Pagbagsak ng mga dahon at pagtigil sa paglaki dulot ng pagsipsip ng dagta. Maaari itong makaapekto sa mga pananim tulad ng mga pipino, melon, at strawberry.'], 
                    60 => ['impact' => 'Moderate: Mga kulay silver na guhit at pekas sa mga dahon dahil sa pagsipsip ng katas, na humahantong sa pagbawas sa kalusugan ng halaman. Maaaring kabilang sa mga apektadong pananim ang mga beans, kamatis, at mga pipino.'], 
                    61 => ['impact' => 'Moderate: Paikot-ikot na mga butas sa mga dahon na nagdudulot ng pagbagsak ng dahon at pagbawas ng photosynthesis. Maaari itong makaapekto sa malawak na hanay ng mga halaman tulad ng lettuce, spinach, at beans.'], 
                    62 => ['impact' => 'Severe: Naka-skeletonized na mga dahon na dulot ng pag-kain ng mga insekto, na humahantong sa malaking pinsala sa halaman. Ang iba pang mga pananim tulad ng talong, patatas, at paminta ay maaari ding maapektuhan nang husto.'],
                    63 => ['impact' => 'Severe: Ang mga kinaing tangkay sa linya ng lupa ay nagdudulot ng pagkalanta at pagbagsak ng halaman, na nakakaapekto sa mga pananim tulad ng mga kamatis, paminta, at lettuce.'], 
                    64 => ['impact' => 'Moderate: Pagtigil sa paglaki at pagdidilaw ng mga dahon dahil sa pag-kain sa ugat nito, na humahantong sa pagbaba ng ani ng pananim. Ito ay maaaring makaapekto sa patatas, karot, at kamatis.'], 
                    65 => ['impact' => 'Severe: Paglagas ng mga dahon ng patatas dahil sa pag-kain ng mga peste, malalang binabawasan ang ani at nakakaapekto sa kalusugan ng halaman. Ang iba pang mga pananim tulad ng kamatis at talong ay maaari ding maapektuhan.'], 
                    66 => ['impact' => 'Severe: Pagkalanta at pagkamatay ng halaman na dulot ng paghuhukay ng larvae sa mga tangkay ng kalabasa, na nakakaapekto sa mga pananim tulad ng pumpkins, zucchini, at cucumber.'], 
                    67 => ['impact' => 'Moderate: Malalaking mga butas sa mga dahon ng repolyo na sanhi ng mga uod, nababawasan ang ani at kalusugan ng halaman. Ang iba pang mga pananim tulad ng kale at spinach ay maaari ding maapektuhan.'], 
                    68 => ['impact' => 'Moderate: Maliliit na mga butas sa mga dahon dahil sa pag-kain na humahantong sa pagtigil sa paglaki. Ito ay maaaring makaapekto sa malawak na hanay ng mga halaman tulad ng talong, beans, at mga kamatis.'],
                    69 => ['impact' => 'Severe: Paglalagas dulot ng pag-kain, pagbabawas ng kalusugan at ani ng halaman. Ang ibang mga pananim tulad ng patatas, paminta, at kamatis ay maaaring maapektuhan nang husto.'], 
                    70 => ['impact' => 'Severe: Ang pagpapakain sa mga butil ng mais ay humahantong sa pagbaba ng ani at kalidad nito, na nakakaapekto sa mga pananim tulad ng mais at iba pang mga pananim na cereal.'],
                    71 => ['impact' => 'Severe: Malaking pagkawasak ng mga dahon dahil sa pagpapakain ng uod, na nakakaapekto sa mga pananim tulad ng beans, mais, at kamatis.'], 
                    72 => ['impact' => 'Moderate: Kupas ang kulay at maling hugis na prutas na dulot ng pagsipsip ng katas, nababawasan ang kalidad at ani ng pananim. Maaaring maapektuhan ang ibang mga pananim tulad ng kamatis, sili, at talong.'], 
                    73 => ['impact' => 'Moderate: Pag-yellow at browning ng damo o dahoon na dulot ng sap-feeding, na nakakaapekto sa turfgrass at iba pang pananim tulad ng trigo at mais.'],
                    74 => ['impact' => 'Severe: Paghina at pagkasira ng mga tangkay ng mais dahil sa pagkakabaon ng uod, na humahantong sa pagbaba ng ani at kalidad ng pananim.'], 
                    75 => ['impact' => 'Moderate: Stippling at pagbagsak ng dahon dulot ng pagsipsip ng dagta, na humahantong sa pagbawas ng sigla ng halaman. Maaari itong makaapekto sa mga pananim tulad ng beans, kamatis, at sili.'], 
                    76 => ['impact' => 'Moderate: Natitigil ang paglaki at nabawasan ang mga ani dahil sa root-feeding, na nakakaapekto sa mga pananim tulad ng carrots, patatas, at sibuyas.'], 
                    77 => ['impact' => 'Moderate: Mga patay na patch sa turf na dulot ng root-feeding, na humahantong sa hindi magandang kalusugan ng damo. Maaaring maapektuhan din ang ibang mga pananim tulad ng beans at kamatis.'], 
                    78 => ['impact' => 'Moderate: Mga patay na patch sa turf na dulot ng root-feeding, na nagpapababa sa kalidad ng turf. Maaari rin itong makaapekto sa iba pang mga pananim tulad ng mais at lettuce.'], 
                    79 => ['impact' => 'Moderate: Pagtigil sa paglaki at pagkalanta ng pananim na dulot ng pag-kain ng ugat, na nakakaapekto sa mga pananim tulad ng repolyo, lettuce, at broccoli.'], 
                    80 => ['impact' => 'Moderate: Pagkasira ng dahon na dulot ng pag-kain ng mga uod sa pananim, na humahantong sa pagbaba ng kalusugan at ani ng halaman. Maaari itong makaapekto sa iba pang mga pananim tulad ng kale, spinach, at broccoli.'], 
                    81 => ['impact' => 'Moderate: Kupas ang kulay at maling hugis na prutas na dulot ng pagsipsip ng katas, nababawasan ang kalidad at ani ng pananim. Ang iba pang mga pananim tulad ng kamatis, sili, at talong ay maaari ding maapektuhan.'], 
                    82 => ['impact' => 'Moderate: Paikot-ikot na mga butas sa mga dahon ng citrus, na humahantong sa pagbaluktot ng dahon at pagbawas ng photosynthesis. Maaari itong makaapekto sa mga dalandan, lemon, at grapefruits.'], 
                    83 => ['impact' => 'Moderate: Banal na paglaki at pagdidilaw ng mga dahon na dulot ng pagpapakain sa ugat, na humahantong sa pagbawas ng sigla ng halaman. Maaari itong makaapekto sa isang malawak na hanay ng mga halaman tulad ng beans, kamatis, at lettuce.'], 
                    84 => ['impact' => 'Severe: Mga galls at kabawasan sa pagtubo ng baging na dulot ng mala-aphid na kumakain sa mga ugat ng ubas, na humahantong sa pagbaba ng ani ng pananim.'], 
                    85 => ['impact' => 'Moderate: Pagtigil sa paglaki at pagdidilaw ng mga dahon na dulot ng pagpapakain ng dagta, na nakakaapekto sa mga halaman ng soybean at nakakabawas ng ani.'], 
                    86 => ['impact' => 'Moderate: Stippling at pagbagsak ng dahon dulot ng pagsipsip ng dagta, na humahantong sa kabawasan sa kalusugan at ani ng halaman. Maaaring kabilang sa mga apektadong pananim ang beans, kamatis, at paminta.'], 
                    87 => ['impact' => 'Severe: Maling hugis at nabubulok na mga mansanas na dulot ng larvae na kumakain at bumubutas sa prutas, na nagpapababa ng kalidad at ani.'], 
                    88 => ['impact' => 'Severe: Nabubulok ang mga prutas na dulot ng paglubog ng larvae sa mga mansanas, peras, at iba pang prutas, na humahantong sa malaking pagkawala ng pananim.'], 
                    89 => ['impact' => 'Moderate: Napaaga ang pagbagsak ng prutas na dulot ng nangingitlog na mga salagubang, na nakakaapekto sa mga pananim tulad ng mga plum, peach, at mansanas.'], 
                    90 => ['impact' => 'Moderate: Pagtigil sa paglaki at pagdidilaw ng mga dahon na dulot ng sap-feeding, na humahantong sa pagbaba ng kalidad at ani ng prutas.'], 
                    91 => ['impact' => 'Moderate: Ang pagkakapilat at pagbaluktot ng mga dahon at prutas ng sitrus na dulot ng pagsipsip ng katas, nababawasan ang ani at kalidad ng pananim.'], 
                    92 => ['impact' => 'Moderate: Mga kulay silver na guhit at pekas sa mga dahon na dulot ng pag-kain sa dagta, na humahantong sa kabawasan sa kalusugan ng halaman. Ito ay maaaring makaapekto sa malawak na hanay ng mga pananim tulad ng beans, kamatis, at mga pipino.'], 
                    93 => ['impact' => 'Moderate: Ang pagkulot at pagdidilaw ng dahon dulot ng pagsipsip ng katas, na humahantong sa pagbaba ng kalusugan ng halaman. Maaari itong makaapekto sa malawak na hanay ng mga pananim tulad ng sili, kamatis, at beans.'], 
                    94 => ['impact' => 'Moderate: Ang pagbagsak ng dahon at pagtigil sa paglaki sanhi ng pagsipsip ng katas, na humahantong sa kabawasan sa kalusugan ng halaman. Maaari itong makaapekto sa mga pananim tulad ng beans, cucumber, at melon.'], 
                    95 => ['impact' => 'Moderate: Pagtigil sa paglaki at pagdidilaw ng mga dahon na dulot ng pagsipsip ng katas, Nababawasan ang sigla ng pagtubo ng halaman. Maaaring maapektuhan din ang iba pang mga pananim tulad ng beans, lettuce, at kamatis.'], 
                    96 => ['impact' => 'Moderate: Pagdidilaw at pagkalanta ng mga dahon dahil sa pag-kain sa katas. Maaari itong makaapekto sa mga pananim tulad ng talong, paminta, at kamatis.'], 
                    97 => ['impact' => 'Moderate: Pagkulot at pagdidilaw ng mga dahon dahil sa pagsipsip ng katas, na humahantong sa kabawasan sa kalusugan ng halaman. Maaaring maapektuhan ang ibang mga pananim tulad ng mga pipino, melon, at sili.'], 
                    98 => ['impact' => 'Moderate: Ang pag-stippling at pagdidilaw ng mga dahon dulot ng pagsipsip ng katas, nababawasan ang kalusugan at ani ng halaman. Kabilang sa mga apektadong pananim ang beans, kamatis, at mga pipino.'], 
                    99 => ['impact' => 'Severe: Paglalagas sanhi ng pag-kain ng mga uod sa halaman, na humahantong sa malaking pagkawala ng pananim at pagbawas ng ani. Maaari itong makaapekto sa mga pananim tulad ng mga kamatis, paminta, at talong.'],
                ];
                
                // Define the mapping of prediction indices to solutions
                $solutionMapping = [
                    0 => 'Alisin ang mga apektadong bahagi ng halaman at ayusin ang daloy ng hangin. Gumamit ng copper-based bactericides bilang pang-iwas. Ang mga copper-based bactericides ay epektibong nagpo-protekta laban sa bacterial diseases sa mga halaman at nakakatulong sa pagpigil ng pagkalat ng impeksyon.',
                    1 => 'Mag-apply ng insecticides at fungicides upang mapigilan ang mga peste at mga fungal disease. Ang mga produkto ay makakatulong upang kontrolin ang mga peste at fungi na maaaring magdulot ng sakit sa mga halaman. Siguraduhing sundin ang tamang mga tagubilin upang maiwasan ang labis na paggamit ng kemikal na maaaring makapinsala sa kapaligiran.',
                    2 => 'Magpatupad ng crop rotation at mag-apply ng fungicides upang pamahalaan ang fungal growth. Ang crop rotation ay makakatulong upang mabawasan ang mga soil-borne pests at pathogens. Sa pamamagitan ng pagpapalit ng mga tanim bawat taon, ang mga pests at fungi ay mahihirapan magparami.',
                    3 => 'Alisin ang mga apektadong halaman at gumamit ng fungicides upang kontrolin ang pagkalat ng fungal infection. Ang mabilis na pag-aalis ng mga apektadong halaman ay makakatulong upang maiwasan ang pagkalat ng impeksyon sa iba pang mga halaman. Mahalaga rin ang tamang aplikasyon ng fungicides upang maiwasan ang relapse.',
                    4 => 'Magpatupad ng pest management program at gumamit ng organic treatments upang maiwasan ang pest infestation. Ang paggamit ng mga natural na pest control methods ay makakatulong upang panatilihing malusog ang mga halaman nang hindi gumagamit ng mga kemikal. Ang mga organic treatments tulad ng neem oil at diatomaceous earth ay mabisa at ligtas.',
                    5 => 'Iwasan ang sobrang patubig at tiyakin ang tamang spacing upang hindi magdulot ng fungal diseases. Ang mga sakit na dulot ng fungus ay madalas na sanhi ng labis na kahalumigmigan at hindi tamang paglalagay ng mga halaman. Ang tamang spacing ay nagpapabuti sa daloy ng hangin at nakakatulong sa pagpigil ng pagdami ng mga fungal spores.',
                    6 => 'Mag-apply ng insecticides upang kontrolin ang mga peste na nagdudulot ng mga sakit sa halaman. Ang mga peste tulad ng mga insekto ay maaaring magdala ng mga sakit sa mga halaman kayat mahalaga ang kontrol sa kanila. Pumili ng insecticides na hindi nakakasama sa mga benepisyal na insekto upang mapanatili ang balanse sa ekosistema.',
                    7 => 'Mag-rotate ng mga crops at mag-apply ng fungicides upang mabawasan ang mga fungal diseases. Ang tamang crop rotation ay nakakatulong upang masugpo ang mga fungal spores at pinipigilan ang kanilang pagdami sa lupa. Siguraduhing gumamit ng mga fungicides na ligtas sa kapaligiran at hindi nagdudulot ng polusyon.',
                    8 => 'Alisin ang mga apektadong bahagi ng halaman at mag-apply ng fungicides upang maiwasan ang impeksyon. Ang pagtanggal ng mga apektadong bahagi ay isang mahalagang hakbang upang hindi kumalat ang sakit. Maging maingat sa pag-apply ng fungicides, upang hindi makaapekto sa ibang bahagi ng halaman o sa kalikasan.',
                    9 => 'Magpatupad ng pest management strategies upang maiwasan ang mga pesteng dulot ng mga insects. Ang integradong pest management ay nagtataguyod ng mas balanseng approach sa pagsugpo ng mga peste. Tiyakin na ang mga pamamaraang ginagamit ay tumutugon sa buong ekosistema, mula sa mga benepisyal na insekto hanggang sa mga halaman.',
                    10 => 'Mag-apply ng fungicides upang makontrol ang pagkalat ng fungal diseases at panatilihing malusog ang mga halaman. Ang mga fungicides ay mahalaga sa pagpigil sa pagkalat ng mga fungal infection sa mga tanim. Mahalaga ang tamang timing at dosis ng fungicides upang maiwasan ang pagbuo ng resistensya ng mga fungi.',
                    11 => 'Mag-rotate ng crops at gumamit ng resistant varieties upang pamahalaan ang mga fungal infections. Ang pagpili ng mga resistant varieties ay makakatulong upang mapigilan ang pag-atake ng fungal diseases. Siguraduhing piliin ang mga variety na tumutugma sa mga kondisyon ng iyong taniman at sa mga halamang nais palaguin.',
                    12 => 'Alisin ang mga apektadong halaman at mag-apply ng fungicides upang makontrol ang pagkalat ng fungal disease. Ang pag-aalis ng apektadong halaman ay isang pangunahing hakbang upang mapanatili ang kalusugan ng buong tanim. Ang mabilis na pagtanggal ng apektadong bahagi ay makakatulong upang limitahan ang pagkalat ng impeksyon.',
                    13 => 'Mag-apply ng insecticides upang mabawasan ang pag-atake ng mga peste. Ang mga insecticides ay nakakatulong upang pigilan ang mga peste na maaaring magdulot ng malubhang epekto sa mga halaman. Gumamit ng mga insecticides na may mataas na target na kakayahan upang hindi maapektuhan ang iba pang organismo.',
                    14 => 'Magpatupad ng integrated pest management upang makontrol ang infestation. Ang integrated pest management ay isang holistic na pamamaraan ng pagsugpo sa mga peste, na kinikilala ang balanseng gamit ng mga kemikal at mga organikong solusyon. Ang pamamahagi ng mga pest-killing organisms, tulad ng ladybugs, ay nakakatulong sa pagkontrol ng mga peste.',
                    15 => 'Mag-rotate ng mga crops upang mapabuti ang kalusugan ng lupa at maiwasan ang mga sakit. Ang crop rotation ay nakakatulong upang ma-renew ang kalusugan ng lupa at mapabuti ang pag-aalaga sa mga tanim. Ang tamang rotation ay nagsisiguro ng mas mabuting pag-absorb ng nutrients at nagpapababa ng pest infestation.',
                    16 => 'Mag-apply ng fungicides upang makontrol ang fungal infections at gumamit ng pest-resistant varieties. Ang pest-resistant varieties ay makakatulong upang maiwasan ang mga sakit mula sa mga peste at fungi. Ang pagtangkilik sa mga variety na resistant sa sakit ay isang long-term solution na makakatulong sa sustainability ng pagtatanim.',
                    17 => 'Magpatupad ng crop rotation upang mapabuti ang kalidad ng lupa at maiwasan ang pagkakaroon ng sakit. Ang regular na pagbabago ng mga tanim sa isang lugar ay tumutulong sa pagpapanatili ng kalusugan ng lupa at pinipigilan ang mga pests. Ang healthy soil ay may kakayahang suportahan ang mas matibay na mga halaman laban sa sakit.',
                    18 => 'Mag-apply ng fungicides at gumamit ng resistant varieties upang maiwasan ang fungal infections. Ang paggamit ng mga varieties na may natural na resistensya laban sa mga sakit ay nakakatulong upang mapanatili ang kalusugan ng mga halaman. Ang resistant varieties ay nagiging alternatibo sa mga kemikal na may mas kaunting epekto sa kapaligiran.',
                    19 => 'Mag-apply ng fungicides upang makontrol ang fungal spread at gumamit ng organic farming methods. Ang paggamit ng mga organikong pamamaraan ay nakakatulong upang maiwasan ang mga kemikal na maaaring magdulot ng panganib sa kalikasan. Ang organic farming ay nakatutok sa paggamit ng mga likas na solusyon upang mapabuti ang kalusugan ng lupa at mga halaman.',
                    20 => 'Iwasan ang sobrang patubig at tiyakin ang tamang spacing upang maiwasan ang pagkalat ng fungal diseases. Ang tamang pangangalaga sa tubig at espasyo ng mga tanim ay nakakatulong upang maiwasan ang pagdami ng mga fungal spores. Siguraduhing ang soil drainage ay maayos upang maiwasan ang pag-ipon ng tubig na maaaring magdulot ng fungus.',
                    21 => 'Magpatupad ng soil treatment upang maiwasan ang mga soil-borne diseases. Ang soil treatments ay mahalaga upang maiwasan ang mga sakit na dulot ng mga microorganisms na nasa lupa. Ang mga soil treatments tulad ng composting at liming ay nakakatulong upang mapabuti ang kalusugan ng lupa at matanggal ang mga harmful pathogens.',
                    22 => 'Mag-apply ng insecticides upang mabawasan ang infestation ng mga pests at gumamit ng fungicides upang kontrolin ang fungal infections. Ang kombinasyon ng insecticides at fungicides ay isang epektibong hakbang upang makontrol ang parehong mga peste at sakit. Subalit, mahalaga na hindi magamit ang mga ito nang labis upang maiwasan ang resistensya.',
                    23 => 'Mag-rotate ng crops at gumamit ng resistant varieties upang pamahalaan ang mga sakit sa mga halaman. Ang tamang crop rotation at resistant varieties ay nakakatulong upang mapabuti ang kalusugan ng mga tanim. Magsimula ng mga bagong cropping strategies upang matulungan ang lupa na makabawi mula sa mga previous infestations.',
                    24 => 'Mag-apply ng fungicides upang kontrolin ang fungal infections at gumamit ng eco-friendly pest control methods. Ang mga eco-friendly methods ay nagtataguyod ng mas ligtas na kapaligiran habang pinapabuti ang kalusugan ng mga halaman. Ang paggamit ng natural predators tulad ng parasitoid wasps ay isang mahusay na alternatibo.',
                    25 => 'Magpatupad ng soil management practices upang mapabuti ang kalusugan ng mga tanim. Ang tamang pamamahala ng lupa ay makakatulong sa pagpapanatili ng kalusugan ng mga tanim at pagbabawas ng mga pests at fungal diseases. Ang paggamit ng organic mulches at soil aeration ay epektibong solusyon sa pagpapabuti ng kalidad ng lupa.',
                    26 => 'Magpatupad ng integrated pest management upang makontrol ang mga peste sa mga tanim. Ang integradong pest management ay nagbibigay ng mas balanseng solusyon sa pagsugpo ng mga peste. Maglaan ng oras upang mag-monitor at gumawa ng masusing pagsusuri upang makita ang tamang solusyon para sa bawat pest infestation.',
                    27 => 'Mag-apply ng fungicides upang maiwasan ang fungal diseases at gumamit ng organic farming methods. Ang paggamit ng mga organikong pamamaraan sa pagsugpo ng fungal diseases ay mas ligtas para sa kalikasan at mga tao. Sa pamamagitan ng mga natural na paraan, mas mapapabuti ang pangangalaga sa mga halaman at kalikasan.',
                    28 => 'Mag-rotate ng crops upang maiwasan ang pagdami ng mga pests at fungal infections. Ang rotation ng mga crops ay tumutulong upang maiwasan ang pagtataglay ng mga pests sa lupa. Siguraduhin na bawat uri ng tanim na ipapalit ay may tamang proteksyon laban sa pests.',
                    29 => 'Mag-apply ng insecticides upang kontrolin ang mga peste at gumamit ng fungicides upang maiwasan ang fungal infections. Ang paggamit ng insecticides at fungicides ay nakakatulong upang makontrol ang parehong mga peste at sakit. Gayunpaman, tiyaking hindi mag-over-apply upang hindi makapinsala sa kapaligiran.',
                    30 => 'Mag-rotate ng crops at gumamit ng resistant varieties upang makontrol ang pesteng epekto. Ang tamang rotation at resistant varieties ay nakakatulong upang kontrolin ang epekto ng mga pests at fungal infections. Ang tamang pagpaplano ng crop cycle ay nagbibigay ng sustansya sa lupa at nagpapatibay sa kalusugan ng mga tanim.',
                    31 => 'Mag-apply ng organic fertilizers upang mapabuti ang kalusugan ng lupa at mga halaman. Ang mga organikong pataba ay nakakatulong sa pagpapabuti ng soil structure at nutrisyon ng mga halaman nang hindi gumagamit ng mga kemikal. Sa pamamagitan nito, natutulungan ang mga halaman na lumaki nang malusog at maiwasan ang mga sakit na dulot ng nutrient deficiencies.',
                    32 => 'Magpatupad ng tamang irrigation system upang maiwasan ang sobrang patubig na nagdudulot ng fungal growth. Ang kontroladong patubig ay nakakatulong upang maiwasan ang kahalumigmigan na paborable sa mga sakit na dulot ng fungi. Ang wastong pamamahagi ng tubig ay nagpapabuti sa kalusugan ng halaman at pumipigil sa fungal infections.',
                    33 => 'Palakasin ang resistensya ng mga halaman sa pamamagitan ng tamang nutrisyon at pangangalaga. Ang tamang nutrisyon ay nagpapalakas sa immune system ng halaman upang mapigilan ang pag-atake ng mga peste at sakit. Ito ay mahalaga lalo na sa mga panahon ng stress tulad ng tagtuyot o sobrang init.',
                    34 => 'Magpatupad ng natural pest control methods tulad ng paggamit ng mga beneficial insects. Ang mga beneficial insects ay makakatulong sa pagpapatay ng mga peste na nagdudulot ng sakit sa mga halaman nang hindi gumagamit ng mga kemikal. Sa ganitong paraan, napapalakas ang natural na ekosistema ng iyong hardin.',
                    35 => 'Alisin ang mga dead plant materials at debris sa paligid ng mga halaman upang maiwasan ang breeding grounds ng mga pests at pathogens. Ang pagtanggal ng mga patay na bahagi ng halaman ay makakatulong upang mabawasan ang mga pathogens at peste.',
                    36 => 'Iwasan ang overcrowding ng mga halaman upang mabigyan ng sapat na espasyo ang bawat isa at maiwasan ang mga fungal infections. Ang tamang spacing ay nagpapabuti sa sirkulasyon ng hangin at nakakatulong upang maiwasan ang mga sakit na dulot ng masikip na kapaligiran.',
                    37 => 'Mag-apply ng mulching upang mapanatili ang moisture at mabawasan ang pagdami ng mga weeds na pwedeng magdala ng sakit. Ang mulching ay nakakatulong upang mapanatili ang kalusugan ng lupa at maiwasan ang mga sakit na dulot ng mga weeds.',
                    38 => 'Magpatupad ng preemptive pest control measures tulad ng pag-spray ng neem oil o iba pang organic pest control products. Ang mga preemptive measures ay makakatulong upang maiwasan ang pagdami ng mga peste bago pa man magdulot ng malalang epekto.',
                    39 => 'I-monitor ang mga halaman para sa mga senyales ng sakit at mag-apply ng fungicides sa unang pagkakataon ng pag-aalala. Ang maagap na pag-aalaga at pagtuklas ng mga sintomas ng sakit ay makakatulong upang maiwasan ang pagkalat ng impeksyon.',
                    40 => 'Iwasan ang paggamit ng mga contaminated tools at kagamitan upang mapigilan ang pagkalat ng sakit. Ang mga kagamitan tulad ng mga gunting, pala, at iba pa ay dapat linisin bago gamitin upang hindi madala ang mga pathogens mula sa isang halaman patungo sa iba.',
                    41 => 'Mag-apply ng preventive treatments tulad ng copper-based fungicides upang mapigilan ang mga fungal infections. Ang copper-based fungicides ay isang uri ng epektibong solusyon sa pagsugpo ng mga fungal diseases sa mga halaman.',
                    42 => 'Magpatupad ng integrated pest and disease management program upang pamahalaan ang parehong peste at sakit. Ang isang holistic na approach na kinikilala ang parehong mga peste at sakit ay makakatulong upang masugpo ang mga ito nang hindi gumagamit ng labis na kemikal.',
                    43 => 'Mag-apply ng foliar sprays upang mapabuti ang kalusugan ng mga halaman at magbigay ng proteksyon laban sa mga sakit. Ang foliar sprays ay maaaring magbigay ng mga nutrisyon at proteksyon laban sa mga sakit na dulot ng fungi at pests.',
                    44 => 'Iwasan ang sobrang nitrogen application upang maiwasan ang labis na paglago ng mga halaman na pwedeng magdulot ng sakit. Ang sobrang nitrogen ay maaaring magdulot ng mga problema sa kalusugan ng halaman at magpahina ng resistensya laban sa mga peste at sakit.',
                    45 => 'Magpatupad ng sanitation practices sa buong lugar ng pagtatanim upang maiwasan ang kontaminasyon. Ang malinis na kapaligiran ay nakakatulong upang mabawasan ang mga pathogens na maaaring magdulot ng sakit.',
                    46 => 'I-monitor ang mga kondisyon ng klima at tiyakin na hindi ito magiging paborable sa mga sakit na dulot ng kahalumigmigan. Ang pag-monitor ng mga kondisyon ng panahon ay mahalaga upang maiwasan ang pagdami ng mga fungal spores at pathogens.',
                    47 => 'Magpatupad ng sustainable farming practices upang mapanatili ang kalusugan ng ecosystem at ng mga halaman. Ang mga sustainable practices tulad ng tamang pag-aalaga sa lupa at tamang crop rotation ay nakakatulong upang maiwasan ang mga sakit.',
                    48 => 'Iwasan ang paggamit ng mga produktong kemikal na hindi ligtas para sa kapaligiran at kalusugan ng tao. Ang paggamit ng mga ligtas at epektibong produkto ay nakakatulong upang maprotektahan ang mga tanim, tao, at kapaligiran.',
                    49 => 'Magpatupad ng early detection system upang mabilis na matukoy ang mga sintomas ng sakit at maagapan ito. Ang maagang pag-detect ng mga sintomas ay mahalaga upang mapigilan ang mabilis na pagkalat ng mga sakit.',
                    50 => 'Mag-apply ng fungicides sa tamang oras at ayon sa rekomendadong dosis upang maiwasan ang resistance development. Ang wastong paggamit ng fungicides ay nakakatulong upang maiwasan ang pagiging resistant ng mga fungi sa mga produkto.',
                    51 => 'Magpatupad ng crop rotation upang maiwasan ang pagdami ng mga soil-borne pathogens. Ang tamang pagpapalit ng mga pananim ay nakakatulong upang maiwasan ang pagkaubos ng mga nutrients sa lupa at mapigilan ang pagdami ng mga sakit.',
                    52 => 'Gamitin ang mga resistant na varieties ng mga halaman na may mataas na resistensya laban sa mga karaniwang sakit. Ang paggamit ng mga hybrid o genetically resistant na varieties ay makakatulong upang mabawasan ang panganib ng sakit.',
                    53 => 'Mag-ingat sa tamang pag-iimbak ng mga binhi upang maiwasan ang pagkakaroon ng mga pathogens mula sa mga contaminated seeds. Ang pagpapasiguro na ang mga binhi ay malinis at wala pang pathogens ay makakatulong sa pag-iwas sa sakit.',
                    54 => 'Siguraduhing malinis ang mga kagamitan tulad ng mga sprayer at mga gamit sa paggupit upang maiwasan ang kontaminasyon. Ang regular na paglilinis ng mga kagamitan ay makakatulong upang mapanatili ang kalinisan at maiwasan ang pagkalat ng sakit.',
                    55 => 'Magpatupad ng integrated soil management upang mapanatili ang balanse ng nutrients sa lupa at mapigilan ang mga sakit na dulot ng nutrient deficiencies. Ang tamang soil management ay nagpapalakas sa kalusugan ng halaman at nagpo-promote ng natural na resistensya.',
                    56 => 'Gumamit ng mga organic control measures tulad ng biocontrol agents upang maiwasan ang paggamit ng mga kemikal. Ang biocontrol agents tulad ng mga beneficial bacteria at fungi ay tumutulong sa pagpigil ng mga peste at sakit sa mga halaman.',
                    57 => 'Magpatupad ng regular na pag-inspeksyon sa mga pananim upang agad na matukoy ang mga palatandaan ng sakit. Ang regular na pag-check sa kalagayan ng mga halaman ay makakatulong sa maagang pag-detect ng mga problema at pagtugon sa mga ito.',
                    58 => 'Siguraduhin ang tamang pag-aalaga ng mga halaman sa panahon ng stress tulad ng tagtuyot o sobrang init upang hindi maging vulnerable sa mga sakit. Ang pagpapalakas ng kalusugan ng halaman sa mga stressful na kondisyon ay nakakatulong upang maiwasan ang pag-atake ng mga pathogens.',
                    59 => 'Mag-apply ng mulching upang mapanatili ang tamang temperature sa lupa at mapigilan ang mga fungal diseases na dulot ng pagbabago sa temperatura. Ang mulching ay nakakatulong upang mapanatili ang tamang temperatura at moisture levels sa paligid ng mga halaman.',
                    60 => 'Gamitin ang mga fungicide at pesticide treatments nang wasto at ayon sa mga gabay upang hindi maging sanhi ng resistensya sa mga pathogens at peste. Ang paggamit ng mga produkto ayon sa mga rekomendasyon ay makakatulong upang maiwasan ang hindi tamang paggamit na maaaring magdulot ng resistance.',
                    61 => 'Iwasan ang pagdadala ng mga sakit mula sa ibang lugar sa pamamagitan ng pagpapasiguro na ang mga kagamitan at mga tao ay hindi nagdadala ng mga pathogens. Ang pagsunod sa mga sanitation protocols ay makakatulong upang hindi makapagdala ng sakit mula sa ibang mga lugar.',
                    62 => 'Siguraduhin na may sapat na sirkulasyon ng hangin sa paligid ng mga halaman upang maiwasan ang pagdami ng mga fungal infections. Ang maayos na airflow ay nakakatulong upang mapanatili ang kalusugan ng halaman at maiwasan ang mga sakit na dulot ng kahalumigmigan.',
                    63 => 'Mag-apply ng treatments na may copper sulfate upang mapigilan ang bacterial diseases sa mga halaman. Ang copper sulfate ay isang epektibong produkto laban sa mga bacterial infections at isang popular na solusyon sa organic farming.',
                    64 => 'Gamitin ang mga high-quality na compost at organic amendments upang mapabuti ang kalusugan ng lupa at mga halaman. Ang composting ay isang natural na paraan upang mapabuti ang kalusugan ng lupa at bawasan ang panganib ng mga soil-borne diseases.',
                    65 => 'Magpatupad ng mga preventive na hakbang sa simula ng season tulad ng pag-spray ng mga organic treatments bago magsimula ang panahon ng sakit. Ang proactive na approach ay makakatulong upang mapigilan ang pagdami ng mga pests at sakit bago pa man ito magsimula.',
                    66 => 'Palakasin ang mga lokal na ekosistema sa pamamagitan ng pagtatanim ng mga puno at halaman na makakatulong sa natural pest control. Ang pagtatanim ng mga companion plants at natural na pest predators ay nakakatulong upang mapanatili ang balanse sa ekosistema. Ang mga ito ay nagiging tahanan ng mga natural na kalaban ng mga peste, kaya’t nakatutulong sa pagpigil sa pagdami ng mga peste at sakit sa iyong mga pananim.',
                    67 => 'Magpatupad ng soil solarization upang mapatay ang mga harmful pathogens sa lupa bago magtanim ng bagong mga pananim. Ang soil solarization ay isang technique na gumagamit ng init mula sa araw upang patayin ang mga pathogens sa ibabaw ng lupa. Ang pamamaraang ito ay hindi gumagamit ng kemikal, kaya’t eco-friendly ito at nakakatulong sa pagpapabuti ng kalusugan ng lupa.',
                    68 => 'Iwasan ang paggamit ng sobrang pesticidal products na maaaring magdulot ng environmental damage at pesticide resistance. Ang tamang paggamit at pag-iwas sa labis na paggamit ng mga kemikal ay makakatulong upang maprotektahan ang kalikasan at kalusugan ng mga halaman. Ang labis na paggamit ng pesticide ay maaaring magdulot ng resistensya sa mga peste, kaya’t kailangan ng balanseng approach sa pest control.',
                    69 => 'Iwasan ang paggamit ng mga non-organic na produkto na maaaring magdulot ng panganib sa mga kalapit na ekosistema at mga organismo. Ang paggamit ng mga organic at sustainable farming practices ay makakatulong upang maiwasan ang mga negatibong epekto sa kalikasan. Sa ganitong paraan, mapapalakas ang biodiversity at mapoprotektahan ang mga natural na yaman.',
                    70 => 'Magpatupad ng transparent na sistema ng pagsubok at monitoring ng mga pesticide at fungicide residues upang matiyak na hindi ito nakakasama sa mga produkto. Ang regular na pagsusuri sa mga residues ay nakakatulong upang mapanatili ang kaligtasan ng mga produkto para sa pagkonsumo. Mahalaga ang transparency upang matiyak na ang mga produkto ay ligtas para sa kalusugan ng mga konsyumer.',
                    71 => 'I-monitor ang temperatura at halumigmig sa paligid ng mga pananim upang maiwasan ang mga fungal infections na lumalaganap sa mataas na kahalumigmigan. Ang tamang pag-monitor ng klima ay makakatulong upang mapigilan ang pagdami ng mga sakit. Ang pagsubok sa mga klima ay mahalaga upang malaman kung kailan ang pinakamataas na panganib para sa mga fungal infections.',
                    72 => 'Magpatupad ng biological control methods tulad ng pag-gamit ng mga natural predators upang mapigilan ang pagdami ng mga peste. Ang biological control ay isang natural at environment-friendly na paraan upang sugpuin ang mga pests at sakit. Sa ganitong paraan, ang kalikasan ang magbibigay ng solusyon, hindi ang mga kemikal.',
                    73 => 'Iwasan ang paggamit ng mga damaged na seedlings at itapon ang mga infected na bahagi ng halaman upang hindi maikalat ang sakit. Ang regular na pag-inspeksyon at tamang disposal ng mga infected na halaman ay makakatulong upang mapigilan ang pagkalat ng sakit. Siguraduhin na ang mga seedlings ay malusog bago itanim upang maiwasan ang pagkalat ng mga impeksyon.',
                    74 => 'Gumamit ng mga anti-fungal sprays sa mga halaman sa panahon ng mataas na panganib ng fungal diseases. Ang regular na pag-spray ng mga safe na fungicides ay makakatulong sa pagpigil ng mga fungal infections. Ang mga anti-fungal sprays ay mabisang proteksyon laban sa mga fungal pathogens na maaaring magdulot ng malawakang pagkalat ng sakit.',
                    75 => 'Magpatupad ng tamang crop spacing upang magbigay ng sapat na espasyo sa mga halaman, na nakakatulong sa mas maayos na sirkulasyon ng hangin at hindi nagiging dahilan ng pagdami ng mga sakit. Ang tamang spacing ay makakatulong upang ang mga halaman ay hindi magkasalamuha, kaya’t napipigilan ang pagkalat ng sakit at peste.',
                    76 => 'Gamitin ang mga eco-friendly na pest control agents tulad ng neem oil at insecticidal soaps upang mapigilan ang pagdami ng mga peste nang hindi nagiging sanhi ng polusyon o panganib sa kalikasan. Ang mga eco-friendly na pest control agents ay ligtas gamitin at nakakatulong upang maprotektahan ang kapaligiran.',
                    77 => 'Magpatupad ng regular na pag-audit at inspection ng lahat ng mga kagamitan at pamamaraan sa pagsasaka upang matiyak na ang lahat ng mga hakbang ay sumusunod sa mga best practices para sa pest control at disease prevention. Ang regular na pag-inspeksyon ay nakakatulong upang maiwasan ang paggamit ng mga hindi epektibong pamamaraan at tiyakin ang kaligtasan ng mga pananim.',
                    78 => 'Iwasan ang pagpaparami ng mga peste at sakit sa pamamagitan ng tamang pag-aalaga ng mga hayop na nakapaligid sa mga taniman. Ang mga hayop ay maaaring magdala ng mga pathogens, kayat mahalaga ang kanilang wastong pangangalaga. Ang tamang hygiene sa mga hayop ay makakatulong upang maiwasan ang pagkalat ng mga sakit sa paligid ng taniman.',
                    79 => 'Gamitin ang mga mulching materials upang mapanatili ang moisture sa lupa at magbigay proteksyon laban sa soil erosion. Ang mulching ay nakakatulong upang mapanatili ang kalusugan ng lupa at maiwasan ang mga soil-borne diseases. Ang mulching ay nakakatulong din upang maiwasan ang sobrang init at magbigay ng nutrients sa lupa.',
                    80 => 'Siguraduhin na ang mga irrigation systems ay hindi nagiging dahilan ng waterlogging at pagkakaroon ng fungal diseases. Ang tamang pamamahagi ng tubig ay mahalaga upang maiwasan ang mga kondisyon na nagpapalaganap ng sakit. Ang waterlogging ay nagpapataas ng panganib ng fungal infections, kaya’t mahalaga ang tamang sistema ng patubig.',
                    81 => 'Palakasin ang ecosystem diversity sa pamamagitan ng pagtatanim ng ibat ibang uri ng halaman na may natural na proteksyon laban sa mga sakit. Ang pagkakaroon ng diverse na taniman ay nakakatulong upang mapigilan ang pagkalat ng sakit. Sa ganitong paraan, ang isang uri ng halaman ay makakatulong upang protektahan ang iba laban sa pests at diseases.',
                    82 => 'Magpatupad ng sanitation measures tulad ng paglilinis ng mga kagamitan at lugar ng trabaho upang maiwasan ang contamination ng mga pananim mula sa mga sakit na dulot ng mga nakaraang operasyon. Ang malinis na kapaligiran ay makakatulong upang mabawasan ang pagkalat ng mga pathogens.',
                    83 => 'Gamitin ang mga sustainable farming practices tulad ng agroforestry upang mapanatili ang natural na balanse ng ekosistema at maiwasan ang mga sakit sa mga pananim. Ang agroforestry ay nakakatulong upang magbigay ng natural na proteksyon laban sa mga sakit at peste. Ang pagsasama ng mga puno sa mga pananim ay nakakatulong sa pagpapabuti ng lupa at nagpapatibay sa kalusugan ng mga halaman.',
                    84 => 'Iwasan ang sobrang pagpapabigat ng mga halaman sa pamamagitan ng paggamit ng tamang fertilizer at pest control upang hindi magdulot ng stress na magpapahina sa kanilang immune system. Ang sobrang kemikal na paggamit ay maaaring magdulot ng stress sa halaman at magpahina sa kanilang kakayahan laban sa mga peste at sakit.',
                    85 => 'Magpatupad ng mga localized na preventative strategies upang maiwasan ang mga sakit na umaapekto lamang sa isang partikular na rehiyon. Ang localized approach ay mas epektibo sa pagtutok sa mga partikular na sakit na nasa isang lugar. Sa ganitong paraan, maaaring tutukan ang mga specific na problemang pangkalusugan na naaapektuhan ng lokal na klima.',
                    86 => 'Gamitin ang mga biodegradable na mga pest control agents upang maiwasan ang long-term environmental damage. Ang mga natural na pest control agents ay mas ligtas gamitin at hindi nakakasama sa kalikasan. Ang paggamit ng biodegradable agents ay nakakatulong upang maprotektahan ang kapaligiran at kalusugan ng mga hayop.',
                    87 => 'Gumamit ng mga weather-based models upang mag-predict ng mga sakit at peste sa pamamagitan ng climate data, kaya makakapaghanda nang maaga at maiwasan ang mga outbreak ng sakit sa mga pananim. Ang pag-monitor ng mga trend sa klima ay makakatulong upang magplano ng mga preventive measures at maiwasan ang mga sakit.',
                    88 => 'Mag-imbak ng mga kemikal sa isang ligtas at kontroladong lugar upang maiwasan ang kontaminasyon at aksidente na maaaring magdulot ng sakit o panganib sa kalusugan ng mga tao at hayop. Ang tamang storage ay mahalaga upang mapanatili ang kaligtasan ng mga kemikal at maiwasan ang aksidente.',
                    89 => 'Siguraduhing maayos ang drainage systems ng mga taniman upang maiwasan ang pagtaas ng kahalumigmigan sa lupa, na siyang sanhi ng ibat ibang fungal at bacterial diseases. Ang tamang drainage ay nakakatulong upang mapanatili ang tamang moisture level sa lupa at maiwasan ang waterlogging.',
                    90 => 'Magpatupad ng integrated pest management (IPM) na gumagamit ng natural at mekanikal na pamamaraan sa pag-kontrol ng peste, kasabay ng tamang paggamit ng pest control products upang mapanatili ang kalusugan ng mga pananim. Ang IPM ay isang sustainable approach sa pest control na nagbabalansi sa mga biological, cultural, at chemical methods.',
                    91 => 'Alisin ang mga apektadong halaman at mag-apply ng fungicides. Iwasang magpatubig mula sa ibabaw upang hindi magdulot ng fungal infections. Ang tamang pamamahagi ng tubig at fungicides ay makakatulong upang pigilan ang pagkalat ng fungal infections.',
                    92 => 'Magpatupad ng integrated pest management upang makontrol ang infestation. Ang IPM ay isang epektibong paraan upang mapanatili ang kalusugan ng mga pananim at maiwasan ang malawakang pagkalat ng mga peste.',
                    93 => 'Magpatupad ng mga mahigpit na pagsubok sa mga tanim at gumamit ng organic treatments upang kontrolin ang infestation. Ang mga organic solutions ay ligtas gamitin at makakatulong sa pagpigil ng mga peste at sakit.',
                    94 => 'Mag-apply ng pest control products at gumamit ng fungicides upang makontrol ang fungal spread. Ang paggamit ng pest control products ay makakatulong upang mapigilan ang paglaki ng mga peste at sakit.',
                    95 => 'Mag-apply ng fungicides at siguruhing may tamang spacing ng mga tanim upang maiwasan ang spread ng sakit. Ang tamang spacing ay nakakatulong upang mapigilan ang pagkalat ng sakit mula sa isang halaman patungo sa iba.',
                    96 => 'Mag-apply ng fungicides at gumamit ng resistant varieties upang pamahalaan ang problema. Ang paggamit ng mga varieties na may natural na resistensya ay makakatulong upang mabawasan ang pangangailangan para sa mga kemikal.',
                    97 => 'Mag-rotate ng mga crops at gumamit ng fungicides upang makontrol ang fungal growth. Ang crop rotation ay isang mabisang paraan upang maiwasan ang soil-borne diseases.',
                    98 => 'Mag-apply ng insecticides upang kontrolin ang mga peste at gumamit ng fungicides upang maiwasan ang fungal diseases. Ang kombinasyong ito ay makakatulong upang pigilan ang pagdami ng mga pests at sakit.',
                    99 => 'Mag-rotate ng crops at gumamit ng pest-resistant varieties upang maiwasan ang mga soil-borne pathogens. Ang rotation ng mga crops ay nakakatulong upang mapanatili ang kalusugan ng lupa at maiwasan ang pagdami ng pathogens.'
                ];
                
                
 // Retrieve the disease name, description, and solution based on the prediction index
 $disease = $diseaseMapping[$predictionIndex] ?? ['name' => 'Unknown Disease', 'description' => 'No description available.'];
 $solution = $solutionMapping[$predictionIndex] ?? 'No solution available.';
 $impact = is_array($potentialImpactMapping[$predictionIndex] ?? null) 
 ? $potentialImpactMapping[$predictionIndex]['impact'] 
 : 'No impact data available.'; 
 
 // Save the analysis result to the database
 ImageAnalysis::create([
    'disease_name' => $disease['name'],
    'detection_count' => 1,
    'average_confidence' => $confidence,
    'date_analyzed' => Carbon::today()->toDateString(),
    'total_analyses' => ImageAnalysis::count() + 1,
    'notifications' => $notifications, // Pass notifications properly to the view
]);


 return view('image_analysis.result', [
     'prediction' => $disease['name'],
     'description' => $disease['description'],
     'solution' => $solution,
     'confidence' => $confidence,
     'impact' => $impact,
     'notifications' => $notifications, // Pass notifications properly to the view

 ]);
} else {
 return view('image_analysis.result', [
     'prediction' => 'Unknown Disease',
     'description' => 'No description available.',
     'solution' => 'No solution available.',
     'confidence' => 0,
     'impact' => 'No impact information available.',
     'notifications' => $notifications, // Pass notifications properly to the view
     // Default impact message

 ]);
}
if (isset($data['error']) && $data['error'] == 'Not a plant image') {
    return view('image_analysis.result', [
        'prediction' => 'Not a Plant',
        'description' => 'The uploaded image does not appear to be a plant.',
        'solution' => 'Please upload an image of a plant for analysis.',
        'confidence' => 0,
        'impact' => 'No impact information available.',
        'notifications' => $notifications,
    ]);
}
}
}

    private function handleBase64Image($base64Image)
    {
        $image = str_replace('data:image/png;base64,', '', $base64Image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'captured_image_' . time() . '.png';
        $imagePath = storage_path('app/images/' . $imageName);

        file_put_contents($imagePath, base64_decode($image));

        return $imagePath;
    }

    private function sendToFlaskAPI($imagePath)
    {
    
        $flaskApiUrl = 'http://127.0.0.1:5000/predict';

        return Http::attach(
            'file', file_get_contents($imagePath), 'image.jpg'
        )->post($flaskApiUrl);
    }
}
