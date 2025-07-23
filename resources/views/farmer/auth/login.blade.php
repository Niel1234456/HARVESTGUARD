<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<style>
    @media (min-width: 230px) and (max-width: 480px) {

       .privacy-checkbox-container {
        display: flex;
        align-items:baseline;
        margin-left: -50%;
        }
    }
    @media (min-width: 480px) and (max-width: 550px) {

        .privacy-checkbox-container {
        margin-left: -45%;
        }
    }
</style>

<body>
    <div class="container">
        <div class="background-side"></div>
        <div class="login-form">
            <img src="/assets/img/Green_and_White_Flat_Illustrative_Feeding_Plant_Agriculture_Logo__2_-removebg-preview.png" alt="Logo" class="logo">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                {{ __('Farmer Login') }}
            </h2>

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

            <form id="login-form" method="POST" action="{{ route('farmer.login') }}">
                @csrf

                <!-- Name -->
                <div class="field">
                    <label for="first_name" class="input-label">
                        <i class="fas fa-user"></i>Unang Pangalan
                    </label>
                    <input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first name">
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                                <!-- Birthday -->
                <div class="field">
                    <label for="birthday" class="input-label">
                        <i class="fas fa-calendar-alt"></i> Kaarawan
                    </label>
                    <input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" required>
                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="field">
                    <label for="password" class="input-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <center>
                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </center>
            </form>

            <div class="form-footer">
                <a href="{{ route('farmer.farmer.forgot-password') }}" class="forgot-password-link">
                Nakalimutan ang Password?
                    </a>
            </div>
            <div class="field">
                    <div class="privacy-checkbox-container">
                        <input id="privacy" type="checkbox" name="privacy">
                        <span class="privacy-text">Sang-ayon sa Privacy Act</span>
                    </div>
                </div>
            <center> 
                <div class="link">
                    Hindi Myembro? <a href="{{ route('farmer.register') }}">Mag Rehistro</a>
                </div>
            </center>
        </div>
    </div>

    <!-- Privacy Act Modal -->
    <div id="privacy-act-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Privacy Act Information</h2>
            <p>Ang HarvestGuard ay nakatuon sa pagprotekta sa inyong personal na impormasyon alinsunod sa Data Privacy Act of 2012 (Republic Act No. 10173) ng Pilipinas. Sa pamamagitan ng pagrehistro at pag-login sa HarvestGuard system, kayo ay pumapayag sa pangangalap at pagproseso ng inyong personal na datos, kabilang ang inyong buong pangalan, email address, username, password, IP address, numero ng telepono, at posisyon/tungkulin. Ang impormasyong ito ay kinakailangan para sa paglikha ng inyong account, pagpapatunay ng inyong pagkakakilanlan, pagbibigay ng secure na access, at pagpapadali ng komunikasyon sa loob ng sistema. Kami ay nagsasagawa ng makatwirang hakbang, tulad ng encryption at access controls, upang matiyak ang seguridad at pagiging kumpidensyal ng inyong datos. Nagsasagawa rin kami ng regular na pagsusuri upang maiwasan ang hindi awtorisadong access. <br><br><br>
                Mayroon kayong karapatang mag-access, magwasto, o humiling ng pagbura ng inyong personal na datos alinsunod sa mga probisyon ng Data Privacy Act. Ang inyong personal na datos ay hindi ibabahagi sa mga ikatlong partido maliban kung kinakailangan ng batas o para sa mahahalagang serbisyo, at ang anumang kasosyo sa ikatlong partido ay kailangang sumunod sa mga kasunduan na poprotektahan ang inyong pribasya. Maaaring gumamit ang HarvestGuard ng cookies at iba pang tracking technologies upang mapahusay ang inyong karanasan; gayunpaman, maaari ninyong pamahalaan ang mga setting na ito sa pamamagitan ng inyong browser. Ang inyong datos ay itatago lamang hangga’t kinakailangan para sa layuning ito at maayos na wawasakin kapag hindi na kailangan.
                Sa patuloy na paggamit ng sistema, kinikilala ninyo at pumapayag sa pangangalap at pagproseso ng inyong personal na datos gaya ng inilarawan sa Pahayag ng Batas sa Pribasya na ito.
            </p>
            <div class="terms-checkbox-container">
                <input id="terms" type="checkbox" name="terms">
                <label for="terms" class="terms-text">Naiintindihan ko ang terms at conditions</label>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
 // Modal functionality
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



    </script>
</body>
</html>
