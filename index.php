<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Agriculture Advisory</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(145deg, #e6f4e8 0%, #d4e9d6 100%);
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated leaf pattern overlay */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath fill='%2366bb6a' fill-opacity='0.06' d='M40 0 L80 40 L40 80 L0 40 Z'/%3E%3C/svg%3E");
            background-size: 100px 100px;
            pointer-events: none;
            z-index: 0;
            animation: floatPattern 30s linear infinite;
        }

        @keyframes floatPattern {
            0% { background-position: 0 0; }
            100% { background-position: 100px 100px; }
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .header-title {
            font-weight: 800;
            color: #1b5e20;
            letter-spacing: -0.5px;
            text-shadow: 2px 2px 10px rgba(0,80,20,0.1);
            animation: fadeInDown 0.8s ease-out;
        }

        .header-title i {
            color: #2E7D32;
            transform: rotate(-5deg);
            transition: transform 0.3s ease;
        }

        .header-title:hover i {
            transform: rotate(0deg) scale(1.1);
        }

        .main-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(200, 230, 201, 0.5);
            border-radius: 32px;
            box-shadow: 0 30px 50px rgba(30, 70, 32, 0.1);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            animation: slideUp 0.6s ease-out;
        }

        .main-card:hover {
            box-shadow: 0 40px 60px rgba(46, 125, 50, 0.15);
            transform: translateY(-6px);
        }

        .section-title {
            font-weight: 700;
            color: #1b5e20;
            margin: 30px 0 20px 0;
            padding-bottom: 8px;
            border-bottom: 2px dashed #b8dfba;
            display: flex;
            align-items: center;
            letter-spacing: -0.3px;
        }

        .section-title i {
            background: #e8f5e9;
            padding: 10px;
            border-radius: 50%;
            margin-right: 12px;
            font-size: 1.2rem;
            color: #2E7D32;
            transition: all 0.3s;
        }

        .section-title:hover i {
            background: #2E7D32;
            color: white;
            transform: rotate(360deg);
        }

        .form-label {
            font-weight: 600;
            color: #1f5a24;
            margin-bottom: 8px;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        .form-control, .form-select {
            border-radius: 18px;
            padding: 12px 18px;
            border: 2px solid #e0f0e1;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            transition: all 0.3s;
            font-size: 1rem;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.02);
        }

        .form-control:focus, .form-select:focus {
            border-color: #66BB6A;
            box-shadow: 0 0 0 4px rgba(102,187,106,0.2);
            background: white;
            outline: none;
        }

        .form-control:hover, .form-select:hover {
            border-color: #9fdfa4;
        }

        .btn-location {
            border-radius: 40px;
            padding: 12px 25px;
            font-weight: 600;
            background: rgba(46,125,50,0.05);
            border: 2px solid #c8e6c9;
            color: #1b5e20;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            backdrop-filter: blur(4px);
        }

        .btn-location:hover {
            background: #2E7D32;
            border-color: #2E7D32;
            color: white;
            transform: scale(1.03);
            box-shadow: 0 12px 20px rgba(46,125,50,0.2);
        }

        .btn-location i {
            transition: transform 0.3s;
        }

        .btn-location:hover i {
            transform: rotate(20deg);
        }

        .btn-analyze {
            background: linear-gradient(145deg, #1f5e24, #2E7D32);
            color: white;
            font-weight: 700;
            padding: 16px;
            border-radius: 40px;
            border: none;
            box-shadow: 0 15px 25px -8px rgba(46,125,50,0.4);
            transition: all 0.3s ease;
            letter-spacing: 1px;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .btn-analyze:hover {
            transform: translateY(-3px);
            box-shadow: 0 25px 30px -8px #2E7D32;
            background: linear-gradient(145deg, #2E7D32, #3d9c42);
        }

        .btn-analyze i {
            transition: all 0.3s;
        }

        .btn-analyze:hover i {
            transform: translateX(5px);
        }

        /* Summary box redesign */
        .summary-box {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(200, 230, 201, 0.7);
            border-radius: 36px;
            padding: 28px 22px;
            box-shadow: 0 25px 40px -10px rgba(0,60,20,0.2);
            transition: 0.3s;
            animation: slideRight 0.6s ease-out;
        }

        .summary-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 50px -10px #2E7D32;
        }

        .summary-box h6 {
            color: #1b5e20;
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.5px;
            border-left: 5px solid #66BB6A;
            padding-left: 15px;
        }

        .summary-box hr {
            opacity: 0.3;
            background: linear-gradient(90deg, transparent, #2E7D32, transparent);
            height: 2px;
        }

        .summary-item {
            background: rgba(232, 245, 233, 0.6);
            border-radius: 30px;
            padding: 14px 18px;
            margin-bottom: 14px;
            font-weight: 500;
            color: #154a19;
            border: 1px solid rgba(102,187,106,0.3);
            backdrop-filter: blur(2px);
            transition: 0.2s;
            display: flex;
            align-items: center;
        }

        .summary-item strong {
            min-width: 90px;
            color: #0f4713;
            font-weight: 700;
        }

        .summary-item span {
            color: #1e5e23;
            font-weight: 600;
            background: rgba(255,255,255,0.5);
            padding: 5px 15px;
            border-radius: 40px;
            margin-left: 10px;
            flex: 1;
        }

        .summary-item:hover {
            background: #daf1dc;
            border-color: #66BB6A;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Live badge */
        .live-badge {
            background: #66BB6A;
            color: white;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 40px;
            margin-left: 10px;
            vertical-align: middle;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.8; }
            50% { opacity: 1; }
            100% { opacity: 0.8; }
        }

        /* input icon decoration */
        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #66BB6A;
            font-size: 1.1rem;
        }

        .input-icon-wrapper input, .input-icon-wrapper select {
            padding-left: 45px;
        }

        /* additional micro-interactions */
        select.form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%232E7D32' class='bi bi-chevron-down' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.5rem center;
            background-size: 1.2rem;
        }

        /* Responsive touch */
        @media (max-width: 768px) {
            .main-card {
                padding: 1.2rem !important;
            }
            .summary-item strong {
                min-width: 70px;
            }
        }

        /* additional fancy glow */
        .glow {
            transition: all 0.3s;
        }
        .glow:hover {
            filter: drop-shadow(0 0 8px #a5d6a7);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="text-center mb-5">
        <h2 class="header-title display-5">
            <i class="fa-solid fa-leaf"></i> Smart Agriculture Advisory
        </h2>
        <p class="text-muted fs-5" style="font-weight: 300; letter-spacing: 1px;">Intelligent Farm Decision Support System</p>
    </div>

    <div class="row g-4">

        <div class="col-lg-8">
            <div class="main-card p-4 p-xl-5">

                <form action="analyze.php" method="POST">

                    <!-- LOCATION & SEASON -->
                    <h5 class="section-title">
                        <i class="fa-solid fa-location-dot"></i> Location & Season
                    </h5>

                    <div class="mb-3 input-icon-wrapper">
                        <i class="fa-solid fa-map-pin"></i>
                        <input type="text" name="location" class="form-control" placeholder="e.g., Nagpur, Punjab" required>
                    </div>

                    <!-- LIVE LOCATION BUTTON -->
                    <button type="button" class="btn btn-location mb-4" onclick="getLocation()">
                        <i class="fa-solid fa-crosshairs"></i> Use My Current Location
                    </button>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="mb-4 input-icon-wrapper">
                        <i class="fa-solid fa-sun-plant-wilt"></i>
                        <select name="season" class="form-select" required>
                            <option value="">Select Season</option>
                            <option value="Kharif">Kharif</option>
                            <option value="Rabi">Rabi</option>
                            <option value="Zaid">Zaid</option>
                        </select>
                    </div>

                    <!-- SOIL -->
                    <h5 class="section-title">
                        <i class="fa-solid fa-seedling"></i> Soil Details
                    </h5>

                    <div class="mb-3 input-icon-wrapper">
                        <i class="fa-solid fa-mound"></i>
                        <select name="soil" class="form-select" required>
                            <option value="">Select Soil Type</option>
                            <option value="Sandy">Sandy</option>
                            <option value="Clay">Clay</option>
                            <option value="Loamy">Loamy</option>
                            <option value="Black">Black Soil</option>
                        </select>
                    </div>

                    <div class="mb-4 input-icon-wrapper">
                        <i class="fa-solid fa-flask"></i>
                        <select name="soil_condition" class="form-select">
                            <option value="Normal">Normal</option>
                            <option value="Low Nitrogen">Low Nitrogen</option>
                            <option value="Low Phosphorus">Low Phosphorus</option>
                            <option value="Low Potassium">Low Potassium</option>
                        </select>
                    </div>

                    <!-- WATER & LAND -->
                    <h5 class="section-title">
                        <i class="fa-solid fa-droplet"></i> Water & Resources
                    </h5>

                    <div class="mb-3 input-icon-wrapper">
                        <i class="fa-solid fa-water"></i>
                        <select name="water" class="form-select" required>
                            <option value="">Select Water Availability</option>
                            <option value="Low">Low</option>
                            <option value="Moderate">Moderate</option>
                            <option value="High">High</option>
                        </select>
                    </div>

                    <div class="mb-4 input-icon-wrapper">
                        <i class="fa-solid fa-chart-simple"></i>
                        <input type="number" name="land_size" class="form-control" placeholder="Area in acres" required>
                    </div>

                    <!-- ADDITIONAL PARAMETERS -->
                    <h5 class="section-title">
                        <i class="fa-solid fa-gears"></i> Additional Parameters
                    </h5>

                    <div class="mb-3 input-icon-wrapper">
                        <i class="fa-solid fa-history"></i>
                        <input type="text" name="previous_crop" class="form-control" placeholder="e.g., Wheat, Rice">
                    </div>

                    <div class="mb-4 input-icon-wrapper">
                        <i class="fa-solid fa-coins"></i>
                        <select name="budget" class="form-select">
                            <option value="Low">Low Investment</option>
                            <option value="Medium">Medium Investment</option>
                            <option value="High">High Investment</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-analyze">
                            <i class="fa-solid fa-chart-line"></i> Analyze My Farm
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- SUMMARY PANEL with live updates -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="summary-box">
                <h6>Farm Overview <span class="live-badge"><i class="fa-regular fa-eye"></i> live</span></h6>
                <hr>
                <div class="summary-item"><strong>Location:</strong> <span id="summaryLocation">-</span></div>
                <div class="summary-item"><strong>Soil:</strong> <span id="summarySoil">-</span></div>
                <div class="summary-item"><strong>Water:</strong> <span id="summaryWater">-</span></div>
                <div class="summary-item"><strong>Season:</strong> <span id="summarySeason">-</span></div>
                <div class="summary-item"><strong>Land size:</strong> <span id="summaryLand">-</span></div>
                <div class="summary-item"><strong>Prev. crop:</strong> <span id="summaryPrevCrop">-</span></div>
                <div class="text-muted mt-3 small text-center">↻ updates as you type</div>
            </div>
        </div>

    </div>
</div>

<script>
// Original getLocation function (unchanged)
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async function(position) {

            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lon;

            // Reverse geocoding using OpenStreetMap (Free)
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`
            );

            const data = await response.json();

            if (data.address.city) {
                document.querySelector('[name="location"]').value = data.address.city;
            } else if (data.address.town) {
                document.querySelector('[name="location"]').value = data.address.town;
            } else {
                document.querySelector('[name="location"]').value = data.address.state;
            }

            alert("Location detected successfully!");
            // trigger summary update
            updateSummary();

        }, function() {
            alert("Location access denied.");
        });
    } else {
        alert("Geolocation not supported.");
    }
}

// New: Live summary update function (does not interfere with original code)
function updateSummary() {
    const location = document.querySelector('[name="location"]').value || '-';
    const soil = document.querySelector('[name="soil"]').value || '-';
    const water = document.querySelector('[name="water"]').value || '-';
    const season = document.querySelector('[name="season"]').value || '-';
    const land = document.querySelector('[name="land_size"]').value ? document.querySelector('[name="land_size"]').value + ' ac' : '-';
    const prevCrop = document.querySelector('[name="previous_crop"]').value || '-';

    document.getElementById('summaryLocation').innerText = location;
    document.getElementById('summarySoil').innerText = soil;
    document.getElementById('summaryWater').innerText = water;
    document.getElementById('summarySeason').innerText = season;
    document.getElementById('summaryLand').innerText = land;
    document.getElementById('summaryPrevCrop').innerText = prevCrop;
}

// Attach event listeners to all inputs for live update
document.addEventListener('DOMContentLoaded', function() {
    const formElements = document.querySelectorAll('input, select');
    formElements.forEach(el => {
        el.addEventListener('input', updateSummary);
        el.addEventListener('change', updateSummary);
    });
    // initial call
    updateSummary();
});

</script>

<!-- Additional small tweak: smooth scroll, etc. -->
</body>
</html>