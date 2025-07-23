<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/request.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Requested Supplies and Borrowed Equipment</title>
</head>
<style>
    .pagination .active .page-link {
        background-color: #28a745;
        border-color: #28a745;
    }
</style>
<body>
@include('farmer.navbar')
@include('farmer.header')

<div class="container">
    <h1>List of request and borrow</h1>
    <div class="summary-cards">
        <div class="card high equipment-card">
            <h3>Total Supply Requests:</h3>
            <p><b><i class="fas fa-boxes"></i>{{ $totalSupplyRequests }}</b></p>
        </div>
        <div class="card high supplies-card">
            <h3>Total Borrow Requests:</h3>
            <p><b><i class="fas fa-tools"></i>{{ $totalBorrowRequests }}</b></p>
        </div>
    </div>
    <div class="d-flex flex-wrap align-items-center">
        <form action="{{ route('farmer.farmer.request') }}" method="GET" class="form-inline d-flex mr-2 mb-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="dropdown mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a href="{{ route('farmer.farmer.request', ['sort' => 'status', 'order' => request('sort') === 'status' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Status {{ request('sort') === 'status' ? (request('order') === 'asc' ? '(asc)' : '(desc)') : '' }}
                </a>
                <a href="{{ route('farmer.farmer.request', ['sort' => 'quantity', 'order' => request('sort') === 'quantity' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Quantity {{ request('sort') === 'quantity' ? (request('order') === 'asc' ? '(asc)' : '(desc)') : '' }}
                </a>
                <a href="{{ route('farmer.farmer.request', ['sort' => 'id', 'order' => request('sort') === 'id' && request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    ID {{ request('sort') === 'id' ? (request('order') === 'asc' ? '(asc)' : '(desc)') : '' }}
                </a>
            </div>
        </div>
    </div>
    <div class="container">
    <div class="table dynamic-table">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Type</th>
                        <th>Ticket Number</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Requested Date <br>
                            (Y-m-d)
                        </th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($supplyRequests as $request)
                        <tr>
                            <td>Supply</td>
                            <td>{{ $request->requesting_number }}</td>
                            <td>{{ $request->supply->name }}</td>
                            <td>{{ $request->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($request->created_at)->format('Y-m-d') }}</td>
                            <td>
                            @if($request->status !== 'approved' && $request->status !== 'rejected')
    <form action="{{ route('farmer.delete.supply.request', $request->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="button" 
            class="btn btn-danger btn-sm delete-request-btn" 
            data-item-id="{{ $request->id }}" 
            data-item-name="{{ $request->supply->name }}">
            Delete
        </button>
    </form>
@else
    <span class="text-danger">Cannot be deleted</span>
@endif

                            </td>
                            <td class="{{ strtolower($request->status) }}">{{ ucfirst($request->status) }}</td>
                        </tr>
                    @endforeach
                    @if($supplyRequests->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No supply requests found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="container">
    <div class="table dynamic-table">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Type</th>
                        <th>Ticket Number</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Returned Date
                        <br>
                        (Y-m-d)
                        </th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowRequests as $request)
                        <tr>
                        <td>Equipment</td>
                            <td>{{ $request->borrow_number }}</td>
                            <td>{{ $request->equipment->name }}</td>
                            <td>{{ $request->quantity }}</td>
                            <td>{{ $request->return_date ? \Carbon\Carbon::parse($request->return_date)->format('Y-m-d') : 'Not returned yet' }}</td>
                            <td>
                                    @if($request->status !== 'approved' && $request->status !== 'rejected')
                                        <form action="{{ route('farmer.farmer.farmer.deleteBorrowRequest', $request->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm delete-request-btn" data-item-name="{{ $request->equipment->name }}">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-danger">Cannot be deleted</span>
                                    @endif
                            </td>
                            <td class="{{ strtolower($request->status) }}">{{ ucfirst($request->status) }}</td>
                        </tr>
                    @endforeach
                    @if($borrowRequests->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">No borrow requests found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($supplyRequests->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $supplyRequests->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif
        @for ($i = 1; $i <= $supplyRequests->lastPage(); $i++)
            <li class="page-item {{ $supplyRequests->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $supplyRequests->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor
        @if ($supplyRequests->hasMorePages())
            <li class="page-item">
                <a href="{{ $supplyRequests->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Toggle sidebar functionality
        $('#hamburger-icon').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

        // Handle delete confirmation prompts dynamically
        document.querySelectorAll(".delete-request-btn").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();

                let form = this.closest("form"); // Get the corresponding form
                let itemId = this.dataset.itemId; // Get the item ID from data attribute
                let itemName = this.dataset.itemName; // Get item name from data attribute

                Swal.fire({
                    title: "Sigurado ka ba?",
                    text: `Ang pagtanggal ng "${itemName}" ay nangangahulugang nagkamali ka sa pag-hingi ng supplies o pag-hiram ng kagamitan, at hindi mo na ito kailangan. Itutuloy?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, Delete",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.action,
                            method: "POST",
                            data: $(form).serialize(),
                            success: function (response) {
                                Swal.fire({
                                    title: "Na-delete na!",
                                    text: `Matagumpay na na-delete ang "${itemName}". Ano ang gusto mong gawin?`,
                                    icon: "success",
                                    showCancelButton: true,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    confirmButtonText: "Ibalik",
                                    cancelButtonText: "Permanenteng Tanggalin"
                                }).then((choice) => {
                                    if (choice.isConfirmed) {
                                        window.location.href = "/undo-delete-supply/" + itemId;
                                    } else {
                                        window.location.href = "/permanently-delete-supply/" + itemId;
                                    }
                                });
                            },
                            error: function () {
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "May nangyaring mali. Pakisubukan muli.",
                                });
                            }
                        });
                    }
                });
            });
        });
    });
</script>

</body>
</html>
