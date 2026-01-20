<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/navbar.php";


require_login();

header('Content-Type: text/html; charset=UTF-8');

// אם אין משתמש מחובר
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION["role"] ?? "Student";
$username = $_SESSION["username"] ?? "משתמש";

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}

// הגדרה של כרטיסים/פעולות לפי תפקיד
$cards = [];

if ($role === "Admin") {
    $headline = "דשבורד מנהל מערכת";
    $subtitle = "שליטה מלאה במערכת CampusPilot";
    $cards = [
        ["title" => "ניהול סטודנטים", "desc" => "צפייה, הוספה, עריכה ומחיקה של סטודנטים.", "href" => "studentsManagment.php"],
        ["title" => "ניהול קורסים", "desc" => "יצירה ועריכת קורסים, פתיחת סמסטרים.", "href" => "courses.html"],
        ["title" => "ניהול הרשמות", "desc" => "בדיקת הרשמות לקורסים ואישורים.", "href" => "enroll.html"],
        ["title" => "ניהול משתמשים", "desc" => "ניהול הרשאות ותפקידים (Admin/Staff/Student).", "href" => "#"],
        ["title" => "דוחות", "desc" => "סטטיסטיקות ודו\"חות פעילות במערכת.", "href" => "#"],
    ];
} elseif ($role === "Staff") {
    $headline = "דשבורד סגל";
    $subtitle = "כלי ניהול קורסים ומעקב אחרי סטודנטים";
    $cards = [
        ["title" => "ניהול קורסים", "desc" => "צפייה בקורסים, עדכון פרטים והגדרות.", "href" => "courses.html"],
        ["title" => "רשימות סטודנטים", "desc" => "צפייה בסטודנטים הרשומים לקורסים שלך.", "href" => "studentsManagment.php"],
        ["title" => "בדיקת הרשמות", "desc" => "אישור/דחייה של בקשות הרשמה במידת הצורך.", "href" => "enroll.html"],
        ["title" => "דוחות קורס", "desc" => "סיכום הרשמות וסטטוסי סטודנטים.", "href" => "#"],
    ];
} else { // Student
    $headline = "דשבורד סטודנט";
    $subtitle = "ניהול פרופיל, קורסים והרשמות";
    $cards = [
        ["title" => "פרופיל משתמש", "desc" => "עדכון פרטים אישיים והגדרות.", "href" => "profile.php"],
        ["title" => "רישום לקורסים", "desc" => "בחירת קורסים והרשמה מהירה.", "href" => "enroll.php"],
       // ["title" => "הקורסים שלי", "desc" => "צפייה בקורסים אליהם נרשמת ובסטטוס.", "href" => "courseManagment.php"],
        ["title" => "תמיכה / יצירת קשר", "desc" => "פנייה לתמיכה או לצוות המערכת.", "href" => "OurTeam.html"],
    ];
}
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | CampusPilot</title>

  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>

  <header class="main-header">
    <h1><?= h($headline) ?></h1>
    <p class="subtitle"><?= h($subtitle) ?></p>
  </header>

  <main class="team-container">
    <div class="card" style="margin-bottom:16px;">
      <h2 style="margin:0 0 6px 0;">שלום, <?= h($username) ?> 👋</h2>
      <p style="margin:0;">תפקיד: <strong><?= h($role) ?></strong></p>
    </div>

    <section class="team-grid">
      <?php foreach ($cards as $c): ?>
        <div class="team-card" style="text-align:right;">
          <h3><?= h($c["title"]) ?></h3>
          <p style="margin:8px 0 12px 0;"><?= h($c["desc"]) ?></p>
          <a class="view-profile-btn" href="<?= h($c["href"]) ?>">כניסה</a>
        </div>
      <?php endforeach; ?>
    </section>
  </main>

  <footer class="main-footer">
    © <span id="yearSpan"></span> CampusPilot | כל הזכויות שמורות
  </footer>

  <script src="assets/js/main.js"></script>
</body>
</html>
