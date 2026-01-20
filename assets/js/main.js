
const navbar = document.getElementById("navbar");
const navToggle = document.getElementById("navToggle");

if (navbar && navToggle) {
  navToggle.addEventListener("click", () => {
    navbar.classList.toggle("open");
  });

  const navLinks = navbar.querySelectorAll(".nav-links a");
  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth <= 768) {
        navbar.classList.remove("open");
      }
    });
  });

  document.addEventListener("click", (e) => {
    const clickedInsideNavbar = navbar.contains(e.target);
    if (!clickedInsideNavbar) {
      navbar.classList.remove("open");
    }
  });
}

const themeToggleBtn = document.getElementById("themeToggleBtn");
const THEME_KEY = "studenthub_theme"; 

function applyTheme(theme) {
  if (theme === "dark") {
    document.body.classList.add("dark-mode");
  } else {
    document.body.classList.remove("dark-mode");
  }
}

(function initTheme() {
  const saved = localStorage.getItem(THEME_KEY);
  if (saved === "dark" || saved === "light") {
    applyTheme(saved);
  } else {
    applyTheme("light");
  }
})();

if (themeToggleBtn) {
  themeToggleBtn.addEventListener("click", () => {
    const isDark = document.body.classList.contains("dark-mode");
    const nextTheme = isDark ? "light" : "dark";
    applyTheme(nextTheme);
    localStorage.setItem(THEME_KEY, nextTheme);
  });
}

const quickCheckForm = document.getElementById("quickCheckForm");
const studentQueryInput = document.getElementById("studentQuery");
const quickCheckResult = document.getElementById("quickCheckResult");

if (quickCheckForm && studentQueryInput && quickCheckResult) {
  quickCheckForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const value = studentQueryInput.value.trim();

    if (value.length < 2) {
      quickCheckResult.textContent = "נא להזין לפחות 2 תווים.";
      quickCheckResult.style.color = "crimson";
      return;
    }

    const isDigitsOnly = /^[0-9]+$/.test(value);

    if (isDigitsOnly) {
      quickCheckResult.textContent = `התקבל מספר ת״ז: ${value}. (במערכת אמיתית נבצע חיפוש במסד נתונים)`;
    } else {
      quickCheckResult.textContent = `התקבל שם לחיפוש: "${value}". (במערכת אמיתית נציג תוצאות מתאימות)`;
    }

    quickCheckResult.style.color = "green";
  });
}

const yearSpan = document.getElementById("yearSpan");
if (yearSpan) {
  yearSpan.textContent = new Date().getFullYear();
}
