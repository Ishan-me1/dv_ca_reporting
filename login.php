<?php
session_start();

// DB connection
$host = "localhost";
$user = "root";
$pass = "Ishan@#$19988276";
$dbname = "reporting_system";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$usernameInput = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $usernameInput = htmlspecialchars($username); // preserve input

    // Validation
    if (empty($username) || empty($password)) {
        $message = "⚠️ Username and password are required.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row["password"])) {
                    $_SESSION["username"] = $username;
                    $_SESSION["role"] = $row["role"];
                    $_SESSION["user_id"] = $row["id"];
                    // Role-based redirection
                    switch ($row["role"]) {
                        case "admin1":
                        case "admin2":
                            header("Location: admin_select_dashboard.php");
                            exit;
                        case "admin3":
                            header("Location: admin_dashboard2.php");
                            exit;
                        default:
                            header("Location: user_dashboard.php");
                            exit;
                    }
                } else {
                    $message = "❌ Invalid password.";
                }
            } else {
                $message = "❌ User not found.";
            }
            $stmt->close();
        } else {
            $message = "❌ Error preparing SQL statement.";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login | SafeNet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
      margin: 0;
    }

    .navbar {
      background: #1e293b !important;
      border-bottom: 2px solid var(--primary);
      padding: 1rem;
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.6rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .login-container {
      max-width: 400px;
      margin: 80px auto 40px;
      background: var(--bg-card);
      border-radius: var(--radius);
      padding: 2.5rem 2rem;
      box-shadow: var(--shadow);
      text-align: center;
      border: 1px solid var(--primary);
    }

    .login-container h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .form-control {
      background: #222;
      border: 1.5px solid var(--primary);
      border-radius: 12px;
      color: var(--text-light);
      margin-bottom: 1.25rem;
      padding: 0.6rem 1rem;
      font-size: 1rem;
      transition: var(--transition);
    }

    .form-control::placeholder {
      color: var(--text-muted);
    }

    .form-control:focus {
      border-color: var(--secondary);
      box-shadow: 0 0 8px var(--secondary);
      background: #0f1f28;
      outline: none;
      color: var(--text-light);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: #fff;
      border: none;
      padding: 0.75rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1rem;
      width: 100%;
      cursor: pointer;
      transition: var(--transition);
      box-shadow: var(--shadow);
    }

    .btn-login:hover {
      background: linear-gradient(135deg, var(--secondary), var(--primary));
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(0, 155, 181, 0.6);
    }

    .message {
      color: #ef4444;
      font-size: 0.9rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    a.text-primary {
      color: var(--primary);
      font-weight: 600;
      text-decoration: none;
    }

    a.text-primary:hover {
      color: var(--secondary);
      text-decoration: underline;
    }

    footer {
      margin-top: auto;
      padding: 1rem 0;
      font-size: 0.9rem;
      color: var(--text-muted);
      text-align: center;
      border-top: 2px solid var(--primary);
      user-select: none;
    }

    @media (max-width: 480px) {
      .login-container {
        margin: 40px 1rem 30px;
        padding: 2rem 1.5rem;
      }
      .login-container h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <i class="fas fa-shield-alt me-2"></i> SafeNet
      </a>
    </div>
  </nav>

  <!-- Login form -->
  <div class="login-container" role="main">
    <h2><i class="fas fa-sign-in-alt me-2"></i>Login</h2>
    
    <?php if (!empty($message)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="post" novalidate>
      <input
        type="text"
        name="username"
        class="form-control"
        placeholder="Username"
        required
        autocomplete="username"
        value="<?php echo $usernameInput; ?>"
      />
      <input
        type="password"
        name="password"
        class="form-control"
        placeholder="Password"
        required
        autocomplete="current-password"
      />
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt me-1"></i> Login
      </button>
    </form>

    <p class="mt-3">
      <a href="register.php" class="text-primary">Don't have an account? Register</a>
    </p>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> SafeNet. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>