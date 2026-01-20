const form = document.getElementById("enrollForm");
const resultMsg = document.getElementById("resultMsg");
const clearBtn = document.getElementById("clearBtn");

const themeToggleBtn = document.getElementById("themeToggleBtn");

const enrollList = document.getElementById("enrollList");
const clearHistoryBtn = document.getElementById("clearHistoryBtn");

const STORAGE_KEY = "campusPilot_enrollments";

function setResult(text, ok) {
  resultMsg.textContent = text;
  resultMsg.style.color = ok ? "green" : "red";
}

function isDigitsOnly(s) {
  return /^[0-9]+$/.test(s);
}

function isAlnumOnly(s) {
  return /^[A-Za-z0-9]+$/.test(s);
}

function loadEnrollments() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    return raw ? JSON.parse(raw) : [];
  } catch {
    return [];
  }
}

function saveEnrollments(items) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
}

function renderEnrollments() {
  const items = loadEnrollments();
  enrollList.innerHTML = "";

  if (items.length === 0) {
    const li = document.createElement("li");
    li.textContent = "אין רישומים עדיין.";
    enrollList.appendChild(li);
    return;
  }

  for (const it of items.slice(-8).reverse()) {
    const li = document.createElement("li");
    li.textContent = `${it.studentName} (${it.studentId}) → ${it.courseName} [${it.courseCode}] | סמסטר ${it.semester} ${it.year}`;
    enrollList.appendChild(li);
  }
}

form.addEventListener("submit", (e) => {
  e.preventDefault();

  const studentId = document.getElementById("studentId").value.trim();
  const studentName = document.getElementById("studentName").value.trim();
  const courseCode = document.getElementById("courseCode").value.trim();
  const courseName = document.getElementById("courseName").value.trim();
  const semester = document.getElementById("semester").value;
  const year = document.getElementById("year").value;
  const confirm = document.getElementById("confirm").checked;

  // ולידציה
  if (!studentId || !studentName || !courseCode || !courseName || !semester || !year) {
    setResult("אנא מלא/י את כל השדות.", false);
    return;
  }

  if (studentId.length < 5 || !isDigitsOnly(studentId)) {
    setResult("מספר סטודנט חייב להיות ספרות בלבד ובאורך מינימלי של 5.", false);
    return;
  }

  if (!isAlnumOnly(courseCode)) {
    setResult("קוד קורס חייב להכיל אותיות באנגלית ומספרים בלבד (ללא רווחים/סימנים).", false);
    return;
  }

  if (!confirm) {
    setResult("כדי לבצע רישום, יש לסמן אישור שהפרטים נכונים.", false);
    return;
  }

  // “רישום” מקומי (דמו)
  const items = loadEnrollments();
  items.push({
    studentId,
    studentName,
    courseCode,
    courseName,
    semester,
    year,
    createdAt: new Date().toISOString(),
  });
  saveEnrollments(items);

  setResult(`בוצע רישום: ${studentName} נוסף/ה לקורס ${courseName} (${courseCode}).`, true);
  form.reset();
  renderEnrollments();
});

// ניקוי טופס
clearBtn.addEventListener("click", () => {
  form.reset();
  setResult("", true);
});

// מצב לילה
themeToggleBtn.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  themeToggleBtn.textContent = document.body.classList.contains("dark-mode") ? "מצב רגיל" : "מצב לילה";
});

// מחיקת היסטוריה
clearHistoryBtn.addEventListener("click", () => {
  localStorage.removeItem(STORAGE_KEY);
  renderEnrollments();
  setResult("ההיסטוריה נמחקה.", true);
});

// אתחול
renderEnrollments();
