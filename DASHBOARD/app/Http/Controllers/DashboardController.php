<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
       $selectedRegion = request('region');
$selectedCategory = request('category');
$selectedYear = request('year');
$regionFilter = "";

if($selectedRegion && $selectedRegion != 'All'){
    $regionFilter = " AND `COL 13` = '$selectedRegion' ";
}
$categoryFilter = "";

if($selectedCategory && $selectedCategory != 'All'){
    $categoryFilter = " AND `COL 15` = '$selectedCategory' ";
}

$yearFilter = "";

if($selectedYear && $selectedYear != 'All'){
    $yearFilter = " AND YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) = $selectedYear ";
}
$regions = DB::table('train')
    ->where('COL 18', '<>', 'Sales')
    ->distinct()
    ->pluck('COL 13');
$categories = DB::table('train')
    ->where('COL 18', '<>', 'Sales')
    ->distinct()
    ->pluck('COL 15');
$availableYears = DB::select("
SELECT DISTINCT
YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) AS Year
FROM train
WHERE `COL 18` <> 'Sales'
ORDER BY Year
");
        // Total Sales
        $totalSales = DB::table('train')
    ->where('COL 18', '<>', 'Sales');

if($selectedRegion && $selectedRegion != 'All'){
    $totalSales->where('COL 13', $selectedRegion);
}

if($selectedCategory && $selectedCategory != 'All'){
    $totalSales->where('COL 15', $selectedCategory);
}
if($selectedYear && $selectedYear != 'All'){
    $totalSales->whereRaw(
        "YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) = ?",
        [$selectedYear]
    );
}

$totalSales = $totalSales
    ->selectRaw('SUM(CAST(`COL 18` AS DECIMAL(10,2))) as total')
    ->first();
        // Top Region
      $topRegion = DB::table('train')
    ->where('COL 18', '<>', 'Sales');

if($selectedRegion && $selectedRegion != 'All'){
    $topRegion->where('COL 13', $selectedRegion);
}

if($selectedCategory && $selectedCategory != 'All'){
    $topRegion->where('COL 15', $selectedCategory);
}

if($selectedYear && $selectedYear != 'All'){
    $topRegion->whereRaw(
        "YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) = ?",
        [$selectedYear]
    );
}
$topRegion = $topRegion
    ->selectRaw('`COL 13` as region, SUM(CAST(`COL 18` AS DECIMAL(10,2))) as sales')
    ->groupBy('COL 13')
    ->orderByDesc('sales')
    ->first();
     // Top Segment
      $topSegment = DB::table('train')
    ->where('COL 18', '<>', 'Sales');

if($selectedRegion && $selectedRegion != 'All'){
    $topSegment->where('COL 13', $selectedRegion);
}

if($selectedCategory && $selectedCategory != 'All'){
    $topSegment->where('COL 15', $selectedCategory);
}

if($selectedYear && $selectedYear != 'All'){
    $topSegment->whereRaw(
        "YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) = ?",
        [$selectedYear]
    );
}
$topCategory = DB::table('train')
    ->where('COL 18', '<>', 'Sales');

if($selectedRegion && $selectedRegion != 'All'){
    $topCategory->where('COL 13', $selectedRegion);
}

if($selectedCategory && $selectedCategory != 'All'){
    $topCategory->where('COL 15', $selectedCategory);
}

if($selectedYear && $selectedYear != 'All'){
    $topCategory->whereRaw(
        "YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) = ?",
        [$selectedYear]
    );
}

$topCategory = $topCategory
    ->selectRaw('`COL 15` as category, SUM(CAST(`COL 18` AS DECIMAL(10,2))) as sales')
    ->groupBy('COL 15')
    ->orderByDesc('sales')
    ->first();

$topSegment = $topSegment
    ->selectRaw('`COL 8` as segment, SUM(CAST(`COL 18` AS DECIMAL(10,2))) as sales')
    ->groupBy('COL 8')
    ->orderByDesc('sales')
    ->first();
       $yearlySales = DB::select("
    SELECT
        YEAR(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) AS Year,
        ROUND(SUM(CAST(`COL 18` AS DECIMAL(10,2))),2) AS Total_Sales
    FROM train
    WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
    GROUP BY Year
    ORDER BY Year
");
$monthlySales = DB::select("
SELECT
MONTH(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) AS MonthNo,
MONTHNAME(STR_TO_DATE(`COL 3`, '%d/%m/%Y')) AS Month,
ROUND(SUM(CAST(`COL 18` AS DECIMAL(10,2))),2) AS Total_Sales
FROM train
WHERE `COL 18` <> 'Sales'
 $regionFilter
$categoryFilter
$yearFilter
GROUP BY MonthNo, Month
ORDER BY MonthNo
");        
$regionSales = DB::select("
SELECT
    `COL 13` AS Region,
    SUM(CAST(`COL 18` AS DECIMAL(10,2))) AS Sales
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 13`
ORDER BY Sales DESC
");
$categorySales = DB::select("
SELECT
    `COL 15` AS Category,
    SUM(CAST(`COL 18` AS DECIMAL(10,2))) AS Sales
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 15`
ORDER BY Sales DESC
");
$segmentSales = DB::select("
SELECT
    `COL 8` AS Segment,
    SUM(CAST(`COL 18` AS DECIMAL(10,2))) AS Sales
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 8`
ORDER BY Sales DESC
");
$shippingSales = DB::select("
SELECT
    `COL 5` AS Ship_Mode,
    SUM(CAST(`COL 18` AS DECIMAL(10,2))) AS Sales
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 5`
ORDER BY Sales DESC
");
$aovSegment = DB::select("
SELECT
    `COL 8` AS Segment,
    AVG(CAST(`COL 18` AS DECIMAL(10,2))) AS Avg_Order_Value
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 8`
ORDER BY Avg_Order_Value DESC
");
$topProducts = DB::select("
SELECT
    `COL 17` AS Product,
    SUM(CAST(`COL 18` AS DECIMAL(10,2))) AS Sales
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 17`
ORDER BY Sales DESC
LIMIT 10
");
$categoryRegion = DB::select("
SELECT
    `COL 13` AS Region,
    `COL 15` AS Category,
    SUM(CAST(`COL 18` AS DECIMAL(10,2))) AS Sales
FROM train
WHERE `COL 18` <> 'Sales'
$regionFilter
$categoryFilter
$yearFilter
GROUP BY `COL 13`, `COL 15`
ORDER BY `COL 13`, `COL 15`
");
return view('dashboard', compact(
    'totalSales',
    'topRegion',
    'topCategory',
    'topSegment',
    'yearlySales',
    'monthlySales',
    'regionSales',
    'categorySales',
    'segmentSales',
'shippingSales',
   'aovSegment',
'topProducts',
'categoryRegion',
'selectedRegion',
'regions',
'categories',
'selectedCategory',
'availableYears',
'selectedYear'

));
}
}