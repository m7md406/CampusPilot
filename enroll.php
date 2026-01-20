<?php
session_start();
header('Content-Type: text/html; charset=UTF-8');

require_once __DIR__ . "/includes/db_connect.php";
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/navbar.php"; 
require_role(["Admin","Student","Staff"]);


$errors = [];
$successMsg = "";

/** Escape helper */
function h($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}

/** Fetch last 10 enrollments */
function fetchRecentEnrollments($conn) {
    $sql = "
      SELECT e.id,
             e.student_external_id,
             e.student_name,
             c.course_code,
             c.course_name,
             e.semester,
             e.year,
             e.enrolled_at
      FROM enrollments e
      JOIN courses c ON c.id = e.course_id
      ORDER BY e.enrolled_at DESC
      LIMIT 10
    ";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentId   = trim($_POST["studentId"] ?? "");
    $studentName = trim($_POST["studentName"] ?? "");
    $courseCode  = trim($_POST["courseCode"] ?? "");
    $courseName  = trim($_POST["courseName"] ?? "");
    $semester    = $_POST["semester"] ?? "";
    $year        = $_POST["year"] ?? "";
    $confirm     = isset($_POST["confirm"]);

    // Validations
    if ($studentId === "" || !preg_match('/^\d{5,}$/', $studentId)) {
        $errors[] = "מספר סטודנט חייב להכיל ספרות בלבד (לפחות 5).";
    }
    if ($studentName === "") $errors[] = "שם סטודנט הוא שדה חובה.";
    if ($courseCode === "" || !preg_match('/^[A-Za-z0-9]+$/', $courseCode)) {
        $errors[] = "קוד קורס: אותיות/מספרים בלבד.";
    }
    if ($courseName === "") $errors[] = "שם קורס הוא שדה חובה.";

    $allowedSem = ["א'", "ב'", "קיץ"];
    if (!in_array($semester, $allowedSem, true)) $errors[] = "סמסטר לא תקין.";

    if ($year === "" || !ctype_digit($year)) $errors[] = "שנה לא תקינה.";
    $yearInt = (int)$year;
    if ($yearInt < 2020 || $yearInt > 2100) $errors[] = "שנה לא תקינה.";

    if (!$confirm) $errors[] = "יש לאשר שהפרטים נכונים.";

    if (!$errors) {
        // 1) Find course by code (NO get_result -> compatible)
        $courseId = null;

        $stmt = $conn->prepare("SELECT id FROM courses WHERE course_code = ? LIMIT 1");
        $stmt->bind_param("s", $courseCode);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($courseIdDb);

        if ($stmt->num_rows === 1) {
            $stmt->fetch();
            $courseId = (int)$courseIdDb;
        }
        $stmt->close();

        // 2) If course not found -> create it
        if ($courseId === null) {
            $ins = $conn->prepare("INSERT INTO courses (course_code, course_name) VALUES (?, ?)");
            $ins->bind_param("ss", $courseCode, $courseName);

            if (!$ins->execute()) {
                $errors[] = "שגיאה ביצירת קורס: " . $conn->error;
            } else {
                $courseId = (int)$conn->insert_id;
            }
            $ins->close();
        }

        // 3) Insert enrollment (prevent duplicates via UNIQUE)
        if (!$errors && $courseId !== null) {
            $insE = $conn->prepare("
                INSERT INTO enrollments (student_external_id, student_name, course_id, semester, year)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insE->bind_param("ssisi", $studentId, $studentName, $courseId, $semester, $yearInt);

            if ($insE->execute()) {
                $successMsg = "הרישום בוצע בהצלחה!";
                // ריקון טופס אחרי הצלחה (אופציונלי)
                $_POST = [];
            } else {
                if ($conn->errno === 1062) {
                    $errors[] = "הסטודנט כבר רשום לקורס הזה באותו סמסטר/שנה.";
                } else {
                    $errors[] = "שגיאה בביצוע רישום: " . $conn->error;
                }
            }
            $insE->close();
        }
    }
}

$recent = fetchRecentEnrollments($conn);
?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>רישום לקורסים | CampusPilot</title>

  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/enroll.css" />
</head>

<body>
  

  <header class="main-header">
    <h1>רישום סטודנט לקורס</h1>
    <p class="subtitle">רישום נשמר בבסיס הנתונים (MySQL)</p>
  </header>

  <main>
    <section class="card">
      <h2>טופס רישום</h2>

      <?php if ($successMsg): ?>
        <p class="result" style="font-weight:700;"><?= h($successMsg) ?></p>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="error-box">
          <ul style="margin:0;">
            <?php foreach ($errors as $e): ?>
              <li><?= h($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- action="" כדי שלא יהיו 404 אחרי שליחה -->
      <form method="POST" action="" novalidate>
        <div class="form-group">
          <label for="studentId">מספר סטודנט</label>
          <input
            type="text"
            id="studentId"
            name="studentId"
            inputmode="numeric"
            placeholder="לדוגמה: 31456723"
            required
            value="<?= h($_POST["studentId"] ?? "") ?>"
          />
          <small class="hint">ספרות בלבד (לפחות 5 ספרות).</small>
        </div>

        <div class="form-group">
          <label for="studentName">שם סטודנט</label>
          <input
            type="text"
            id="studentName"
            name="studentName"
            placeholder="שם מלא"
            required
            value="<?= h($_POST["studentName"] ?? "") ?>"
          />
        </div>

        <div class="form-group">
          <label for="courseCode">קוד קורס</label>
          <input
            type="text"
            id="courseCode"
            name="courseCode"
            placeholder="לדוגמה: CS101"
            required
            value="<?= h($_POST["courseCode"] ?? "") ?>"
          />
          <small class="hint">אותיות/מספרים בלבד.</small>
        </div>

        <div class="form-group">
          <label for="courseName">שם קורס</label>
          <input
            type="text"
            id="courseName"
            name="courseName"
            placeholder="לדוגמה: מבוא לתכנות"
            required
            value="<?= h($_POST["courseName"] ?? "") ?>"
          />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="semester">סמסטר</label>
            <select id="semester" name="semester" required>
              <option value="" disabled <?= empty($_POST["semester"]) ? "selected" : "" ?>>בחר סמסטר</option>
              <option value="א'" <?= (($_POST["semester"] ?? "") === "א'") ? "selected" : "" ?>>א'</option>
              <option value="ב'" <?= (($_POST["semester"] ?? "") === "ב'") ? "selected" : "" ?>>ב'</option>
              <option value="קיץ" <?= (($_POST["semester"] ?? "") === "קיץ") ? "selected" : "" ?>>קיץ</option>
            </select>
          </div>

          <div class="form-group">
            <label for="year">שנה</label>
            <select id="year" name="year" required>
              <option value="" disabled <?= empty($_POST["year"]) ? "selected" : "" ?>>בחר שנה</option>
              <?php foreach ([2025, 2026, 2027] as $y): ?>
                <option value="<?= $y ?>" <?= (($_POST["year"] ?? "") == (string)$y) ? "selected" : "" ?>><?= $y ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group checkbox-group">
          <input type="checkbox" id="confirm" name="confirm" <?= isset($_POST["confirm"]) ? "checked" : "" ?> />
          <label for="confirm">אני מאשר/ת שהפרטים נכונים</label>
        </div>

        <div class="actions">
          <button type="submit" class="primary">בצע רישום</button>
          <a class="secondary" href="enroll.php" style="display:inline-block; text-decoration:none; padding:10px 14px; border-radius:10px;">נקה</a>
        </div>
      </form>
    </section>

    <section class="card" style="margin-top:16px;">
      <h2>רישומים אחרונים</h2>
      <p class="subtitle-small">10 האחרונים מה-DB</p>

      <?php if (!$recent): ?>
        <p>אין עדיין רישומים.</p>
      <?php else: ?>
        <ul class="enroll-list">
          <?php foreach ($recent as $r): ?>
            <li>
              <strong><?= h($r["student_name"]) ?></strong> (<?= h($r["student_external_id"]) ?>)
              — <?= h($r["course_code"]) ?> / <?= h($r["course_name"]) ?>
              — סמסטר <?= h($r["semester"]) ?> <?= h($r["year"]) ?>
              <br>
              <small><?= h($r["enrolled_at"]) ?></small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>
  </main>

  <footer class="main-footer">
    <p>© 2026 CampusPilot</p>
  </footer>

  <script src="assets/js/main.js"></script>
</body>
</html>
