
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboardAD.css') }}">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
</head> 
<style>

.weather-selector select {
    -webkit-appearance: none; 
    -moz-appearance: none; 
    appearance: none; 
    background-image: none; 
    padding-right: 10px; 
}
</style>
<body>
@include('farmer.navbar')
@include('farmer.header')
<div class="container">
    <div class="left-section">
        <div class="summary-cards">
            <!-- Supply Requests -->
            <div class="card high equipment-card">
                <h3>Total Supply Requests:</h3>
                <p><b><i class="fas fa-boxes"></i>{{ $totalSupplyRequests }}</b></p>
            </div>
            <!-- Borrow Requests -->
            <div class="card high supplies-card">
                <h3>Total Borrow Requests:</h3>
                <p><b><i class="fas fa-tools"></i>{{ $totalBorrowRequests }}</b></p>
            </div>
        </div>
        <div class="card-container">
            <div class="table-container">
            <div class="table-header">
                <div class="table-cell">Type</div>
                <div class="table-cell">Item Name</div>
                <div class="table-cell">Quantity</div>
                <div class="table-cell">Status</div>
            </div>
            @foreach($supplyRequests as $request)
            <div class="table-row">
                <div class="table-cell">Supply</div>
                <div class="table-cell">{{ $request->supply->name }}</div>
                <div class="table-cell">{{ $request->quantity }}</div>
                <div class="table-cell status {{ strtolower($request->status) }}">{{ ucfirst($request->status) }}</div>
            </div>
            @endforeach
            @foreach($borrowRequests as $request)
            <div class="table-row">
                <div class="table-cell">Borrow</div>
                <div class="table-cell">{{ $request->equipment->name }}</div>
                <div class="table-cell">{{ $request->quantity }}</div>
                <div class="table-cell status {{ strtolower($request->status) }}">{{ ucfirst($request->status) }}</div>
            </div>
            @endforeach
            </div>
            <br>
            <h4>RECENT NEWS</h4>
            <div class="card-news">
                <div class="card-body-news">

                    <div class="row-news">
                        @foreach($recentNews as $news)
                            <div class="col-md-4 mb-3">
                                <div class="news-item">
                                    @if($news->image)
                                        <img src="{{ asset('images/' . $news->image) }}" alt="{{ $news->title }}" class="img-fluid">
                                    @endif
                                    <h5>{{ $news->title }}</h5> 
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
    <!-- Recent Events Section -->
        <h4>RECENT EVENTS</h4>
            <div class="card-events">
                <div class="card-body-events">
                    @foreach($recentEvents as $event)
                        <div class="event-item">
                        <h5><b>{{ $event->title }}</b></h5>
<p><b>Description: </b>{{ $event->description }}</p>
<p><strong>Start:</strong> {{ \Carbon\Carbon::parse($event->start)->format('F j, Y') }} at {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}</p>
<p><strong>End:</strong> {{ \Carbon\Carbon::parse($event->end)->format('F j, Y') }} at {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</p>
                        </div>
                    @endforeach
                </div>
            </div> 
        </div>
    </div>
    <div class="right-section">
        <div class="weather-forecast">
            <h5>Weather Forecast</h5>
            <div class="weather-custom-container">
            <div class="weather-selector">
                <select id="location-selector" class="form-control">
                    <option value="Carmona, Cavite">Carmona, Cavite</option>
                </select>
            </div>
                <div class="custom-location">
                    <input type="text" id="custom-location-input" class="form-control" placeholder="Enter name of location">
                    <button id="add-custom-location" class="btn btn-primary">Search Location</button>
                </div>
            </div>
            <div id="weather-container">
                <h2>${data.name}</h2>
                <div class="weather-temp-wrapper">
                    <p><b>Weather</b> <span id="weather-type">${weatherType}</span></p>
                    <p><b>Temperature</b> <span id="temp">${temp} °C</span></p>
                </div>
                <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}.png" alt="${weatherType}">
            </div>
            <div class="weather-details">
                <div class="weather-detail-card">
                    <i class="fas fa-tint"></i>
                    <p><b>Humidity:</b> <span id="humidity">--</span>%</p>
                </div>
                <div class="weather-detail-card">
                    <i class="fas fa-wind"></i>
                    <p><b>Wind Speed:</b> <span id="windSpeed">--</span> m/s</p>
                </div>
                <div class="weather-detail-card">
                    <i class="fas fa-tachometer-alt"></i>
                    <p><b>Pressure:</b> <span id="pressure">--</span> hPa</p>
                </div>
                <div class="weather-detail-card">
                    <i class="fas fa-eye"></i>
                    <p><b>Visibility:</b> <span id="visibility">--</span> km</p>
                </div>
                <div class="weather-detail-card">
                    <i class="fas fa-sun"></i>
                    <p><b>Sunrise:</b> <span id="sunrise">--</span></p>
                </div>
                <div class="weather-detail-card">
                    <i class="fas fa-moon"></i>
                    <p><b>Sunset:</b> <span id="sunset">--</span></p>
                </div>
            </div>
            <div class="hourly-forecast" id="hourly-container"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="forecast.js"></script>
<script>
     $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active');
        });
    });
    
