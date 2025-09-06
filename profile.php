<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];

$host = "localhost";
$user = "root";
$pass = "Ishan@#$19988276";
$dbname = "reporting_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch DV reports
$dv_stmt = $conn->prepare("SELECT id, reporter_name, contact_email, victim_name, victim_age, victim_gender, relationship, violence_type, incident_date, location, description, suspect_name, victim_danger FROM dv_reports WHERE reporter_name = ? ORDER BY incident_date DESC");
$dv_stmt->bind_param("s", $username);
$dv_stmt->execute();
$dv_result = $dv_stmt->get_result();

// Fetch CA reports
$ca_stmt = $conn->prepare("SELECT id, reporter_name, contact_email, victim_name, victim_age, victim_gender, relationship, abuse_type, incident_date, location, description, suspect_name, evidence_file, created_at FROM ca_reports WHERE reporter_name = ? ORDER BY incident_date DESC");
$ca_stmt->bind_param("s", $username);
$ca_stmt->execute();
$ca_result = $ca_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Profile - Complaints</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
  background: var(--dark-bg);
  color: var(--dark-text);
  font-family: 'Inter', sans-serif;
  padding: 40px 20px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
}

.container {
  background: var(--dark-bg-secondary);
  max-width: 1200px;
  width: 100%;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  padding: 2.5rem 2rem;
  border: 1px solid var(--primary-color);
}

h2, h4 {
  color: var(--primary-color);
  font-weight: 700;
  text-align: center;
  margin-bottom: 1.5rem;
  text-shadow: 0 0 8px rgba(0,135,158,0.7);
}

h4 {
  margin-top: 2rem;
  font-size: 1.3rem;
}

.search-bar {
  margin-bottom: 2rem;
  text-align: center;
}

.search-input {
  width: 100%;
  max-width: 400px;
  padding: 10px 16px;
  border-radius: 12px;
  border: 2px solid var(--primary-color);
  background: #1a1a1a;
  color: var(--dark-text);
  transition: var(--transition);
}
.search-input:focus {
  outline: none;
  border-color: var(--secondary-color);
  box-shadow: 0 0 10px rgba(0,155,181,0.5);
}

table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 12px;
  font-size: 0.95rem;
}

thead th {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  font-weight: 600;
  padding: 12px 16px;
  border-radius: 12px 12px 0 0;
}

tbody tr {
  background: #1f1f1f;
  box-shadow: 0 3px 8px rgba(0,155,181,0.15);
  border-radius: 12px;
  transition: var(--transition);
}

tbody tr:hover {
  background: #2a2a2a;
  box-shadow: 0 8px 16px rgba(0,155,181,0.3);
}

tbody td {
  padding: 12px 16px;
  color: var(--dark-text);
}

a {
  color: var(--primary-color);
  font-weight: 600;
}
a:hover {
  text-decoration: underline;
}

p.no-data {
  text-align: center;
  color: var(--primary-color);
  font-weight: 600;
  margin-top: 1rem;
}

.btn-back {
  display: block;
  margin: 2.5rem auto 0;
  background: var(--primary-color);
  color: white;
  font-weight: 700;
  padding: 0.6rem 1.8rem;
  border-radius: 12px;
  border: none;
  width: 200px;
  box-shadow: 0 4px 12px rgba(0,135,158,0.4);
  cursor: pointer;
  transition: var(--transition);
}
.btn-back:hover {
  background: var(--secondary-color);
  transform: translateY(-2px);
}
</style>
<script>
function searchReports() {
  const query = document.getElementById('searchInput').value.toLowerCase();
  const tables = document.querySelectorAll('tbody');
  tables.forEach(tbody => {
    Array.from(tbody.rows).forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(query) ? '' : 'none';
    });
  });
}
</script>
</head>
<body>
<main class="container" role="main">
  <h2>User Profile - Your Complaints</h2>
  <div class="search-bar">
    <input type="text" id="searchInput" onkeyup="searchReports()" class="search-input" placeholder="🔍 Search by victim, location, suspect, etc.">
  </div>

  <section>
    <h4>Domestic Violence Complaints</h4>
    <?php if ($dv_result && $dv_result->num_rows > 0): ?>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Contact Email</th>
            <th>Victim</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Relationship</th>
            <th>Violence Type</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
            <th>Suspect</th>
            <th>Victim Danger</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $dv_result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['contact_email']) ?></td>
            <td><?= htmlspecialchars($row['victim_name']) ?></td>
            <td><?= htmlspecialchars($row['victim_age']) ?></td>
            <td><?= htmlspecialchars($row['victim_gender']) ?></td>
            <td><?= htmlspecialchars($row['relationship']) ?></td>
            <td><?= htmlspecialchars($row['violence_type']) ?></td>
            <td><?= htmlspecialchars($row['incident_date']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['suspect_name']) ?></td>
            <td><?= htmlspecialchars($row['victim_danger']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <p class="no-data">No domestic violence complaints found for you.</p>
    <?php endif; ?>
  </section>

  <section>
    <h4>Child Abuse Complaints</h4>
    <?php if ($ca_result && $ca_result->num_rows > 0): ?>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Contact Email</th>
            <th>Victim</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Relationship</th>
            <th>Abuse Type</th>
            <th>Date</th>
            <th>Location</th>
            <th>Description</th>
            <th>Suspect</th>
            <th>Evidence</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $ca_result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['contact_email']) ?></td>
            <td><?= htmlspecialchars($row['victim_name']) ?></td>
            <td><?= htmlspecialchars($row['victim_age']) ?></td>
            <td><?= htmlspecialchars($row['victim_gender']) ?></td>
            <td><?= htmlspecialchars($row['relationship']) ?></td>
            <td><?= htmlspecialchars($row['abuse_type']) ?></td>
            <td><?= htmlspecialchars($row['incident_date']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['suspect_name']) ?></td>
            <td>
              <?php if ($row['evidence_file']): ?>
                <a href="<?= htmlspecialchars($row['evidence_file']) ?>" target="_blank">View</a>
              <?php else: ?>No File<?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
      <p class="no-data">No child abuse complaints found for you.</p>
    <?php endif; ?>
  </section>

  <button onclick="location.href='user_dashboard.php'" class="btn-back">← Back to Dashboard</button>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
