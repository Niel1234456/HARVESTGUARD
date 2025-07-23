<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>

<body>

<div class="container">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
    {{ __('FARMER REGISTRATION') }}
    </h2>
    <form method="POST" action="{{ route('farmer.register') }}" enctype="multipart/form-data">
        @csrf
    <div class="form-container">

    <div class="form-group">
        <!-- Email Address -->
        <div class="mt-4">
        <x-input-label for="first_name" :value="__('First Name')" class="custom-label"/>
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" placeholder="Example: Juan" :value="old('first_name')" required autofocus />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>
        <br>

            <!-- Middle Initial -->
    <div class="mt-4">
        <x-input-label for="middle_initial" :value="__('Middle Initial')" class="custom-label"/>
        <x-text-input id="middle_initial" class="block mt-1 w-full" type="text" name="middle_initial" placeholder="Example: M" :value="old('middle_initial')" maxlength="1" />
        <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
    </div>

    </div>
    <div class="form-group">
    <!-- Last Name -->
    <div class="mt-4">
        <x-input-label for="last_name" :value="__('Last Name')" class="custom-label"/>
        <x-text-input 
            id="last_name" 
            class="block mt-1 w-full" 
            type="text" 
            name="last_name" 
            placeholder="Example: Dela Cruz"
            :value="old('last_name')" 
            required 
        />
        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
    </div>
    <br>

    <!-- Phone Number -->
    <div class="mt-4">
        <x-input-label for="phone" :value="__('Phone Number')" />
        <x-text-input 
            id="phone" 
            class="block mt-1 w-full" 
            type="tel" 
            name="phone" 
            :value="old('phone')" 
            placeholder="e.g., 09123456789 (optional)" 
            pattern="[0-9]{11}" 
            maxlength="11" 
            minlength="11" 
            title="Phone number must be exactly 11 digits (e.g., 09123456789)" 
        />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>
</div>


<div class="form-group">

    <!-- Birth Date -->
    <div class="mt-4">
        <x-input-label for="birth_date" :value="__('Birth Date')" class="custom-label"/>
        <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" required />
        <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
    </div>
    <br>

        <!-- Gender -->
    <div class="mt-4">
        <x-input-label for="gender" :value="__('Gender')" class="custom-label"/>
        <select id="gender" name="gender" class="block mt-1 w-full" required>
            <option class="custom-label" value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
            <option class="custom-label" value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
            <option class="custom-label" value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
        </select>
        <x-input-error :messages="$errors->get('gender')" class="mt-2" class="custom-label"/>
    </div>
 
</div>
<div class="form-group">
    <div>
        <label for="farmers_activity" class="custom-label">Type of Farmer</label>
        <select name="farmers_activity" id="farmers_activity">
            <option class="custom-label" value="">Select Type of Farmer</option>
            <option class="custom-label" value="Rice Farmer" {{ old('farmers_activity') == 'rice_farming' ? 'selected' : '' }}>Rice Farmer</option>
            <option class="custom-label" value="Vegetable Farmer" {{ old('farmers_activity') == 'vegetable_farming' ? 'selected' : '' }}>Vegetable Farmer</option>
            <option class="custom-label" value="Fruit Farmer" {{ old('farmers_activity') == 'fruit_farming' ? 'selected' : '' }}>Fruit Farmer</option>
            <option class="custom-label" value="livestock" {{ old('farmers_activity') == 'livestock' ? 'selected' : '' }}>Livestock</option>
            <option class="custom-label" value="poultry" {{ old('farmers_activity') == 'poultry' ? 'selected' : '' }}>Poultry</option>
            <option class="custom-label" value="others" {{ old('farmers_activity') == 'others' ? 'selected' : '' }}>Others</option>
        </select>
    </div>
    <br>

     <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" class="custom-label"/>
            <select id="country" name="country" class="block mt-1 w-full" required onchange="fetchRegions(this.value)">
                <option class="custom-label" value="" disabled {{ old('country') ? '' : 'selected' }}>Select your country</option>
                <option class="custom-label" value="1694008" {{ old('country') == '1694008' ? 'selected' : '' }}>Philippines</option>
                <!-- Add more countries as needed -->
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>
</div>
    <div class="form-group">

        <div>
            <x-input-label for="region" :value="__('Region')" class="custom-label"/>
            <select id="region" name="region" class="block mt-1 w-full" required onchange="fetchProvinces(this.value)">
                <option class="custom-label" value="" disabled {{ old('region') ? '' : 'selected' }}>Select a country first</option>
            </select>
            <x-input-error :messages="$errors->get('region')" class="mt-2" />
        </div>
        <br>
        <div class="mt-4">
            <x-input-label for="province" :value="__('Province')" class="custom-label"/>
            <select id="province" name="province" class="block mt-1 w-full" required onchange="fetchCities(this.value)">
                <option class="custom-label" value="" disabled {{ old('province') ? '' : 'selected' }}>Select a region first</option>
            </select>
            <x-input-error :messages="$errors->get('province')" class="mt-2" />
        </div>
    </div>
  

    <div class="form-group">

        <div>
            <x-input-label for="city" :value="__('City')" class="custom-label"/>
            <select id="city" name="city" class="block mt-1 w-full" required>
                <option value="" disabled {{ old('city') ? '' : 'selected' }}>Select a province first</option>
            </select>
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>
        <br>

    <div class="mt-4">
        <x-input-label for="street_address" :value="__('Current Address')" class="custom-label"/>
        <x-text-input id="street_address" class="block mt-1 w-full" type="text" name="street_address" :value="old('street_address')" required />
        <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
    </div>
