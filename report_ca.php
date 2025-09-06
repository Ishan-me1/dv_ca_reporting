<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$host = "localhost";
$user = "root";  // your DB username
$pass = "Ishan@#$19988276";  // your DB password
$dbname = "reporting_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$showOptions = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reporter_name = trim($_POST["reporter_name"]);
    $contact_email = trim($_POST["contact_email"]);
    $victim_name = trim($_POST["victim_name"]);
    $victim_age = !empty($_POST["victim_age"]) ? intval($_POST["victim_age"]) : null;
    $victim_gender = $_POST["victim_gender"];
    $relationship = trim($_POST["relationship"]);
    $incident_date = $_POST["incident_date"];
    $location = trim($_POST["location"]);
    $description = trim($_POST["description"]);
    $suspect_name = trim($_POST["suspect_name"]);
    $abuse_type = $_POST["abuse_type"];

    // Handle file upload
    $evidence_file = null;
    if (isset($_FILES["evidence"]) && $_FILES["evidence"]["error"] == UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = basename($_FILES["evidence"]["name"]);
        $target_file = $upload_dir . time() . "_" . $filename;
        if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file)) {
            $evidence_file = $target_file;
        }
    }

    $sql = "INSERT INTO ca_reports 
        (reporter_name, contact_email, victim_name, victim_age, victim_gender, relationship, incident_date, location, description, suspect_name, abuse_type, evidence_file)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssss",
            $reporter_name,
            $contact_email,
            $victim_name,
            $victim_age,
            $victim_gender,
            $relationship,
            $incident_date,
            $location,
            $description,
            $suspect_name,
            $abuse_type,
            $evidence_file
        );
        if ($stmt->execute()) {
            $showOptions = true;
        } else {
            $message = "❌ Failed to submit report.";
        }
        $stmt->close();
    } else {
        $message = "❌ Database error.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Report Child Abuse</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

<style>
  :root {
    --primary-color: #00879E;
    --primary-color-light: #00a9c7;
    --secondary-color: #006f85;
    --bg-dark: #121212;
    --bg-container: #1e1e1e;
    --text-light: #e0e0e0;
    --text-muted: #888;
    --border-radius: 16px;
    --box-shadow: 0 10px 25px rgba(0, 135, 158, 0.5);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  body {
    background: var(--bg-dark);
    color: var(--text-light);
    font-family: 'Inter', sans-serif;
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 30px 15px;
  }

  .dashboard-container {
    background: var(--bg-container);
    max-width: 700px;
    width: 100%;
    border-radius: var(--border-radius);
    padding: 2.5rem 3rem;
    box-shadow: var(--box-shadow);
    border: 1px solid var(--primary-color);
    text-align: center;
  }

  h2 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 2rem;
    text-align: center;
    text-shadow: 0 0 10px rgba(0, 135, 158, 0.7);
    user-select: none;
  }

  .alert {
    font-weight: 600;
  }

  label.form-label,
  label {
    font-weight: 600;
    color: var(--primary-color);
    display: block;
    margin-bottom: 0.5rem;
    text-align: left;
  }

  input.form-control,
  select.form-select,
  textarea.form-control {
    background: #222;
    border: 1.5px solid #444;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    color: var(--text-light);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  input.form-control::placeholder,
  textarea.form-control::placeholder {
    color: var(--text-muted);
  }

  input.form-control:focus,
  select.form-select:focus,
  textarea.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 8px var(--primary-color-light);
    outline: none;
    background: #1a1a1a;
  }

  .btn-custom {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-color-light));
    color: #fff;
    border: none;
    padding: 0.75rem 1.75rem;
    font-size: 1rem;
    border-radius: 12px;
    font-weight: 600;
    min-width: 180px;
    box-shadow: 0 4px 14px rgba(0, 135, 158, 0.35);
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
  }

  .btn-custom:hover,
  .btn-custom:focus {
    transform: translateY(-3px);
    box-shadow: 0 8px 22px rgba(0, 169, 199, 0.6);
    outline: none;
  }

  .btn-secondary-custom {
    background: #444;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
    padding: 0.75rem 1.75rem;
    font-size: 1rem;
    border-radius: 12px;
    font-weight: 600;
    min-width: 180px;
    transition: background 0.3s, color 0.3s;
    cursor: pointer;
  }

  .btn-secondary-custom:hover,
  .btn-secondary-custom:focus {
    background: var(--primary-color);
    color: white;
    outline: none;
  }

  .after-submit form {
    display: inline-block;
    margin: 0 0.5rem 1rem 0.5rem;
  }

  .after-submit button {
    min-width: 180px;
  }

  .invalid-feedback {
    color: #ef4444;
    font-weight: 600;
  }

  /* Responsive */
  @media (max-width: 576px) {
    .dashboard-container {
      padding: 1.5rem 1.5rem;
    }
    .btn-custom, .btn-secondary-custom {
      min-width: 140px;
      padding: 0.6rem 1.25rem;
      font-size: 0.95rem;
    }
  }
