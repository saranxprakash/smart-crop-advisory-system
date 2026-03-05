<?php

// ================= SAFE INPUT DATA =================
$location = $_POST['location'] ?? '';
$season = $_POST['season'] ?? '';
$soil = $_POST['soil'] ?? '';
$water = $_POST['water'] ?? '';
$land_size = $_POST['land_size'] ?? 1;
$budget = $_POST['budget'] ?? 'Medium';
$previous_crop = $_POST['previous_crop'] ?? '';
$lat = $_POST['latitude'] ?? '';
$lon = $_POST['longitude'] ?? '';

$apiKey = "ec49e345f1cfba5aefbf665d6b8cc0c4"; // 🔥 PUT YOUR REAL KEY HERE

// ================= WEATHER APIs =================
if (!empty($lat) && !empty($lon)) {
    $weatherURL = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$apiKey&units=metric";
    $forecastURL = "https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$apiKey&units=metric";
} else {
    $weatherURL = "https://api.openweathermap.org/data/2.5/weather?q=$location&appid=$apiKey&units=metric";
    $forecastURL = "https://api.openweathermap.org/data/2.5/forecast?q=$location&appid=$apiKey&units=metric";
}

$currentData = json_decode(@file_get_contents($weatherURL), true);
$forecastData = json_decode(@file_get_contents($forecastURL), true);

$temp = $currentData['main']['temp'] ?? 28;
$humidity = $currentData['main']['humidity'] ?? 60;
$wind = $currentData['wind']['speed'] ?? 5;
$rain = $currentData['rain']['1h'] ?? 0;

// ================= LOAD CROPS =================
$crops = json_decode(file_get_contents(__DIR__."/crops.json"), true);
$results = [];

// ================= MARKET PRICES =================
$marketPrices = [
"Wheat"=>2200,"Rice"=>2100,"Maize"=>1800,"Mustard"=>5000,
"Cotton"=>6500,"Sugarcane"=>350,"Tomato"=>1200,
"Potato"=>1500,"Soybean"=>4000,"Groundnut"=>5500
];

// ================= CROP SCORING + AI YIELD =================
foreach ($crops as $crop) {

    $score=0;$risk=0;

    if(in_array($soil,$crop['soil_types']))$score+=15;else$risk+=10;
    if(in_array($season,$crop['seasons']))$score+=15;else$risk+=10;
    if($temp>=$crop['min_temp']&&$temp<=$crop['max_temp'])$score+=20;else$risk+=15;
    if($rain>=$crop['min_rain']&&$rain<=$crop['max_rain'])$score+=15;else$risk+=15;
    if($water==$crop['water_need'])$score+=10;else$risk+=10;
    if($budget=="High")$score+=10;
    if(!empty($previous_crop)&&strtolower($previous_crop)==strtolower($crop['name']))$risk+=15;

    // Region Bonus
    if(stripos($location,"Punjab")!==false && $crop['name']=="Wheat") $score+=10;
    if(stripos($location,"Maharashtra")!==false && $crop['name']=="Cotton") $score+=10;

    // AI Yield Model
    $idealTemp = ($crop['min_temp'] + $crop['max_temp']) / 2;
    $tempFactor = 1 - abs($temp - $idealTemp) / 40;
    $soilFactor = in_array($soil, $crop['soil_types']) ? 1 : 0.7;
    $seasonFactor = in_array($season, $crop['seasons']) ? 1 : 0.8;

    $yield = max(10, round(25 * $tempFactor * $soilFactor * $seasonFactor));

    $price=$marketPrices[$crop['name']]??2000;
    $revenue=$yield*$land_size*$price;

    $confidence=max(0,min(100,100-$risk+($score/5)));

    $results[]=[
        "name"=>$crop['name'],
        "score"=>$score,
        "confidence"=>round($confidence),
        "fertilizer"=>$crop['fertilizer'],
        "instructions"=>$crop['instructions'],
        "duration"=>$crop['growth_duration'],
        "price"=>$price,
        "revenue"=>$revenue
    ];
}

usort($results,fn($a,$b)=>$b['score']<=>$a['score']);
$bestCrop=$results[0];

// ================= CLIMATE RISK ENGINE =================
$heat=0;$rainRisk=0;$windRisk=0;$humidityRisk=0;

if(isset($forecastData['list'])){
    foreach($forecastData['list'] as $entry){
        if(($entry['main']['temp']??0)>35)$heat++;
        if(($entry['rain']['3h']??0)>40)$rainRisk++;
        if(($entry['wind']['speed']??0)>12)$windRisk++;
        if(($entry['main']['humidity']??0)>85)$humidityRisk++;
    }
}

$alerts=[];$climateRiskScore=0;

if($heat>8){$alerts[]=["type"=>"danger","title"=>"🔥 Heatwave Risk","msg"=>"Use mulching & drip irrigation."];$climateRiskScore+=25;}
if($rainRisk>6){$alerts[]=["type"=>"danger","title"=>"🌧 Flood Risk","msg"=>"Ensure proper drainage."];$climateRiskScore+=25;}
if($windRisk>6){$alerts[]=["type"=>"warning","title"=>"💨 Strong Wind Alert","msg"=>"Support tall crops."];$climateRiskScore+=15;}
if($humidityRisk>8){$alerts[]=["type"=>"danger","title"=>"🌫 Disease Risk","msg"=>"Apply preventive fungicide spray."];$climateRiskScore+=20;}

