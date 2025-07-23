<!DOCTYPE html>
<html>
<head>
    <title>Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            background-color: #184c42;
            width: 20%;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .sidebar a span.icon {
            margin-right: 10px;
        }
        .main-content {
            width: 80%;
            padding: 20px;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header .user-info img {
            border-radius: 50%;
            margin-right: 10px;
        }
        .header .user-info .username {
            font-size: 16px;
        }
        .content {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .content form .upload-section {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
            margin-bottom: 20px;
        }
        .content form .upload-box {
            background-color: #184c42;
            color: white;
            width: 60%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 10px;
            box-sizing: border-box;
        }
        .content form .upload-box input[type="file"] {
            display: none;
        }
        .content form .upload-box label {
            background-color: #f48024;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            color: white;
        }
        .content form .upload-box img {
            max-width: 100%;
            margin-bottom: 20px;
            display: none;
        }
        .content form textarea {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            height: 100px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 10px;
            border: 1px solid #ccc;
            resize: vertical;
            background-color: #f8f8f8;
        }
        .content form button {
            padding: 10px;
            font-size: 16px;
            background-color: #f48024;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
        }
        .content form button[type="button"] {
            background-color: #184c42;
            margin-bottom: 10px;
        }
    </style>
    <!-- Load TensorFlow.js -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <!-- Load a pre-trained model for image classification -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet"></script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <li><a href="{{ route('farmer.dashboard') }}"><span class="icon">🏠</span> Dashboard</a></li>
            <li><a href="{{ route('farmer.farmer.update') }}"><span class="icon">🔄</span> Updates</a></li>
            <li><a href="{{ route('farmer.supplies') }}"><span class="icon">🛒</span>Supply Available</a></li>
            <li><a href="{{ route('farmer.equipment') }}"><span class="icon">🛠️</span> Equipment Available</a></li>
            <a href="#"><span class="icon">👤</span> Profile</a>
            <a href="#"><span class="icon">⚙️</span> Settings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background: none; border: none; color: #fff; font-size: 18px; padding: 0; margin: 0; cursor: pointer;">
                    <span class="icon">🚪</span> Log Out
                </button>
            </form>
            <a href="#"><span class="icon">❓</span> Help</a>
        </div>
        <div class="main-content">
            <div class="header">
                <div class="welcome">
                    Welcome to your dashboard
                </div>
                <div class="user-info">
                    <img src="path/to/user/image.jpg" alt="User Image" width="40">
                    <div class="username">Farmer Name</div>
                </div>
            </div>
            <div class="content">
                <h1>Update</h1>
                <form action="{{ route('farmer.farmer.PdfConvert') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="upload-section">
                        <div class="upload-box">
                            <label for="imageUpload">Drag or Upload files here</label>
                            <input type="file" id="imageUpload" name="file" accept="image/*" required>
                            <img id="image" src="#" alt="Uploaded Image">
                        </div>
                    </div>
                    
                    <h2>Description:</h2>
                    <textarea id="description" name="description" required></textarea>
                    
                    <button type="button" id="classifyButton">Describe Image</button>
                    <button type="submit">Convert to PDF</button>
                </form>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function redirectToUpdatesPage() {
            window.location.href = "{{ route('farmer.farmer.update') }}";
        }

        // Function to load the selected image
        function loadImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const imageElement = document.getElementById('image');
                    imageElement.src = e.target.result;
                    imageElement.style.display = 'block'; // Display the image once it's loaded
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Function to classify the uploaded image
        async function classifyImage() {
            const image = document.getElementById('image');
            const model = await mobilenet.load(); // Load the MobileNet model
            const predictions = await model.classify(image); // Classify the image

            // Get the top three predictions
            const topPredictions = predictions.slice(0, 3);
            
            // Simulate an AI-generated detailed description
            const description = generateDescription(topPredictions);

            // Display the description
            const descriptionElement = document.getElementById('description');
            descriptionElement.textContent = description;
        }

        // Simulate an AI-generated description
        function generateDescription(predictions) {
            let description = `The image likely contains `;
            predictions.forEach((prediction, index) => {
                if (index === predictions.length - 1) {
                    description += `and ${prediction.className.toLowerCase()}.`;
                } else {
                    description += `${prediction.className.toLowerCase()}, `;
                }
            });
            description += ` The confidence levels are: `;
            predictions.forEach((prediction, index) => {
                if (index === predictions.length - 1) {
                    description += `and ${Math.round(prediction.probability * 100)}%.`;
                } else {
                    description += `${Math.round(prediction.probability * 100)}%, `;
                }
            });
            return description;
        }

        // Event listener for the image upload input
        document.getElementById('imageUpload').addEventListener('change', function() {
            loadImage(this);
        });

        // Event listener for the classify button
        document.getElementById('classifyButton').addEventListener('click', function() {
            classifyImage();
        });
    </script>
</body>
</html>
