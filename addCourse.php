<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . "/includes/db_connect.php";
<?php require_once __DIR__ . "/includes/navbar.php"; ?>


$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $courseCode = trim($_POST["course_code"] ?? "");
    $courseName = trim($_POST["course_name"] ?? "");

    if ($courseCode === "") {
        $errors[] = "יש להזין קוד קורס.";
    }
    if ($courseName === "") {
        $errors[] = "יש להזין שם קורס.";
    }

    // בדיקת כפילות קוד קורס
    if (!$errors) {
        $stmt = $conn->prepare("SELECT id FROM courses WHERE course_code = ? LIMIT 1");
        $stmt->bind_param("s", $courseCode);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "קורס עם קוד זה כבר קיים.";
        }
        $stmt->close();
    }

    // שמירה
    if (!$errors) {
        $stmt = $conn->prepare(
            "INSERT INTO courses (course_code, course_name) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $courseCode, $courseName);

        if ($stmt->execute()) {
            $success = true;
            $_POST = []; // ניקוי הטופס
        } else {
            $errors[] = "שגיאה בשמירה למסד הנתונים.";
        }
        $stmt->close();
    }
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
  <title>הוספת קורס | CampusPilot</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <nav class="navbar">
    <div class="nav-container">
      <a class="logo" href="index.html">
        <img class="logo-img" src="assets/images/Logo.png" alt="CampusPilot" />
      </a>
    </div>
  </nav>

  <main class="card" style="max-width:520px; margin:40px auto;">
    <h1>הוספת קורס</h1>

    <?php if ($success): ?>
      <p class="result" style="font-weight:700;">✅ הקורס נוסף בהצלחה</p>
      <a href="courseManagment.php" class="primary" style="display:inline-block; margin-top:10px;">
        חזרה לניהול קורסים
      </a>
    <?php endif; ?>

    <?php if ($errors): ?>
      <div class="error-box">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= h($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="form-group">
        <label for="course_code">קוד קורס</label>
        <input
          type="text"
          id="course_code"
          name="course_code"
          required
          value="<?= h($_POST["course_code"] ?? "") ?>"
        />
      </div>

      <div class="form-group">
        <label for="course_name">שם קורס</label>
        <input
          type="text"
          id="course_name"
          name="course_name"
          required
          value="<?= h($_POST["course_name"] ?? "") ?>"
        />
      </div>

      <button type="submit" class="primary">שמור קורס</button>
      <a href="courseManagment.php" class="secondary" style="margin-right:8px;">ביטול</a>
    </form>
  </main>
</body>
</html>
