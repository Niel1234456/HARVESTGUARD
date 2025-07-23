<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Disease Prediction Result</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/result.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<body>

@include('farmer.navbar')
@include('farmer.header')
@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif
        <div class="container">
            <h1>Plant Disease Prediction Result</h1>
            <h2>Prediction: {{ $prediction }}</h2>
            <p><b>Confidence:</b> {{ $confidence }}%</p>
            <p><b>Description:</b> {{ $description }}</p>
            <p><b>Impact:</b> {{ $impact }}</p> <!-- Display the impact of the disease -->

            <div class="button-container">
                <form id="pdfForm" action="{{ route('farmer.generate.pdf.report') }}" method="POST">
                    @csrf
                    <input type="hidden" name="prediction" value="{{ $prediction }}">
                    <input type="hidden" name="confidence" value="{{ $confidence }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <input type="hidden" name="solution" value="{{ $solution }}">
                    <input type="hidden" name="impact" value="{{ $impact }}"> <!-- Include impact in the PDF -->
                    @if(isset($imageUrl))
    <img src="{{ $imageUrl }}" style="width: 300px; height: auto;" alt="Analyzed Plant Image">
@endif
                    <div class="form-group">
                        <label>
                        <button id="pdfBtn" type="submit">Convert to PDF</button>
                            <input type="checkbox" name="sendToAdmin" value="1"> Send to Admin
                        </label>
                    </div>
                </form>
                <button id="solutionBtn">Solution</button>
            </div>

            <!-- The Modal -->
            <div id="solutionModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Solution</h2>
                    <p>{{ $solution }}</p>
                    <h5>NOTE: These possible solutions are optional, we advise converting this into a PDF REPORT to notify the Agriculture Office of Carmona City</h5>
                </div>
            </div>

            <a href="{{ route('farmer.image.analysis.form') }}" class="analyze-link">Analyze Another Image</a>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("solutionModal");

        // Get the button that opens the modal
        var btn = document.getElementById("solutionBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
        });
    });
    </script>
</body>
</html>
