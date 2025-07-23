<!DOCTYPE html>
<html>
<head>
  <title>Image Recognition with AI</title>
  <!-- Load TensorFlow.js -->
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
  <!-- Load a pre-trained model for image classification -->
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet"></script>
</head>
<body>
  <h1>Image Recognition with AI</h1>
  <input type="file" id="imageUpload" accept="image/*">
  <img id="image" src="#" alt="Uploaded Image" style="max-width: 500px; display: none;">

  <h2>Description:</h2>
  <p id="description"></p>

  <!-- Button to trigger image classification -->
  <button id="classifyButton">Describe Image</button>

  <!-- JavaScript code -->
  <script>
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