</div>

<div class="form-group">
    <!-- Address Line 2 -->
    <div class="mt-4">
        <x-input-label for="street_address2" :value="__('Farm Address')" class="custom-label"/>
        <x-text-input id="street_address2" class="block mt-1 w-full" type="text" name="street_address2" :value="old('street_address2')" />
        <x-input-error :messages="$errors->get('street_address2')" class="mt-2" />
    </div>
    <br>
    <div class="mt-4">
    <label for="id_type" class="custom-label">ID type</label>
        <select name="id_type" id="id_type">
            <option value="" class="custom-label">-- Choose Identification Card Type (ID) --</option>
            <option class="custom-label" value="National ID" {{ old('id_type') == 'nat_ID' ? 'selected' : '' }}>National ID</option>
            <option class="custom-label" value="Passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
            <option class="custom-label" value="Tin ID" {{ old('id_type') == 'tin_ID' ? 'selected' : '' }}>TIN ID</option>
            <option class="custom-label" value="Driver License" {{ old('id_type') == 'license' ? 'selected' : '' }}>Driver's License</option>
            <option class="custom-label" value="Voters ID" {{ old('id_type') == 'voters' ? 'selected' : '' }}>Voter's ID</option>
            <option class="custom-label" value="others" {{ old('id_type') == 'others' ? 'selected' : '' }}>Others</option>
        </select>
    </div>
</div>
<div class="form-group">
    <div class="mt-4">
        <x-input-label for="postal_code" :value="__('Postal Code')" class="custom-label"/>
        <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" required />
        <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
    </div>
    <br>
    <div class="mt-4">
        <label for="id_number" class="custom-label">ID Number</label>
        <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}">
    </div>

</div>
<div class="form-group">

        <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Password')" class="custom-label"/>
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
    <br>
    <div class="mt-4">
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="custom-label"/>
        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
    </div>
</div>
<div class="form-group">

</div>
<div class="form-group">
   <!-- Profile Picture -->
   <div class="mt-4">
        <x-input-label for="profile_picture" :value="__('Profile Picture')" class="custom-label"/>
        <input type="file" id="profile_picture" name="profile_picture" class="block mt-1 w-full" accept="image/*" onchange="previewImage(event)" />
        <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
    </div>
    <br>
    <!-- Profile Picture Preview -->
    <div class="mt-4">
        <img id="profile_picture_preview" src="#" alt="Profile Picture Preview" style="display: none; border-radius: 50%; width: 100px; height: 100px; object-fit: cover;" />
    </div>
</div>

<p>Pindutin ang reload icon nang isang beses upang matiyak na tama ang CAPTCHA.</p>
<div class="form-group mt-2 mb-2">
    <div class="captcha d-flex align-items-center">
<span class="me-2">{!! captcha_img('math') !!}</span>
        <button type="button" class="btn btn-info reload" id="reload">
            &#x21bb;
            
        </button>
    </div>
</div>



<div class="form-group mb-2">
    <input type="text" class="form-control" placeholder="Ilagay ang kabuuang halaga (TOTAL VALUE)" name="captcha">
    @error('captcha')
        <label for="" class="text-danger">{{ $message }}</label>
    @enderror
</div>

    <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ml-4">
            {{ __('Register') }}
        </x-primary-button>
    </div>
    <div>
    <a class="already" href="{{ route('farmer.login') }}">
            {{ __('Already Registered?') }}
        </a>
    </div>
</form>
</div>


<script>

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    
    form.addEventListener("submit", function (event) {
        let isValid = true; // Track form validity
        
        // Get all input fields (excluding file inputs)
        const inputs = form.querySelectorAll("input:not([type='file']), select");
        
        inputs.forEach(input => {
            if (input.hasAttribute("required")) { // Only check required fields
                if (input.value.trim() === "") {
                    input.style.border = "2px solid red";
                    isValid = false; // Mark form as invalid
                } else {
                    input.style.border = "2px solid green";
                }
            }
        });

        if (!isValid) {
            event.preventDefault(); // Prevent form submission if invalid
        }
    });

    // Reset border on input
    form.querySelectorAll("input, select").forEach(input => {
        input.addEventListener("input", function () {
            if (this.value.trim() !== "") {
                this.style.border = "2px solid green";
            } else {
                this.style.border = ""; // Reset border color
            }
        });
    });
});

