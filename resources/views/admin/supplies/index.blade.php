<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Supplies Management</title>
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
    <h1>Supplies Management</h1>

<div class="d-flex align-items-center justify-content-between mb-3">
    <div class="d-flex align-items-center me-3">
        <form method="GET" action="{{ route('admin.supplies.index') }}" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Name or Unit" value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>

    <div class="d-flex align-items-center">
    <form action="{{ route('admin.supplies.index') }}" method="GET" class="d-flex align-items-center">

        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ request('sort') ? ucfirst(str_replace('_', ' ', request('sort'))) : 'Sort by' }}
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a class="dropdown-item" href="{{ route('admin.supplies.index', ['sort' => 'name_asc']) }}">Name (A-Z)</a>
                <a class="dropdown-item" href="{{ route('admin.supplies.index', ['sort' => 'name_desc']) }}">Name (Z-A)</a>
                <a class="dropdown-item" href="{{ route('admin.supplies.index', ['sort' => 'quantity_asc']) }}">Quantity (Low to High)</a>
                <a class="dropdown-item" href="{{ route('admin.supplies.index', ['sort' => 'quantity_desc']) }}">Quantity (High to Low)</a>
                <a class="dropdown-item" href="{{ route('admin.supplies.index', ['sort' => 'unit_asc']) }}">Unit (A-Z)</a>
                <a class="dropdown-item" href="{{ route('admin.supplies.index', ['sort' => 'unit_desc']) }}">Unit (Z-A)</a>
            </div>
        </div>
    </form>
</div>

</div>

<div>
    <table class="table dynamic-table">
    <thead>

                <tr>
                    <th>#</th> 
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <tr id="columnLabels">
                </tr>
            </thead>

        <tbody>
            @foreach($supplies as $index => $supply)
            <tr>
                <td>{{ $index + 1 }}</td> 
                <td>{{ $supply->name }}</td>
                <td>{{ $supply->quantity }}</td>
                <td>{{ $supply->unit }}</td>
                <td>
                    @if($supply->image)
                        <img src="{{ asset('images/' . $supply->image) }}" alt="{{ $supply->name }}" width="100">
                    @else
                        No image
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editSupplyModal-{{ $supply->id }}">Edit</button>
                    <button type="button" class="btn btn-danger" onclick="showDeleteModal({{ $supply->id }}, '{{ $supply->name }}')">Delete</button>
                    <form id="delete-form-{{ $supply->id }}" action="{{ route('admin.supplies.destroy', $supply->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addSupplyModal">
        Add Supply
    </button>
</div>


<div class="modal fade" id="addSupplyModal" tabindex="-1" role="dialog" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplyModalLabel">Add Supply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.supplies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Add Supply</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
                <p>Please type the <b>name of the supply</b>to confirm deletion:</p>
                <input type="text" id="confirmSupplyName" class="form-control" placeholder="Supply Name">
                <input type="hidden" id="deleteSupplyId">
                <input type="hidden" id="actualSupplyName">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div></center>

@foreach($supplies as $supply)
<div class="modal fade" id="editSupplyModal-{{ $supply->id }}" tabindex="-1" role="dialog" aria-labelledby="editSupplyModalLabel-{{ $supply->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplyModalLabel-{{ $supply->id }}">Edit Supply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $supply->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $supply->quantity }}" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unit</label>
                        <input type="text" name="unit" id="unit" class="form-control" value="{{ $supply->unit }}" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control">
                        @if($supply->image)
                            <img src="{{ asset('images/' . $supply->image) }}" alt="{{ $supply->name }}" width="100" class="mt-2">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Update Supply</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($supplies->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $supplies->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $supplies->lastPage(); $i++)
            <li class="page-item {{ $supplies->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $supplies->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor

        @if ($supplies->hasMorePages())
            <li class="page-item">
                <a href="{{ $supplies->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.showDeleteModal = function (supplyId, supplyName) {
            $('#deleteSupplyId').val(supplyId);
            $('#actualSupplyName').val(supplyName);
            $('#confirmSupplyName').val('');
            $('#deleteConfirmModal').modal('show');
        };

        $('#confirmDeleteButton').click(function () {
            let enteredName = $('#confirmSupplyName').val().trim();
            let actualName = $('#actualSupplyName').val().trim();

            if (enteredName === actualName) {
                let supplyId = $('#deleteSupplyId').val();
                $('#delete-form-' + supplyId).submit();
            } else {
                alert("Supply name does not match. Please try again.");
            }
        });

        document.querySelectorAll("input[name='quantity']").forEach(function (input) {
            input.addEventListener("input", function () {
                if (this.value <= 0) {
                    alert("Quantity must be greater than zero.");
                    this.value = ""; 
                }
            });
        });

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

        $('#hamburger-icon').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        const columns = ['#', 'Name', 'Quantity', 'Unit', 'Image', 'Actions'];
        const columnLabelsRow = document.getElementById('columnLabels');

        function getExcelColumnLetter(index) {
            let letter = '';
            while (index >= 0) {
                letter = String.fromCharCode((index % 26) + 65) + letter;
                index = Math.floor(index / 26) - 1;
            }
            return letter;
        }

        if (columnLabelsRow) {
            columns.forEach((col, index) => {
                const th = document.createElement('th');
                th.textContent = getExcelColumnLetter(index);
                columnLabelsRow.appendChild(th);
            });
        }

        @if(session('update'))
            Swal.fire({
                icon: 'info',
                title: 'Updated!',
                text: '{{ session("update") }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('delete'))
    Swal.fire({
        title: "Supply Deleted",
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
            form.action = "{{ route('admin.supplies.restore') }}";
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
@if(session('error'))
    Swal.fire({
        title: "Deletion Failed",
        html: `{!! session('error') !!}`,
        icon: "error",
        confirmButtonText: "Dismiss",
        confirmButtonColor: "#d33"
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