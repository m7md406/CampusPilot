<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/includes/db_connect.php";

/* Escape helper */
function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role     = $_POST["role"] ?? "Student";

    /* ולידציות */
    if ($username === "") {
        $errors[] = "שם משתמש הוא שדה חובה.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "אימייל לא תקין.";
    }

    if (strlen($password) < 6) {
        $errors[] = "הסיסמה חייבת להכיל לפחות 6 תווים.";
    }

    /* תפקידים מותרים */
    if (!in_array($role, ["Student", "Staff"], true)) {
        $errors[] = "תפקיד לא תקין.";
    }

    /* בדיקת כפילות */
    if (!$errors) {
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1"
        );
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "שם משתמש או אימייל כבר קיימים במערכת.";
        }
        $stmt->close();
    }

    /* שמירה למסד */
    if (!$errors) {
        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, role)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            /* 🔥 redirect חד וברור */
            header("Location: login.php?registered=1");
            exit;
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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>הרשמה למערכת | CampusPilot</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<header class="main-header">
  <h1>יצירת חשבון חדש</h1>
  <p class="subtitle">הצטרפו ל־CampusPilot</p>
</header>

<main class="card">
  <h2>הרשמה</h2>

  <?php if ($errors): ?>
    <div class="error-box">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= h($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="">
    <div class="form-group">
      <label>שם משתמש</label>
      <input type="text" name="username" required value="<?= h($_POST["username"] ?? "") ?>">
    </div>

    <div class="form-group">
      <label>אימייל</label>
      <input type="email" name="email" required value="<?= h($_POST["email"] ?? "") ?>">
    </div>

    <div class="form-group">
      <label>סיסמה</label>
      <input type="password" name="password" required>
      <small>לפחות 6 תווים</small>
    </div>

    <div class="form-group">
      <label>תפקיד</label>
      <select name="role" required>
        <option value="Student">סטודנט</option>
        <option value="Staff">סגל הוראה</option>
      </select>
    </div>

    <button type="submit" class="primary">בצע הרשמה</button>
  </form>
</main>

<script src="assets/js/main.js"></script>
</body>
</html>
