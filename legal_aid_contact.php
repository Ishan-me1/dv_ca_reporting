<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Legal Aid Commission Contacts</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
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
    font-family: 'Inter', sans-serif;
    color: var(--text-light);
    min-height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 30px 15px;
  }
  .dashboard-container {
    max-width: 800px;
    background: var(--bg-container);
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    width: 100%;
    border: 1px solid var(--primary-color);
  }
  h2 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 1rem;
    text-align: center;
    text-shadow: 0 0 10px rgba(0, 135, 158, 0.7);
  }
  .popup {
    background: rgba(0,135,158,0.2);
    border: 1px solid var(--primary-color);
    color: var(--primary-color-light);
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 12px;
    font-size: 0.95rem;
    font-weight: 600;
    text-align: center;
  }
  .search-bar {
    margin-bottom: 20px;
  }
  .search-input {
    width: 100%;
    padding: 10px 15px;
    border-radius: 12px;
    border: 1px solid var(--primary-color);
    background: #222;
    color: var(--text-light);
    font-size: 1rem;
    transition: var(--transition);
  }
  .search-input:focus {
    outline: none;
    border-color: var(--primary-color-light);
    box-shadow: 0 0 10px rgba(0,169,199,0.4);
  }
  .contact-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #222;
    border: 1px solid #444;
    padding: 12px 20px;
    margin: 8px 0;
    border-radius: 12px;
    transition: background 0.2s;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-light);
  }
  .contact-card:hover {
    background: #2a2a2a;
  }
  .call-btn {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-color-light));
    color: #fff;
    border: none;
    padding: 6px 14px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 12px rgba(0, 135, 158, 0.3);
    transition: var(--transition);
  }
  .call-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(0, 169, 199, 0.5);
  }
  .btn.back-btn {
    margin-top: 20px;
    padding: 10px 24px;
    font-size: 1.1rem;
    border-radius: 12px;
    background: var(--secondary-color);
    color: #fff;
    border: none;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 135, 158, 0.3);
    transition: var(--transition);
  }
  .btn.back-btn:hover {
    background: var(--primary-color);
    transform: translateY(-2px);
  }
</style>
<script>
  window.onload = () => {
    alert("⚠️ Victims earning less than Rs. 15,000/month are eligible for Legal Aid Commission support.");
  };

  function searchBranches() {
    const input = document.getElementById('branchSearch').value.toLowerCase();
    const cards = document.querySelectorAll('.contact-card');
    cards.forEach(card => {
      const text = card.textContent.toLowerCase();
      card.style.display = text.includes(input) ? 'flex' : 'none';
    });
  }
</script>
</head>
<body>
<div class="dashboard-container">
  <h2>Legal Aid Commission – Branch Contacts</h2>
  <div class="popup">
    ⚠️ Victims who earn less than Rs. 15,000 per month are only eligible for Legal Aid Commission support.
  </div>

  <div class="search-bar">
    <input type="text" id="branchSearch" onkeyup="searchBranches()" class="search-input" placeholder="🔍 Search branches by name..." />
  </div>

  <!-- Main Branch -->
  <div class="contact-card">
    <span class="contact-name">🏢 Main Branch (Colombo): +94 11 533 5329</span>
    <a href="tel:+94115335329" class="call-btn">Call</a>
  </div>

  <?php
  $branches = [
    ["Attanagalla","0332297020"],["Avissawella","0362233857"],["Anuradhapura","0252224465"],["Ampara","0632223496"],
    ["Akkaraipattu","0672279462"],["Bandarawela","0572224733"],["Balapitiya","0912255753"],["Battaramulla","0112877687"],
    ["Batticaloa","0652225399"],["Baddegama","0912292051"],["Badulla","0552225759"],["Balangoda","0452289099"],
    ["Chilaw","0322222175"],["Chavakachcheri","0212270882"],["Dambulla","0662284551"],["Deiyandara","0412268077"],
    ["Deniyaya","0412271128"],["Embilipitiya","0472230299"],["Galgamuwa","0372253290"],["Gampaha","0332248804"],
    ["Galle","0912226124"],["Hatton","0512225238"],["Hambantota","0472221092"],["Horana","0342265244"],
    ["Homagama","0112748813"],["Hingurakgoda","0272245521"],["Jaffna","0212224545"],["Kalutara","0342222017"],
    ["Kakirawa","0252263536"],["Kaduwela","0112548150"],["Kadawatha","0112922440"],["Kalmunai","0672223710"],
    ["Kandy","0812388978"],["Kegalle","0352231790"],["Kurunegala","0372229641"],["Kilinochchi","0212285618"],
    ["Kanthale","0262234521"],["Kebethigollawa","0252298101"],["Kuliyapitiya","0372284611"],["Maho","0372275075"],
    ["Matale","0662224828"],["Matara","0412233815"],["Mahiyanganaya","0552258332"],["Mannar","0232222045"],
    ["Monaragala","0552276891"],["Mullaithivu","0212290077"],["Minuwangoda","0112297790"],["Muthur","0262238777"],
    ["Negombo","0312222779"],["Nuwara Eliya","0522235260"],["Nugegoda","0112809068"],["Panadura","0382244822"],
    ["Pothuvil","0632248485"],["Point Pedro","0212260211"],["Polgahawela","0372243039"],["Pugoda","0112405333"],
    ["Rathnapura","0452226899"],["Thambuththeygama","0252276259"],["Thissamaharama","0472239611"],["Tangalle","0472240122"],
    ["Trincomalee","0262226328"],["Vavuniya","0242221863"],["Valachchenai","0652258349"],["Warakapola","0372277071"],
    ["Welimada","0572244860"],["Walasmulla","0472245566"],["Wallawaya","0552274466"],["Wariyapola","0372268199"],
    ["Polonnaruwa","0272226572"]
  ];

  foreach($branches as $branch){
    echo '<div class="contact-card">
      <span class="contact-name">'.htmlspecialchars($branch[0]).' – '.$branch[1].'</span>
      <a href="tel:+94'.substr($branch[1],1).'" class="call-btn">Call</a>
    </div>';
  }
  ?>

  <form action="report_dv.php" method="get">
    <button class="btn back-btn">← Back to Dashboard</button>
  </form>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
