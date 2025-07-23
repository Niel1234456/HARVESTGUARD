<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<style>
    /* General styling */

.login-form {
    margin-top: 5%;
    margin-bottom: 3%;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 20px 40px;
    max-width: 800px; /* Increase the width */
    width: 100%;

}

h2 {
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
}

/* Flex container for the fields */
.field-group {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    grid-template-columns: repeat(4, 170px);

}

/* Each input field */
.field {
    width: 100%; /* Default for small screens */
    display: flex;
    flex-direction: column;
}

.field input, .field select {
    padding: 0.5rem;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.field {
        width: calc(50% - 1rem); /* Each field takes up 50% space */
    }


/* Error message */
.x-input-error {
    color: red;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Button and form adjustments */
.x-primary-button {
    background-color: #4caf50;
    color: white;
    padding: 0.75rem;
    border-radius: 4px;
    cursor: pointer;
}

.x-primary-button:hover {
    background-color: #45a049;
}

.mt-4 {
    margin-top: 1rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-6 {
    margin-top: 1.5rem;
}

@media (min-width: 769px) and (max-width: 1024px) {
    .login-form{
        width: 70%; /* Ensure full width on mobile */

    }
}
@media (min-width: 403px) and (max-width: 768px) {
    .form-group {
        width: 82%; /* Ensure full width on mobile */
    }
    .login-form{
        width: 80%; /* Ensure full width on mobile */

    }
    .custom-label{
        font-size: 13px;
        }

    .login-form {
        padding: 15px 20px;
    }

    button {
        padding: 12px;
    }
}

@media (min-width: 230px) and (max-width: 403px) {
    .form-group {
        width: 80%; /* Ensure full width on mobile */
    }
    .login-form{
    font-size: 10px;
    }
    .block.w-full{
        font-size: 10px;
    }
    .login-form{
        width: 70%; /* Ensure full width on mobile */

    }

    .login-form {
        padding: 15px 20px;
    }

    button {
        padding: 12px;
    }
}



</style>

<body>
    <div class="login-form">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('D.A ADMIN REGISTRATION') }}
        </h2>
        
        <form method="POST" action="{{ route('admin.register') }}" enctype="multipart/form-data">
            @csrf

        <div class="field-group">
            <!-- First Name -->
            <div class="field mt-4">
                <x-input-label for="first_name" :value="__('First Name')" class="custom-label"/>
                <x-text-input id="first_name" class="block w-full" type="text" name="first_name" placeholder="Example: Juan" :value="old('first_name')" required autofocus autocomplete="given-name" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <br>
                        <!-- Last Name -->
            <div class="field mt-4">
                <x-input-label for="last_name" :value="__('Last Name')" class="custom-label"/>
                <x-text-input id="last_name" class="block w-full" type="text" name="last_name" placeholder="Example: Delacruz" :value="old('last_name')" required autocomplete="family-name" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>

        </div>
        <div class="field-group">

            <!-- Middle Initial -->
            <div class="field mt-4">
                <x-input-label for="middle_initial" :value="__('Middle Initial')" class="custom-label"/>
                <x-text-input id="middle_initial" class="block w-full" type="text" name="middle_initial" placeholder="M" :value="old('middle_initial')" maxlength="1" autocomplete="additional-name" />
                <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
            </div>
            <br>

            <div class="field mt-4">
                <x-input-label for="email" :value="__('Email')" class="custom-label"/>
                <x-text-input id="email" class="block w-full" type="email" name="email" placeholder="Example: juandelacruz@gmail.com" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>
        <div class="field-group">

            <!-- Position -->
            <div class="field mt-4">
                <x-input-label for="position" :value="__('Position')" class="custom-label"/>
                <x-text-input id="position" class="block w-full" type="text" name="position" placeholder="Example: Farm Supervisor" :value="old('position')" />
                <x-input-error :messages="$errors->get('position')" class="mt-2" />
            </div> 
            <br>
                        <!-- Contact Number -->
                        <div class="field mt-4">
    <x-input-label for="contact_number" :value="__('Contact Number')" class="custom-label"/>
    <x-text-input 
        id="contact_number" 
        class="block w-full" 
        type="tel" 
        name="contact_number" 
        :value="old('contact_number')" 
        required 
        placeholder="e.g., 09123456789" 
        pattern="[0-9]{11}" 
        maxlength="11" 
        minlength="11" 
        title="Contact number must be exactly 11 digits (e.g., 09123456789)" 
    />
    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
</div>


        </div>
        <div class="field-group">
    <!-- Birthday -->
    <div class="field mt-4">
        <x-input-label for="birthday" :value="__('Birthday')" class="custom-label"/>
        <x-text-input id="birthday" class="block w-full" type="date" name="birthday" :value="old('birthday')" onchange="updateAge()" />
        <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
    </div>
    <br>
    <!-- Age -->
    <div class="field mt-4">
        <x-input-label for="age" :value="__('Age')" class="custom-label"/>
        <x-text-input id="age" class="block w-full" type="number" name="age" min="1" max="120" :value="old('age')" oninput="updateBirthday()" onblur="validateAge()" />
        <span id="age-error" class="text-red-500 text-sm mt-1"></span> <!-- Error message -->
        <x-input-error :messages="$errors->get('age')" class="mt-2" />
    </div>
</div>

        <div class="field-group">

        <div class="field mt-4">
                <x-input-label for="gender" :value="__('Gender')" class="custom-label"/>
                <select id="gender" name="gender" class="block w-full">
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
            </div>
            <br>
            <div class="flex-1">
                <x-input-label for="country" :value="__('Country')" class="custom-label"/>
                <select id="country" name="country" class="block mt-1 w-full" required onchange="fetchRegions(this.value)">
                    <option value="" disabled {{ old('country') ? '' : 'selected' }}>Select your country</option>
                    <option value="1694008" {{ old('country') == '1694008' ? 'selected' : '' }}>Philippines</option>
                    <!-- Add more countries as needed -->
                </select>
                <x-input-error :messages="$errors->get('country')" class="mt-2" />
            </div>

        </div>
        <div class="field-group flex items-center gap-4 mt-4">
            <div class="flex-1">
                <x-input-label for="region" :value="__('Region')" class="custom-label"/>
                <select id="region" name="region" class="block mt-1 w-full" required onchange="fetchProvinces(this.value)">
                    <option value="" disabled {{ old('region') ? '' : 'selected' }}>Select a country first</option>
                </select>
                <x-input-error :messages="$errors->get('region')" class="mt-2" />
            </div>
            <br>
            <div class="flex-1">
                <x-input-label for="province" :value="__('Province')" class="custom-label"/>
                <select id="province" name="province" class="block mt-1 w-full" required onchange="fetchCities(this.value)">
                    <option value="" disabled {{ old('province') ? '' : 'selected' }}>Select a region first</option>
                </select>
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>

        </div>
        <div class="field-group flex items-center gap-4 mt-4">
            <div class="flex-1">
                <x-input-label for="city" :value="__('City')" class="custom-label"/>
                <select id="city" name="city" class="block mt-1 w-full" required>
                    <option value="" disabled {{ old('city') ? '' : 'selected' }}>Select a province first</option>
                </select>
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>
            <br>
             <!-- Postal Code -->
            <div class="field mt-4">
                <x-input-label for="postal_code" :value="__('Postal Code')" class="custom-label"/>
                <x-text-input id="postal_code" class="block w-full" type="text" name="postal_code" :value="old('postal_code')" />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>
        </div>


        <div class="field-group">
         <!-- Address -->
            <div class="field mt-4">
                <x-input-label for="address" :value="__('Address')" class="custom-label"/>
                <x-text-input id="address" class="block w-full" type="text" name="address" :value="old('address')" />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
            <br>
                <div class="field mt-4">
                <x-input-label for="id_type" :value="__('ID number')" class="custom-label"/>
                <x-text-input id="id_type" class="block w-full" type="text" name="id_type" :value="old('id_type')" />
                <x-input-error :messages="$errors->get('id_type')" class="mt-2" />
                </div>
        </div>
        <div class="form-group mt-4">
        <div class="field mt-4">
                <x-input-label for="profile_picture" :value="__('Upload Profile Picture')" class="custom-label"/>
                <input type="file" name="profile_picture" id="profile_picture" class="block w-full" accept="image/*" onchange="previewImage(event)">
                <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
            </div>
            <br>
            <div class="field mt-4">
                <x-input-label for="office_picture" :value="__('Upload Office ID Picture')" class="custom-label"/>
                <input type="file" name="office_picture" id="office_picture" class="block w-full" accept="image/*" onchange="previewImage(event)">
                <x-input-error :messages="$errors->get('office_picture')" class="mt-2" />
            </div>
        </div>

        
        <div class="field-group">
            <!-- Password -->
            <div class="field mt-4">
                <x-input-label for="password" :value="__('Password')" class="custom-label"/>
                <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <br>
            <!-- Confirm Password -->
            <div class="field mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="custom-label"/>
                <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('admin.login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ml-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Include any required JavaScript files here -->
    <script src="{{ asset('js/app.js') }}"></script> <!-- Example for app.js -->
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

let lastCalculatedAge = null; // Store the last valid calculated age

    function updateAge() {
        const birthdayInput = document.getElementById("birthday").value;
        const ageField = document.getElementById("age");
        const ageError = document.getElementById("age-error");

        if (birthdayInput) {
            const birthday = new Date(birthdayInput);
            const today = new Date();
            let age = today.getFullYear() - birthday.getFullYear();
            const monthDiff = today.getMonth() - birthday.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
                age--;
            }

            lastCalculatedAge = age; // Store last valid age
            ageField.value = age;
            ageError.textContent = ""; // Clear any error message
        }
    }

    function updateBirthday() {
        const ageInput = document.getElementById("age").value;
        const birthdayField = document.getElementById("birthday");
        const ageError = document.getElementById("age-error");

        if (ageInput && ageInput > 0 && ageInput <= 120) {
            const today = new Date();
            const birthYear = today.getFullYear() - ageInput;
            const birthMonth = today.getMonth();
            const birthDay = today.getDate();
            const formattedDate = `${birthYear}-${String(birthMonth + 1).padStart(2, '0')}-${String(birthDay).padStart(2, '0')}`;
            birthdayField.value = formattedDate;

            lastCalculatedAge = ageInput; // Update last valid age
            ageError.textContent = ""; // Clear error message
        } else {
            ageError.textContent = "Please enter a valid age between 1 and 120.";
            birthdayField.value = ""; // Reset birthday field
        }
    }

    function validateAge() {
        const ageField = document.getElementById("age");
        const ageError = document.getElementById("age-error");
        const ageValue = parseInt(ageField.value);

        if (!ageValue || ageValue < 1 || ageValue > 120) {
            ageError.textContent = "Invalid age. Please enter a valid number between 1 and 120.";
            ageField.value = lastCalculatedAge ?? ""; // Reset to last valid age
        } else if (lastCalculatedAge !== null && ageValue !== lastCalculatedAge) {
            ageError.textContent = "Age does not match the birthday. Please enter the correct age.";
            ageField.value = lastCalculatedAge; // Reset to last correct age
        } else {
            ageError.textContent = ""; // Clear error message
        }
    }
</script>
</body>
</html>
