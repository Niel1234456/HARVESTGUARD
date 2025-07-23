<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Equipment Management</title> 
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>   
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tables.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head> 
    <style>
        .pagination .active .page-link {
        background-color: #28a745;
        border-color: #28a745;
    }
    </style>
<body>
@include('admin.header')
@include('admin.navbar')
<div class="container mt-4">
    <h1>Equipment Management</h1>
    <div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center me-3">
        <form method="GET" action="{{ route('admin.equipment.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Name or Unit" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="d-flex align-items-center">
    <form action="{{ route('admin.equipment.index') }}" method="GET" class="d-flex align-items-center">

        <!-- Dropdown Button -->
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ request('sort') ? ucfirst(str_replace('_', ' ', request('sort'))) : 'Sort by' }}
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a class="dropdown-item" href="{{ route('admin.equipment.index', ['sort' => 'name_asc']) }}">Name (A-Z)</a>
                <a class="dropdown-item" href="{{ route('admin.equipment.index', ['sort' => 'name_desc']) }}">Name (Z-A)</a>
                <a class="dropdown-item" href="{{ route('admin.equipment.index', ['sort' => 'quantity_asc']) }}">Quantity (Low to High)</a>
                <a class="dropdown-item" href="{{ route('admin.equipment.index', ['sort' => 'quantity_desc']) }}">Quantity (High to Low)</a>
                <a class="dropdown-item" href="{{ route('admin.equipment.index', ['sort' => 'unit_asc']) }}">Unit (A-Z)</a>
                <a class="dropdown-item" href="{{ route('admin.equipment.index', ['sort' => 'unit_desc']) }}">Unit (Z-A)</a>
            </div>
        </div>
    </form>
</div>

</div>


    <table class="table dynamic-table">
        <thead>
            <tr>
                <th class="row-number">#</th> 
                <th>Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Image</th>
                <th>Actions</th>
                <tr id="columnLabels">
                </tr>
            </tr>
        </thead>
        <tbody>
            @foreach($equipments as $equipment)
            <tr>
                <td class="row-number">{{ $loop->iteration }}</td> 
                <td>{{ $equipment->name }}</td>
                <td>{{ $equipment->quantity }}</td>
                <td>{{ $equipment->unit }}</td>
                <td>
                    @if($equipment->image)
                        <img src="{{ asset('images/' . $equipment->image) }}" alt="{{ $equipment->name }}" width="100">

                        @else
                        <p>No image available</p>
                    @endif
                </td>
                <td>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editEquipmentModal-{{ $equipment->id }}">
                        Edit
                    </button>                      <button type="button" class="btn btn-danger" onclick="showDeleteModal({{ $equipment->id }}, '{{ $equipment->name }}')">Delete</button>
                    <form id="delete-form-{{ $equipment->id }}" action="{{ route('admin.equipment.destroy', $equipment->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addEquipmentModal">
        Add Equipment
    </button>
</div>

<!-- Delete Confirmation Modal -->
<center><div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please type the <b>name of the equipment</b> to confirm deletion:</p>
                <input type="text" id="confirmEquipmentName" class="form-control" placeholder="Equipment Name">
                <input type="hidden" id="deleteEquipmentId">
                <input type="hidden" id="actualEquipmentName">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div></center>