$climateRiskScore=min(100,$climateRiskScore);

// ================= FORECAST TREND (DAILY AVERAGE) =================
$forecastTemps=[];$forecastRain=[];$forecastLabels=[];

if(isset($forecastData['list'])){
    $daily=[];
    foreach($forecastData['list'] as $entry){
        $date=date("Y-m-d",$entry['dt']);
        if(!isset($daily[$date])){
            $daily[$date]=["temp"=>0,"rain"=>0,"count"=>0];
        }
        $daily[$date]["temp"]+=$entry['main']['temp']??0;
        $daily[$date]["rain"]+=$entry['rain']['3h']??0;
        $daily[$date]["count"]++;
    }
    foreach($daily as $date=>$data){
        $forecastLabels[]=date("D",strtotime($date));
        $forecastTemps[]=round($data["temp"]/$data["count"],1);
        $forecastRain[]=round($data["rain"],1);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Smart Farm Intelligence Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{background:#f4f7f6;font-family:'Segoe UI';}
.card{border-radius:15px;box-shadow:0 6px 20px rgba(0,0,0,0.05);}
.highlight{background:linear-gradient(135deg,#2E7D32,#66BB6A);color:white;}
.price-card{background:linear-gradient(135deg,#1565C0,#42A5F5);color:white;}
</style>
</head>

<body>
<div class="container py-5">

<h2 class="text-success mb-4">Smart Farm Intelligence Report</h2>

<div class="row mb-4">

<div class="col-md-4">
<div class="card highlight p-3">
<h5>Best Crop</h5>
<h3><?php echo $bestCrop['name']; ?></h3>
<p>Suitability Score: <?php echo $bestCrop['score']; ?>/100</p>
<p>Confidence: <?php echo $bestCrop['confidence']; ?>%</p>
</div>
</div>

<div class="col-md-4">
<div class="card price-card p-3">
<h5>Market Intelligence</h5>
<h4>₹ <?php echo number_format($bestCrop['price']); ?>/quintal</h4>
<p>Est Revenue: ₹ <?php echo number_format($bestCrop['revenue']); ?></p>
<p>Growth Duration: <?php echo $bestCrop['duration']; ?> days</p>
</div>
</div>

<div class="col-md-4">
<div class="card p-3">
<h5>Current Weather</h5>
<p>Temp: <?php echo $temp; ?>°C</p>
<p>Humidity: <?php echo $humidity; ?>%</p>
<p>Wind: <?php echo $wind; ?> m/s</p>
<p><strong>Climate Risk Index:</strong> <?php echo $climateRiskScore; ?>%</p>
</div>
</div>

</div>

<div class="card p-4 mb-4">
<h5>Fertilizer & Growing Guide</h5>
<p><?php echo $bestCrop['fertilizer']; ?></p>
<p><?php echo $bestCrop['instructions']; ?></p>
</div>

<div class="card p-4 mb-4">
<h5>⚠ Climate Advisory</h5>
<?php if(!empty($alerts)): ?>
<?php foreach($alerts as $alert): ?>
<div class="alert alert-<?php echo $alert['type']; ?>">
<strong><?php echo $alert['title']; ?></strong><br>
<?php echo $alert['msg']; ?>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="alert alert-success">
✅ No major climate risks expected this week.
</div>
<?php endif; ?>
</div>

<div class="card p-4 mb-4">
<h5>📊 5-Day Weather Trend</h5>
<canvas id="weatherTrendChart"></canvas>
</div>

<div class="card p-4">
<h5>Crop Suitability Comparison</h5>
<canvas id="cropChart"></canvas>
</div>

</div>

<script>
const labels=<?php echo json_encode($forecastLabels); ?>;
const temps=<?php echo json_encode($forecastTemps); ?>;
const rains=<?php echo json_encode($forecastRain); ?>;

if(labels.length>0){
new Chart(document.getElementById('weatherTrendChart'),{
type:'line',
data:{
labels:labels,
datasets:[
{label:'Temperature (°C)',data:temps,borderColor:'#e53935',backgroundColor:'rgba(229,57,53,0.2)',tension:0.4},
{label:'Rainfall (mm)',data:rains,borderColor:'#1e88e5',backgroundColor:'rgba(30,136,229,0.2)',tension:0.4}
]
}
});
}else{
document.getElementById('weatherTrendChart').outerHTML="<p class='text-muted'>Forecast not available.</p>";
}

new Chart(document.getElementById('cropChart'),{
type:'bar',
data:{
labels:<?php echo json_encode(array_column($results,'name')); ?>,
datasets:[{
label:'Suitability Score',
data:<?php echo json_encode(array_column($results,'score')); ?>,
backgroundColor:'#2E7D32'
}]
}
});
</script>

</body>
</html>