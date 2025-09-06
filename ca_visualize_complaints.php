<?php
session_start();

// Authentication check (only admin roles can access)
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin1', 'admin2', 'admin3'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "Ishan@#$19988276";
$dbname = "reporting_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts by abuse_type (for pie chart)
$pie_sql = "SELECT abuse_type, COUNT(*) AS count FROM ca_reports GROUP BY abuse_type";
$pie_result = $conn->query($pie_sql);
$pie_data = [];
while ($row = $pie_result->fetch_assoc()) {
    $pie_data[$row['abuse_type']] = (int)$row['count'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Child Abuse Complaints Visualization</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

  :root {
    --primary-color: #00879E;
    --primary-color-light: #00a9c7;
    --secondary-color: #006f85;
    --bg-dark: #121212;
    --bg-container: #1e1e1e;
    --text-light: #e0e0e0;
    --border-radius: 16px;
    --box-shadow: 0 10px 25px rgba(0, 135, 158, 0.5);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: var(--bg-dark);
    color: var(--text-light);
    padding: 3rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h1 {
    text-align: center;
    color: var(--primary-color);
    font-weight: 700;
    font-size: 2.6rem;
    margin-bottom: 2.5rem;
    text-shadow: 0 0 10px rgba(0, 135, 158, 0.7);
  }

  .chart-container {
    background: var(--bg-container);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 3rem;
    width: 100%;
    max-width: 650px;
    border: 2px solid var(--primary-color-light);
    text-align: center;
  }

  .chart-container:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0, 169, 199, 0.7);
  }

  .btn-group {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
  }

  .btn {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
    border: none;
    padding: 0.8rem 1.4rem;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(0, 135, 158, 0.6);
    transition: var(--transition);
  }

  .btn:hover {
    background: var(--primary-color-light);
    transform: translateY(-3px);
  }
</style>
</head>
<body>

<h1>Child Abuse Complaints Visualization</h1>

<div class="chart-container">
  <h2>Complaints by Abuse Type</h2>
  <canvas id="caPieChart" width="520" height="520"></canvas>

  <div class="btn-group">
    <button class="btn" onclick="downloadChart()">Download Chart (PNG)</button>
    <button class="btn" onclick="downloadCSV()">Download Data (CSV)</button>
  </div>
</div>

<script>
  const pieData = <?php echo json_encode($pie_data); ?>;
  const labels = Object.keys(pieData);
  const values = Object.values(pieData);

  const ctx = document.getElementById('caPieChart').getContext('2d');

  // Gradient colors
  const gradients = [
    ['#007a8f', '#00d0f0'], ['#004d57', '#009eb5'], 
    ['#00a9c7', '#006674'], ['#006f85', '#00a3c1'],
    ['#009db9', '#00444c'], ['#00b7d3', '#00707c']
  ].map(([start, end]) => {
    const g = ctx.createLinearGradient(0, 0, 0, 520);
    g.addColorStop(0, start); g.addColorStop(1, end);
    return g;
  });

  const caChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels,
      datasets: [{
        data: values,
        backgroundColor: gradients,
        borderColor: '#121212',
        borderWidth: 3,
        hoverOffset: 25,
      }]
    },
    options: {
      plugins: {
        legend: {
          position: 'bottom',
          labels: { color: 'white', font: { size: 14, weight: '700' } }
        },
        tooltip: {
          callbacks: {
            label: ctx => {
              let total = ctx.dataset.data.reduce((a, b) => a + b, 0);
              let value = ctx.parsed;
              let pct = ((value / total) * 100).toFixed(1);
              return `${ctx.label}: ${value} (${pct}%)`;
            }
          }
        },
        datalabels: {
          color: '#fff',
          font: { weight: '700', size: 14 },
          formatter: (value, ctx) => {
            let total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            let pct = ((value / total) * 100).toFixed(1);
            return pct + "%";
          }
        }
      }
    },
    plugins: [ChartDataLabels]
  });

  // Download chart as PNG
  function downloadChart() {
    const a = document.createElement('a');
    a.href = caChart.toBase64Image();
    a.download = 'ChildAbuse_Complaints_PieChart.png';
    a.click();
  }

  // Download data as CSV
  function downloadCSV() {
    let csv = "Abuse Type,Count\n";
    labels.forEach((label, i) => {
      csv += `${label},${values[i]}\n`;
    });
    const blob = new Blob([csv], { type: "text/csv" });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = "ChildAbuse_Complaints_Data.csv";
    a.click();
  }
</script>

</body>
</html>
