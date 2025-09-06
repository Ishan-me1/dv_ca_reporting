<?php
session_start();
// Only allow admin1 and admin2
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin1', 'admin2'])) {
    header("Location: login.php");
    exit();
}
// Handle dashboard selection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["dashboard"])) {
        if ($_POST["dashboard"] == "1") {
            header("Location: admin_dashboard1.php");
            exit();
        } elseif ($_POST["dashboard"] == "2") {
            header("Location: admin_dashboard2.php");
            exit();
        }
    } elseif (isset($_POST["graph"])) {
        if ($_POST["graph"] == "dv") {
            header("Location: dv_visualize_complaints.php");
            exit();
        } elseif ($_POST["graph"] == "ca") {
            header("Location: ca_visualize_complaints.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Select Dashboard or Analysis</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
<style>
:root {
  --primary-color: #00879E;
  --secondary-color: #009bb5;
  --dark-bg: #121212;
  --dark-bg-secondary: #1e1e1e;
  --dark-text: #e0e0e0;
  --dark-text-secondary: #a0a0a0;
  --border-radius: 16px;
  --box-shadow: 0 10px 20px -5px rgba(0,0,0,0.5), 0 4px 6px -2px rgba(0,0,0,0.4);
  --transition: all 0.3s ease;
}

body {
  margin: 0;
  min-height: 100vh;
  background: var(--dark-bg);
  font-family: 'Inter', sans-serif;
  color: var(--dark-text);
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.container {
  max-width: 600px;
  width: 100%;
  background: var(--dark-bg-secondary);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 2.5rem 2rem;
  text-align: center;
  border: 1px solid var(--primary-color);
}

.logo {
  margin-bottom: 1.5rem;
}
.logo img {
  width: 100px;
  height: 100px;
  object-fit: contain;
  filter: drop-shadow(0 0 6px rgba(0,135,158,0.6));
  border-radius: 50%;
}

h2 {
  color: var(--primary-color);
  font-weight: 700;
  font-size: 1.8rem;
  margin-bottom: 1rem;
  text-shadow: 0 0 8px rgba(0,135,158,0.7);
  user-select: none;
}

p {
  color: var(--dark-text-secondary);
  margin-bottom: 2rem;
  user-select: none;
}

form {
  margin-bottom: 2rem;
}

.btn {
  border-radius: 12px;
  padding: 0.75rem 2rem;
  font-size: 1.05rem;
  font-weight: 600;
  color: #fff;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border: none;
  margin: 0.5rem;
  min-width: 200px;
  box-shadow: 0 4px 12px rgba(0,135,158,0.4);
  cursor: pointer;
  transition: var(--transition);
  user-select: none;
}
.btn:hover, .btn:focus {
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
  box-shadow: 0 6px 18px rgba(0,155,181,0.6);
  transform: translateY(-2px);
  outline: none;
}

hr {
  border: none;
  height: 1px;
  background: var(--primary-color);
  margin: 2rem 0;
}
</style>
</head>
<body>
<div class="container" role="main" aria-labelledby="welcomeTitle">
  <div class="logo" aria-hidden="true">
    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" 
         alt="Admin Dashboard Logo" 
         loading="lazy">
  </div>
  <h2 id="welcomeTitle">Welcome, <?= htmlspecialchars($_SESSION['username']); ?></h2>
  <p>Please select which dashboard or analysis you’d like to open:</p>

  <form method="post" aria-label="Dashboard selection form">
    <button type="submit" name="dashboard" value="1" class="btn">
      <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard 1
    </button>
    <button type="submit" name="dashboard" value="2" class="btn">
      <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard 2
    </button>
  </form>

  <hr />

  <form method="post" aria-label="Graphical analysis selection form">
    <button type="submit" name="graph" value="dv" class="btn">
      <i class="fas fa-chart-pie me-2"></i> DV Graphical Analysis
    </button>
    <button type="submit" name="graph" value="ca" class="btn">
      <i class="fas fa-chart-line me-2"></i> CA Graphical Analysis
    </button>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
