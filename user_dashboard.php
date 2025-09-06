<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Dashboard | SafeNet</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <!-- Font Awesome for icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      --primary: #00879E;
      --secondary: #10b981;
      --danger: #ef4444;
      --bg-dark: #0d1117;
      --bg-card: rgba(30, 30, 30, 0.85);
      --text-light: #e0e0e0;
      --text-muted: #94a3b8;
      --radius: 16px;
      --shadow: 0 8px 25px rgba(0, 135, 158, 0.3);
      --transition: all 0.3s ease;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, var(--bg-dark), #1a1f25);
      color: var(--text-light);
      margin: 0;
      padding: 20px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .dashboard-container {
      background: var(--bg-card);
      max-width: 700px;
      width: 100%;
      border-radius: var(--radius);
      padding: 3rem 2.5rem;
      box-shadow: var(--shadow);
      text-align: center;
      border: 1px solid var(--primary);
    }

    .dashboard-container h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 0 8px rgba(0, 135, 158, 0.5);
      user-select: none;
    }

    .intro-text {
      color: var(--text-muted);
      font-size: 1.1rem;
      margin-bottom: 2rem;
      user-select: none;
    }

    .cards-row {
      display: flex;
      gap: 1.3rem;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 2.5rem;
    }

    .card-button {
      flex: 1 1 200px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
      border-radius: 15px;
      padding: 1.25rem 1.5rem;
      font-size: 1.1rem;
      font-weight: 600;
      border: none;
      cursor: pointer;
      box-shadow: 0 6px 14px rgba(0, 135, 158, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
      transition: var(--transition);
      text-decoration: none;
      user-select: none;
    }

    .card-button i {
      font-size: 1.4rem;
    }

    .card-button:hover,
    .card-button:focus {
      background: linear-gradient(135deg, var(--secondary), var(--primary));
      box-shadow: 0 10px 25px rgba(0, 155, 181, 0.5);
      transform: translateY(-3px);
      outline: none;
    }

    .btn-logout {
      background: #78909c;
      color: white;
      padding: 0.75rem 2.5rem;
      font-weight: 600;
      border-radius: 20px;
      border: none;
      box-shadow: 0 3px 8px rgba(120, 144, 156, 0.6);
      transition: background 0.3s ease;
      cursor: pointer;
      margin-top: 1.5rem;
      width: 100%;
      user-select: none;
    }

    .btn-logout:hover,
    .btn-logout:focus {
      background-color: #607d8b;
      outline: none;
    }

    /* Responsive */
    @media (max-width: 480px) {
      .dashboard-container {
        margin: 40px 1rem 30px;
        padding: 2rem 1.5rem;
      }
      .dashboard-container h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <main class="dashboard-container" role="main" aria-labelledby="welcomeTitle">
    <h2 id="welcomeTitle">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p class="intro-text">Select what you’d like to report or do next</p>

    <div class="cards-row" role="group" aria-label="Reporting options">
      <form action="report_dv.php" method="get" class="d-inline">
        <button type="submit" class="card-button" aria-label="Report Domestic Violence">
          <i class="fas fa-home"></i> Domestic Violence
        </button>
      </form>
      <form action="report_ca.php" method="get" class="d-inline">
        <button type="submit" class="card-button" aria-label="Report Child Abuse">
          <i class="fas fa-child"></i> Child Abuse
        </button>
      </form>
    </div>

    <p class="intro-text">Additional options:</p>

    <div class="cards-row" role="group" aria-label="Additional options">
      <form action="legal_aid_contact.php" method="get" class="d-inline">
        <button type="submit" class="card-button" aria-label="Contact Legal Aid">
          <i class="fas fa-gavel"></i> Legal Aid
        </button>
      </form>
      <form action="volunteer_lawyer_contact.php" method="get" class="d-inline">
        <button type="submit" class="card-button" aria-label="Contact Volunteer Lawyer">
          <i class="fas fa-handshake"></i> Volunteer Lawyer
        </button>
      </form>
      <form action="profile.php" method="get" class="d-inline">
        <button type="submit" class="card-button" aria-label="View Profile">
          <i class="fas fa-user-circle"></i> Profile
        </button>
      </form>
    </div>

    <!-- ✅ Logout now handled properly -->
    <form action="logout.php" method="post">
      <button type="submit" class="btn-logout" aria-label="Logout">Logout</button>
    </form>
  </main>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
