<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmers Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/FarmerM.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head>
<style>
    /* Modal Background */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); /* Dark overlay */
}

/* Modal Content */
.modal-dialog {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
}

.modal-content {
    background-color: white;
    width: 400px; /* Adjust width */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    padding: 20px;
}

/* Modal Header */
.modal-header {
    background-color: #2f8f2f; /* Green color */
    color: white;
    padding: 15px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.modal-header .close {
    background: none;
    border: none;
    font-size: 20px;
    color: white;
    cursor: pointer;
}

/* Modal Body */
.modal-body {
    padding: 15px;
    font-size: 16px;
    text-align: center;
}

/* Modal Footer */
.modal-footer {
    padding: 15px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

/* Buttons */
.btn {
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    font-weight: bold;
}

.btn-danger {
    background-color: #d9534f;
    color: white;
}

.btn-danger:hover {
    background-color: #c9302c;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

</style>
<body>
@include('admin.header')
@include('admin.navbar')
    <div class="content">
        <h1>Farmers Management</h1>
        <form id="farmerSearchForm" method="GET" action="{{ route('admin.admin.farmers') }}">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search Farmers..." value="{{ request('search') }}">
                </div>
                <div class="col-md-6">
                    <select id="sortSelect" name="sort_by" class="form-control">
                        <option value="first_name_asc" {{ request('sort_by') == 'first_name_asc' ? 'selected' : '' }}>Sort by Name (A-Z)</option>
                        <option value="email_desc" {{ request('sort_by') == 'email_desc' ? 'selected' : '' }}>Sort by Email (Z-A)</option>
                        <option value="phone_desc" {{ request('sort_by') == 'phone_desc' ? 'selected' : '' }}>Sort by Phone (Descending)</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                 <a href="{{ route('admin.admin.approval.index') }}" class="btn btn-info">Go to Approval Page</a>
            </div>
        </form>
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
            
                @if (count($registeredFarmers) > 0)
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>birthday</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registeredFarmers as $index => $farmer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="javascript:void(0);" class="farmer-first_name" data-farmer-id="{{ $farmer->id }}">
                                            {{ $farmer->first_name }}
                                        </a>
                                        
    <div class="tooltip-content" style="display:none;">
    <h1>Transaction Record</h1>
    @if($farmer->borrowRequests->isEmpty() && $farmer->supplyRequests->isEmpty())
        <p>No Transaction Record</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Ticket #</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th> Item released/returned</th>
                </tr>
            </thead>
            <tbody>
              <!-- Borrowed Equipment Rows -->
              @foreach($farmer->borrowRequests as $borrowed)
                <tr>
                    <td>{{ $borrowed->borrow_number }}</td> <!-- Ticket Number Column -->
                    <td>Borrowed Equipment</td>
                    <td>{{ $borrowed->equipment ? $borrowed->equipment->name : 'Equipment not found' }}</td>
                    <td>{{ $borrowed->quantity }}</td>
                    <td>{{ $borrowed->equipment ? $borrowed->equipment->unit : '' }}</td>
                    <td>{{ $borrowed->created_at->format('Y-m-d') }}</td>
                    <td>
                        @if($borrowed->status === 'approved')
                            <span style="color: green;">Approved</span>
                        @elseif($borrowed->status === 'rejected')
                            <span style="color: red;">Rejected</span>
                        @elseif($borrowed->status === 'returned')
                            <span style="color: blue;">Returned</span>
                        @elseif($borrowed->status === 'pending')
                            <span style="color: orange;">Pending</span>
                        @else
                            <span style="color: gray;">Unknown</span>
                        @endif
                    </td>
                    <td>
                        @if($borrowed->is_released === 'Yes')
                            <span style="color: blue;">Released</span>
                        @elseif($borrowed->is_released === 'No')
                        <span style="color: orange;">Not Release</span>
                        @endif
                        
                        @if($borrowed->is_returned === 'Yes')
                        <br> <span style="color: green;">Returned</span>
                        @elseif($borrowed->is_returned === 'No')
                            <br><span style="color: orange;">Not Return</span>
                        @endif
                    </td>
                </tr>
            @endforeach


                @foreach($farmer->supplyRequests as $request)
                <tr>
                    <td>{{ $request->requesting_number }}</td> <!-- Ticket Number Column -->
                    <td>Requested Supply</td>
                    <td>{{ $request->supply ? $request->supply->name : 'Supply not found' }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td>{{ $request->supply ? $request->supply->unit : '' }}</td>
                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                    <td>
                        @switch($request->status)
                            @case('approved')
                                <span style="color: green;">Approved</span>
                                @break
                            @case('rejected')
                                <span style="color: red;">Rejected</span>
                                @break
                            @case('deleted')
                                <span style="color: gray;">Deleted</span>
                                @break
                            @default
                                <span style="color: black;">Pending</span>
                        @endswitch
                    </td>
                    <td>
                        @if($request->is_released === 'Yes')
                            <span style="color: blue;">Released</span>
                        @elseif($request->is_released === 'No')
                            <span style="color: orange;">Not Released</span>
                        @else
                            <span style="color: gray;">Unknown</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

                                        
    </td>                             
        <td>{{ $farmer->birth_date }}</td>
        <td>{{ $farmer->phone }}</td>
        <td>
        <a href="{{ route('admin.listReports') }}" class="btn btn-sm btn-custom btn-view-report">View Report</a>
        <button type="button" 
    class="btn btn-sm btn-danger delete-farmer-btn" 
    data-farmer-name="{{ $farmer->first_name }}" 
    data-delete-url="{{ route('admin.farmers.delete', $farmer->id) }}">
    Delete
</button>
                </td>
                <div class="overlay"></div>
                 </tr>
                    @endforeach
               </tbody>       
            </table>
                @else
                    <p>No farmers found.</p>
                @endif
        </div> 
    </div>
</div>

<!-- Delete Confirmation Modal -->
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteFarmerModal" tabindex="-1" aria-labelledby="deleteFarmerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFarmerModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please type the farmer's first name to confirm deletion:</p>
                <input type="text" id="confirmFirstName" class="form-control" placeholder="Enter first name" required>
                <small class="text-danger d-none" id="nameError">First name does not match!</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteFarmerForm" method="POST" action="{{ route('admin.farmers.delete', ['id' => $farmer->id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled onclick="showFinalWarning()">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    <ul class="pagination">
        <!-- Previous Page Link -->
        @if ($registeredFarmers->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $registeredFarmers->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif

        <!-- Page Number Links -->
        @for ($i = 1; $i <= $registeredFarmers->lastPage(); $i++)
            <li class="page-item {{ $registeredFarmers->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $registeredFarmers->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor

        <!-- Next Page Link -->
        @if ($registeredFarmers->hasMorePages())
            <li class="page-item">
                <a href="{{ $registeredFarmers->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000 // Auto-close after 3 seconds
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                showConfirmButton: true
            });
        @endif
    });
        // Search Input and Sorting
        document.getElementById('searchInput').addEventListener('input', function() {
            document.getElementById('farmerSearchForm').submit();
        });

        document.getElementById('sortSelect').addEventListener('change', function() {
            document.getElementById('farmerSearchForm').submit();
        });


// Farmer Tooltip Display Toggle
document.querySelectorAll('.farmer-first_name').forEach(function(farmerNameElement) {
    farmerNameElement.addEventListener('click', function() {
        const tooltipContent = this.nextElementSibling;
        const overlay = document.querySelector('.overlay');

        // Toggle show class for animation
        if (tooltipContent.classList.contains('show')) {
            tooltipContent.classList.remove('show');
            overlay.style.display = 'none'; // Hide overlay
            setTimeout(() => {
                tooltipContent.style.display = 'none'; // Set to none after animation completes
            }, 300); // Match this with the duration of the CSS transition
        } else {
            tooltipContent.style.display = 'block'; // Show first
            overlay.style.display = 'block'; // Show overlay
            setTimeout(() => {
                tooltipContent.classList.add('show'); // Then add class to animate
            }, 10); // Small timeout to allow display to take effect
        }
    });
});

// Close the tooltip and overlay when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.classList.contains('farmer-first_name')) {
        const tooltipContents = document.querySelectorAll('.tooltip-content');
        const overlay = document.querySelector('.overlay');
        
        tooltipContents.forEach(function(content) {
            if (content.classList.contains('show')) {
                content.classList.remove('show');
                content.style.display = 'none';
            }
        });
        overlay.style.display = 'none'; // Hide overlay
    }
});

$(document).ready(function () {
    let selectedFarmerName = '';
    let deleteFormAction = '';

    // When clicking the delete button, store farmer details
    $('.delete-farmer-btn').on('click', function () {
        selectedFarmerName = $(this).data('farmer-name'); // Get farmer's name
        deleteFormAction = $(this).data('delete-url'); // Get delete URL

        $('#confirmFirstName').val(''); // Reset input field
        $('#confirmDeleteBtn').prop('disabled', true); // Disable button initially
        $('#nameError').addClass('d-none'); // Hide error message

        $('#deleteFarmerForm').attr('action', deleteFormAction); // Set form action
        $('#deleteFarmerModal').modal('show'); // Show modal
    });

    // Enable delete button only when the correct name is entered
    $('#confirmFirstName').on('input', function () {
        let enteredName = $(this).val().trim().toLowerCase();

        if (enteredName === selectedFarmerName.toLowerCase()) {
            $('#confirmDeleteBtn').prop('disabled', false);
            $('#nameError').addClass('d-none'); // Hide error
        } else {
            $('#confirmDeleteBtn').prop('disabled', true);
            $('#nameError').toggleClass('d-none', !enteredName); // Show error only if text is entered
        }
    });

    // Show final warning before deleting
    $('#confirmDeleteBtn').on('click', function (e) {
        e.preventDefault(); // Prevent immediate form submission

        Swal.fire({
            title: "Final Warning",
            text: "All information of the farmer will be deleted permanently. Are you sure you want to proceed?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, Delete"
        }).then((result) => {
            if (result.isConfirmed) {
                $('#deleteFarmerForm').submit(); // Submit form if confirmed
            }
        });
    });
});


    </script>
</body>
</html>