</style>
</head>
<body>

<div class="dashboard-container" role="main" aria-labelledby="pageTitle">
  <h2 id="pageTitle">Report Child Abuse</h2>

  <?php if (!empty($message)): ?>
    <div class="alert alert-danger text-center" role="alert">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <?php if ($showOptions): ?>
    <div class="alert alert-success text-center" role="alert" style="background-color:#004d59; color:#a0f0ff; border:none;">
      ✅ Report submitted successfully!
    </div>
    <div class="text-center after-submit" role="region" aria-label="Next actions">
      <form action="legal_aid_contact.php" method="get" class="d-inline">
        <button type="submit" class="btn btn-custom" aria-label="Contact Legal Aid Commission">Contact Legal Aid Commission</button>
      </form>
      <form action="volunteer_lawyer_contact.php" method="get" class="d-inline">
        <button type="submit" class="btn btn-custom" aria-label="Contact Volunteer Lawyer">Contact Volunteer Lawyer</button>
      </form>
      <form action="user_dashboard.php" method="get" class="d-inline">
        <button type="submit" class="btn btn-secondary-custom" aria-label="Back to Dashboard">Back to Dashboard</button>
      </form>
    </div>
  <?php else: ?>
    <form action="report_ca.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
      <div class="mb-3">
        <label for="reporter_name" class="form-label">Your Name</label>
        <input type="text" class="form-control" id="reporter_name" name="reporter_name" value="<?php echo htmlspecialchars($username); ?>" required readonly>
        <div class="invalid-feedback">Please enter your name.</div>
      </div>

      <div class="mb-3">
        <label for="contact_email" class="form-label">Your Email</label>
        <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="you@example.com" required>
        <div class="invalid-feedback">Please enter a valid email.</div>
      </div>

      <div class="mb-3">
        <label for="victim_name" class="form-label">Victim's Name</label>
        <input type="text" class="form-control" id="victim_name" name="victim_name" placeholder="Victim's Name">
      </div>

      <div class="mb-3">
        <label for="victim_age" class="form-label">Victim's Age</label>
        <input type="number" class="form-control" id="victim_age" name="victim_age" placeholder="Age" min="0">
      </div>

      <div class="mb-3">
        <label for="victim_gender" class="form-label">Victim's Gender</label>
        <select class="form-select" id="victim_gender" name="victim_gender" required>
          <option value="" disabled selected>Select gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
        <div class="invalid-feedback">Please select the victim's gender.</div>
      </div>

      <div class="mb-3">
        <label for="relationship" class="form-label">Your Relationship to Victim</label>
        <input type="text" class="form-control" id="relationship" name="relationship" placeholder="Relationship">
      </div>

      <div class="mb-3">
        <label for="incident_date" class="form-label">Date of Incident</label>
        <input type="date" class="form-control" id="incident_date" name="incident_date" required>
        <div class="invalid-feedback">Please provide the incident date.</div>
      </div>

      <div class="mb-3">
        <label for="location" class="form-label">Location of Incident</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Location" required>
        <div class="invalid-feedback">Please provide the location.</div>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Describe What Happened</label>
        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Details" required></textarea>
        <div class="invalid-feedback">Please provide a description.</div>
      </div>

      <div class="mb-3">
        <label for="suspect_name" class="form-label">Suspected Offender's Name</label>
        <input type="text" class="form-control" id="suspect_name" name="suspect_name" placeholder="Name">
      </div>

      <div class="mb-3">
        <label for="abuse_type" class="form-label">Type of Abuse</label>
        <select class="form-select" id="abuse_type" name="abuse_type" required>
          <option value="" disabled selected>Select type of abuse</option>
          <option value="Physical">Physical</option>
          <option value="Sexual">Sexual</option>
          <option value="Emotional">Emotional</option>
          <option value="Neglect">Neglect</option>
        </select>
        <div class="invalid-feedback">Please select the type of abuse.</div>
      </div>

      <div class="mb-4">
        <label for="evidence" class="form-label">Upload Evidence (optional)</label>
        <input class="form-control" type="file" id="evidence" name="evidence" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.mp4,.mov" />
      </div>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <button type="submit" class="btn btn-custom" aria-label="Submit Report">Submit Report</button>
        <a href="user_dashboard.php" class="btn btn-secondary-custom" aria-label="Back to Dashboard">Back to Dashboard</a>
      </div>
    </form>
  <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  (() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })();
</script>

</body>
</html>
