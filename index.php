<?php include __DIR__ . "/includes/navbar.php"; ?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CampusPilot | דף הבית</title>

  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>

<nav class="navbar" id="navbar">
  <div class="nav-container">

    <a class="logo" href="index.php" aria-label="דף הבית">
      <img class="logo-img" src="assets/images/Logo.png" alt="לוגו פרויקט סדנה" />
    </a>

    <button class="nav-toggle" id="navToggle" aria-label="פתח תפריט">☰</button>

    <button id="themeToggleBtn" class="nav-btn" type="button" aria-label="החלפת מצב תצוגה">🌓</button>
  </div>
</nav>

<header class="main-header">
  <h1>ברוכים הבאים ל-CampusPilot</h1>
  <p class="subtitle">
    מערכת לניהול סטודנטים, קורסים והרשמות — בצורה פשוטה, מודרנית ונוחה.
  </p>
</header>

<main>

  <section class="profile-section">
    <img class="profile-img" src="assets/images/Campusmain.png" alt="תמונת אווירה של לימודים" />

    <div>
      <h2 style="margin-bottom: 10px; color: var(--primary-color);">
        הכל במקום אחד
      </h2>

      <p style="margin-bottom: 14px;">
        התחבר לפי תפקיד (Admin / Staff / Student), צפה בדשבורד עם נתונים כלליים,
        נהלי סטודנטים וקורסים, ובצע הרשמה לקורסים בצורה קלה.
      </p>

      <p style="opacity: 0.85;">
        טיפ: בלחיצה על 🌓 בתפריט אפשר לעבור למצב לילה.
      </p>
    </div>
  </section>

  <section class="video-section" aria-labelledby="videoTitle">
    <h2 id="videoTitle">סרטון על המקום</h2>

    <div class="video-wrapper">
      <iframe
        src="https://www.youtube.com/embed/LlCwHnp3kL4"
        title="סרטון על המקום CampusPilot"
        allow="autoplay; encrypted-media; picture-in-picture"
        allowfullscreen>
      </iframe>
    </div>
  </section>

  <!-- גלריית תמונות -->
  <section class="gallery-section" aria-labelledby="galleryTitle">
    <h2 id="galleryTitle">חיי הקמפוס</h2>

    <div style="display:flex; gap:16px; flex-wrap:wrap; justify-content:center;">
      <img src="assets/images/campus1.jpg" alt="תמונה מהקמפוס" style="width:260px; border-radius:10px;">
      <img src="assets/images/campus2.jpg" alt="סטודנטים בקמפוס" style="width:260px; border-radius:10px;">
    </div>
  </section>

  <section class="contact-section" aria-labelledby="quickActionTitle">
    <h2 id="quickActionTitle">בדיקה מהירה</h2>

    <p style="margin-bottom: 14px; opacity: 0.9;">
      הזן שם סטודנט או ת״ז לבדיקה מהירה.
    </p>

    <form id="quickCheckForm">
      <div class="form-group">
        <label for="studentQuery">שם סטודנט / ת״ז</label>
        <input
          type="text"
          id="studentQuery"
          name="studentQuery"
          placeholder="לדוגמה: Israel Israeli / 123456789"
          required
        />
      </div>

      <button type="submit">בדוק</button>
      <p id="quickCheckResult" style="margin-top: 12px; font-weight: 700;"></p>
    </form>
  </section>

</main>

<footer class="main-footer">
  <div style="max-width: 1000px; margin: 0 auto; padding: 0 16px;">
    <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
      <img src="assets/images/Logo.png" alt="לוגו CampusPilot" style="height: 44px;" />
      <div style="text-align:center; line-height:1.8;">
        <div><strong>יצירת קשר</strong></div>
        <div>מייל: support@CampusPilot.com</div>
        <div>טלפון: 050-3520524</div>
      </div>
      <div style="opacity: 0.75; margin-top: 8px;">
        © <span id="yearSpan"></span> CampusPilot | כל הזכויות שמורות
      </div>
    </div>
  </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
