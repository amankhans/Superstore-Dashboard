<!DOCTYPE html>
<html>
<head>
    <title>Superstore Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:20px;
        }

        .header{
            text-align:center;
            margin-bottom:30px;
        }

       .cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:20px;
}
     .card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    transition:0.3s ease;
    cursor:pointer;
    border-left:6px solid #007bff;
}
.card:hover{
    transform:translateY(-5px);
}
       .card h2{
    margin:0;
    color:#007bff;
    font-size:32px;
    font-weight:bold;
}
.card:nth-child(1){
    border-left-color:#28a745;
}

.card:nth-child(2){
    border-left-color:#007bff;
}

.card:nth-child(3){
    border-left-color:#fd7e14;
}

.card:nth-child(4){
    border-left-color:#6f42c1;
}
        .chart-container{
            background:white;
            margin-top:20px;
            padding:20px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        }
.chart-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}
.filter-box{
    background:white;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);

    display:flex;
    gap:25px;
    align-items:center;
}
.filter-box select{
    padding:8px;
    margin-left:10px;
    border-radius:5px;
}
    </style>
</head>

<body>

<div class="header">
    <h1>📊 Superstore Sales Dashboard</h1>

<p>
Interactive Business Intelligence Dashboard
built using Laravel, MySQL and Chart.js
</p>
</div>
<div class="filter-box">

<form method="GET">

<label><b>Region Filter:</b></label>

<select name="region" onchange="this.form.submit()">

<option value="All">All Regions</option>

@foreach($regions as $region)

<option value="{{ $region }}"
{{ $selectedRegion == $region ? 'selected' : '' }}>
{{ $region }}
</option>

@endforeach

</select>




<label><b>Category Filter:</b></label>

<select name="category" onchange="this.form.submit()">

<option value="All">All Categories</option>

@foreach($categories as $category)

<option value="{{ $category }}"
{{ $selectedCategory == $category ? 'selected' : '' }}>
{{ $category }}
</option>

@endforeach

</select>
<label><b>Year Filter:</b></label>

<select name="year" onchange="this.form.submit()">

<option value="All">All Years</option>

@foreach($availableYears as $year)

<option value="{{ $year->Year }}"
{{ $selectedYear == $year->Year ? 'selected' : '' }}>
{{ $year->Year }}
</option>

@endforeach

</select>

</form>

</div>
<div class="cards">

    <div class="card">
        <h3>💰 Total Sales</h3>
        <h2>${{ number_format($totalSales->total/1000000,2) }}M</h2>
    </div>

    <div class="card">
        <h3>🌍 Top Region</h3>
        <h2>{{ $topRegion->region }}</h2>
    </div>

    <div class="card">
      <h3>📦 Top Category</h3>
        <h2>{{ $topCategory->category }}</h2>
    </div>

    <div class="card">
      <h3>👥 Top Segment</h3>
        <h2>{{ $topSegment->segment }}</h2>
    </div>

</div>

<div class="chart-grid">

<div class="chart-container">
    <h2>Year-over-Year Sales Trend</h2>
    <canvas id="salesChart"></canvas>
</div>

<div class="chart-container">
    <h2>Monthly Sales Trend</h2>
    <canvas id="monthlyChart"></canvas>
</div>

<div class="chart-container">
    <h2>Regional Sales Performance</h2>
    <canvas id="regionChart"></canvas>
</div>

<div class="chart-container">
    <h2>Category Sales Performance</h2>
    <canvas id="categoryChart"></canvas>
</div>

<div class="chart-container">
    <h2>Customer Segment Performance</h2>
    <canvas id="segmentChart"></canvas>
</div>

<div class="chart-container">
    <h2>Shipping Mode Analysis</h2>
    <canvas id="shippingChart"></canvas>
</div>

<div class="chart-container">
    <h2>Average Order Value by Segment</h2>
    <canvas id="aovChart"></canvas>
</div>

<div class="chart-container" style="grid-column: span 2;">
    <h2>Top 10 Products by Sales</h2>
    <canvas id="productChart"></canvas>
</div>

