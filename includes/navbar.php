<?php
require_once __DIR__ . "/auth.php";

$logged = function_exists('is_logged_in') ? is_logged_in() : false;
$role = function_exists('current_role') ? current_role() : 'Guest';

$menuGuest = [
  ["index.php", "בית"],
  ["OurTeam.php", "הצוות שלנו"],
  ["register.php", "הרשמה"],
  ["login.php", "התחברות"],
];

$menuStudent = [
  ["index.php", "בית"],
  ["mainDashboard.php", "לוח בקרה"],
  ["enroll.php", "רישום לקורסים"],
  ["profile.php", "פרופיל משתמש"],
];

$menuStaff = [
  ["index.php", "בית"],
  ["mainDashboard.php", "לוח בקרה"],
  ["studentsManagment.php", "צפייה בסטודנטים"],
  ["courseManagment.php", "ניהול קורסים"],
  ["profile.php", "פרופיל משתמש"],
];

$menuAdmin = [
  ["index.php", "בית"],
  ["mainDashboard.php", "לוח בקרה"],
  ["studentsManagment.php", "ניהול סטודנטים"],
  ["courseManagment.php", "ניהול קורסים"],
  ["profile.php", "פרופיל משתמש"],
];

$menu = $menuGuest;
if ($logged) {
  if ($role === "Admin") $menu = $menuAdmin;
  elseif ($role === "Staff") $menu = $menuStaff;
  else $menu = $menuStudent;
}
?>

<nav class="navbar">
  <div class="nav-container">

    <a href="index.php" class="logo">
      <img src="assets/images/Logo.png" class="logo-img" alt="CampusPilot">
    </a>

    <button class="nav-toggle" id="navToggle">☰</button>

    <ul class="nav-links" id="navLinks">
      <?php foreach ($menu as [$href, $text]): ?>
        <li><a href="<?= $href ?>"><?= $text ?></a></li>
      <?php endforeach; ?>

      <?php if ($logged): ?>
        <li><a href="logout.php">התנתקות</a></li>
      <?php endif; ?>
    </ul>

    <button id="themeToggleBtn">🌓</button>

  </div>
</nav>
