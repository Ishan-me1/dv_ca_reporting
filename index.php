<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SafeNet | Report & Support</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #007aff;
      --secondary: #34c759;
      --bg-dark: #1c1c1e;
      --bg-card: rgba(255, 255, 255, 0.06);
      --text-light: #f5f5f7;
      --text-muted: #a1a1aa;
      --radius: 20px;
      --shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      --transition: all 0.3s ease;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'Inter', sans-serif;
      background: linear-gradient(135deg, #0f0f10, #1c1c1e);
      color: var(--text-light);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Navbar */
    .navbar {
      background: #1c1c1e !important;
      border-bottom: 1px solid #2c2c2e;
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 1.6rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .navbar-nav .btn {
      border-radius: 30px;
      padding: 0.5rem 1.5rem;
      border: 2px solid var(--primary);
      color: var(--primary);
      transition: var(--transition);
    }
    .navbar-nav .btn:hover {
      background: var(--primary);
      color: white;
      box-shadow: var(--shadow);
    }

    /* Hero Card */
    .hero-card {
      background: var(--bg-card);
      backdrop-filter: blur(20px);
      border-radius: var(--radius);
      padding: 3rem 2rem;
      max-width: 850px;
      margin: 4rem auto;
      text-align: center;
      box-shadow: var(--shadow);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .hero-card h1 {
      font-size: 2.6rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .hero-card p {
      color: var(--text-muted);
      font-size: 1.1rem;
      margin-bottom: 2rem;
    }

    /* Buttons */
    .btn-modern {
      border-radius: 32px;
      font-weight: 600;
      padding: 0.85rem 2rem;
      font-size: 1rem;
      transition: var(--transition);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }
    .btn-primary-modern {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      border: none;
    }
    .btn-primary-modern:hover {
      transform: scale(1.03);
      box-shadow: var(--shadow);
    }
    .btn-outline-modern {
      background: transparent;
      border: 2px solid var(--primary);
      color: var(--primary);
    }
    .btn-outline-modern:hover {
      background: var(--primary);
      color: white;
    }

    /* Features */
    .features {
      margin-top: 3rem;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.5rem;
    }
    .feature-card {
      background: var(--bg-card);
      border-radius: 20px;
      padding: 2rem;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.15);
      transition: var(--transition);
      backdrop-filter: blur(10px);
    }
    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: var(--shadow);
    }
    .feature-card i {
      font-size: 2rem;
      color: var(--primary);
      margin-bottom: 1rem;
    }

    /* Footer */
    footer {
      background: #1c1c1e;
      padding: 2rem 0;
      text-align: center;
      margin-top: 4rem;
      border-top: 1px solid #2c2c2e;
    }
    footer .footer-link {
      color: var(--primary);
      margin: 0 1rem;
      text-decoration: none;
      font-weight: 500;
    }
    footer .footer-link:hover {
      text-decoration: underline;
      color: var(--secondary);
    }

    /* Chatbot */
    #chat-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: var(--primary);
      color: #fff;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      cursor: pointer;
      z-index: 1000;
      box-shadow: var(--shadow);
    }
    #chat-box {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 320px;
      border-radius: 20px;
      background: rgba(255, 255, 255, 0.06);
      color: var(--text-light);
      font-family: inherit;
      z-index: 1000;
      display: none;
      box-shadow: var(--shadow);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    #chat-box div {
      font-size: 0.95rem;
    }
    #chat-messages {
      height: 250px;
      overflow-y: auto;
      padding: 10px;
    }
    #chat-messages div {
      margin: 6px 0;
      padding: 10px 14px;
      border-radius: 18px;
      max-width: 75%;
      word-wrap: break-word;
    }
    #chat-messages .user {
      background: var(--primary);
      color: #fff;
      align-self: flex-end;
      margin-left: auto;
    }
    #chat-messages .bot {
      background: #333;
      color: var(--text-light);
      align-self: flex-start;
      margin-right: auto;
    }
    #chat-box input {
      border: none;
      padding: 8px;
      flex: 1;
      background: #111;
      color: var(--text-light);
      border-radius: 0 0 0 20px;
    }
    #chat-box button {
      background: var(--secondary);
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 0 0 20px 0;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fas fa-shield-alt me-2"></i> SafeNet
      </a>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <div class="navbar-nav">
          <a href="login.php" class="btn">Login</a>
          <a href="register.php" class="btn">Register</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="hero-card">
    <h1><i class="fas fa-shield-heart me-2"></i> Welcome to SafeNet</h1>
    <p>A secure platform to report <strong>Domestic Violence</strong> and <strong>Child Abuse</strong>.  
       Your safety, privacy, and justice are our mission.</p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="login.php" class="btn-modern btn-primary-modern"><i class="fas fa-sign-in-alt"></i> Login</a>
      <a href="register.php" class="btn-modern btn-outline-modern"><i class="fas fa-user-plus"></i> Register</a>
      <a href="volunteer_lawyer_register.php" class="btn-modern btn-primary-modern"><i class="fas fa-balance-scale"></i> Volunteer Lawyer</a>
    </div>

    <!-- Features -->
    <div class="features">
      <div class="feature-card">
        <i class="fas fa-user-shield"></i>
        <h5>Complete Privacy</h5>
        <p>Your identity is protected with strong security.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-heart"></i>
        <h5>Compassionate Care</h5>
        <p>24/7 support with trained professionals.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-hands-helping"></i>
        <h5>Legal Assistance</h5>
        <p>Free access to volunteer lawyers.</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-mobile-alt"></i>
        <h5>Always Accessible</h5>
        <p>Report safely from any device.</p>
      </div>
    </div>
  </div>

  <!-- Chatbot -->
  <div id="chat-icon">💬</div>
  <div id="chat-box">
    <div style="background: var(--primary); color: #fff; padding: 10px; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
      <span>💬 SafeNet Assistant</span>
      <button id="close-chat">✖</button>
    </div>
    <div id="chat-messages" style="display: flex; flex-direction: column;"></div>
    <div style="display: flex;">
      <input id="chat-input" placeholder="Type a message...">
      <button id="chat-send">Send</button>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>&copy; <span id="year"></span> SafeNet. All rights reserved.</p>
    <div>
      <a href="#" class="footer-link">Privacy Policy</a>
      <a href="#" class="footer-link">Terms</a>
      <a href="#" class="footer-link">Contact</a>
    </div>
  </footer>

  <!-- JS -->
  <script>
    document.getElementById("year").textContent = new Date().getFullYear();

    const chatIcon = document.getElementById("chat-icon");
    const chatBox = document.getElementById("chat-box");
    const closeBtn = document.getElementById("close-chat");
    const sendBtn = document.getElementById("chat-send");
    const input = document.getElementById("chat-input");
    const messages = document.getElementById("chat-messages");

    chatIcon.onclick = () => {
      chatBox.style.display = chatBox.style.display === "none" ? "block" : "none";
    };
    closeBtn.onclick = () => chatBox.style.display = "none";

    function addMessage(text, sender) {
      const msg = document.createElement("div");
      msg.className = sender;
      msg.textContent = text;
      messages.appendChild(msg);
      messages.scrollTop = messages.scrollHeight;
    }

    async function sendMessage() {
      const msg = input.value.trim();
      if (!msg) return;
      addMessage(msg, "user");
      input.value = "";

      try {
        const res = await fetch("gemini.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ message: msg })
        });
        const data = await res.json();
        addMessage(data.reply, "bot");
      } catch (err) {
        addMessage("Error connecting to server.", "bot");
      }
    }

    sendBtn.onclick = sendMessage;
    input.addEventListener("keypress", e => { if (e.key === "Enter") sendMessage(); });
  </script>

</body>
</html>
