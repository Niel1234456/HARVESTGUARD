<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <style>

/* Bell Icon Styling */
.notification-bell-container {
    position: relative;
    display: inline-block;
}

.notification-icon {
    font-size: 24px;
    cursor: pointer;
    margin-top: -9%;
    transition: color 0.3s ease-in-out;
}

.notification-icon i {
    color: #333; /* Default bell color */
}

/* Bell turns dark yellow when clicked */
.notification-bell-container.active .notification-icon i {
    color: #d4af37; /* Dark yellow */
}

/* Notification Indicator (small red dot) */
.notification-circle {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 10px;
    height: 10px;
    background-color: red;
    border-radius: 50%;
    display: block; /* Hidden by default */
}

/* Dropdown Menu Styling */
.dropdown-menu {
    margin-left: 70px;
    display: none; /* Hidden by default */
    position: absolute;
    background: white;
    width: 250px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    padding: 10px;
    z-index: 1000;
    border: none; /* Ensure no border is applied */
}

/* Show dropdown when active */
.notification-bell-container.active .dropdown-menu {
    display: block;
}

/* Notification Item Styling */
.notification-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 10px;
    text-decoration: none;
    color: #333;
    transition: background 0.3s;
}

.notification-item:hover {
    background: #f5f5f5;
}

/* Remove Notification Button */
.remove-notification {
    color: red;
    cursor: pointer;
    font-weight: bold;
}

.remove-notification:hover {
    color: darkred;
}

 
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="header d-flex align-items-center">
            <div class="icon notification-icon" id="hamburger-icon">
                <i class="fas fa-bars"></i>
            </div>
            <h3 class="portal-title"><b>HARVESTGUARD D.A ADMIN PORTAL</b></h3>

            <div class="profile-icon" id="profile-icon">
                <i class="fas fa-user"></i>
            </div>

            <div id="dropdown-menu" class="Pdropdown-menu">
                <a href="{{ route('admin.admin.profile.show') }}" class="dropdown-item">
                    <i class="fas fa-user-circle fa-2x"></i> Profile
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                    @csrf
                    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="logout-icon">
                        <i class="fas fa-sign-out-alt"></i> Log out <!-- Replace with the desired icon -->
                    </a>
                </form>
            </div>

            <div class="dropdown notification-bell-container">
                <button  type="button" id="notificationBell">
                    <div class="icon notification-icon">
                        <i class="fas fa-bell"></i>
                        <span class="notification-circle" id="notificationIndicator" style="display: none;"></span>
                    </div>
                </button>
                <div class="dropdown-menu" id="notificationDropdown" aria-labelledby="notificationBell">
                    <p>Notifications</p>
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notification)
                            <a class="dropdown-item notification-item" href="#" data-id="{{ $notification->id }}">
                                <span class="success-message">{{ $notification->message }}</span>
                                <span class="remove-notification" data-id="{{ $notification->id }}">&times;</span>
                            </a>
                        @endforeach
                    @else
                        <a class="dropdown-item" href="#">No new notifications</a>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </header>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        function toggleProfileDropdown() {
            const dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        }

        document.getElementById('profile-icon').addEventListener('click', function (event) {
            event.stopPropagation();
            toggleProfileDropdown();
        });

        window.onclick = function (event) {
            const dropdownMenu = document.getElementById('dropdown-menu');
            if (!document.getElementById('profile-icon').contains(event.target) && dropdownMenu.style.display === 'block') {
                dropdownMenu.style.display = 'none';
            }

            const notificationDropdown = document.getElementById('notificationDropdown');
            if (!document.getElementById('notificationBell').contains(event.target) && notificationDropdown.style.display === 'block') {
                notificationDropdown.style.display = 'none';
            }
        };

        const notificationBell = document.getElementById('notificationBell');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationIndicator = document.getElementById('notificationIndicator');

        // Check localStorage to see if notifications were already opened
        if (localStorage.getItem('notificationsRead') === 'true') {
            if (notificationIndicator) {
                notificationIndicator.style.display = 'none';
            }
        }

        notificationBell.addEventListener('click', () => {
            const isVisible = notificationDropdown.style.display === 'block';
            notificationDropdown.style.display = isVisible ? 'none' : 'block';

            if (!isVisible) {
                markAllNotificationsAsRead();
            }
        });

        function markNotificationAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Notification marked as read.');
                    document.querySelector(`[data-id='${notificationId}']`).classList.add('read-notification');
                } else {
                    console.error('Error:', data.error || 'Unknown error');
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
        }

        function markAllNotificationsAsRead() {
            document.querySelectorAll('.notification-item').forEach(item => {
                const notificationId = item.getAttribute('data-id');
                markNotificationAsRead(notificationId);
            });

            if (notificationIndicator) {
                notificationIndicator.style.display = 'none';
            }

            // Store the read status in localStorage
            localStorage.setItem('notificationsRead', 'true');
        }

        document.querySelectorAll('.remove-notification').forEach(item => {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                const notificationId = this.parentElement.getAttribute('data-id');

                fetch(`/admin/notifications/${notificationId}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Notification deleted successfully.');
                        this.parentElement.remove();
                    } else {
                        console.error('Error:', data.error || 'Unknown error');
                    }
                })
                .catch(error => console.error('Error deleting notification:', error));
            });
        });

    });

    document.addEventListener("DOMContentLoaded", function () {
    const bell = document.getElementById("notificationBell");
    const dropdown = document.getElementById("notificationDropdown");
    const bellContainer = document.querySelector(".notification-bell-container");

    // Toggle dropdown and bell color
    bell.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event from bubbling
        bellContainer.classList.toggle("active");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!bellContainer.contains(event.target)) {
            bellContainer.classList.remove("active");
        }
    });


});
</script>


</body>
</html>
