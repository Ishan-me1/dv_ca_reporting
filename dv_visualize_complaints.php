<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "Ishan@#$19988276";
$dbname = "reporting_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pie_sql = "SELECT violence_type, COUNT(*) AS count FROM dv_reports GROUP BY violence_type";
$pie_result = $conn->query($pie_sql);
$pie_data = [];
while ($row = $pie_result->fetch_assoc()) {
    $pie_data[$row['violence_type']] = (int)$row['count'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>DV Complaints Visualization</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

  :root {
    --primary-color: #00879E;
    --primary-color-light: #00a9c7;
    --secondary-color: #006f85;
    --bg-dark: #0b1215;
    --bg-container: #16222a;
    --text-light: #e0e7ea;
    --border-radius: 18px;
    --box-shadow: 0 12px 30px rgba(0, 135, 158, 0.6);
    --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
  }

  body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #05141f, #0f2a3d);
    color: var(--text-light);
    padding: 4rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h1 {
    color: var(--primary-color);
    font-weight: 800;
    font-size: 2.8rem;
    margin-bottom: 2.5rem;
    text-shadow: 0 0 14px rgba(0, 135, 158, 0.9);
  }

  .chart-container {
    background: var(--bg-container);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 3rem;
    width: 100%;
    max-width: 650px;
    border: 1.5px solid var(--primary-color-light);
    text-align: center;
  }

  .chart-container h2 {
    color: var(--primary-color-light);
    font-size: 1.7rem;
    margin-bottom: 1.8rem;
    text-shadow: 0 0 8px rgba(0, 169, 199, 0.9);
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

<h1>Domestic Violence Complaints Visualization</h1>

<div class="chart-container">
  <h2>Complaints by Violence Type</h2>
  <canvas id="dvPieChart" width="520" height="520"></canvas>

  <div class="btn-group">
    <button class="btn" onclick="downloadChart()">Download Chart (PNG)</button>
    <button class="btn" onclick="downloadCSV()">Download Data (CSV)</button>
  </div>
</div>

<script>
  const pieData = <?php echo json_encode($pie_data); ?>;
  const labels = Object.keys(pieData);
  const values = Object.values(pieData);

  const pieCtx = document.getElementById('dvPieChart').getContext('2d');

  const gradients = [
    ['#007a8f', '#00d0f0'], ['#004d57', '#009eb5'], 
    ['#00a9c7', '#006674'], ['#006f85', '#00a3c1'],
    ['#009db9', '#00444c'], ['#00b7d3', '#00707c']
  ].map(([start, end]) => {
    const g = pieCtx.createLinearGradient(0, 0, 0, 520);
    g.addColorStop(0, start); g.addColorStop(1, end);
    return g;
  });

  const dvChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels,
      datasets: [{
        data: values,
        backgroundColor: gradients,
        borderColor: '#0b1215',
        borderWidth: 3,
        hoverOffset: 25
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
    a.href = dvChart.toBase64Image();
    a.download = 'DV_Complaints_PieChart.png';
    a.click();
  }

  // Download data as CSV
  function downloadCSV() {
    let csv = "Violence Type,Count\n";
    labels.forEach((label, i) => {
      csv += `${label},${values[i]}\n`;
    });
    const blob = new Blob([csv], { type: "text/csv" });
    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = "DV_Complaints_Data.csv";
    a.click();
  }
</script>

</body>
</html>
