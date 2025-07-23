<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

    <title>Event Calendar</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar and Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
@include('admin.header')
@include('admin.navbar')

<div class="container">
    <div class="row mt-5">
        <!-- Calendar -->
        <div class="col-md-7">
            <div class="card">
                <h3 class="card-header p-3">E V E N T  C A L E N D A R</h3>
                <div class="card-body">
                    <div id='calendar'></div>
                </div>

                <br>
            <button class="btn btn-success mb-3" id="addEventBtn">
                <i class="fas fa-plus"></i> Add Event
            </button>
            </div>
        </div>

        <!-- News Section -->
    <div class="col-md-5">
    <div class="card">
        <h4 class="card-header">Add News</h4>
        <form id="newsForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="newsTitle" class="form-label">News Title</label>
                <input type="text" class="form-control" id="newsTitle" name="news_title" required>
            </div>
            <div class="mb-3">
                <label for="newsLink" class="form-label">News Link</label>
                <input type="url" class="form-control" id="newsLink" name="news_link" required>
            </div>
            <div class="mb-3">
                <label for="newsImage" class="form-label">News Image</label>
                <input type="file" class="form-control" id="newsImage" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Add News</button>
        </form>

        <center><h4 class="mt-4">News List</h4></center>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Link</th>
                    <th scope="col">Actions</th> <!-- Actions Column -->
                </tr>
            </thead>
            <tbody id="newsTableBody">
                <!-- News items will be appended here -->
            </tbody>
        </table>
    </div>
    </div>
</div>

<!-- Undo Delete Modal -->
<div class="modal fade" id="undoDeleteModal" tabindex="-1" aria-labelledby="undoDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Undo Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>A news item was deleted. Do you want to undo?</p>
                <input type="hidden" id="undoDeleteNewsId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="undoDeleteBtn">Undo</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal for event creation and editing -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="mb-3">
                        <label for="eventTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="eventDescription" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="eventStartDate" class="form-label">Date to Start</label>
                        <input type="date" class="form-control" id="eventStartDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventStartTime" class="form-label">Time to Start</label>
                        <input type="time" class="form-control" id="eventStartTime" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventEndDate" class="form-label">Date to End</label>
                        <input type="date" class="form-control" id="eventEndDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventEndTime" class="form-label">Time to End</label>
                        <input type="time" class="form-control" id="eventEndTime" name="end_time" required>
                    </div>
                    <input type="hidden" id="eventId">
                    <button type="submit" class="btn btn-primary" id="saveEventBtn">Save Event</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing event details -->
<!-- Modal for viewing event details -->
<div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Title:</strong> <span id="eventDetailsTitle"></span></p>
                <p><strong>Description:</strong> <span id="eventDetailsDescription"></span></p>
                <p><strong>Date to Start:</strong> <span id="eventDetailsStartDate"></span></p>
                <p><strong>Time to Start:</strong> <span id="eventDetailsStartTime"></span></p>
                <p><strong>Date to End:</strong> <span id="eventDetailsEndDate"></span></p>
                <p><strong>Time to End:</strong> <span id="eventDetailsEndTime"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editEventBtn">Edit Event</button>
                <button type="button" class="btn btn-danger" id="deleteEventBtn">Delete Event</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>


