<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/forgot-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <style>
        .name {
            height: 40px;
            width: 250px;
            border-radius: 5px;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-button:hover {
            background-color: #c82333;
        }

        .modal-button-1
        {
            background-color:rgb(61, 220, 53);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-button-1:hover {
            background-color:rgb(28, 161, 41);
        }

    </style>
</head>

<body>

<div class="container">
    <center><h2>Reset Password</h2>

    <!-- Display success message inside modal -->
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var successModal = document.getElementById("successModal");
                successModal.style.display = "block";
            });
        </script>
    @endif

    <!-- Display error message inside modal -->
    @if(session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var errorModal = document.getElementById("errorModal");
                errorModal.style.display = "block";
            });
        </script>
    @endif

    <form id="passwordResetForm" action="{{ route('farmer.farmer.update.password', ['id' => $id]) }}" method="POST">
        @csrf
        <label for="password">Bagong Password:</label>
        <input class="name" type="password" name="password" required>
        <br>
        <label for="password_confirmation">Kompirmahin ang Password:</label>
        <input class="name" type="password" name="password_confirmation" required>

        <button type="submit" class="button">Baguhin Password</button>
    </form>
</div>
</center> 

<!-- Error Modal (Same Password Warning) -->
<div id="errorModal" class="modal">
    <div class="modal-content">
        <p><strong>Error:</strong> Ang bagong Password na iyong nilagay ay dapat hindi pareho sa luma.</p>
        <button class="modal-button" id="errorModalClose">OK</button>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal">
    <div class="modal-content">
        <p>Ang iyong Password ay matagumpay na na-reset!</p>
        <button class="modal-button-1" id="modalOkButton">OK</button>
    </div>
</div>
<!-- Mismatch Password Modal -->
<div id="passwordMismatchModal" class="modal">
    <div class="modal-content">
        <p><strong>Error:</strong> Ang mga password ay hindi tugma!</p>
        <button class="modal-button" id="passwordMismatchModalClose">OK</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var errorModal = document.getElementById("errorModal");
        var errorCloseButton = document.getElementById("errorModalClose");
        var passwordMismatchModal = document.getElementById("passwordMismatchModal");
        var passwordMismatchModalClose = document.getElementById("passwordMismatchModalClose");
        var form = document.getElementById("passwordResetForm");
        var password = document.querySelector("input[name='password']");
        var passwordConfirmation = document.querySelector("input[name='password_confirmation']");
        
        form.addEventListener("submit", function (event) {
            if (password.value !== passwordConfirmation.value) {
                event.preventDefault(); // Prevent form submission
                passwordMismatchModal.style.display = "block";
            }
        });

        // Close modals when buttons are clicked
        if (passwordMismatchModalClose) {
            passwordMismatchModalClose.addEventListener("click", function () {
                passwordMismatchModal.style.display = "none";
            });
        }

        if (errorCloseButton) {
            errorCloseButton.addEventListener("click", function () {
                errorModal.style.display = "none";
            });
        }
    });

    document.getElementById("modalOkButton").addEventListener("click", function () {
        window.location.href = "{{ route('farmer.login') }}"; // Redirect to login page
    });
</script>


</body>
