<?php
// 1. חיבור למסד הנתונים (מוודא שהנתיב לקובץ שיצרנו נכון)
include '../includes/db_connect.php';
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/navbar.php";
require_role(["Staff"]);

header('Content-Type: text/html; charset=UTF-8');

// 2. לוגיקה להוספת סטודנט - מתבצעת כשהמשתמש לוחץ על כפתור השליחה
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    // קבלת נתונים מהטופס
    $full_name = $conn->real_escape_string($_POST['name']);
    $student_id = $conn->real_escape_string($_POST['id']);
    $year = $conn->real_escape_string($_POST['year']);
    $department = "כללי"; // ניתן להוסיף שדה כזה בטופס כדי שיהיו 4 שדות כנדרש

    // שאילתת הוספה (INSERT)
    $sql = "INSERT INTO students (full_name, student_external_id, year, department) 
            VALUES ('$full_name', '$student_id', '$year', '$department')";

    if ($conn->query($sql) === TRUE) {
        $message = "הסטודנט נוסף בהצלחה!";
    } else {
        $message = "שגיאה: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ניהול סטודנטים - CampusPilot</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/studentsManagment.js" defer></script>
</head>
<body>



  <header class="main-header">
    <h1>רישום סטודנטים חדשים</h1>
    <p class="subtitle">רישום סטודנט חדש למערכת</p>
    <?php if($message != "") echo "<p style='color: green; font-weight: bold;'>$message</p>"; ?>
  </header>

  <main>
    <div class="search-section">
        <input type="text" id="searchInput" placeholder="חפש לפי שם...">
        <button onclick="searchStudent()">חפש</button>
    </div>

    <hr>

    <h2>הוספת סטודנט</h2>
    <form method="POST" action="studentsManagment.php">
        <input type="text" name="name" placeholder="שם מלא" required>
        <input type="text" name="id" placeholder="תעודת זהות" required>
        <input type="text" name="year" placeholder="שנת לימוד" required>
        <button type="submit" name="add_student">הוסף למערכת</button>
    </form>

    <hr>

    <h2>רשימת סטודנטים (מבסיס הנתונים)</h2>
    <table border="1" id="studentTable" style="width: 100%; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th>שם מלא</th>
                <th>תעודת זהות</th>
                <th>שנת לימוד</th>
                <th>מחלקה</th>
            </tr>
        </thead>
        <tbody id="studentList">
            <?php
            // שליפת הנתונים מהטבלה (SELECT)
            $sql_select = "SELECT * FROM students ORDER BY id DESC";
            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_external_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['department']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>אין סטודנטים רשומים במערכת</td></tr>";
            }
            ?>
        </tbody>
    </table>
  </main>

</body>
</html>