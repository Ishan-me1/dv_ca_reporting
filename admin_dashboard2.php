<?php
session_start();
// Allow admin1, admin2, and admin3
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

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_btn'], $_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $stmt = $conn->prepare("DELETE FROM ca_reports WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    if ($stmt->execute()) {
        echo "<script>alert('Report deleted successfully.'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to delete report.');</script>";
    }
    $stmt->close();
}

$sql = "SELECT id, reporter_name, contact_email, victim_name, victim_age, victim_gender, relationship, 
        incident_date, location, description, suspect_name, abuse_type, evidence_file 
        FROM ca_reports ORDER BY incident_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard 2 - Child Abuse Reports</title>
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
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}
header h1 {
  color: var(--primary-color);
  font-size: 1.8rem;
  margin: 0;
  text-shadow: 0 0 8px rgba(0,135,158,0.7);
}
header p {
  color: var(--dark-text-secondary);
  margin: 0.3rem 0 0;
  font-size: 0.95rem;
}
header .btn {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  text-decoration: none;
  margin-left: 0.5rem;
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
  border-spacing: 0 8px;
}
thead th {
  background: var(--primary-color);
  color: white;
  padding: 10px 14px;
  font-weight: 600;
  text-align: left;
  border-radius: 8px;
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
  padding: 10px 14px;
  color: var(--dark-text);
  vertical-align: middle;
}
tbody a {
  color: var(--primary-color);
  text-decoration: none;
  font-weight: 600;
}
tbody a:hover {
  text-decoration: underline;
}
.delete-btn {
  background-color: red;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 5px;
  cursor: pointer;
}
@media (max-width: 768px) {
  header {
    flex-direction: column;
    align-items: flex-start;
  }
  header .btn { margin: 0.3rem 0 0; }
}
</style>
</head>
<body>
<div class="dashboard-container">
<header>
  <div>
    <h1>Admin Dashboard 2 - Child Abuse Reports</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)</p>
  </div>
  <div>
    <a href="ca_visualize_complaints.php" class="btn">CA Graphical Analysis</a>
    <a href="logout.php" class="btn">Logout</a>
  </div>
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
        <th>Incident Date</th>
        <th>Location</th>
        <th>Description</th>
        <th>Suspect</th>
        <th>Abuse Type</th>
        <th>Evidence</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $email = htmlspecialchars($row['contact_email']);
        echo "<tr>";
        echo "<td>".htmlspecialchars($row['id'])."</td>";
        echo "<td>".htmlspecialchars($row['reporter_name'])."</td>";
        echo "<td>
                <a href='https://mail.google.com/mail/?view=cm&to=$email' target='_blank' title='Send Email via Gmail'>
                  ✉️ $email
                </a>
              </td>";
        echo "<td>".htmlspecialchars($row['victim_name'])."</td>";
        echo "<td>".htmlspecialchars($row['victim_age'])."</td>";
        echo "<td>".htmlspecialchars($row['victim_gender'])."</td>";
        echo "<td>".htmlspecialchars($row['relationship'])."</td>";
        echo "<td>".htmlspecialchars($row['incident_date'])."</td>";
        echo "<td>".htmlspecialchars($row['location'])."</td>";
        echo "<td>".htmlspecialchars($row['description'])."</td>";
        echo "<td>".htmlspecialchars($row['suspect_name'])."</td>";
        echo "<td>".htmlspecialchars($row['abuse_type'])."</td>";

        // Evidence File Handling
        if (!empty($row['evidence_file'])) {
            $filePath = htmlspecialchars($row['evidence_file']);
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','gif'])) {
                echo "<td><a href='$filePath' target='_blank'>Image</a></td>";
            } elseif (in_array($ext, ['mp4','webm','ogg'])) {
                echo "<td><a href='$filePath' target='_blank'>Video</a></td>";
            } else {
                echo "<td><a href='$filePath' target='_blank'>View</a></td>";
            }
        } else {
            echo "<td>No</td>";
        }

        // Delete Button
        echo "<td>
                <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this report?\");'>
                  <input type='hidden' name='delete_id' value='" . htmlspecialchars($row['id']) . "'>
                  <button type='submit' name='delete_btn' class='delete-btn'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='14'>No reports found</td></tr>";
}
?>
    </tbody>
  </table>
</div>
</div>
</body>
</html>
<?php $conn->close(); ?>
