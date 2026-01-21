let students = [];
console.log("studentsManagment.js loaded");
window.searchStudent = function () {
  const query = document.getElementById("searchInput").value.trim().toLowerCase();
  const tbody = document.getElementById("studentList");
  const rows = tbody.querySelectorAll("tr");

  rows.forEach(row => {
    const nameCell = row.querySelector("td:nth-child(1)"); // שם מלא
    const idCell   = row.querySelector("td:nth-child(2)"); // ת"ז

    const name = (nameCell?.textContent || "").trim().toLowerCase();
    const sid  = (idCell?.textContent || "").trim().toLowerCase();

    // חיפוש לפי שם או ת"ז
    const match = name.includes(query) || sid.includes(query);

    row.style.display = match ? "" : "none";
  });
};




