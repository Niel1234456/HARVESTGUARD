<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{ asset('assets/css/insight.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Charts Dashboard</title>
</head>
<body>
@include('admin.header')
@include('admin.navbar')
<div class="summary-cards">
            <div class="card high">
                <h3>Available Equipment</h3>
                <p><b>{{ $equipmentStats['total'] }} <i class="fas fa-arrow-up"></i></b></p>
            </div>
            <div class="card low">
                <h3>Borrowed Equipment</h3>
                <p><b>{{ $equipmentStats['borrowed'] }} <i class="fas fa-arrow-down"></i></b></p>
            </div>
            <div class="card high">
                <h3>Available Supply</h3>
                <p><b>{{ $supplyStats['total'] }} <i class="fas fa-arrow-up"></i></b></p>
            </div>
            <div class="card low">
                <h3>Requested Supplies</h3>
                <p><b>{{ $supplyStats['requested'] }} <i class="fas fa-arrow-down"></i></b></p>
            </div>
        </div>

        <br>

        <div class="parent-container">
            <!-- Most and Least Requested Supply -->
            <div class="cards-container">
                <h3>Most Requested Supply</h3>
                <p class="count"> <b>{{ $mostRequestedSupply['count'] }} </b> 
                <span class="parentheses">(</span><span class="percentage">{{ $mostRequestedSupply['percentage'] }}</span><span class="parentheses">%)</span></p>
                <p> {{ $mostRequestedSupply['name'] }}</p>

                <h3>Least Requested Supply</h3>
                <p class="count"><b>{{ $leastRequestedSupply['count'] }}</b> 
                <span class="parentheses">(</span><span class="percentage">{{ $leastRequestedSupply['percentage'] }}</span><span class="parentheses">%)</span></p>
                <p>{{ $leastRequestedSupply['name'] }}</p>
            </div>

            <!-- Most and Least Borrowed Equipment -->
            <div class="cards-container">
                <h3>Most Borrowed Equipment</h3>
                <p class = "count"> <b>{{ $mostBorrowedEquipment['count'] }} </b> 
                <span class="parentheses">(</span><span class="percentage">{{ $mostBorrowedEquipment['percentage']  }}</span><span class="parentheses">%)</span></p>
                <p>{{ $mostBorrowedEquipment['name'] }}</p>
           
                <h3>Least Borrowed Equipment</h3>
                <p  class = "count"><b>{{ $leastBorrowedEquipment['count'] }}</b> 
                <span class="parentheses">(</span><span class="percentage">{{ $leastBorrowedEquipment['percentage'] }}</span><span class="parentheses">%)</span></p>
                <p>{{ $leastBorrowedEquipment['name'] }}</p>
                </div>
        </div>
    <div class="container">
        <!-- First Row -->
        <div class="chart-row">
            <div class="chart-container" id="equipmentSupplyChartContainer">
                <canvas id="equipmentSupplyChart"></canvas>
            </div>
            <div class="chart-container" id="managementChartContainer">
                <canvas id="managementChart"></canvas>
                <div class="legend"></div>
            </div>
        </div>
        <!-- Second Row -->
        <div class="chart-row">
            <div class="chart-container" id="farmerRegistrationChartContainer">
                <canvas id="farmerRegistrationChart"></canvas>
            </div>
            <div class="chart-container" id="combinedAreaChartContainer">
                <canvas id="combinedAreaChart"></canvas>
            </div>
        </div>
        <!-- Third Row -->
        <div class="chart-row">
            <div class="chart-container" id="diseaseAnalyticsChartContainer">
                <canvas id="diseaseAnalyticsChart"></canvas>  

            </div>
        </div> 
    </div>
    <a href="{{ route('admin.admin.insight.report') }}" class="btn btn-red" target="_blank">Generate Report</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function() {
        let supplyPercentage = {{ $supplyPercentage }};
        let equipmentPercentage = {{ $equipmentPercentage }};

        if (supplyPercentage <= 20) {
            Swal.fire({
                icon: 'warning',
                title: 'Low Supply Alert!',
                text: 'Available supplies have dropped below 20%.  Please check Equipment Management.Please restock soon.',
                confirmButtonColor: '#d33'
            });
        }

        if (equipmentPercentage <= 20) {
            Swal.fire({
                icon: 'warning',
                title: 'Low Equipment Alert!',
                text: 'Available equipment has dropped below 20%. Please check Equipment Management. and restock soon. ',
                confirmButtonColor: '#d33'
            });
        }
    });

document.addEventListener('DOMContentLoaded', function() {
    // Data variables
    const supplyRequestsData = @json($supplyRequests);
    const borrowRequestsData = @json($borrowRequests);
    const farmersPerMonthData = @json($farmersPerMonth);
    const mostCommonDiseases = @json($mostCommonDiseases->pluck('disease_name'));
    const diseaseDistribution = @json($diseaseDistribution->pluck('count'));
    const averageConfidenceLevels = @json($averageConfidenceLevels->pluck('avg_confidence'));
    const combinedLabels = @json($combinedLabels);
    const combinedSupplyData = @json($combinedData['supplyRequests']);
    const combinedBorrowData = @json($combinedData['borrowRequests']);

    // Function to create charts
    function createChart(ctx, type, data, options) {
        return new Chart(ctx, { type, data, options });
    }

    // Equipment vs Supply Requests Chart
    new Chart(document.getElementById('equipmentSupplyChart'), {
        type: 'line',
        data: {
            labels: supplyRequestsData.map(data => `Week ${data.week}`),
            datasets: [
                {
                    label: 'Requested Supplies',
                    data: supplyRequestsData.map(data => data.count),
                    borderColor: 'rgba(34, 139, 34, 1)',
                    backgroundColor: 'rgba(34, 139, 34, 0.5)',
                    borderWidth: 4
                },
                {
                    label: 'Borrowed Equipment',
                    data: borrowRequestsData.map(data => data.count),
                    borderColor: 'rgba(255, 204, 0, 1)',
                    backgroundColor: 'rgba(255, 204, 0, 0.5)',
                    borderWidth: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true },
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('managementChart'), {
        type: 'doughnut',
        data: {
            labels: ['Available Supplies', 'Requested Supplies', 'Available Equipment', 'Borrowed Equipment'],
            datasets: [{
                data: [
                    {{ round(($supplyStats['available'] / $supplyStats['total']) * 100, 2) }},
                    {{ round(($supplyStats['requested'] / $supplyStats['total']) * 100, 2) }},
                    {{ round(($equipmentStats['available'] / $equipmentStats['total']) * 100, 2) }},
                    {{ round(($equipmentStats['borrowed'] / $equipmentStats['total']) * 100, 2) }}
                ],
                backgroundColor: [
                    'rgba(34, 139, 34, 0.8)',
                    'rgba(255, 255, 0, 0.8)',
                    'rgba(50, 205, 50, 0.8)',
                    'rgba(255, 215, 0, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 139, 34, 1)',
                    'rgba(255, 255, 0, 1)',
                    'rgba(50, 205, 50, 1)',
                    'rgba(255, 215, 0, 1)'
                ],
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                    align: 'start',
                    labels: {
                        boxWidth: 20,
                        padding: 10,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) label += ': ';
                            if (context.parsed !== null) label += context.parsed + '%';
                            return label;
                        }
                    }
                }
            },
            cutout: '60%',
            maintainAspectRatio: false
        }
    });

    // Farmer Registration Chart (Horizontal Bar Chart)
    new Chart(document.getElementById('farmerRegistrationChart'), {
        type: 'bar',
        data: {
            labels: farmersPerMonthData.map(data => `Month ${data.month}`),
            datasets: [{
                label: 'Registered Farmers',
                data: farmersPerMonthData.map(data => data.count),
                backgroundColor: 'rgba(34, 139, 34, 0.8)',
                borderColor: 'rgba(34, 139, 34, 1)',
                borderWidth: 4
            }]
        },
        options: {
            indexAxis: 'y', // This makes the chart horizontal
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Combined Area Chart
    createChart(document.getElementById('combinedAreaChart'), 'line', {
        labels: combinedLabels,
        datasets: [{
            label: 'Supply Requests',
            data: combinedSupplyData,
            backgroundColor: 'rgba(255, 215, 0, 0.4)',
            borderColor: 'rgba(255, 215, 0, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.2
        }, {
            label: 'Borrowed Equipment',
            data: combinedBorrowData,
            backgroundColor: 'rgba(0, 128, 0, 0.4)',
            borderColor: 'rgba(0, 128, 0, 1)',
            borderWidth: 3,
            fill: true,
            tension: 0.5
        }]
    }, {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: { beginAtZero: true },
            y: { beginAtZero: true }
        }
    });

    var ctx = document.getElementById('diseaseAnalyticsChart').getContext('2d');
    var diseaseAnalyticsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($mostCommonDiseases->pluck('disease_name')),
            datasets: [
                {
                    label: ' Average Confidence (%)',
                    data: @json($averageConfidenceLevels->pluck('avg_confidence')),
                    backgroundColor: 'rgba(255, 140, 0, 0.4)',
                    borderColor: 'rgba(255, 140, 0, 1)',
                    borderWidth: 15
                },
                { 
                    label: ' Disease Count                                                                                          Year: 2025',
                    data: @json($diseaseDistribution->pluck('count')),
                    backgroundColor: 'rgba(0, 128, 0, 0.4)',
                    borderColor: 'rgba(0, 128, 0, 1)',
                    borderWidth: 15
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            scales: {
                x: {
                    stacked: true,
                    beginAtZero: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    align: 'start',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 5,
                        padding: 12,
                        font: {
                            size: 12
                        },
                        color: '#34495e'
                    }
                }
            }
        }
    });

    // Add click event to expand/collapse charts
    document.querySelectorAll('.chart-container').forEach(container => {
        container.addEventListener('click', function() {
            const isExpanded = this.classList.contains('expanded');
            document.querySelectorAll('.chart-container').forEach(el => el.classList.remove('expanded'));
            if (!isExpanded) {
                this.classList.add('expanded');
            }
        });
    });

    // Hamburger menu functionality
    $('#hamburger-icon').on('click', function(){
        $('#sidebar').toggleClass('active'); // Toggle sidebar visibility
    });

});

// Notification toggle function
function toggleNotification() {
    var notificationCard = document.getElementById('notification-card');
    notificationCard.classList.toggle('show');
}
</script>
</body>
</html>
