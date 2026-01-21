<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

require_once __DIR__ . "/includes/db_connect.php";
require_once __DIR__ . "/includes/auth.php";

require_role(["Staff"]);

/* Escape */
function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}

/* שליפת סיכום הרשמות לפי קורס */
$sql = "
SELECT 
    c.course_code,
    c.course_name,
    COUNT(e.id) AS total_enrollments
FROM courses c
LEFT JOIN enrollments e ON e.course_id = c.id
GROUP BY c.id
ORDER BY total_enrollments DESC
";

$res = $conn->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>דוחות | CampusPilot</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php require_once __DIR__ . "/includes/navbar.php"; ?>

<header class="main-header">
  <h1>דוחות קורסים</h1>
  <p class="subtitle">סיכום הרשמות סטודנטים לקורסים</p>
</header>

<main class="card">
  <h2>סיכום הרשמות</h2>

  <?php if (!$rows): ?>
    <p>אין נתונים להצגה.</p>
  <?php else: ?>
    <table style="width:100%; border-collapse:collapse;">
      <thead>
        <tr>
          <th style="text-align:right;">קוד קורס</th>
          <th style="text-align:right;">שם קורס</th>
          <th style="text-align:right;">כמות נרשמים</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= h($r["course_code"]) ?></td>
            <td><?= h($r["course_name"]) ?></td>
            <td><?= h($r["total_enrollments"]) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</main>

<script src="assets/js/main.js"></script>
</body>
</html>
