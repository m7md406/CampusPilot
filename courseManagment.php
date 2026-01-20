<?php
require_once __DIR__ . "/includes/db_connect.php";
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/navbar.php";
require_role(["Staff"]);


// שליפת קורסים
$sql = "SELECT id, course_code, course_name, created_at FROM courses ORDER BY id DESC";
$res = $conn->query($sql);

$courses = [];
if ($res) {
    $courses = $res->fetch_all(MYSQLI_ASSOC);
}

function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ניהול קורסים | CampusPilot</title>

  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    .wrap { max-width: 1000px; margin: 30px auto; padding: 0 12px; }
    .card { background:#fff; padding:16px; border-radius:10px; box-shadow:0 6px 16px rgba(0,0,0,.08); margin-bottom: 14px; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:10px; border-bottom:1px solid #e5e7eb; text-align:right; }
    th { background:#f1f5f9; }
    .btn { display:inline-block; padding:8px 12px; border-radius:8px; text-decoration:none; }
    .btn-primary { background:#2563eb; color:#fff; }
    .muted { color:#6b7280; }
  </style>
</head>
<body>



  <div class="wrap">

    <div class="card">
      <h1>ניהול קורסים</h1>
      <p class="muted">רשימת קורסים מתוך מסד הנתונים</p>

      <a class="btn btn-primary" href="addCourse.php">+ הוספת קורס</a>
      <!-- אם אין לך עדיין addCourse.php, אפשר ליצור -->
    </div>

    <div class="card">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>קוד</th>
            <th>שם קורס</th>
            <th>נוצר בתאריך</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($courses) === 0): ?>
            <tr>
              <td colspan="4" class="muted">אין עדיין קורסים בטבלה.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($courses as $c): ?>
              <tr>
                <td><?= h($c["id"]) ?></td>
                <td><?= h($c["course_code"]) ?></td>
                <td><?= h($c["course_name"]) ?></td>
                <td><?= h($c["created_at"]) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>

  <script src="assets/js/main.js"></script>
</body>
</html>
