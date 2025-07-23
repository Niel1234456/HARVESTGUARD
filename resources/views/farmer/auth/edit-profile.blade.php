
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/farmeredit.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<body>
@include('farmer.navbar')
@include('farmer.header')
<title>Edit Profile Information</title>
<div class="container">
    <h2>Edit Profile</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('farmer.farmer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ $farmer->first_name }}" required>
        </div>
        <div class="form-group">
            <label for="">M.I</label>
            <input type="text" name="middle_initial" class="form-control" value="{{ $farmer->middle_initial }}" required>
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ $farmer->last_name}}" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ $farmer->phone }}" required>
        </div>
 
        <div class="form-group">
            <label for="birth_date">Birth Date</label>
            <input type="date" name="birth_date" class="form-control" value="{{ $farmer->birth_date }}" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender</label>
            <select name="gender" class="form-control" required>
                <option value="Male" {{ $farmer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $farmer->gender == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="street_address">Street Address</label>
            <input type="text" name="street_address" class="form-control" value="{{ $farmer->street_address }}" required>
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" class="form-control" value="{{ $farmer->city }}" required>
        </div>

        <div class="form-group">
            <label for="province">Province</label>
            <input type="text" name="province" class="form-control" value="{{ $farmer->province }}" required>
        </div>

        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" name="country" class="form-control" value="{{ $farmer->country }}" required>
        </div>

        <div class="form-group">
            <label for="postal_code">Postal Code</label>
            <input type="text" name="postal_code" class="form-control" value="{{ $farmer->postal_code }}" required>
        </div>
 
        <div class="form-group">
    <label for="farmers_activity">Type of Farmer</label>
    <select name="farmers_activity" id="farmers_activity" class="form-control" required>
        <option value="" disabled>Select Type of Farmer</option>
        <option value="Rice Farming" {{ old('farmers_activity', $farmer->farmers_activity) == 'rice_farming' ? 'selected' : '' }}>Rice Farmer</option>
        <option value="Vegetable Farming" {{ old('farmers_activity', $farmer->farmers_activity) == 'vegetable_farming' ? 'selected' : '' }}>Vegetable Farmer</option>
        <option value="Fruit Farming" {{ old('farmers_activity', $farmer->farmers_activity) == 'fruit_farming' ? 'selected' : '' }}>Fruit Farmer</option>
        <option value="livestock" {{ old('farmers_activity', $farmer->farmers_activity) == 'livestock' ? 'selected' : '' }}>Livestock</option>
        <option value="poultry" {{ old('farmers_activity', $farmer->farmers_activity) == 'poultry' ? 'selected' : '' }}>Poultry</option>
        <option value="others" {{ old('farmers_activity', $farmer->farmers_activity) == 'others' ? 'selected' : '' }}>Others</option>
    </select>

    </div>


        <div class="form-group">
    <label for="id_type">ID Type</label>
    <select name="id_type" id="id_type" class="form-control">
        <option value="" disabled>Select ID Type</option>
        <option value="National ID" {{ old('id_type', $farmer->id_type) == 'nat_ID' ? 'selected' : '' }}>National ID</option>
        <option value="Passport" {{ old('id_type', $farmer->id_type) == 'passport' ? 'selected' : '' }}>Passport</option>
        <option value="Tin ID" {{ old('id_type', $farmer->id_type) == 'tin_ID' ? 'selected' : '' }}>TIN ID</option>
        <option value="Driver License" {{ old('id_type', $farmer->id_type) == 'license' ? 'selected' : '' }}>Driver's License</option>
        <option value="Voters ID" {{ old('id_type', $farmer->id_type) == 'voters' ? 'selected' : '' }}>Voter's ID</option>
    </select>

        </div>

        <div class="form-group">
            <label for="id_number">ID Number</label>
            <input type="text" name="id_number" class="form-control" value="{{ $farmer->id_number }}" required>
        </div>

        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            @if ($farmer->profile_picture) <br> <br> <br>
                <img src="{{ asset('images/profile_pictures/' . $farmer->profile_picture) }}" alt="Profile Picture" width="150">
            @endif
            <input type="file" name="profile_picture" class="form-control">
        </div>

        {{-- Password Update Fields --}}
        <!-- Password Update -->
        <div class="form-group">
            <label for="password">New Password (leave blank if not changing)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<script>
             $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
        });
    });
    </script>
</body>
</html>
