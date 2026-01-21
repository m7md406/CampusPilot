<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . "/includes/db_connect.php";

if (!function_exists('h')) {
    function h($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$errors = [];
$success = false;

// אם כבר מחובר — נשלח לדשבורד
if (isset($_SESSION["user_id"])) {
    header("Location: mainDashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login    = trim($_POST["login"] ?? "");     // יכול להיות username או email
    $password = $_POST["password"] ?? "";

    if ($login === "") $errors[] = "יש להזין שם משתמש או אימייל.";
    if ($password === "") $errors[] = "יש להזין סיסמה.";

    if (!$errors) {
        $stmt = $conn->prepare("
            SELECT id, username, email, password, role
            FROM users
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $res = $stmt->get_result();

        if (!$res || $res->num_rows !== 1) {
            $errors[] = "שם משתמש/אימייל או סיסמה לא נכונים.";
        } else {
            $user = $res->fetch_assoc();

            // לפי הטבלה שלך: password הוא טקסט רגיל
            if ((string)$user["password"] !== (string)$password) {
                $errors[] = "שם משתמש/אימייל או סיסמה לא נכונים.";
            } else {
                // התחברות הצליחה
                $_SESSION["user_id"] = (int)$user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                header("Location: mainDashboard.php");
                exit;
            }
        }

        $stmt->close();
    }
}


?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>התחברות | CampusPilot</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <nav class="navbar" id="navbar">
    <div class="nav-container">

      <a class="logo" href="index.php" aria-label="דף הבית">
        <img class="logo-img" src="assets/images/Logo.png" alt="לוגו CampusPilot" />
      </a>

      <button class="nav-toggle" id="navToggle" aria-label="פתח תפריט">☰</button>

      <ul class="nav-links" id="navLinks">
        <li><a href="index.php">בית</a></li>
        <li><a href="OurTeam.html">הצוות שלנו</a></li>
        <li><a href="register.php">הרשמה</a></li>
        <li><a href="login.php" aria-current="page">התחברות</a></li>


      </ul>

      <button id="themeToggleBtn" class="nav-btn" type="button" aria-label="החלפת מצב תצוגה">🌓</button>
    </div>
  </nav>

  <header class="main-header">
    <h1>התחברות</h1>
    <p class="subtitle">כניסה לחשבון CampusPilot</p>
  </header>

  <main class="card">
    <h1>כניסה למערכת</h1>
    <p class="subtitle">הזינו שם משתמש/אימייל וסיסמה</p>

    <?php if (!empty($errors)): ?>
      <div class="card" style="margin-bottom:12px;">
        <div class="error-box">
          <ul style="margin:0;">
            <?php foreach ($errors as $e): ?>
              <li><?= h($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="login">שם משתמש או אימייל:</label>
        <input
          type="text"
          id="login"
          name="login"
          required
          value="<?= h($_POST["login"] ?? "") ?>"
          autocomplete="username"
        >
      </div>

      <div class="form-group">
        <label for="password">סיסמה:</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
      </div>

      <button type="submit" class="primary">התחבר</button>
    </form>

    <div style="margin-top:14px;">
      <small>אין לך חשבון? <a href="register.php">להרשמה</a></small>
    </div>
  </main>

  <script src="assets/js/main.js"></script>
</body>
</html>