$('#reload').click(function() {
    $.ajax({
        type: 'GET',
        url: 'reload-captcha',
        success: function(data) {
            $(".captcha span").html(data.captcha);
        }
    });
});
        
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        const preview = document.getElementById('profile_picture_preview');
        
        if (file) {
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }

    const geonamesUsername = 'nieljoseph'; // Replace with your GeoNames username

// Helper function to populate a dropdown
function populateDropdown(selectElement, options, placeholder, selectedValue = null) {
    selectElement.innerHTML = `<option value="" disabled>${placeholder}</option>`;
    options.forEach(optionData => {
        const option = document.createElement("option");
        option.value = optionData.geonameId;
        option.textContent = optionData.name;
        if (selectedValue && option.value === selectedValue) {
            option.selected = true;
        }
        selectElement.appendChild(option);
    });
}

// Fetch data and handle errors
function fetchData(url, onSuccess, onError) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch data.');
            }
            return response.json();
        })
        .then(data => onSuccess(data))
        .catch(error => {
            console.error(error);
            if (onError) onError(error);
        });
}

function fetchRegions(countryId) {
    const regionSelect = document.getElementById("region");
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");

    // Clear and show loading
    populateDropdown(regionSelect, [], 'Loading regions...');
    populateDropdown(provinceSelect, [], 'Select a province');
    populateDropdown(citySelect, [], 'Select a city');

    fetchData(
        `https://secure.geonames.org/childrenJSON?geonameId=${countryId}&username=${geonamesUsername}`,
        (data) => {
            const selectedRegion = localStorage.getItem('selectedRegion');
            populateDropdown(regionSelect, data.geonames, 'Select a region', selectedRegion);
        },
        () => {
            regionSelect.innerHTML = '<option value="" disabled selected>Failed to load regions</option>';
        }
    );
}

function fetchProvinces(regionId) {
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");

    // Clear and show loading
    populateDropdown(provinceSelect, [], 'Loading provinces...');
    populateDropdown(citySelect, [], 'Select a city');

    fetchData(
        `https://secure.geonames.org/childrenJSON?geonameId=${regionId}&username=${geonamesUsername}`,
        (data) => {
            const selectedProvince = localStorage.getItem('selectedProvince');
            populateDropdown(provinceSelect, data.geonames, 'Select a province', selectedProvince);
        },
        () => {
            provinceSelect.innerHTML = '<option value="" disabled selected>Failed to load provinces</option>';
        }
    );
}

function fetchCities(provinceId) {
    const citySelect = document.getElementById("city");

    // Clear and show loading
    populateDropdown(citySelect, [], 'Loading cities...');

    fetchData(
        `https://secure.geonames.org/childrenJSON?geonameId=${provinceId}&username=${geonamesUsername}`,
        (data) => {
            const selectedCity = localStorage.getItem('selectedCity');
            populateDropdown(citySelect, data.geonames, 'Select a city', selectedCity);
        },
        () => {
            citySelect.innerHTML = '<option value="" disabled selected>Failed to load cities</option>';
        }
    );
}

// Save selection to localStorage
function saveSelection(key, value) {
    localStorage.setItem(key, value);
}

// Restore selections on page load
function restoreSelections() {
    const countrySelect = document.getElementById("country");
    const regionSelect = document.getElementById("region");
    const provinceSelect = document.getElementById("province");
    const citySelect = document.getElementById("city");

    const selectedCountry = localStorage.getItem('selectedCountry');
    const selectedRegion = localStorage.getItem('selectedRegion');
    const selectedProvince = localStorage.getItem('selectedProvince');
    const selectedCity = localStorage.getItem('selectedCity');

    if (selectedCountry) {
        countrySelect.value = selectedCountry;
        fetchRegions(selectedCountry);
    }
    if (selectedRegion) {
        fetchProvinces(selectedRegion);
    }
    if (selectedProvince) {
        fetchCities(selectedProvince);
    }
}

// Attach event listeners
document.getElementById("country").addEventListener("change", function () {
    const countryId = this.value;
    saveSelection('selectedCountry', countryId);
    fetchRegions(countryId);
});

document.getElementById("region").addEventListener("change", function () {
    const regionId = this.value;
    saveSelection('selectedRegion', regionId);
    fetchProvinces(regionId);
});

document.getElementById("province").addEventListener("change", function () {
    const provinceId = this.value;
    saveSelection('selectedProvince', provinceId);
    fetchCities(provinceId);
});

document.getElementById("city").addEventListener("change", function () {
    const cityId = this.value;
    saveSelection('selectedCity', cityId);
});

// Restore dropdown selections on page load
document.addEventListener("DOMContentLoaded", restoreSelections);
</script>
</body>
</html>
