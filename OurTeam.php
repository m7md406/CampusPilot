
<?php include __DIR__ . "/includes/navbar.php"; ?>
<!doctype html>
<html lang="he" dir="rtl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>הצוות שלנו | CampusPilot</title>

  <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body>

  <header class="main-header">
    <h1>ברוכים הבאים ל-CampusPilot</h1>
    <p class="subtitle">
      מערכת לניהול סטודנטים, קורסים והרשמות — בצורה פשוטה, מודרנית ונוחה.
    </p>
  </header>
<main>
<section class="team-container">
  <div class="team-grid">

    <div class="team-card">
      <img src="./assets/images/Or.png" alt="אור עדני" />
      <h3>אור עדני</h3>
      <span class="role">מפתחת Full Stack ואחראית הצוות</span>
      <a class="view-profile-btn" href="./personalHtml/OrAdani.html">לפרופיל האישי</a>
    </div>

    <div class="team-card">
      <img src="./assets/images/Artiom.jpg" alt="ארטיום גרינברג" />
      <h3>ארטיום גרינברג</h3>
      <span class="role">מפתח Full Stack</span>
      <a class="view-profile-btn" href="./personalHtml/ArtiomGrinberg.html">לפרופיל האישי</a>
    </div>

    <div class="team-card">
      <img src="./assets/images/Muhammad.jpeg" alt="מוחמד זידאן" />
      <h3>מוחמד זידאן</h3>
      <span class="role">מפתח Full Stack</span>
      <a class="view-profile-btn" href="./personalHtml/MuhammadZedan.html">לפרופיל האישי</a>
    </div>

    <div class="team-card">
      <img src="./assets/images/Raanan.jpeg" alt="רענן נאסר אלדין" />
      <h3>רענן נאסר אלדין</h3>
      <span class="role">מפתח Full Stack</span>
      <a class="view-profile-btn" href="./personalHtml/RaananNaserAldin.html">לפרופיל האישי</a>
    </div>

  </div>
</section>
<section class="contact-section">
  <h2>יצירת קשר עם אחראית הצוות</h2>
  <p>יש שאלה או הערה? אפשר להשאיר הודעה ונחזור אליכם.</p>

 <form id="contactForm"
      action="mailto:ortalia2108@gmail.com"
      method="post"
      enctype="text/plain"
      autocomplete="on">

  <fieldset style="border: 1px solid #ddd; padding: 15px; border-radius: 12px;">
    <legend style="padding: 0 10px;">טופס יצירת קשר</legend>

    <!-- input #1: text -->
    <div class="form-group">
      <label for="fullName">שם מלא</label>
      <input type="text" id="fullName" name="fullName" required />
    </div>

    <!-- input #2: email -->
    <div class="form-group">
      <label for="email">אימייל</label>
      <input type="email" id="email" name="email" required />
    </div>

    <!-- input #3: tel -->
    <div class="form-group">
      <label for="phone">טלפון</label>
      <input type="tel" id="phone" name="phone" placeholder="050-0000000" />
    </div>

    <!-- input: number -->
    <div class="form-group">
      <label for="studentId">מספר סטודנט</label>
      <input type="number" id="studentId" name="studentId" min="1" step="1" />
    </div>

    <!-- input: date -->
    <div class="form-group">
      <label for="contactDate">תאריך לחזרה</label>
      <input type="date" id="contactDate" name="contactDate" />
    </div>

    <!-- input: time -->
    <div class="form-group">
      <label for="contactTime">שעה לחזרה</label>
      <input type="time" id="contactTime" name="contactTime" />
    </div>

    <!-- radio -->
    <div class="form-group">
      <p style="margin: 0 0 8px;">דרך יצירת קשר מועדפת</p>
      <label style="margin-left: 12px;">
        <input type="radio" name="preferredContact" value="email" checked />
        אימייל
      </label>
      <label>
        <input type="radio" name="preferredContact" value="phone" />
        טלפון
      </label>
    </div>

    <!-- checkbox -->
    <div class="form-group">
      <label>
        <input type="checkbox" name="newsletter" value="yes" />
        אני רוצה לקבל עדכונים
      </label>
    </div>

    <!-- select -->
    <div class="form-group">
      <label for="topic">נושא הפנייה</label>
      <select id="topic" name="topic" required>
        <option value="" selected disabled>בחרו נושא</option>
        <option value="students">ניהול סטודנטים</option>
        <option value="courses">ניהול קורסים</option>
        <option value="enroll">רישום לקורסים</option>
        <option value="other">אחר</option>
      </select>
    </div>

    <!-- textarea -->
    <div class="form-group">
      <label for="message">הודעה</label>
      <textarea id="message" name="message" rows="4" required></textarea>
    </div>

    <!-- file -->
    <div class="form-group">
      <label for="attachment">צירוף קובץ (אופציונלי)</label>
      <input type="file" id="attachment" name="attachment" />
    </div>

    <!-- buttons -->
    <div class="form-group" style="display:flex; gap:10px; flex-wrap:wrap;">
      <button type="submit">שליחה</button>
      <input type="reset" value="ניקוי טופס" />
    </div>

    <p id="status" aria-live="polite"></p>
  </fieldset>
</form>

  <div id="leadBox" style="margin-top:20px;">
    <h3>אחראית הצוות</h3>
    <ul>
      <li><strong>שם:</strong> אור טל עדני</li>
      <li><strong>מייל:</strong> <a href="mailto:ortalia2108@gmail.com">ortalia2108@gmail.com</a></li>
      <li><strong>טלפון:</strong> 050-3520524</li>
    </ul>
  </div>
</section>

</main>
<footer class="main-footer">
  © <span id="yearSpan"></span> CampusPilot | כל הזכויות שמורות
</footer>

<script src="./assets/js/main.js"></script>
</body>
</html>
