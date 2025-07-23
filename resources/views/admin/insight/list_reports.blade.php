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
    <title>PDF Reports by Admin</title>
    <style>
        th, td {
            text-align: center;
        }
        .btn.btn-info {
            font-size: 12px;
        }
        h1 {
            font-size: 40px;
        }
        @media screen and (max-width: 768px) {
            h1 {
                font-size: 30px;
            }
        }

        .tag {
            display: inline-block;
            padding: 3px 7px;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            border-radius: 3px;
            margin-left: 10px;
        }
        .new {
            background-color: #28a745;
        }
        .old {
            background-color: #6c757d;
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
<h1>Generated PDF Reports</h1>
<div class="mb-3">
    <div class="d-flex flex-wrap align-items-center">
        <a href="{{ route('admin.admin.farmers') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
        </a>
        <a href="{{ route('admin.listReports') }}" class="btn btn-secondary">View Farmer Generated Pdf</a>

        <!-- Search Form -->
        <form action="{{ route('admin.admin.insight.list_reports') }}" method="GET" class="form-inline d-flex mr-2 mb-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mr-2" placeholder="Search by file name...">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Sort Dropdown -->
        <div class="dropdown mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Sort by
            </button>
            <div class="dropdown-menu" aria-labelledby="sortDropdown">
                <a href="{{ route('admin.admin.insight.list_reports', ['sort' => 'name', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    File Name (asc)
                </a>
                <a href="{{ route('admin.admin.insight.list_reports', ['sort' => 'created_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" class="dropdown-item">
                    Date Created (asc)
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
</div>


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pdfFiles as $file)
                    @php
                        $isNew = \Carbon\Carbon::createFromTimestamp($file['created_at'])->greaterThanOrEqualTo(\Carbon\Carbon::now()->subDays(7));
                    @endphp
                    <tr>
                        <td>{{ $file['name'] }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($file['created_at'])->toDayDateTimeString() }}</td>
                        <td>
                            <span class="tag {{ $isNew ? 'new' : 'old' }}">
                                {{ $isNew ? 'New' : 'Old' }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-info" href="{{ $file['url'] }}" target="_blank">Download</a>
                            <form action="{{ route('admin.reports.delete', $file['name']) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this file?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

</div>
<!-- Custom Pagination -->
<div class="d-flex justify-content-center">
    <ul class="pagination">
        @if ($pdfFiles->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $pdfFiles->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif

        @for ($i = 1; $i <= $pdfFiles->lastPage(); $i++)
            <li class="page-item {{ $pdfFiles->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $pdfFiles->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor

        @if ($pdfFiles->hasMorePages())
            <li class="page-item">
                <a href="{{ $pdfFiles->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
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
