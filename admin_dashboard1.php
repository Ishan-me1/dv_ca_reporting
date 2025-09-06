<?php
session_start();
// Allow only admin1 and admin2
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin1', 'admin2'])) {
    header("Location: login.php");
    exit();
}

// DB connection
$host = "localhost";
$user = "root";
$pass = "Ishan@#$19988276";
$dbname = "reporting_system";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_btn'], $_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM dv_reports WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    if ($stmt->execute()) {
        echo "<script>alert('Report deleted successfully.'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to delete report.');</script>";
    }
    $stmt->close();
}

// Fetch reports
$sql = "SELECT id, reporter_name, contact_email, victim_name, victim_age, victim_gender,
        relationship, violence_type, incident_date, location, description, suspect_name,
        victim_danger, reported_before, evidence_file
        FROM dv_reports ORDER BY incident_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard 1 - DV Reports</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
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
}
body {
  margin: 0;
  font-family: 'Inter', sans-serif;
  background: var(--dark-bg);
  color: var(--dark-text);
  padding: 20px;
}
.dashboard-container {
  max-width: 1200px;
  margin: auto;
  background: var(--dark-bg-secondary);
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 2rem;
  border: 1px solid var(--primary-color);
}
header {
  text-align: center;
  margin-bottom: 2rem;
}
header h1 {
  color: var(--primary-color);
  font-size: 1.8rem;
  margin-bottom: 0.5rem;
  text-shadow: 0 0 8px rgba(0,135,158,0.7);
}
header p {
  color: var(--dark-text-secondary);
  margin-bottom: 1rem;
}
header .btn {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  padding: 0.5rem 1.5rem;
  border: none;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  box-shadow: 0 4px 10px rgba(0,135,158,0.4);
  transition: 0.3s ease;
}
header .btn:hover {
  background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
  box-shadow: 0 6px 16px rgba(0,155,181,0.6);
}
.table-container {
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
}
thead th {
  background: var(--primary-color);
  color: white;
  text-align: left;
  padding: 12px 15px;
  border-radius: 8px;
  font-weight: 600;
}
tbody tr {
  background: #1e1e1e;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,135,158,0.2);
  transition: background 0.3s;
}
tbody tr:hover {
  background: rgba(0,135,158,0.2);
}
tbody td {
  padding: 10px 15px;
  color: var(--dark-text);
  vertical-align: middle;
}
tbody img, tbody video {
  border-radius: 6px;
}
button.delete-btn {
  background-color: red;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}
button.predict-btn {
  background-color: #00aa00;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 5px;
}
@media (max-width: 768px) {
  header h1 { font-size: 1.4rem; }
  thead th, tbody td { font-size: 0.85rem; }
}
</style>
</head>
<body>
<div class="dashboard-container">
<header>
  <h1>Admin Dashboard 1 - Domestic Violence Reports</h1>
  <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)</p>
  <a href="logout.php" class="btn">Logout</a>
</header>
<div class="table-container">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Reporter</th>
        <th>Email</th>
        <th>Victim Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Relationship</th>
        <th>Type</th>
        <th>Incident Date</th>
        <th>Location</th>
        <th>Description</th>
        <th>Suspect</th>
        <th>Danger?</th>
        <th>Reported Before?</th>
        <th>Evidence</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $email = htmlspecialchars($row['contact_email']);
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>" . htmlspecialchars($row['reporter_name']) . "</td>";
        echo "<td>
                <a href='https://mail.google.com/mail/?view=cm&to=$email' target='_blank' title='Send Email via Gmail' style='color: #00aaff; text-decoration: none;'>
                  ✉️ $email
                </a>
              </td>";
        echo "<td>" . htmlspecialchars($row['victim_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['victim_age']) . "</td>";
        echo "<td>" . htmlspecialchars($row['victim_gender']) . "</td>";
        echo "<td>" . htmlspecialchars($row['relationship']) . "</td>";
        echo "<td>" . htmlspecialchars($row['violence_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['incident_date']) . "</td>";
        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['suspect_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['victim_danger']) . "</td>";
        echo "<td>" . htmlspecialchars($row['reported_before']) . "</td>";

        // Evidence
        if (!empty($row['evidence_file'])) {
            $filePath = htmlspecialchars($row['evidence_file']);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif'])) {
                echo "<td><a href='$filePath' target='_blank'><img src='$filePath' width='80' height='60' alt='Evidence'></a></td>";
            } elseif (in_array($ext, ['mp4','webm','ogg'])) {
                echo "<td><video width='120' height='80' controls><source src='$filePath' type='video/$ext'></video></td>";
            } else {
                echo "<td><a href='$filePath' target='_blank'>View File</a></td>";
            }
        } else {
            echo "<td>No</td>";
        }

        // Action buttons (Delete & Predict)
        echo "<td>
                <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this report?\");' style='display:inline;'>
                  <input type='hidden' name='delete_id' value='$id'>
                  <button type='submit' name='delete_btn' class='delete-btn'>Delete</button>
                </form>
                <form method='get' action='predict.php' style='margin-top:5px;'>
                  <input type='hidden' name='id' value='$id'>
                  <button type='submit' class='predict-btn'>Predict</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='16'>No reports found</td></tr>";
}
?>
    </tbody>
  </table>
</div>
</div>
</body>
</html>
<?php $conn->close(); ?>
