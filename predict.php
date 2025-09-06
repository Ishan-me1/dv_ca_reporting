<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>DV Report Prediction</title>
<style>
/* Google Font */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background-color: #121212;
    color: #e0e0e0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 50px 20px;
}

.container {
    background-color: #1e1e1e;
    padding: 30px 40px;
    border-radius: 16px;
    box-shadow: 0 10px 20px -5px rgba(0,0,0,0.6), 0 4px 6px -2px rgba(0,0,0,0.5);
    max-width: 700px;
    width: 100%;
    text-align: center;
}

h1 {
    color: #00d4ff;
    margin-bottom: 20px;
    text-shadow: 0 0 8px rgba(0,212,255,0.5);
}

.result-box {
    margin-top: 25px;
    padding: 20px;
    border-radius: 12px;
    background: #272727;
    border-left: 6px solid #00d4ff;
    font-size: 1.2rem;
    word-wrap: break-word;
}

.result-box.error {
    border-left-color: #ff4b5c;
    color: #ff4b5c;
}

a.back-btn {
    display: inline-block;
    margin-top: 20px;
    text-decoration: none;
    padding: 8px 16px;
    background: linear-gradient(135deg, #00d4ff, #00879e);
    color: white;
    border-radius: 10px;
    font-weight: 600;
    transition: 0.3s ease;
}

a.back-btn:hover {
    background: linear-gradient(135deg, #00879e, #00d4ff);
}

</style>
</head>
<body>
<div class="container">
<h1>Domestic Violence Report Prediction</h1>

<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // DB connection
    $conn = new mysqli("localhost", "root", "Ishan@#$19988276", "reporting_system");
    if ($conn->connect_error) die("<div class='result-box error'>Connection failed: " . $conn->connect_error . "</div>");

    // Fetch complaint text
    $stmt = $conn->prepare("SELECT description FROM dv_reports WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($complaint);
    $stmt->fetch();
    $stmt->close();

    if (!empty($complaint)) {
        // Send complaint to ML model API
        $url = "http://127.0.0.1:5000/predict";
        $data = json_encode(["complain" => $complaint]);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['risk_level'])) {
            echo "<div class='result-box'>Predicted Risk Level: " . htmlspecialchars($result['risk_level']) . "</div>";
        } else {
            echo "<div class='result-box error'>Error: Could not get prediction.</div>";
        }
    } else {
        echo "<div class='result-box error'>No complaint text found for this report.</div>";
    }

    $conn->close();
} else {
    echo "<div class='result-box error'>No report ID received.</div>";
}
?>

<a href="admin_dashboard1.php" class="back-btn">Back to Dashboard</a>
</div>
</body>
</html>
