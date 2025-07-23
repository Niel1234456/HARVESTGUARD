<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/forgot-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>

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
</style>

<body>
    <div class="container">
        <center>
            <h2>Reset Password</h2>

            <form method="POST" action="{{ route('admin.admin.reset.password') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <label for="email">Email</label>
                <input class="name" type="email" name="email" required>

                <label for="password">New Password</label>
                <input class="name" type="password" name="password" required>

                <label for="password_confirmation">Confirm Password</label>
                <input class="name" type="password" name="password_confirmation" required>

                <button type="submit" class="button">Reset Password</button>
            </form>
        </center>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <p>Password has been reset successfully!</p>
            <button class="modal-button" onclick="window.location.href='{{ route('admin.login') }}'">OK</button>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <p>{{ session('error') ?? $errors->first('password') }}</p>
            <button class="modal-button" onclick="document.getElementById('errorModal').style.display='none'">OK</button>
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
            var successModal = document.getElementById("successModal");
            var errorModal = document.getElementById("errorModal");

            @if (session('status'))
                successModal.style.display = "block";
            @endif

            @if ($errors->has('password') || session('error'))
                errorModal.style.display = "block";
            @endif
        });
    </script>
</body>
</html>
