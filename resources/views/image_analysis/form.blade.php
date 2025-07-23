<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Analysis</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/analysis.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
@include('farmer.navbar')
@include('farmer.header')

<br>  
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif   
<h1>PLANT DISEASE DETECTION</h1>
<form id="imageForm" class = "image" action="{{ route('farmer.image.analysis.analyze') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="custom-file-container">
        <span class="file-input-label">Image Preview</span>
        <input type="file" name="image" id="imageInput" accept="image/*" class="file-input-container">
        <img id="imagePreview" style="display:none;" />
    </div>
    <div class="camera-container" id="cameraContainer" style="display:none;">
        <video id="video" autoplay></video>
        <canvas id="canvas" style="display:none;"></canvas>
    </div>
<center>
<button type="button" id="chooseImageButton">upload file</button>
<button type="button" id="switchCameraButton">
    <i class="fas fa-sync-alt"></i>
</button>
<button type="button" id="captureButton">take picture</button>
</center>
    <div class="description-input-container">
        <label for="descriptionInput" class="description-label"></label>
        <textarea id="descriptionInput" name="description" rows="4" placeholder="Deskripsyon ng Larawan"></textarea>
    </div>

    <input type="hidden" name="capturedImage" id="capturedImage">
</form>

<!-- Button moved outside the form -->
<div class="describe-button-container">
    <button type="submit" form="imageForm" id="describeImageButton">Describe Image</button>
</div>

<div class="warning-container">
    <h4><b>PAALALA</b></h4>
    <p>Ang seksyong ito ay ginagamit upang ilarawan ang posibleng sakit ng halaman sa iyong mga pananim. Maaari ka ring magdagdag ng mga paglalarawan sa iyong mga larawan upang gawing mas tumpak ang pagsusuri. Mga larawan lamang ng halaman o pananim ang maaaring i-upload.</p>
</div>

@if ($errors->any())
    <div class="error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    let videoStream;
    let currentFacingMode = "environment"; // Default to back camera

    function startCamera(facingMode) {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({ video: { facingMode: facingMode } })
            .then(function (stream) {
                videoStream = stream;
                document.getElementById('video').srcObject = stream;
            })
            .catch(function (error) {
                console.error('Error accessing the camera: ', error);
            });
    }

    function showCamera() {
        document.getElementById('cameraContainer').style.display = 'block';
        startCamera(currentFacingMode);
    }

    function hideCamera() {
        document.getElementById('cameraContainer').style.display = 'none';
    }

    document.getElementById('switchCameraButton').addEventListener('click', function () {
        currentFacingMode = (currentFacingMode === "environment") ? "user" : "environment";
        startCamera(currentFacingMode);
    });

    document.getElementById('chooseImageButton').addEventListener('click', function () {
        hideCamera();
        document.getElementById('imageInput').click();
    });

    document.getElementById('imageInput').addEventListener('change', function (event) {
        var input = event.target;
        var imagePreview = document.getElementById('imagePreview');
        var label = document.querySelector('.file-input-label');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                label.style.display = 'none';
                document.getElementById('capturedImage').value = '';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            imagePreview.style.display = 'none';
            label.style.display = 'block';
        }
    });
    document.getElementById('captureButton').addEventListener('click', function () {
    showCamera(); // Open the camera, but don't take a picture yet.

    setTimeout(function () {
        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');
        var video = document.getElementById('video');

        // Ensure the camera stream has started before capturing
        if (video.videoWidth > 0 && video.videoHeight > 0) { 
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            var imagePreview = document.getElementById('imagePreview');
            imagePreview.src = canvas.toDataURL('image/png');
            imagePreview.style.display = 'block';
            document.getElementById('capturedImage').value = canvas.toDataURL('image/png');
            document.querySelector('.file-input-label').style.display = 'none';

            // Keep camera open for multiple shots (remove hideCamera)
        } else {
            console.error("Camera feed is not ready yet.");
        }
    }, 1000); // Increased delay to ensure video is fully loaded
});



    window.onload = function () {
        hideCamera();
    };

    $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
        });
    });

// Show SweetAlert loading when form is submitted
document.getElementById('imageForm').addEventListener('submit', function(e) {
    Swal.fire({
        title: 'Processing...',
        text: 'Analyzing your image, please wait.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
});

</script>
</body>
</html>