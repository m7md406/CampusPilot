<?php
$authPath = __DIR__ . "/auth.php";
if (!file_exists($authPath)) {
    die("auth.php לא נמצא בנתיב: " . $authPath);
}
require_once $authPath;

$logged = function_exists('is_logged_in') ? is_logged_in() : false;

$role = 'Guest';
if (function_exists('current_role')) {
    $role = current_role();
} elseif (function_exists('role')) {
    $role = role();
}


// תפריטים לפי תפקיד
$menuGuest = [
  ["index.html", "בית"],
  ["OurTeam.html", "הצוות שלנו"],
  ["register.php", "הרשמה"],
  ["login.php", "התחברות"],
];

$menuStudent = [
  ["index.html", "בית"],
  ["mainDashboard.php", "לוח בקרה"],
  ["courses.php", "קורסים"],
  ["enroll.php", "רישום לקורסים"],
  ["profile.php", "פרופיל משתמש"],
];

$menuStaff = [
  ["index.html", "בית"],
  ["mainDashboard.php", "לוח בקרה"],
  ["studentsManagment.php", "צפייה בסטודנטים"],
  ["courseManagment.php", "ניהול קורסים"],
  ["enroll.php", "רישום לקורסים"],
  ["profile.php", "פרופיל משתמש"],
];

$menuAdmin = [
  ["index.html", "בית"],
  ["mainDashboard.php", "לוח בקרה"],
  ["studentsManagment.php", "ניהול סטודנטים"],
  ["courseManagment.php", "ניהול קורסים"],
  ["enroll.php", "רישום לקורסים"],
  ["profile.php", "פרופיל משתמש"],
];

$menu = $menuGuest;
if ($logged) {
    if ($role === "Admin") $menu = $menuAdmin;
    elseif ($role === "Staff") $menu = $menuStaff;
    else $menu = $menuStudent;
}
?>
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a class="logo" href="index.html" aria-label="דף הבית">
      <img class="logo-img" src="assets/images/Logo.png" alt="לוגו CampusPilot" />
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="פתח/סגור תפריט">☰</button>

    <ul class="nav-links" id="navLinks">
      <?php foreach ($menu as [$href, $text]): ?>
        <li><a href="<?= htmlspecialchars($href) ?>"><?= htmlspecialchars($text) ?></a></li>
      <?php endforeach; ?>

      <?php if ($logged): ?>
        <li><a href="logout.php">התנתקות</a></li>
      <?php endif; ?>
    </ul>

    <button id="themeToggleBtn" class="nav-btn" type="button" aria-label="החלפת מצב תצוגה">🌓</button>
  </div>
</nav>
