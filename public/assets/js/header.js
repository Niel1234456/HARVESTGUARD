    // Toggle dropdown menu visibility
    function toggleDropdown() {
        const dropdownMenu = document.getElementById('dropdown-menu');
        dropdownMenu.style.display = dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '' ? 'block' : 'none';
    }

    // Close dropdown menu when clicking outside
    window.onclick = function(event) {
        const dropdownMenu = document.getElementById('dropdown-menu');
        const profileIcon = document.getElementById('profile-icon');

        if (!profileIcon.contains(event.target)) {
            dropdownMenu.style.display = 'none'; // Hide dropdown if clicking outside
        }
    }