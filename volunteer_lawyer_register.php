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

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $specialization = trim($_POST["specialization"]);
    $courts = isset($_POST["courts"]) ? implode(", ", $_POST["courts"]) : "";

    $sql = "INSERT INTO vol_lawyer (name, email, phone, specialization, courts) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssss", $name, $email, $phone, $specialization, $courts);
        if ($stmt->execute()) {
            $message = "Registration Successful!";
            $success = true;
        } else {
            $message = "❌ Failed to register.";
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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Volunteer Lawyer Registration | SafeNet</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">

<style>
:root {
  --primary: #00879E;
  --secondary: #10b981;
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
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin: 0;
  padding: 20px;
}

.container {
  background: var(--bg-card);
  max-width: 650px;
  width: 100%;
  border-radius: var(--radius);
  padding: 3rem 2.5rem;
  box-shadow: var(--shadow);
  color: var(--text-light);
  text-align: center;
  border: 1px solid var(--primary);
}

h2 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

label {
  font-weight: 600;
  color: var(--primary);
  display: block;
  text-align: left;
  margin-bottom: 0.5rem;
}

.form-control,
.select2-selection--multiple {
  border-radius: 12px !important;
  border: 1.5px solid var(--primary) !important;
  padding: 0.5rem 0.75rem !important;
  font-size: 1rem !important;
  background: #222;
  color: var(--text-light);
  transition: var(--transition);
}

.form-control::placeholder {
  color: var(--text-muted);
}

.form-control:focus,
.select2-container--focus .select2-selection--multiple {
  border-color: var(--secondary) !important;
  box-shadow: 0 0 8px var(--secondary);
  background: #0f1f28;
  outline: none;
  color: var(--text-light);
}

.btn {
  border-radius: 20px;
  padding: 0.75rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 600;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  color: #fff;
  border: none;
  box-shadow: var(--shadow);
  transition: var(--transition);
  width: 100%;
  cursor: pointer;
}

.btn:hover,
.btn:focus {
  background: linear-gradient(135deg, var(--secondary), var(--primary));
  box-shadow: 0 10px 25px rgba(0, 155, 181, 0.6);
  transform: translateY(-2px);
  outline: none;
}

.btn-secondary {
  background: #78909c;
  border-radius: 20px;
  font-weight: 600;
  padding: 0.6rem 1rem;
  box-shadow: 0 3px 8px rgba(120,144,156,0.6);
  transition: background-color 0.3s ease;
  margin-top: 1rem;
  width: 100%;
  color: white;
  border: none;
  cursor: pointer;
}

.btn-secondary:hover,
.btn-secondary:focus {
  background: #607d8b;
  box-shadow: 0 5px 12px rgba(96,125,139,0.7);
}

.success-message {
  color: var(--secondary);
  font-weight: 900;
  font-size: 2.2rem;
  margin-bottom: 2rem;
  user-select: none;
}

p[style*="color:#d32f2f"] {
  font-weight: 700;
  margin-bottom: 1rem;
}

@media (max-width: 480px) {
  .container {
    margin: 40px 1rem 30px;
    padding: 2rem 1.5rem;
  }
  h2 {
    font-size: 1.5rem;
  }
}
</style>

<?php if($success): ?>
<script>
  setTimeout(() => { window.location.href = 'index.php'; }, 5000);
</script>
<?php endif; ?>
</head>
<body>

<div class="container" role="main" aria-labelledby="pageTitle">
<h2 id="pageTitle"><i class="fas fa-gavel"></i> Volunteer Lawyer Registration</h2>

<?php if ($success): ?>
<p class="success-message" role="alert"><?php echo htmlspecialchars($message); ?></p>
<?php else: ?>
  <?php if (!empty($message)): ?>
    <p style="color:#d32f2f;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <form action="volunteer_lawyer_register.php" method="post" novalidate>
    <div class="mb-4">
      <label for="name">Name <sup>*</sup></label>
      <input type="text" name="name" id="name" class="form-control" required autocomplete="name" />
    </div>
    <div class="mb-4">
      <label for="email">Email <sup>*</sup></label>
      <input type="email" name="email" id="email" class="form-control" required autocomplete="email" />
    </div>
    <div class="mb-4">
      <label for="phone">Phone <sup>*</sup></label>
      <input type="text" name="phone" id="phone" class="form-control" required autocomplete="tel" />
    </div>
    <div class="mb-4">
      <label for="specialization">Specialization <sup>*</sup></label>
      <input type="text" name="specialization" id="specialization" class="form-control" required />
    </div>
    <div class="mb-4">
      <label for="courts">Courts You Work With <sup>*</sup></label>
      <select name="courts[]" id="courts" class="form-control courts-select" multiple required style="width:100%;">
        <optgroup label="Magistrate Courts">
          <option value="Akkaraipattu Magistrate Court">Akkaraipattu Magistrate Court</option>
          <option value="Ampara Magistrate Court">Ampara Magistrate Court</option>
          <option value="Angunukolapelessa Magistrate Court">Angunukolapelessa Magistrate Court</option>
          <option value="Anuradhapura Magistrate Court">Anuradhapura Magistrate Court</option>
          <option value="Avissawella Magistrate Court">Avissawella Magistrate Court</option>
          <option value="Badulla Magistrate Court">Badulla Magistrate Court</option>
          <option value="Balangoda Magistrate Court">Balangoda Magistrate Court</option>
          <option value="Batticaloa Magistrate Court">Batticaloa Magistrate Court</option>
          <option value="Colombo Magistrate Court">Colombo Magistrate Court</option>
          <option value="Dambulla Magistrate Court">Dambulla Magistrate Court</option>
          <option value="Dehiwala Magistrate Court">Dehiwala Magistrate Court</option>
          <option value="Gampaha Magistrate Court">Gampaha Magistrate Court</option>
          <option value="Hambantota Magistrate Court">Hambantota Magistrate Court</option>
          <option value="Hatton Magistrate Court">Hatton Magistrate Court</option>
          <option value="Homagama Magistrate Court">Homagama Magistrate Court</option>
          <option value="Jaffna Magistrate Court">Jaffna Magistrate Court</option>
          <option value="Kalutara Magistrate Court">Kalutara Magistrate Court</option>
          <option value="Kandy Magistrate Court">Kandy Magistrate Court</option>
          <option value="Kegalle Magistrate Court">Kegalle Magistrate Court</option>
          <option value="Kilinochchi Magistrate Court">Kilinochchi Magistrate Court</option>
          <option value="Kurunegala Magistrate Court">Kurunegala Magistrate Court</option>
          <option value="Mannar Magistrate Court">Mannar Magistrate Court</option>
          <option value="Matale Magistrate Court">Matale Magistrate Court</option>
          <option value="Matara Magistrate Court">Matara Magistrate Court</option>
          <option value="Monaragala Magistrate Court">Monaragala Magistrate Court</option>
          <option value="Mullaitivu Magistrate Court">Mullaitivu Magistrate Court</option>
          <option value="Nuwara Eliya Magistrate Court">Nuwara Eliya Magistrate Court</option>
          <option value="Polonnaruwa Magistrate Court">Polonnaruwa Magistrate Court</option>
          <option value="Puttalam Magistrate Court">Puttalam Magistrate Court</option>
          <option value="Ratnapura Magistrate Court">Ratnapura Magistrate Court</option>
          <option value="Trincomalee Magistrate Court">Trincomalee Magistrate Court</option>
          <option value="Vavuniya Magistrate Court">Vavuniya Magistrate Court</option>
        </optgroup>
        <optgroup label="District Courts">
          <option value="Colombo District Court">Colombo District Court</option>
          <option value="Mount Lavinia District Court">Mount Lavinia District Court</option>
          <option value="Kalutara District Court">Kalutara District Court</option>
          <option value="Gampaha District Court">Gampaha District Court</option>
          <option value="Kandy District Court">Kandy District Court</option>
          <option value="Kurunegala District Court">Kurunegala District Court</option>
          <option value="Matara District Court">Matara District Court</option>
          <option value="Anuradhapura District Court">Anuradhapura District Court</option>
          <option value="Hambantota District Court">Hambantota District Court</option>
          <option value="Jaffna District Court">Jaffna District Court</option>
          <option value="Ratnapura District Court">Ratnapura District Court</option>
          <option value="Badulla District Court">Badulla District Court</option>
        </optgroup>
        <optgroup label="High Courts">
          <option value="Colombo High Court">Colombo High Court</option>
          <option value="Avissawella High Court">Avissawella High Court</option>
          <option value="Kandy High Court">Kandy High Court</option>
          <option value="Kurunegala High Court">Kurunegala High Court</option>
          <option value="Galle High Court">Galle High Court</option>
          <option value="Jaffna High Court">Jaffna High Court</option>
          <option value="Batticaloa High Court">Batticaloa High Court</option>
        </optgroup>
      </select>
      <div class="form-text text-start" style="color: var(--text-muted);">Search and select multiple courts where you practice.</div>
    </div>
    <button type="submit" class="btn">Register</button>
  </form>
<?php endif; ?>

<form action="index.php" method="get" class="mt-3">
  <button type="submit" class="btn btn-secondary">← Back to Home</button>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
  $('.courts-select').select2({
    placeholder: "Search and select courts",
    allowClear: true,
    width: '100%',
    dropdownParent: $('.container')
  });
});
</script>
</body>
</html>