</div>
<div class="chart-container">
    <h2>Category × Region Matrix</h2>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Region</th>
                <th>Category</th>
                <th>Sales</th>
            </tr>
        </thead>

        <tbody>
            @foreach($categoryRegion as $row)
            <tr>
                <td>{{ $row->Region }}</td>
                <td>{{ $row->Category }}</td>
                <td>${{ number_format($row->Sales,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@php
$years = [];
$sales = [];

foreach($yearlySales as $row){
    $years[] = $row->Year;
    $sales[] = $row->Total_Sales;
}
$months = [];
$monthlyTotals = [];

foreach($monthlySales as $row){
    $months[] = $row->Month;
    $monthlyTotals[] = $row->Total_Sales;
}
$regions = [];
$regionTotals = [];

foreach($regionSales as $row){
    $regions[] = $row->Region;
    $regionTotals[] = $row->Sales;
}
$categories = [];
$categoryTotals = [];

foreach($categorySales as $row){
    $categories[] = $row->Category;
    $categoryTotals[] = $row->Sales;
}
$segments = [];
$segmentTotals = [];

foreach($segmentSales as $row){
    $segments[] = $row->Segment;
    $segmentTotals[] = $row->Sales;
}
$shipModes = [];
$shippingTotals = [];

foreach($shippingSales as $row){
    $shipModes[] = $row->Ship_Mode;
    $shippingTotals[] = $row->Sales;
}
$aovSegments = [];
$aovValues = [];

foreach($aovSegment as $row){
    $aovSegments[] = $row->Segment;
    $aovValues[] = round($row->Avg_Order_Value,2);
}
$products = [];
$productSales = [];

foreach($topProducts as $row){
    $products[] = $row->Product;
    $productSales[] = $row->Sales;
}
@endphp
<script>

const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($years),
      datasets: [{
    label: 'Sales',
    data: @json($sales),
    borderColor: '#007bff',
    backgroundColor: '#007bff'
}]
    }
});
const monthlyCtx = document.getElementById('monthlyChart');

new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: @json($months),
       datasets: [{
    label: 'Monthly Sales',
    data: @json($monthlyTotals),
    backgroundColor: '#28a745'
}]
    }
});
const regionCtx = document.getElementById('regionChart');

new Chart(regionCtx, {
    type: 'bar',
    data: {
        labels: @json($regions),
        datasets: [{
            label: 'Regional Sales',
            data: @json($regionTotals),
            backgroundColor: '#fd7e14'
        }]
    }
});
const categoryCtx = document.getElementById('categoryChart');

new Chart(categoryCtx, {
    type: 'bar',
    data: {
        labels: @json($categories),
        datasets: [{
    label: 'Category Sales',
    data: @json($categoryTotals),
    backgroundColor: '#6f42c1'
}]
    }
});
const segmentCtx = document.getElementById('segmentChart');

new Chart(segmentCtx, {
    type: 'bar',
    data: {
        labels: @json($segments),
       datasets: [{
    label: 'Segment Sales',
    data: @json($segmentTotals),
    backgroundColor: '#dc3545'
}]    }
});
const shippingCtx = document.getElementById('shippingChart');

new Chart(shippingCtx, {
    type: 'bar',
    data: {
        labels: @json($shipModes),
       datasets: [{
    label: 'Shipping Mode Sales',
    data: @json($shippingTotals),
    backgroundColor: '#20c997'
}]    }
});
const aovCtx = document.getElementById('aovChart');

new Chart(aovCtx, {
    type: 'bar',
    data: {
        labels: @json($aovSegments),
        datasets: [{
    label: 'Average Order Value',
    data: @json($aovValues),
    backgroundColor: '#ffc107'
}]    }
});
const productCtx = document.getElementById('productChart');

new Chart(productCtx, {
    type: 'bar',
    data: {
        labels: @json($products),
        datasets: [{
    label: 'Product Sales',
    data: @json($productSales),
    backgroundColor: '#0d6efd'
}]    },
   options: {
    indexAxis: 'y',
    responsive: true,
    plugins: {
        legend: {
            display: false
        }
    },
}
});
</script>
<div style="
text-align:center;
margin-top:30px;
color:gray;
font-size:14px;
">
Superstore Sales Dashboard | Laravel + MySQL + Chart.js
</div>
</body>
</html>