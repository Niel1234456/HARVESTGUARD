<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">


</head>

<body>
    <div class="container">
        <div class="background-side"></div>
        <div class="login-form">
            <img src="/assets/img/Green_and_White_Flat_Illustrative_Feeding_Plant_Agriculture_Logo__2_-removebg-preview.png" alt="Logo" class="logo">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                {{ __('D.A ADMIN LOGIN') }}
            </h2>

                        <!-- Error Pop-Ups -->
             @if ($errors->any())
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: "{{ $errors->first() }}",
                    });
                    
                </script>
            @endif

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form id="login-form" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <!-- Email Address -->
                <div class="field">
                    <label for="email" class="input-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="field relative">
                    <label for="password" class="input-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="relative">
                        <input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                        <i id="togglePassword"></i>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <center>
                    <x-primary-button id="login-button" class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </center>

                <!-- Privacy Act Checkbox -->


                <!-- Remember Me -->
                <div class="form-footer">


                    <a href="{{ route('admin.admin.forgot-password') }}" class="forgot-password-link">
                    Forgot Password?
                    </a>
                </div>

                <div class="field">
                    <div class="privacy-checkbox-container">
                        <input id="privacy" type="checkbox">
                        <span class="privacy-text">I agree to the Privacy Act</span>
                    </div>
                </div>


                <center>
                    <div class="link">
                        Not a member? <a href="{{ route('admin.register') }}">register now</a>
                    </div>
                </center>
            </form>
        </div>
    </div>

    <!-- Modal for Privacy Act -->
    <div id="privacy-act-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Privacy Act Information</h2>
            <p>HarvestGuard is committed to safeguarding your personal information in compliance with the Data Privacy Act of 2012 (Republic Act No. 10173) of the Philippines. By registering and logging into the HarvestGuard system, you consent to the collection and processing of your personal data, including your full name, email address, username, password, IP address, 
                phone number, and position/role. This information is necessary for creating your account, verifying your identity, enabling secure access, and facilitating communication within the system. We take all reasonable measures, such as encryption and access controls, to ensure the security and confidentiality of your data. We also conduct periodic audits to prevent unauthorized access. <br> <br>
                <br> You have the right to access, correct, or request the deletion of your personal data in accordance with the provisions of the Data Privacy Act. Your personal data will not be shared with third parties except when required by law or for essential services, and any third-party partners will be contractually bound to protect your privacy. HarvestGuard may use cookies and other tracking technologies to enhance your user experience; however, you can manage these preferences through your browser settings. Your data will be retained only as long as necessary for the purposes it was collected and will be securely disposed of when no longer needed.
                By continuing to use the system, you acknowledge and consent to the collection and processing of your personal data as described in this Privacy Act Statement..</p>
                <div class="terms-checkbox-container">
                <input id="terms" type="checkbox" name="terms">
                <label for="terms" class="terms-text">I understand the terms and conditions</label>
            </div>
            </div>
    </div>

    <script>
// Modal functionality
const modal = document.getElementById("privacy-act-modal");
const privacyCheckbox = document.getElementById("privacy");
const termsCheckbox = document.getElementById("terms");
const closeModal = document.querySelector(".close");

// Show modal when Privacy Act checkbox is checked
privacyCheckbox.addEventListener("change", function () {
    modal.style.display = privacyCheckbox.checked ? "block" : "none";
});

// Close modal when 'x' is clicked
closeModal.addEventListener("click", function () {
    modal.style.display = "none";
});

// Close modal when clicking outside
window.addEventListener("click", function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Automatically close the modal when 'I understand the terms and conditions' is checked
termsCheckbox.addEventListener("change", function () {
    if (termsCheckbox.checked) {
        modal.style.display = "none";
    }
});

// Validate checkboxes before submitting
document.getElementById("login-form").addEventListener("submit", function (event) {
    if (!privacyCheckbox.checked || !termsCheckbox.checked) {
        event.preventDefault(); // Prevent form submission

        // Show a single alert for both checkboxes
        alert("You must agree to the Privacy Act and understand the Terms and Conditions before proceeding.");
    }
});
document.getElementById('togglePassword').addEventListener('click', function() {
        let passwordInput = document.getElementById('password');
        let icon = this;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
    </script>
</body>
</html>
