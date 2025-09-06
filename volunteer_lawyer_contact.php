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

// Fetch data from vol_lawyer table
$sql = "SELECT name, email, phone, specialization, courts FROM vol_lawyer";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Volunteer Lawyer Contact</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Font Awesome -->
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
    background: var(--dark-bg);
    color: var(--dark-text);
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 15px;
  }

  .container {
    max-width: 1000px;
    background: var(--dark-bg-secondary);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    padding: 2.5rem 2rem;
    width: 100%;
    border: 1px solid var(--primary-color);
  }

  h2 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    text-shadow: 0 0 8px rgba(0,135,158,0.7);
  }

  .search-bar {
    margin-bottom: 2rem;
  }

  .search-input {
    width: 100%;
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
    box-shadow: 0 0 10px rgba(0, 155, 181, 0.5);
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
    font-size: 1rem;
  }

  thead th {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 12px 12px 0 0;
    text-align: left;
  }

  tbody tr {
    background: #1f1f1f;
    box-shadow: 0 3px 8px rgba(0,155,181,0.15);
    border-radius: 12px;
    transition: background-color 0.3s ease;
  }

  tbody tr:hover {
    background: #2a2a2a;
    box-shadow: 0 8px 16px rgba(0,155,181,0.3);
  }

  tbody td {
    padding: 14px 20px;
    vertical-align: middle;
    color: var(--dark-text);
  }

  .action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .action-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border-radius: 12px;
    padding: 6px 14px;
    font-weight: 600;
    text-decoration: none;
    font-size: 0.9rem;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(0,135,158,0.3);
  }
  .action-btn:hover {
    background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0,135,158,0.4);
    text-decoration: none;
  }

  .btn-back {
    display: block;
    margin: 2rem auto 0;
    background: var(--primary-color);
    color: white;
    font-weight: 700;
    padding: 0.6rem 1.8rem;
    border-radius: 12px;
    border: none;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(0,135,158,0.4);
    transition: var(--transition);
    cursor: pointer;
    width: 200px;
  }

  .btn-back:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
  }

  p.no-data {
    text-align: center;
    font-size: 1.2rem;
    color: var(--primary-color);
    margin-top: 2rem;
    font-weight: 600;
  }
</style>
<script>
function searchLawyers() {
  const input = document.getElementById('lawyerSearch').value.toLowerCase();
  const rows = document.querySelectorAll('tbody tr');
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    row.style.display = text.includes(input) ? '' : 'none';
  });
}
</script>
</head>
<body>
<div class="container">
  <h2>Volunteer Lawyer Contact</h2>
  <div class="search-bar">
    <input type="text" id="lawyerSearch" onkeyup="searchLawyers()" class="search-input" placeholder="🔍 Search by name, specialization, or courts...">
  </div>

  <?php if ($result && $result->num_rows > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Specialization</th>
        <th>Courts</th>
        <th>Contact</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
        <td><?php echo htmlspecialchars($row['courts']); ?></td>
        <td>
          <div class="action-buttons">
            <a class="action-btn" href="tel:<?php echo htmlspecialchars($row['phone']); ?>"><i class="fas fa-phone"></i> Call</a>
            <a class="action-btn" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($row['email']); ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-envelope"></i> Email</a>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p class="no-data">No volunteer lawyers found.</p>
  <?php endif; ?>

  <form action="user_dashboard.php" method="get">
    <button type="submit" class="btn-back">← Back to Dashboard</button>
  </form>
</div>
<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
