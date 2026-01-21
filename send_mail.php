<?php
// send_mail.php
header('Content-Type: text/html; charset=utf-8');

$to = "artiomg615@gmail.com";
$subject = "פנייה חדשה מטופס יצירת קשר - CampusPilot";

// בסיס אבטחה
function clean($v) {
  return trim(str_replace(["\r", "\n"], " ", $v ?? ""));
}

$fullName = clean($_POST['fullName'] ?? '');
$email    = clean($_POST['email'] ?? '');
$phone    = clean($_POST['phone'] ?? '');
$studentId= clean($_POST['studentId'] ?? '');
$cDate    = clean($_POST['contactDate'] ?? '');
$cTime    = clean($_POST['contactTime'] ?? '');
$pref     = clean($_POST['preferredContact'] ?? '');
$news     = isset($_POST['newsletter']) ? 'כן' : 'לא';
$topic    = clean($_POST['topic'] ?? '');
$message  = trim($_POST['message'] ?? '');

if ($fullName === '' || $email === '' || $message === '' || $topic === '') {
  http_response_code(400);
  echo "שגיאה: חסרים שדות חובה.";
  exit;
}

// תוכן המייל
$bodyText =
"פנייה חדשה התקבלה:\n\n" .
"שם מלא: $fullName\n" .
"אימייל: $email\n" .
"טלפון: $phone\n" .
"מספר סטודנט: $studentId\n" .
"תאריך לחזרה: $cDate\n" .
"שעה לחזרה: $cTime\n" .
"דרך יצירת קשר מועדפת: $pref\n" .
"רוצה עדכונים: $news\n" .
"נושא: $topic\n\n" .
"הודעה:\n$message\n";

// כותרות
$fromHeader = "From: CampusPilot <no-reply@your-domain.com>\r\n";
$replyTo    = "Reply-To: $email\r\n";

// טיפול בקובץ מצורף (אופציונלי)
$hasFile = isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK;

if ($hasFile) {
  $fileTmp  = $_FILES['attachment']['tmp_name'];
  $fileName = basename($_FILES['attachment']['name']);
  $fileType = $_FILES['attachment']['type'] ?: "application/octet-stream";
  $fileData = chunk_split(base64_encode(file_get_contents($fileTmp)));

  $boundary = "==Multipart_Boundary_x" . md5(time()) . "x";

  $headers  = $fromHeader;
  $headers .= $replyTo;
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

  $body  = "--$boundary\r\n";
  $body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
  $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
  $body .= $bodyText . "\r\n\r\n";

  $body .= "--$boundary\r\n";
  $body .= "Content-Type: $fileType; name=\"$fileName\"\r\n";
  $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
  $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
  $body .= $fileData . "\r\n";
  $body .= "--$boundary--";
} else {
  $headers  = $fromHeader;
  $headers .= $replyTo;
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

  $body = $bodyText;
}

$ok = mail($to, "=?UTF-8?B?" . base64_encode($subject) . "?=", $body, $headers);

if ($ok) {
  echo "נשלח בהצלחה! תודה שפנית אלינו.";
} else {
  http_response_code(500);
  echo "שגיאה: לא הצלחנו לשלוח מייל מהשרת.";
}