$(document).ready(function () {
    var SITEURL = "{{ url('/') }}";

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#hamburger-icon').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
    $('#addEventBtn').on('click', function () {
        $('#eventModal').modal('show');
        $('#eventForm')[0].reset();
        $('#eventId').val('');
    });
    $('#calendar').fullCalendar({
        editable: true,
        events: SITEURL + "/fullcalendar",
        displayEventTime: false,
        selectable: true,
        selectHelper: true,
        eventRender: function (event, element) {
            var now = moment();
            var start = moment(event.start);
            var end = moment(event.end);
            if (now.isBetween(start, end, null, '[]')) {
                element.append("<div class='label-ongoing'>Ongoing</div>");
            } else if (now.isAfter(end)) {
                element.append("<div class='label-done'>Done</div>");
            } else if (now.isBefore(start)) {
                element.append("<div class='label-soon'>Soon to Happen</div>");
            }
        },
        select: function (start, end) {
            $('#eventModal').modal('show');
            $('#eventForm')[0].reset();
            $('#eventId').val('');
            $('#eventStartDate').val(moment(start).format('YYYY-MM-DD'));
            $('#eventStartTime').val(moment(start).format('HH:mm'));
            $('#eventEndDate').val(moment(end).format('YYYY-MM-DD'));
            $('#eventEndTime').val(moment(end).format('HH:mm'));

            $('#saveEventBtn').off('click').on('click', function (e) {
                e.preventDefault();
                var title = $('#eventTitle').val();
                var description = $('#eventDescription').val();
                var start = $('#eventStartDate').val() + 'T' + $('#eventStartTime').val();
                var end = $('#eventEndDate').val() + 'T' + $('#eventEndTime').val();
                var id = $('#eventId').val();

                $.ajax({
                    url: SITEURL + "/fullcalendarAjax",
                    type: "POST",
                    data: { title, description, start, end, id, type: id ? 'update' : 'add' },
                    success: function () {
                        $('#calendar').fullCalendar('refetchEvents');
                        $('#eventModal').modal('hide');
                        Swal.fire('Success!', 'Event saved successfully!', 'success');
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to save event.', 'error');
                    }
                });
            });
        },

        eventClick: function (event) {
    $('#eventDetailsTitle').text(event.title);
    $('#eventDetailsDescription').text(event.description);
    $('#eventDetailsStartDate').text(moment(event.start).format('YYYY-MM-DD'));
    $('#eventDetailsStartTime').text(event.start_time || moment(event.start).format('HH:mm')); // Handle missing start time
    $('#eventDetailsEndDate').text(moment(event.end).format('YYYY-MM-DD'));
    $('#eventDetailsEndTime').text(event.end_time || moment(event.end).format('HH:mm')); // Handle missing end time

    $('#editEventBtn').off('click').on('click', function () {
        $('#eventModal').modal('show');
        $('#eventTitle').val(event.title);
        $('#eventDescription').val(event.description || ''); // Prevent undefined value
        $('#eventStartDate').val(moment(event.start).format('YYYY-MM-DD'));
        $('#eventStartTime').val(event.start_time || moment(event.start).format('HH:mm')); // Handle missing start time
        $('#eventEndDate').val(moment(event.end).format('YYYY-MM-DD'));
        $('#eventEndTime').val(event.end_time || moment(event.end).format('HH:mm')); // Handle missing end time
        $('#eventId').val(event.id);

        // Save changes
        $('#saveEventBtn').off('click').on('click', function (e) {
            e.preventDefault();
            var updatedTitle = $('#eventTitle').val();
            var updatedDescription = $('#eventDescription').val();
            var updatedStart = $('#eventStartDate').val() + 'T' + $('#eventStartTime').val();
            var updatedEnd = $('#eventEndDate').val() + 'T' + $('#eventEndTime').val();

            $.ajax({
                url: SITEURL + "/fullcalendarAjax",
                type: "POST",
                data: {
                    id: event.id,
                    title: updatedTitle,
                    description: updatedDescription,
                    start: updatedStart,
                    end: updatedEnd,
                    type: 'update'
                },
                success: function () {
                    $('#calendar').fullCalendar('refetchEvents');
                    $('#eventModal').modal('hide');
                    Swal.fire('Updated!', 'Event updated successfully!', 'success');
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to update event.', 'error');
                }
            });
        });
    });

    $('#deleteEventBtn').off('click').on('click', function () {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the event!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: SITEURL + "/fullcalendarAjax",
                    data: {
                        id: event.id,
                        type: 'delete'
                    },
                    type: "POST",
                    success: function () {
                        $('#calendar').fullCalendar('removeEvents', event.id);
                        $('#eventDetailsModal').modal('hide');
                        Swal.fire('Deleted!', 'The event has been deleted.', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Failed to delete event.', 'error');
                    }
                });
            }
        });
    });

    $('#eventDetailsModal').modal('show');
}

});

    function fetchNews() {
        $.ajax({
            url: "{{ route('news.index') }}",
            type: "GET",
            success: function (data) {
                var newsTableBody = $('#newsTableBody');
                newsTableBody.empty();
                $.each(data, function (index, news) {
                    var imageTag = news.image_url ? '<img src="' + news.image_url + '" width="100" alt="News Image">' : 'No image';
                    newsTableBody.append(
                        '<tr>' +
                        '<td>' + imageTag + '</td>' +
                        '<td>' + news.title + '</td>' +
                        '<td><a href="' + news.link + '" target="_blank">View</a></td>' +
                        '<td><button class="btn btn-danger delete-news" data-id="' + news.id + '">Delete</button></td>' +
                        '</tr>'
                    );
                });
            },
            error: function () {
                Swal.fire('Error!', 'Failed to load news.', 'error');
            }
        });
    }

    $('#newsForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "{{ route('news.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                fetchNews();
                $('#newsForm')[0].reset();
                Swal.fire('Success!', 'News added successfully!', 'success');
            },
            error: function () {
                Swal.fire('Error!', 'Failed to add news.', 'error');
            }
        });
    });

$(document).ready(function () {
    $('#newsTableBody').on('click', '.delete-news', function () {
        var id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the news item!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/news/" + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (response) {
                        fetchNews(); // Refresh news list

                        if (response.undo) {
                            // Show Undo Alert with SweetAlert2
                            Swal.fire({
                                title: 'News Deleted!',
                                text: 'Do you want to undo?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Undo',
                                cancelButtonText: 'Dismiss',
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#d33',
                            }).then((undoResult) => {
                                if (undoResult.isConfirmed) {
                                    restoreNews(id);
                                }
                            });
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Failed to delete news.', 'error');
                    }
                });
            }
        });
    });

    function restoreNews(newsId) {
        $.ajax({
            url: "/news/restore",
            type: "POST",
            data: { id: newsId, _token: "{{ csrf_token() }}" },
            success: function () {
                fetchNews(); // Refresh news list
                Swal.fire('Restored!', 'The news item has been restored.', 'success');
            },
            error: function () {
                Swal.fire('Error!', 'Failed to restore news.', 'error');
            }
        });
    }
});
    fetchNews();
});
</script>

</body>
</html>
