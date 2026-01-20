<?php
// הגדרות שרת (מתאים לרוב שרתי האחסון החינמיים כמו Byethost)
$servername = "sql305.byethost33.com"; // יש להחליף בכתובת השרת שלכם [cite: 42]
$username = "b33_40473766"; 
$password = "123456";
$dbname = "b33_40473766_CampusPilot";

// יצירת חיבור
$conn = new mysqli($servername, $username, $password, $dbname);


// בדיקת חיבור
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// הגדרת קידוד לתמיכה בעברית
$conn->set_charset("utf8mb4");
?>