<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/approval.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <title>Request Approval</title> 
    <style>
        th {
            text-align: center;
        }
        h1 {
            font-size: 40px;
        }

        @media screen and (max-width: 768px) {
            h1 {
                font-size: 30px;
            }
        }


    .pagination .active .page-link {
        background-color: #28a745;
        border-color: #28a745;
    }

    </style>
</head>
<body>
@include('admin.header')
@include('admin.navbar')
<br>
<h1>Supply Requests for Approval</h1>
<div class="mb-3">
    <div class="d-flex flex-wrap align-items-center">
        <!-- Buttons -->
        <a href="{{ route('admin.admin.equipment.approval.index') }}" class="btn btn-info">Borrow Approval</a>
        <a href="{{ route('admin.admin.history-records') }}" class="btn btn-secondary">View All History Records</a>

        <!-- Search Form -->
        <form action="{{ route('admin.admin.approval.index') }}" method="GET" class="form-inline d-flex mr-2 mb-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Sort Dropdown -->
        <div class="dropdown mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by 
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a href="{{ route('admin.admin.approval.index', ['sort' => 'requesting_number', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                Requesting number (asc)
                </a>
                <a href="{{ route('admin.admin.approval.index', ['sort' => 'status', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Status (asc)
                </a>
                <a href="{{ route('admin.admin.approval.index', ['sort' => 'quantity', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Quantity (asc)
                </a>
                <a href="{{ route('admin.admin.approval.index', ['sort' => 'id', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Id (asc)
                </a>
            </div>
        </div>
    </div>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Requesting Number</th>
            <th>Supply</th>
            <th>Farmer</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Status</th>
            <th>Released</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($supplyRequests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->requesting_number }}</td>
            <td>{{ $request->supply->name }}</td>
            <td>{{ $request->farmer->first_name }}</td>
            <td>{{ $request->quantity }}</td>
            <td>{{ $request->description }}</td>
            <td>{{ ucfirst($request->status) }}</td>
            <td>{{ $request->is_released === 'Yes' ? 'Yes' : 'No' }}</td>
            <td>
                @if($request->status === 'pending')
                    <form action="{{ route('admin.admin.approval.approve', $request->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form action="{{ route('admin.admin.approval.reject', $request->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                @elseif($request->status === 'approved' && $request->is_released === 'No')
                    <form action="{{ route('admin.admin.approval.release', $request->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-info">Mark as Released</button>
                    </form>
                @elseif($request->is_released === 'Yes')
                   <!-- No actions needed -->
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Custom Pagination -->
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
<script>
    $(document).ready(function() {
        $('#hamburger-icon').on('click', function() {
            $('#sidebar').toggleClass('active');
        });

        @if(session('update'))
    Swal.fire({
        icon: 'info', // You can change this to 'success' if preferred
        title: 'Released!',
        text: '{{ session("update") }}',
        showConfirmButton: false,
        timer: 3000 // Auto-close after 3 seconds
        });
    @endif

    @if(session('delete'))
        Swal.fire({
            icon: 'error',
            title: 'Rejected!',
            text: '{{ session("delete") }}', // Corrected from delete("delete") to session("delete")
            showConfirmButton: false,
            timer: 3000
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
