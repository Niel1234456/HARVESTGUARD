<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Existing Farmers Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ExistingFarmers.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    @media (max-width: 768px) {
        #searchInput{
        margin-bottom: 2%;
        width: 33.3em;
        }

        #sortSelect{
        width: 33.3em;
        }
    }
</style>
</head>
<body>
@include('admin.header')
@include('admin.navbar')



    <div class="container">
    <h1>Archive Farmers</h1>
    <div class="row mb-3 align-items-center">
        <div class="col-md-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search Farmers...">
        </div>
        <div class="col-md-3">
            <select id="sortSelect" class="form-control">
                <option value="name">Sort by Name (A-Z)</option>
                <option value="age">Sort by Age (Ascending)</option>
                <option value="middle_initial">Sort by M.I (A-Z)</option>
            </select>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#addFarmerModal">Add Archive Farmer</button>
        </div>
    </div>
</div>

        @if ($existingFarmers->isNotEmpty())
    <div class="container">
        <table id="farmersTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>M.I</th>
                    <th>Age</th>
                    <th>Birthday</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Address 1</th>
                    <th>Address 2</th>
                    <th>Actions</th>
                    <tr id="columnLabels">
                </tr>
                </tr>
            </thead>
            <tbody>
                @foreach ($existingFarmers as $farmer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $farmer->firstname }}</td>
                        <td>{{ $farmer->lastname }}</td>
                        <td>{{ $farmer->middle_initial }}</td>
                        <td>{{ $farmer->age }}</td>
                        <td>{{ $farmer->birthday }}</td>
                        <td>{{ $farmer->email }}</td>
                        <td>{{ $farmer->phone_number }}</td>
                        <td>{{ $farmer->address_1 }}</td>
                        <td>{{ $farmer->address_2 }}</td>
                        <td>

                            <form action="{{ route('admin.existingFarmers.destroy', $farmer->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                        
                    </tr>
                    <div class="modal fade" id="editFarmerModal-{{ $farmer->id }}" tabindex="-1" role="dialog" aria-labelledby="editFarmerModalLabel-{{ $farmer->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editFarmerModalLabel-{{ $farmer->id }}">Edit Farmer</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('admin.existingFarmers.update', $farmer->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="firstname-{{ $farmer->id }}">First Name</label>
                                            <input type="text" id="firstname-{{ $farmer->id }}" name="firstname" class="form-control" value="{{ $farmer->firstname }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastname-{{ $farmer->id }}">Last Name</label>
                                            <input type="text" id="lastname-{{ $farmer->id }}" name="lastname" class="form-control" value="{{ $farmer->lastname }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="middle_initial-{{ $farmer->id }}">Middle Initial</label>
                                            <input type="text" id="middle_initial-{{ $farmer->id }}" name="middle_initial" class="form-control" value="{{ $farmer->middle_initial }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="age-{{ $farmer->id }}">Age</label>
                                            <input type="number" id="age-{{ $farmer->id }}" name="age" class="form-control" value="{{ $farmer->age }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="birthday-{{ $farmer->id }}">Birthday</label>
                                            <input type="date" id="birthday-{{ $farmer->id }}" name="birthday" class="form-control" value="{{ $farmer->birthday }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email-{{ $farmer->id }}">Email</label>
                                            <input type="email" id="email-{{ $farmer->id }}" name="email" class="form-control" value="{{ $farmer->email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone_number-{{ $farmer->id }}">Phone Number</label>
                                            <input type="text" id="phone_number-{{ $farmer->id }}" name="phone_number" class="form-control" value="{{ $farmer->phone_number }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="address_1-{{ $farmer->id }}">Address 1</label>
                                            <input type="text" id="address_1-{{ $farmer->id }}" name="address_1" class="form-control" value="{{ $farmer->address_1 }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="address_2-{{ $farmer->id }}">Address 2</label>
                                            <input type="text" id="address_2-{{ $farmer->id }}" name="address_2" class="form-control" value="{{ $farmer->address_2 }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
        <div class="modal fade" id="addFarmerModal" tabindex="-1" role="dialog" aria-labelledby="addFarmerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFarmerModalLabel">Add New Farmer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.existingFarmers.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" id="firstname" name="firstname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" id="lastname" name="lastname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="middle_initial">Middle Initial</label>
                                <input type="text" id="middle_initial" name="middle_initial" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="number" id="age" name="age" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="birthday">Birthday</label>
                                <input type="date" id="birthday" name="birthday" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="address_1">Address 1</label>
                                <input type="text" id="address_1" name="address_1" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="address_2">Address 2</label>
                                <input type="text" id="address_2" name="address_2" class="form-control">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Farmer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@else
    <p>No Historical Farmer Data.</p>
@endif
    <div class="d-flex justify-content-center">
        <ul class="pagination">
            @if ($existingFarmers->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Previous</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $existingFarmers->previousPageUrl() }}" class="page-link">Previous</a>
                </li>
            @endif

            @for ($i = 1; $i <= $existingFarmers->lastPage(); $i++)
                <li class="page-item {{ $existingFarmers->currentPage() == $i ? 'active' : '' }}">
                    <a href="{{ $existingFarmers->url($i) }}" class="page-link">{{ $i }}</a>
                </li>
            @endfor
            @if ($existingFarmers->hasMorePages())
                <li class="page-item">
                    <a href="{{ $existingFarmers->nextPageUrl() }}" class="page-link">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Next</span>
                </li>
            @endif
        </ul>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
             $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active'); 
        });
    });
        
        function confirmDelete() {
            return confirm('Are you sure you want to delete this farmer?');
        }

        document.getElementById('searchInput').addEventListener('keyup', function () {
            var value = this.value.toLowerCase();
            var rows = document.querySelectorAll('#farmersTable tbody tr');
            rows.forEach(function (row) {
                var text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? '' : 'none';
            });
        });

        document.getElementById('sortSelect').addEventListener('change', function () {
            var sortBy = this.value;
            var rows = Array.from(document.querySelectorAll('#farmersTable tbody tr'));
            rows.sort(function (a, b) {
                var aText = a.querySelector('td:nth-child(' + (sortBy === 'name' ? 2 : (sortBy === 'age' ? 5 : 7)) + ')').innerText;
                var bText = b.querySelector('td:nth-child(' + (sortBy === 'name' ? 2 : (sortBy === 'age' ? 5 : 7)) + ')').innerText;
                return aText.localeCompare(bText);
            });
            var tbody = document.querySelector('#farmersTable tbody');
            tbody.innerHTML = '';
            rows.forEach(function (row) {
                tbody.appendChild(row);
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');

            hamburger.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const columns = ['#', 'Name', 'Quantity', 'Unit', 'Image', 'Actions', 'Email', '1,', '2', '3', '4'];
            const columnLabelsRow = document.getElementById('columnLabels');

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
        document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar');
        const hamburger = document.querySelector('.hamburger');

        hamburger.addEventListener('click', function () {
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.add('expanded');
            } else {
                sidebar.classList.remove('expanded');
                sidebar.classList.add('collapsed');
            }
        });
    });
    </script>
</body>

</html>
