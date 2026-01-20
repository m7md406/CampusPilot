<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/navbar.php"; 
require_login();

require_once __DIR__ . "/includes/db_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];
$errors = [];
$successMsg = "";

// שליפת פרטי משתמש
$stmt = $conn->prepare("SELECT id, username, email, role, password FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows !== 1) {
    // מצב חריג: סשן קיים אבל משתמש לא נמצא
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$user = $res->fetch_assoc();
$stmt->close();

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}

// טיפול בעדכון פרופיל
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    // 1) עדכון אימייל
    if ($action === "update_email") {
        $newEmail = trim($_POST["email"] ?? "");

        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "אימייל לא תקין.";
        } else {
            // בדיקה שהאימייל לא תפוס ע"י משתמש אחר
            $chk = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
            $chk->bind_param("si", $newEmail, $userId);
            $chk->execute();
            $chkRes = $chk->get_result();
            $isTaken = ($chkRes && $chkRes->num_rows > 0);
            $chk->close();

            if ($isTaken) {
                $errors[] = "האימייל הזה כבר קיים במערכת.";
            }
        }

        if (!$errors) {
            $up = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $up->bind_param("si", $newEmail, $userId);

            if ($up->execute()) {
                $successMsg = "האימייל עודכן בהצלחה.";
                $user["email"] = $newEmail;
                $_SESSION["email"] = $newEmail; // אופציונלי
            } else {
                $errors[] = "שגיאה בעדכון אימייל: " . $conn->error;
            }
            $up->close();
        }
    }

    // 2) שינוי סיסמה (לפי העמודה password אצלך כרגע)
    if ($action === "change_password") {
        $current = $_POST["current_password"] ?? "";
        $newPass = $_POST["new_password"] ?? "";
        $newPass2 = $_POST["new_password2"] ?? "";

        if ($current === "" || $newPass === "" || $newPass2 === "") {
            $errors[] = "יש למלא את כל שדות הסיסמה.";
        } elseif ($newPass !== $newPass2) {
            $errors[] = "הסיסמאות החדשות לא תואמות.";
        } elseif (strlen($newPass) < 6) {
            $errors[] = "הסיסמה החדשה חייבת להכיל לפחות 6 תווים.";
        } elseif ($current !== (string)$user["password"]) {
            $errors[] = "הסיסמה הנוכחית שגויה.";
        }

        if (!$errors) {
            $up = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $up->bind_param("si", $newPass, $userId);

            if ($up->execute()) {
                $successMsg = "הסיסמה עודכנה בהצלחה.";
                $user["password"] = $newPass; // כדי שהבדיקות בהמשך יהיו עקביות
            } else {
                $errors[] = "שגיאה בעדכון סיסמה: " . $conn->error;
            }
            $up->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>פרופיל משתמש | CampusPilot</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
 
  <header class="main-header">
    <h1>הפרופיל שלי</h1>
    <p class="subtitle">צפייה ועדכון פרטים אישיים</p>
  </header>

  <main class="team-container">
    <?php if ($successMsg): ?>
      <div class="card" style="margin-bottom:14px;">
        <p style="margin:0;"><strong><?= h($successMsg) ?></strong></p>
      </div>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="card" style="margin-bottom:14px;">
        <div class="error-box">
          <ul style="margin:0;">
            <?php foreach ($errors as $e): ?>
              <li><?= h($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <!-- כרטיס פרטים -->
    <section class="team-card" style="text-align:right; padding:16px;">
      <h2 style="margin-top:0;">פרטי חשבון</h2>

      <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
        <div><strong>שם משתמש:</strong> <?= h($user["username"]) ?></div>
        <div><strong>תפקיד:</strong> <?= h($user["role"]) ?></div>
        <div style="grid-column: 1 / -1;"><strong>אימייל:</strong> <?= h($user["email"]) ?></div>
      </div>
    </section>

    <!-- עדכון אימייל -->
    <section class="card" style="margin-top:16px;">
      <h2 style="margin-top:0;">עדכון אימייל</h2>

      <form method="POST" action="profile.php">
        <input type="hidden" name="action" value="update_email">
        <div class="form-group">
          <label for="email">אימייל חדש</label>
          <input type="email" id="email" name="email" required value="<?= h($user["email"]) ?>">
        </div>
        <button type="submit" class="primary">שמור אימייל</button>
      </form>
    </section>

    <!-- שינוי סיסמה -->
    <section class="card" style="margin-top:16px;">
      <h2 style="margin-top:0;">שינוי סיסמה</h2>

      <form method="POST" action="profile.php" autocomplete="off">
        <input type="hidden" name="action" value="change_password">

        <div class="form-group">
          <label for="current_password">סיסמה נוכחית</label>
          <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
          <label for="new_password">סיסמה חדשה</label>
          <input type="password" id="new_password" name="new_password" required minlength="6">
        </div>

        <div class="form-group">
          <label for="new_password2">אימות סיסמה חדשה</label>
          <input type="password" id="new_password2" name="new_password2" required minlength="6">
        </div>

        <button type="submit" class="primary">עדכן סיסמה</button>
      </form>

      <p style="margin-top:10px; font-size:0.95em;">
        הערה: כרגע אצלך הסיסמה נשמרת בעמודה <strong>password</strong>. בהמשך נוכל לשדרג ל־<strong>password_hash</strong> בצורה בטוחה.
      </p>
    </section>
  </main>

  <footer class="main-footer">
    © <span id="yearSpan"></span> CampusPilot | כל הזכויות שמורות
  </footer>

  <script src="assets/js/main.js"></script>
</body>
</html>
