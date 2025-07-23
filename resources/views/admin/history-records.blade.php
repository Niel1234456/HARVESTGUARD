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
<br>
<h1>All History Records <p>(all history records of Released Supplies)</p></h1>
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
<div class="mb-3">
    <div class="d-flex flex-wrap align-items-center">
        <!-- Buttons -->
        <a href="{{ route('admin.admin.approval.index') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
        </a>        
        <a href="{{ route('admin.admin.history-records-borrowed') }}" class="btn btn-secondary">View All Borrowed Records</a>

        <!-- Search Form -->
        <form action="{{ route('admin.admin.history-records') }}" method="GET" class="form-inline d-flex mr-2 mb-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Sort Dropdown -->
        <div class="dropdown mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by 
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a href="{{ route('admin.admin.history-records', ['sort' => 'requesting_number', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                Requesting number (asc)
                </a>
                <a href="{{ route('admin.admin.history-records', ['sort' => 'status', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Status (desc)
                </a>
                <a href="{{ route('admin.admin.history-records', ['sort' => 'quantity', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Quantity (desc)
                </a>
                <a href="{{ route('admin.admin.history-records', ['sort' => 'id', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Id (asc)
                </a>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Request Number</th>
            <th>Supply</th>
            <th>Farmer</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Status</th> <!-- Status Column -->
            <th>Release Item</th> <!-- New Release Item Column -->
        </tr>
    </thead>
    <tbody>
    @foreach ($releasedRequests as $request)
        <tr>
            <td>{{ $request->id }}</td>
            <td>{{ $request->requesting_number }}</td>
            <td>{{ $request->supply->name }}</td>
            <td>{{ $request->farmer->first_name }}</td>
            <td>{{ $request->quantity }}</td>
            <td>{{ $request->description }}</td>
            <td>
            @switch($request->status)
                            @case('approved')
                                <span class="badge badge-success">Approved</span>
                                @break
                            @case('rejected')
                            <span class="badge" style="background-color: red; color: white;">Rejected</span>
                            @break
                            @default
                                <span style="color: black;">Pending</span>
                        @endswitch
            </td>
            <td>
                @if ($request->is_released == 'Yes')
                    <span class="badge badge-info">Yes</span>
                @else
                    <span class="badge badge-secondary">No</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<!-- Pagination Links -->
<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item {{ $releasedRequests->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $releasedRequests->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            @foreach ($releasedRequests->getUrlRange(1, $releasedRequests->lastPage()) as $page => $url)
                <li class="page-item {{ $releasedRequests->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            <li class="page-item {{ $releasedRequests->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $releasedRequests->nextPageUrl() }}" aria-label="Next">
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
