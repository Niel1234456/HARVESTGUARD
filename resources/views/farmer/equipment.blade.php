<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Equipment</title>
    <link rel="stylesheet" href="{{ asset('assets/css/equipment.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="reminder-box">
            <i class="fas fa-info-circle"></i>
            Para Manghiram ng gamit, <b>Pindutin ang larawan</b> ng gamit na kailangan mo, o pindutin ang "<b>Request Equipment</b>" na button sa ibaba.
        </div>
        <div class="content">
            <h4><b>Available Equipment</b></h4>
            <div class="grid">
                @php $hasEquipment = false; @endphp
                @forelse ($equipment as $equip)
                    @if ($equip->quantity > 0)
                        @php $hasEquipment = true; @endphp
                        <div class="card" data-toggle="modal" data-target="#borrowEquipmentModal" 
                             data-id="{{ $equip->id }}" data-name="{{ $equip->name }}">
                            <img src="{{ asset('images/' . $equip->image) }}" alt="Equipment Image">
                            <h3>{{ $equip->name }}</h3>
                            <p>Bilang: {{ $equip->quantity }} {{ $equip->unit }}</p>
                        </div>
                    @endif
                @empty
                    <p>No equipment available at the moment.</p>
                @endforelse
                @if (!$hasEquipment)
                    <p>No equipment available at the moment.</p>
                @endif
            </div>
            <button type="button" class="request-button" data-toggle="modal" data-target="#borrowEquipmentModal">
                Request Equipment
            </button>
        </div>
    </div>
</div>

<!-- Borrow Equipment Modal -->
<div class="modal fade" id="borrowEquipmentModal" tabindex="-1" aria-labelledby="borrowEquipmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowEquipmentModalLabel">Borrow Equipment Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('farmer.borrow.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="equipment">Select Equipment:</label>
                        <select name="equipment_id" id="equipment" class="form-control" required>
                            @foreach ($equipment as $equip)
                                <option value="{{ $equip->id }}">{{ $equip->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="return_date">Return Date:</label><div class="alert alert-warning">
                    <strong>Paalala:</strong> Isang Linggo lang pwede manghiram ng gamit.</div>
                        <input type="date" name="return_date" id="return_date" class="form-control" required>
                        <div class="alert alert-danger mt-2 d-none" id="returnDateError"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Borrow Request</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Borrow Ticket Modal -->
<div class="modal fade" id="borrowTicketModal" tabindex="-1" aria-labelledby="borrowTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowTicketModalLabel">Borrow Equipment Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Equipment:</strong> <span id="ticket-equipment"></span></p>
                <p><strong>Piraso:</strong> <span id="ticket-quantity"></span></p>
                <p><strong>deskripsyon:</strong> <span id="ticket-description"></span></p>
                <p><strong>Petsa ng Pagbabalik:</strong> <span id="ticket-return-date"></span></p>
                <p><strong>Borrow ID:</strong> <span id="ticket-id"></span></p>
                <div class="alert alert-warning">
                    <strong>Paalala:</strong> Mangyaring I-download ang Borrow Equipment Ticket Form na ito upang ipakita kapag kukunin at isasauli ang kagamitan.
                </div>
            </div>
            <div class="modal-footer">
                <button id="downloadTicket" class="btn btn-success">Download Ticket</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> 
<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    <ul class="pagination">
        <!-- Previous Page Link -->
        @if ($equipment->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $equipment->previousPageUrl() }}" class="page-link">Previous</a>
            </li>
        @endif

        <!-- Pagination Links -->
        @for ($i = 1; $i <= $equipment->lastPage(); $i++)
            <li class="page-item {{ $equipment->currentPage() == $i ? 'active' : '' }}">
                <a href="{{ $equipment->url($i) }}" class="page-link">{{ $i }}</a>
            </li>
        @endfor

        <!-- Next Page Link -->
        @if ($equipment->hasMorePages())
            <li class="page-item">
                <a href="{{ $equipment->nextPageUrl() }}" class="page-link">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>

<!-- Bootstrap & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
        document.getElementById('downloadTicket').addEventListener('click', function () {
        let modalContent = document.querySelector("#borrowTicketModal .modal-content");

        html2canvas(modalContent).then(canvas => {
            let image = canvas.toDataURL("image/png");
            let link = document.createElement("a");
            link.href = image;
            link.download = "borrow_ticket.png";
            link.click();
        });
    });
    $(document).ready(function() {
        $('.card').on('click', function() {
            let equipmentId = $(this).data('id');
            let equipmentName = $(this).data('name');

            $('#equipment').val(equipmentId);
            $('#borrowEquipmentModalLabel').text('Borrow Equipment - ' + equipmentName);
        });

        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active');
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000
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

        $("#return_date").attr("min", new Date().toISOString().split("T")[0]);

        $("#return_date").on("change", function() {
            var today = new Date();
            var returnDate = new Date($(this).val());
            var maxReturnDate = new Date(today);
            maxReturnDate.setDate(today.getDate() + 7);

            if (returnDate > maxReturnDate) {
                $("#returnDateError").text("Pitong (7) Araw lamang maari mahiram ang gamit").removeClass("d-none");
                $(this).val('');
            } else {
                $("#returnDateError").addClass("d-none");
            }
        });
    });
    $(document).ready(function () {
    // Check if the session contains success message
    @if (session('success') && session('borrowRequest'))
        let borrowRequest = @json(session('borrowRequest')); // Retrieve borrow request data from session

        // Populate modal fields
        $("#ticket-borrower").text("{{ Auth::user()->name }}");
        $("#ticket-equipment").text(borrowRequest.equipment_name);
        $("#ticket-quantity").text(borrowRequest.quantity);
        $("#ticket-description").text(borrowRequest.description);
        $("#ticket-return-date").text(borrowRequest.return_date);
        $("#ticket-id").text(borrowRequest.borrow_number);

        // Show the modal
        $("#borrowTicketModal").modal("show");
    @endif
});
</script>

</body>
</html>
