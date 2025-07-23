<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supply and Equipment Status Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 40px;
            padding: 20px;
            border: 1px solid #000;
            text-align: center;
        }
        header, footer {
            text-align: center;
            padding: 10px;
        }
        header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        h1, h2 {
            text-align: center;
        }
        p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<h4 style="background-color: #004d00; color: white; padding: 10px; text-align: center; border-radius: 5px;">
    City Agriculture Office of Carmona | Department of Agriculture
</h4>
<h1>Comprehensive Analysis Report</h1>

<style>
    p {
        text-align: justify;
        text-indent: 30px;
    }
    ul {
        text-align: justify;
        text-indent: 30px;
    }
</style>


<p>
    This report provides an in-depth analysis of the supply and equipment inventory, plant disease detection trends, and the borrowing and request patterns observed in the system. By examining real-time data, we identify key trends, compare performance metrics, analyze distribution patterns, and evaluate relationships between various factors. This analysis aims to provide valuable insights that can help in decision-making and resource optimization.
</p>
<p>
    Based on the recorded data, the total supply available is <b>{{ $supplyStats['total'] }}</b>, with <b>{{ $supplyStats['requested'] }}</b> supplies requested, leaving <b>{{ $supplyStats['available'] }}</b> supplies in stock. The most requested supply is <b>{{ $mostRequestedSupply['name'] }}</b>, requested <b>{{ $mostRequestedSupply['count'] }}</b> times, accounting for <b>{{ $mostRequestedSupply['percentage'] }}%</b> of total requests. Conversely, the least requested supply is <b>{{ $leastRequestedSupply['name'] }}</b>, with only <b>{{ $leastRequestedSupply['count'] }}</b> requests, making up <b>{{ $leastRequestedSupply['percentage'] }}%</b> of total requests. Similarly, equipment usage trends show that out of <b>{{ $equipmentStats['total'] }}</b> total equipment, <b>{{ $equipmentStats['borrowed'] }}</b> have been borrowed, leaving <b>{{ $equipmentStats['available'] }}</b> available. The most borrowed equipment is <b>{{ $mostBorrowedEquipment['name'] }}</b>, borrowed <b>{{ $mostBorrowedEquipment['count'] }}</b> times, representing <b>{{ $mostBorrowedEquipment['percentage'] }}%</b> of the total, while the least borrowed equipment is <b>{{ $leastBorrowedEquipment['name'] }}</b>, borrowed <b>{{ $leastBorrowedEquipment['count'] }}</b> times, making up <b>{{ $leastBorrowedEquipment['percentage'] }}%</b>.
</p>
<p>
    In terms of plant disease analysis, a total of <b>{{ $totalImagesAnalyzed }}</b> images have been analyzed, revealing <b>{{ $healthyCount }}</b> healthy plants and <b>{{ $diseasedCount }}</b> diseased plants. The most common plant diseases identified include:
</p>
<ul>
    @foreach ($mostCommonDiseases as $disease)
        <li><b>{{ $disease->disease_name }}</b> - Identified <b>{{ $disease->total }}</b> times</li>
    @endforeach
</ul>
<p>
    These findings highlight recurring patterns in plant health issues, aiding in early detection and management strategies.
</p>

<p>
    The analysis of real-time data provides actionable insights into resource management and plant health monitoring. By recognizing trends, making informed comparisons, evaluating distributions, and understanding relationships, stakeholders can make strategic decisions to enhance efficiency and sustainability. Regular monitoring and data-driven adjustments will be essential in ensuring optimal resource utilization and improved agricultural outcomes.
</p>

<script>
    window.onload = function() {
        window.print();
    };
</script>

    <div class="title">
        <h1>DATA TABLE</h1>
    </div>
<p>This table provides the Data Table of supply availability, equipment utilization, and plant health assessment. The data presented herein aims to identify critical shortages, common requests, and areas for improvement to ensure efficient resource management and agricultural productivity.</p>

<h2>Supply Status</h2>
<table>
    <tr>
        <th>Total Supply</th>
        <th>Requested Supply</th>
        <th>Available Supply</th>
    </tr>
    <tr>
        <td>{{ $supplyStats['total'] }}</td>
        <td>{{ $supplyStats['requested'] }}</td>
        <td>{{ $supplyStats['available'] }}</td>
    </tr>
</table>

<h2>Equipment Status</h2>
<table>
    <tr>
        <th>Total Equipment</th>
        <th>Borrowed Equipment</th>
        <th>Available Equipment</th>
    </tr>
    <tr>
        <td>{{ $equipmentStats['total'] }}</td>
        <td>{{ $equipmentStats['borrowed'] }}</td>
        <td>{{ $equipmentStats['available'] }}</td>
    </tr>
</table>

<h2>Most and Least Requested Supplies</h2>
<table>
    <tr>
        <th>Supply Name</th>
        <th>Request Count</th>
        <th>Percentage</th>
    </tr>
    <tr>
        <td>{{ $mostRequestedSupply['name'] }}</td>
        <td>{{ $mostRequestedSupply['count'] }}</td>
        <td>{{ $mostRequestedSupply['percentage'] }}%</td>
    </tr>
    <tr>
        <td>{{ $leastRequestedSupply['name'] }}</td>
        <td>{{ $leastRequestedSupply['count'] }}</td>
        <td>{{ $leastRequestedSupply['percentage'] }}%</td>
    </tr>
</table>

<h2>Most and Least Borrowed Equipment</h2>
<table>
    <tr>
        <th>Equipment Name</th>
        <th>Borrow Count</th>
        <th>Percentage</th>
    </tr>
    <tr>
        <td>{{ $mostBorrowedEquipment['name'] }}</td>
        <td>{{ $mostBorrowedEquipment['count'] }}</td>
        <td>{{ $mostBorrowedEquipment['percentage'] }}%</td>
    </tr>
    <tr>
        <td>{{ $leastBorrowedEquipment['name'] }}</td>
        <td>{{ $leastBorrowedEquipment['count'] }}</td>
        <td>{{ $leastBorrowedEquipment['percentage'] }}%</td>
    </tr>
</table>

<h2>Plant Disease Analysis</h2>
<table>
    <tr>
        <th>Total Analyzed Images</th>
        <th>Healthy Plants</th>
        <th>Diseased Plants</th>
    </tr>
    <tr>
        <td>{{ $totalImagesAnalyzed }}</td>
        <td>{{ $healthyCount }}</td>
        <td>{{ $diseasedCount }}</td>
    </tr>
</table>

<h2>Most Common Plant Diseases</h2>
<ul style="text-align: center; list-style-position: inside;">
    @foreach ($mostCommonDiseases as $disease)
        <li>{{ $disease->disease_name }} - Identified {{ $disease->total }} times</li>
    @endforeach
</ul>

<footer>
    <p>&copy; 2025 Supply and Equipment Analysis Report | All Rights Reserved</p>
</footer>

</body>
</html>