@foreach($equipments as $equipment)
<div class="modal fade" id="editEquipmentModal-{{ $equipment->id }}" tabindex="-1" role="dialog" aria-labelledby="editEquipmentModalLabel-{{ $equipment->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEquipmentModalLabel-{{ $equipment->id }}">Edit Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.equipment.update', $equipment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $equipment->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $equipment->quantity }}" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" value="{{ $equipment->unit }}" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image:</label>
                        <input type="file" id="image" name="image" class="form-control" accept="images/">
                    </div>
                    @if($equipment->image)
                        <div class="form-group">
                            <img src="{{ asset('images/' . $equipment->image) }}" alt="{{ $equipment->name }}" width="100">
                            <p>Current Image</p>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-success">Update Equipment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="addEquipmentModal" tabindex="-1" role="dialog" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEquipmentModalLabel">Add Equipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.equipment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name of the Equipment:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit of the Equipment:</label>
                        <input type="text" id="unit" name="unit" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Upload Image:</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Equipment</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    <ul class="pagination">
        @if ($equipments->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $equipments->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $equipments->lastPage(); $i++)
            <li class="page-item {{ $equipments->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $equipments->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor

        @if ($equipments->hasMorePages())
            <li class="page-item">
                <a href="{{ $equipments->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showDeleteModal(equipmentId, equipmentName) {
        $('#deleteEquipmentId').val(equipmentId);
        $('#actualEquipmentName').val(equipmentName);
        $('#confirmEquipmentName').val('');
        $('#deleteConfirmModal').modal('show');
    }

    $('#confirmDeleteButton').click(function() {
        let enteredName = $('#confirmEquipmentName').val().trim();
        let actualName = $('#actualEquipmentName').val().trim();
        
        if (enteredName === actualName) {
            let equipmentId = $('#deleteEquipmentId').val();
            $('#delete-form-' + equipmentId).submit();
        } else {
            alert("Equipment name does not match. Please try again.");
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Select all quantity input fields
        document.querySelectorAll("input[name='quantity']").forEach(function (input) {
            input.addEventListener("input", function () {
                if (this.value <= 0) {
                    alert("Quantity must be greater than zero.");
                    this.value = ""; // Clear the input field
                }
            });
        });

        // Prevent form submission if invalid quantity
        document.querySelectorAll("form").forEach(function (form) {
            form.addEventListener("submit", function (event) {
                let quantityInputs = form.querySelectorAll("input[name='quantity']");
                for (let input of quantityInputs) {
                    if (input.value <= 0 || input.value === "") {
                        alert("Please enter a valid quantity greater than zero.");
                        event.preventDefault(); // Prevent form submission
                        return;
                    }
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const columns = ['#', 'Name', 'Quantity', 'Unit', 'Image', 'Actions'];
        const columnLabelsRow = document.getElementById('columnLabels');

        // Function to convert column index to Excel letter
        function getExcelColumnLetter(index) {
            let letter = '';
            let temp;
            while (index >= 0) {
                temp = index % 26;
                letter = String.fromCharCode(temp + 65) + letter;
                index = Math.floor(index / 26) - 1;
            }
            return letter;
        }
        columns.forEach((col, index) => {
            const th = document.createElement('th');
            th.textContent = getExcelColumnLetter(index);
            columnLabelsRow.appendChild(th);
        });
    });

    $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); 
        });

        @if(session('update'))
        Swal.fire({
            icon: 'info',
            title: 'Updated!',
            text: '{{ session("update") }}',
            showConfirmButton: false,
            timer: 3000
        });
        @endif

        @if(session('delete') && !session('force_deleted'))
    Swal.fire({
        title: "Equipment Deleted",
        text: "Do you want to undo?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Undo",
        cancelButtonText: "Dismiss",
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "{{ route('admin.equipment.restore') }}";
            form.style.display = "none";

            let csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = "{{ csrf_token() }}";

            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
@endif

@if(session('borrow_error'))
    Swal.fire({
        title: "Deletion Failed",
        text: "{{ session('borrow_error') }}",
        icon: "error",
        confirmButtonText: "OK",
        confirmButtonColor: "#d33"
    });
@endif

@if(session('error'))
    Swal.fire({
        title: "Deletion Failed",
        text: "{{ session('error') }}",
        icon: "error",
        confirmButtonText: "Dismiss",
        confirmButtonColor: "#d33"
    });
@endif

@if(session('force_deleted'))
    Swal.fire({
        title: "Permanently Deleted",
        text: "The equipment has been permanently deleted and cannot be restored.",
        icon: "success",
        confirmButtonText: "OK",
        confirmButtonColor: "#28a745"
    });
@endif

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session("success") }}',
            showConfirmButton: true
        });
        @endif
    });
</script>

</body>
</html>
