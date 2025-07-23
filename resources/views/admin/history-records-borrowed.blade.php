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
    <title>All History Records</title>
</head>
<body>
@include('admin.header')
@include('admin.navbar')
<style>
    th{
        text-align: center;

        
    }
    h1{
        font-size: 40px;
    }

    @media screen and (max-width: 768px) {
    h1{
        font-size: 30px;

    }
    .dropdown.mb-2{
        right: 3%; 
        top: 25%;
    }
}

    .pagination .active .page-link {
        background-color: #28a745;
        border-color: #28a745;
    }
    
</style>
<br>
<div><h1>All History Records <p>(all history records of Released and returned Equipment)</p></h1>

</div>

<div class="mb-3">
    <div class="d-flex flex-wrap align-items-center">
        <a href="{{ route('admin.admin.equipment.approval.index') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
        </a>        
        <a href="{{ route('admin.admin.history-records') }}" class="btn btn-secondary">View All Requested Records</a>


        <form action="{{ route('admin.admin.history-records-borrowed') }}" method="GET" class="form-inline d-flex mr-2 mb-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <div class="dropdown mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by 
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a href="{{ route('admin.admin.history-records-borrowed', ['sort' => 'borrow_number', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                Borrow number (desc)
                </a>
                <a href="{{ route('admin.admin.history-records-borrowed', ['sort' => 'status', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Status (asc)
                </a>
                <a href="{{ route('admin.admin.history-records-borrowed', ['sort' => 'quantity', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Quantity (asc)
                </a>
                <a href="{{ route('admin.admin.history-records-borrowed', ['sort' => 'id', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Id (desc)
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Borrow Number</th>
            <th>Farmer</th>
            <th>Equipment</th>
            <th>Quantity</th>
            <th>Status</th> 
            <th>Released</th> 
            <th>Returned At</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($returnedBorrowRequests as $borrowRequest)
        <tr>
            <td>{{ $borrowRequest->id }}</td>
            <td>{{ $borrowRequest->borrow_number }}</td>
            <td>{{ $borrowRequest->farmer->first_name }}</td>
            <td>{{ $borrowRequest->equipment->name }}</td>
            <td>{{ $borrowRequest->quantity }}</td>
            <td>
                @if ($borrowRequest->status == 'approved')
                    <span class="badge badge-success">Approved</span>
                @elseif ($borrowRequest->status == 'rejected')
                    <span class="badge" style="background-color: red; color: white;">Rejected</span>
                @else
                    <span class="badge badge-warning">Pending</span>
                @endif
            </td>
            <td>
                @if ($borrowRequest->is_released == 'Yes')
                    <span class="badge badge-success">Yes</span>
                @else
                    <span class="badge badge-secondary">No</span>
                @endif
            </td>
            <td>{{ $borrowRequest->returned_at ?? 'Restricted for 7days' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<!-- Pagination Links -->
<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item {{ $returnedBorrowRequests->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $returnedBorrowRequests->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @foreach ($returnedBorrowRequests->getUrlRange(1, $returnedBorrowRequests->lastPage()) as $page => $url)
                <li class="page-item {{ $returnedBorrowRequests->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <li class="page-item {{ $returnedBorrowRequests->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $returnedBorrowRequests->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<script>
    $(document).ready(function() {
        $('#hamburger-icon').on('click', function() {
        $('#sidebar').toggleClass('active');
        });
    });
</script>
</body>
</html>