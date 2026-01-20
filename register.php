<?php
    ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once __DIR__ . "/includes/db_connect.php";
require_once __DIR__ . "/includes/navbar.php";

if (!function_exists('h')) {
    function h($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}


$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role     = $_POST["role"] ?? "Student";

    // ולידציות
    if ($username === "") $errors[] = "שם משתמש הוא שדה חובה.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "אימייל לא תקין.";
    if (strlen($password) < 6) $errors[] = "הסיסמה חייבת להכיל לפחות 6 תווים.";

    // ברישום נרצה לאפשר רק Student/Staff (Admin רק ע״י מנהל)
    $allowedRoles = ["Student", "Staff"];
    if (!in_array($role, $allowedRoles, true)) $errors[] = "תפקיד לא תקין.";

    // בדיקת כפילויות (בלי get_result כדי לעבוד בכל שרת)
    if (!$errors) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "שם משתמש או אימייל כבר קיימים במערכת.";
        }
        $stmt->close();
    }

    // שמירה למסד נתונים
    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            $success = true;
            $_POST = [];
        } else {
            $errors[] = "שגיאה בשמירה למסד הנתונים.";
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
  <title>הרשמה למערכת | CampusPilot</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>

  <!-- התפריט כבר הודפס מתוך includes/navbar.php, אז לא לשכפל כאן -->

  <header class="main-header">
    <h1>יצירת חשבון חדש באתר</h1>
    <p class="subtitle">הצטרפו לניהול האקדמי של CampusPilot</p>
  </header>

  <main class="card">
    <h1>הרשמה</h1>
    <p class="subtitle">מלאו פרטים כדי ליצור חשבון</p>

    <?php if ($success): ?>
      <div class="card" style="margin-bottom:12px;">
        <p style="margin:0; font-weight:700;">נרשמת בהצלחה! אפשר להתחבר עכשיו.</p>
      </div>
    <?php endif; ?>

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

    <form action="" method="POST">
      <div class="form-group">
        <label for="username">שם משתמש:</label>
        <input type="text" id="username" name="username" required value="<?= h($_POST["username"] ?? "") ?>">
      </div>

      <div class="form-group">
        <label for="email">אימייל:</label>
        <input type="email" id="email" name="email" required value="<?= h($_POST["email"] ?? "") ?>">
      </div>

      <div class="form-group">
        <label for="password">סיסמה:</label>
        <input type="password" id="password" name="password" required>
        <small class="hint">לפחות 6 תווים</small>
      </div>

      <div class="form-group">
        <label for="role">תפקיד במערכת:</label>
        <select id="role" name="role" required>
          <option value="Student" <?= (($_POST["role"] ?? "Student") === "Student") ? "selected" : "" ?>>סטודנט</option>
          <option value="Staff" <?= (($_POST["role"] ?? "") === "Staff") ? "selected" : "" ?>>סגל הוראה</option>
        </select>
      </div>

      <button type="submit" class="primary">בצע הרשמה</button>
    </form>
  </main>

  <script src="assets/js/main.js"></script>
</body>
</html>
