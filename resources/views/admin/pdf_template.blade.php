<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Farmer Report</title>
    <style>
        /* Define your PDF styles here */
        body {
            font-family: Arial, sans-serif;
        }
        /* Example styles */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #00796b;
        }
        /* Add more styles as needed */
    </style>
</head>
<body>
    <div class="container">
        <h2>Report for {{ $farmer->name }}</h2>
        <p>Borrowed Equipment: {{ $farmer->borrowed_equipment }}</p>
        <p>Requested Supply: {{ $farmer->requested_supply }}</p>
        <!-- Add more report details as needed -->
    </div>
</body>
</html>
