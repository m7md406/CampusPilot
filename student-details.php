<?php
require_once __DIR__ . "/includes/db_connect.php";
require_once __DIR__ . "/includes/navbar.php"; 

header('Content-Type: text/html; charset=UTF-8');

$id = $_GET["id"] ?? null;
$student = null;
$error = null;

if (!$id || !ctype_digit($id)) {
    $error = "מזהה סטודנט לא תקין.";
} else {
    $stmt = $conn->prepare("
        SELECT id, full_name, email, phone, department, registration_date, externalId
        FROM students
        WHERE id = ?
        LIMIT 1
    ");
    $idInt = (int)$id;
    $stmt->bind_param("i", $idInt);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows === 1) {
        $student = $res->fetch_assoc();
    } else {
        $error = "סטודנט לא נמצא.";
    }

    $stmt->close();
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>פרטי סטודנט | CampusPilot</title>

  <!-- לפי המבנה שלך -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <nav class="navbar" id="navbar">
    <div class="nav-container">
      <a class="logo" href="index.html" aria-label="דף הבית">
        <img class="logo-img" src="assets/images/Logo.png" alt="לוגו CampusPilot" />
      </a>

      <button class="nav-toggle" id="navToggle" aria-label="פתח/סגור תפריט">☰</button>

       <ul class="nav-links">
        <li><a href="index.html">בית</a></li>
        <li><a href="OurTeam.html">הצוות שלנו</a></li>
        <li><a href="register.php">הרשמה</a></li>
        <li><a href="login.php">התחברות</a></li>
        <li><a href="mainDashboard.php">לוח בקרה</a></li>
        <li><a href="studentsManagment.php">ניהול סטודנטים</a></li>
        <li><a href="courseManagment.php">ניהול קורסים</a></li>
        <li><a href="enroll.php">רישום לקורסים</a></li>
        <li><a href="profile.php">פרופיל משתמש</a></li>
      </ul>

      <button id="themeToggleBtn" class="nav-btn" type="button" aria-label="החלפת מצב תצוגה">🌓</button>
    </div>
  </nav>

  <main class="card">
    <h1>פרטי סטודנט</h1>
    <p class="subtitle">מידע מתוך בסיס הנתונים</p>

    <?php if ($error): ?>
      <div class="error-box">
        <?= h($error) ?>
      </div>
      <div style="margin-top:12px;">
        <a class="view-profile-btn" href="studentsManagment.php">חזרה לניהול סטודנטים</a>
      </div>

    <?php else: ?>
      <div class="team-card" style="text-align:right; padding:16px;">
        <h3 style="margin-top:0;"><?= h($student["full_name"] ?: "ללא שם") ?></h3>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px; margin-top:12px;">
          <div><strong>ID:</strong> <?= h($student["id"]) ?></div>
          <div><strong>External ID:</strong> <?= h($student["externalId"]) ?></div>

          <div><strong>אימייל:</strong> <?= h($student["email"]) ?></div>
          <div><strong>טלפון:</strong> <?= h($student["phone"] ?: "לא הוזן") ?></div>

          <div><strong>מחלקה:</strong> <?= h($student["department"] ?: "לא הוזן") ?></div>
          <div><strong>תאריך רישום:</strong> <?= h($student["registration_date"]) ?></div>
        </div>

        <div style="display:flex; gap:10px; margin-top:16px; flex-wrap:wrap;">
          <a class="view-profile-btn" href="studentsManagment.php">חזרה לניהול סטודנטים</a>
          <a class="view-profile-btn" href="student-details.php?id=<?= h($student["id"]) ?>">רענון</a>
        </div>
      </div>
    <?php endif; ?>
  </main>

  <script src="assets/js/main.js"></script>
</body>
</html>
