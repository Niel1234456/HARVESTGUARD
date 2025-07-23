<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplies Available</title>
    <link rel="stylesheet" href="{{ asset('assets/css/equipment.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <style>
        .pagination .active .page-link {
            background-color: #28a745;
            border-color: #28a745;
        }
    </style>
</head>
<body>
@include('farmer.navbar')
@include('farmer.header')

<!-- Main Content -->

<div class="main-content">
    
<div class="reminder-box">
                <i class="fas fa-info-circle"></i>
                Para Humingi ng suplay, <b>Pindutin ang larawan</b> ng suplay na kailangan mo o pindutin ang "<b>Request Supplies</b>" na button sa ibaba.
            </div>
    <div class="content">
        
        <h4><b>Available Supplies</b></h4>
        <div class="grid">
            @php $hasSupply = false; @endphp
            @foreach ($supplies as $supply)
                @if ($supply->quantity > 0)
                    @php $hasSupply = true; @endphp
                    <div class="card supply-item" data-id="{{ $supply->id }}" data-name="{{ $supply->name }}">
                        <img src="{{ asset('images/' . $supply->image) }}" onerror="this.onerror=null;this.src='{{ asset('images/supply-placeholder.jpg') }}';" alt="Supply Image">
                        <h3>{{ $supply->name }}</h3>
                        <p>Quantity: {{ $supply->quantity }} {{ $supply->unit }}</p>
                    </div>
                @endif
            @endforeach
            @if (!$hasSupply)
                <p>No supplies available at the moment.</p>
            @endif
        </div>
        <button class="request-button" data-toggle="modal" data-target="#requestModal">Request Supplies</button>

    </div>
</div>

<!-- Request Modal -->
<div id="requestModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supply Request Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('farmer.send-request') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="supply_id">Supply:</label>
                        <select id="supply_id" name="supply_id" class="form-control" required>
                            @foreach($supplies as $supply)
                                <option value="{{ $supply->id }}">{{ $supply->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Optional description..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    <ul class="pagination">
        @if ($supplies->onFirstPage())
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
        @else
            <li class="page-item"><a href="{{ $supplies->previousPageUrl() }}" class="page-link">Previous</a></li>
        @endif
        @for ($i = 1; $i <= $supplies->lastPage(); $i++)
            <li class="page-item {{ $supplies->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $supplies->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor
        @if ($supplies->hasMorePages())
            <li class="page-item"><a href="{{ $supplies->nextPageUrl() }}" class="page-link">Next</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Next</span></li>
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
            $('#sidebar').toggleClass('active');
        });

        $('.supply-item').on('click', function(){
            let supplyId = $(this).data('id');
            let supplyName = $(this).data('name');

            $('#supply_id').val(supplyId);
            $('#requestModal').modal('show');
        });

        // SweetAlert2 Popup for Success & Error Messages
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
</script>
</body>
</html>