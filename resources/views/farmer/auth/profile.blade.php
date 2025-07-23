
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Profile</title>
    <link rel="stylesheet" href="{{ asset('assets/css/farmerprofile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<body>
    @include('farmer.navbar')
    @include('farmer.header')

    <div class="container">
        <div class="card user-card-full">
            <div class="user-profile">
                @if ($farmer->profile_picture)
                    <img src="{{ asset('images/profile_pictures/' . $farmer->profile_picture) }}" alt="Profile Picture">
                @endif
                <h3>{{ $farmer->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>First Name:</strong> {{ $farmer->first_name}}</p>
                        <p><strong>Last Name:</strong> {{ $farmer->last_name}}</p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>M.I:</strong> {{ $farmer->middle_initial}}</p>
                        <p><strong>Phone:</strong> {{ $farmer->phone }}</p>
                        <p><strong>Birth Date:</strong> {{ $farmer->birth_date }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <p><strong>Gender:</strong> {{ $farmer->gender }}</p>
                        <p><strong>Street Address:</strong> {{ $farmer->street_address }}</p>
                    </div>

                    <div class="col-md-6">
                        <p><strong>Type of Farmer:</strong> {{ $farmer->farmers_activity }}</p>
                        <p><strong>ID Type:</strong> {{ $farmer->id_type }}</p>
                        <p><strong>ID Number:</strong> {{ $farmer->id_number }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('farmer.farmer.profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
             $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); 
        });
    });
    </script>
    </body>
</html>