document.addEventListener('DOMContentLoaded', function () {
    const apiKey = 'd45331dee5142f9567062b406ba10cd2';

    function updateWeatherBackground(weatherType) {
        const weatherContainer = document.getElementById('weather-container');
        weatherContainer.classList.remove('clear-sky', 'rainy', 'cloudy', 'snowy', 'stormy', 'foggy', 'hazy', 'windy', 'sunny');

        switch (weatherType) {
            case 'Clear':
                weatherContainer.classList.add('clear-sky');
                break;
            case 'Rain':
                weatherContainer.classList.add('rainy');
                break;
            case 'Clouds':
                weatherContainer.classList.add('cloudy');
                break;
            case 'Snow':
                weatherContainer.classList.add('snowy');
                break;
            case 'Storm':
                weatherContainer.classList.add('stormy');
                break;
            case 'Fog':
                weatherContainer.classList.add('foggy');
                break;
            case 'Haze':
                weatherContainer.classList.add('hazy');
                break;
            case 'Wind':
                weatherContainer.classList.add('windy');
                break;
            case 'Sunny':
                weatherContainer.classList.add('sunny');
                break;
            default:
                weatherContainer.classList.add('default');
                break;
        }
    }

    function fetchWeatherData(location) {
        fetch(`https://api.openweathermap.org/data/2.5/weather?q=${location}&appid=${apiKey}&units=metric`)
            .then(response => response.json())
            .then(data => {
                const weatherType = data.weather[0].main;
                const temp = data.main.temp;
                const humidity = data.main.humidity;
                const windSpeed = data.wind.speed;
                const pressure = data.main.pressure;
                const visibility = data.visibility / 1000;
                const sunrise = new Date(data.sys.sunrise * 1000).toLocaleTimeString();
                const sunset = new Date(data.sys.sunset * 1000).toLocaleTimeString();

                document.getElementById('weather-container').innerHTML = `
                    <h2>${data.name}</h2>
                    <p><b>Temperature:</b> ${temp} °C</p>
                    <p><b>Weather:</b> ${weatherType}</p>
                    <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}.png" alt="${weatherType}">
                `;

                document.getElementById('humidity').textContent = humidity;
                document.getElementById('windSpeed').textContent = windSpeed;
                document.getElementById('pressure').textContent = pressure;
                document.getElementById('visibility').textContent = visibility;
                document.getElementById('sunrise').textContent = sunrise;
                document.getElementById('sunset').textContent = sunset;

                updateWeatherBackground(weatherType);

                // Fetch and display hourly forecast
                fetchHourlyForecast(location);
            })
            .catch(error => console.error('Error fetching weather data:', error));
    }
    function fetchHourlyForecast(location) {
    fetch(`https://api.openweathermap.org/data/2.5/forecast?q=${location}&appid=${apiKey}&units=metric`)
        .then(response => response.json())
        .then(data => {
            const hourlyContainer = document.getElementById('hourly-container');
            hourlyContainer.innerHTML = '<h5>Hourly Forecast</h5>';

            data.list.slice(0, 8).forEach(entry => {
                const time = new Date(entry.dt * 1000).toLocaleTimeString();
                const temp = entry.main.temp;
                const weatherType = entry.weather[0].main;
                const icon = entry.weather[0].icon;

                // Determine the class based on weather type
                let weatherClass;
                switch (weatherType.toLowerCase()) {
                    case 'clear':
                    case 'sunny': 
                        weatherClass = 'sunny-card';
                        break;
                    case 'clouds':
                        weatherClass = 'cloudy-card';
                        break;
                    case 'rain':
                        weatherClass = 'rainy-card';
                        break;
                    case 'snow':
                        weatherClass = 'snowy-card';
                        break;
                    case 'fog':
                        weatherClass = 'foggy-card';
                        break;
                    default:
                        weatherClass = 'default-card'; // Fallback class
                }

                hourlyContainer.innerHTML += `
                    <div class="hourly-card ${weatherClass}">
                        <p><b>${time}</b></p>
                        <img src="https://openweathermap.org/img/wn/${icon}.png" alt="${weatherType}">
                        <p>${temp} °C</p>
                    </div>
                `;
            });
        })
        .catch(error => console.error('Error fetching hourly forecast:', error));
}


    document.getElementById('location-selector').addEventListener('change', function () {
        fetchWeatherData(this.value);
    });

    document.getElementById('add-custom-location').addEventListener('click', function () {
        const customLocation = document.getElementById('custom-location-input').value;
        if (customLocation) {
            fetchWeatherData(customLocation);
        }
    });

    // Fetch initial weather data for default location
    fetchWeatherData('Carmona, Cavite');
    fetch('https://pixabay.com/api/?key=45792800-b2d84641b91e8d91896d66a36&q=clear+sky')
  .then(response => response.json())
  .then(data => {
    const imageUrl = data.hits[0].largeImageURL;
    document.querySelector('.clear-sky').style.backgroundImage = `url(${imageUrl})`;
  });
  
  document.getElementById('newsForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Get the input values
    const title = document.getElementById('newsTitle').value;
    const link = document.getElementById('newsLink').value;

    // Call the Laravel API to fetch the thumbnail
    fetch(`/fetch-thumbnail?url=${encodeURIComponent(link)}`)
        .then(response => response.json())
        .then(data => {
            if (data.thumbnail) {
                // Create a new row in the news table with the fetched thumbnail
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${title}</td>
                    <td>
                        <a href="${link}" target="_blank">
                            <img src="${data.thumbnail}" alt="Thumbnail" width="50" height="50">
                        </a>
                    </td>
                    <td><button class="btn btn-danger btn-sm delete-news">Delete</button></td>
                `;

                // Append the new row to the table
                document.getElementById('newsTableBody').appendChild(newRow);
            } else {
                alert('Thumbnail not found or link is invalid.');
            }
        });

    // Clear the form after submission
    this.reset();
});

// Delete news item functionality
document.getElementById('newsTableBody').addEventListener('click', function(event) {
    if (event.target.classList.contains('delete-news')) {
        event.target.closest('tr').remove();
    }
});

});


</script>



</body>
</html>
