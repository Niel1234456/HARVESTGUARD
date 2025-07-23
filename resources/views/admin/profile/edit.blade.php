<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Include any required CSS files -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminedit.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

</head>
<body>
@include('admin.header')
@include('admin.navbar')
<div class="container">
    <h2>Edit Information</h2>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('admin.admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-profile-id">
    <!-- Profile and Office ID in one row -->
    <div class="image-upload-row">
        <!-- Profile Picture Upload -->
        <div class="profile-picture-wrapper">
            <input type="file" name="profile_picture" id="profile-picture-input" accept="image/*" onchange="previewProfilePicture(event)" hidden>
            <div class="profile-picture-container" onclick="document.getElementById('profile-picture-input').click();">
                <img id="profile-picture-preview" src="{{ asset('images/profile_pictures/' . $admin->profile_picture ?? 'default-avatar.png') }}" alt="Profile Picture">
                <div class="overlay">
                    <div class="text">Update Profile Picture</div>
                </div>
            </div>
            <button type="button" class="btn-upload" onclick="document.getElementById('profile-picture-input').click();">Edit</button>
        </div>

        <!-- Office ID Upload -->
        <div class="office-id-wrapper">
            <input type="file" name="office_picture" id="office-id-input" accept="image/*" onchange="previewOfficeID(event)" hidden>
            <div class="office-id-container" onclick="document.getElementById('office-id-input').click();">
                <img id="office-id-preview" src="{{ asset('images/office_pictures/' . $admin->office_picture ?? 'default-office-id.png') }}" alt="Office ID Picture">
                <div class="overlay">
                    <div class="text">Update Office ID</div>
                </div>
            </div>
            <button type="button" class="btn-upload" onclick="document.getElementById('office-id-input').click();">Edit</button>
        </div>
    </div>
</div>

        <!-- Name and Email in one row -->
        <div class="form-row">
            <div class="form-group-inline">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ $admin->first_name}}" class="form-control" required>
            </div>
            <div class="form-group-inline">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ $admin->last_name}}" class="form-control" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group-inline"> 
                <label>M.I</label>
                <input type="text" name="middle_initial" value="{{ $admin->middle_initial}}" class="form-control" required>
            </div>
            <div class="form-group-inline">
                <label>Email</label>
                <input type="email" name="email" value="{{ $admin->email }}" class="form-control" required>
            </div>
        </div>

        <!-- Contact Number and Address in one row -->
        <div class="form-row">
            <div class="form-group-inline">
                <label>Contact Number</label>
                <input type="text" name="contact_number" value="{{ $admin->contact_number }}" class="form-control">
            </div>
            <div class="form-group-inline">
                <label>Address</label>
                <input type="text" name="address" value="{{ $admin->address }}" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group-inline">
                <label>Age</label>
                <input type="number" name="age" value="{{ $admin->age }}" class="form-control">
            </div>
            <div class="form-group-inline">
                <label>Birthday</label>
                <input type="date" name="birthday" value="{{ $admin->birthday }}" class="form-control">
            </div>
        </div>
        <!-- Password and Confirm Password in one row -->
        <div class="form-row">
            <div class="form-group-inline">
                <label>Password(leave blank if not changing)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group-inline">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

        <button type="submit" class="btn btn-success">Update Profile</button>
    </form>
</div>



    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    
    <!-- JavaScript to preview the profile picture and office ID before form submission -->
    <script>
        $(document).ready(function() {
            $('#hamburger-icon').on('click', function() {
                $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
            });
        });
        // Function to preview the profile picture before form submission
    function previewProfilePicture(event) {
    const reader = new FileReader();
    const preview = document.getElementById('profile-picture-preview');

    reader.onload = function() {
        if (reader.readyState === 2) {
            preview.src = reader.result;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}
        // Function to preview the office ID before form submission
        function previewOfficeID(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('office-id-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show the image preview
                }
                reader.readAsDataURL(file); // Read the file as a data URL
            }
        }
   
    </script>
</body>
</html>
