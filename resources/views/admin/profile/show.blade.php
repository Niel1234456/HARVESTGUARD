<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/adminprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<body>
@include('admin.header')
@include('admin.navbar')

<div class="container">
    <div class="card user-card-full">
        <div class="row">
            <!-- Profile Picture Section -->
            <div class="col-sm-4 user-profile">
                <div>
                    <p><strong>Profile Picture</strong></p>
                    @if($admin->profile_picture)
                        <img src="{{ asset('images/profile_pictures/' . $admin->profile_picture) }}" alt="Profile Picture">
                    @else
                        <p>No profile picture uploaded.</p>
                    @endif
                </div>
                <h6 class="f-w-600">{{ $admin->first_name }} {{ $admin->middle_initial }}. {{ $admin->last_name }}</h6>
                <p>Administrator</p>

            </div>

            <!-- Profile Details Section -->
            <div class="col-sm-8">
                <div class="card-block">
                    <h6 class="f-w-600">Information</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="f-w-600">Email</p>
                            <p class="text-muted">{{ $admin->email }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="f-w-600">Contact Number</p>
                            <p class="text-muted">{{ $admin->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <p class="f-w-600">Address</p>
                            <p class="text-muted">{{ $admin->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="f-w-600">Birthday</p>
                            <p class="text-muted">{{ $admin->birthday ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.admin.profile.edit') }}" class="btn-edit">Edit Profile</a>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons and JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
        });
    });
</script>
</body> 
</html>
