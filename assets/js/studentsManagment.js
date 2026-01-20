let students = [];

function addStudent() {
    let name = document.getElementById("name").value;
    let id = document.getElementById("id").value;
    let year = document.getElementById("year").value;

    students.push({ name, id, year });
    displayStudents();
}

function displayStudents() {
    let list = document.getElementById("studentList");
    list.innerHTML = "";

    students.forEach((student, index) => {
        list.innerHTML += `
            <li>
            <span>
            ${student.name} : ${student.id} ---- Year: ${student.year}
            </span>
            <div>
            <button onclick="viewDetails(${index})">Details</button>
            <button onclick="editStudent(${index})">Edit</button>
            <button onclick="deleteStudent(${index})">Delete</button>
            </div>
            </li>

        `;
    });
}


function searchStudent() {
    let query = document.getElementById("searchInput").value.toLowerCase();
    let list = document.getElementById("studentList");
    list.innerHTML = "";

    students
        .filter(s => s.name.toLowerCase().includes(query))
        .forEach(student => {
            list.innerHTML += `<li>${student.name}</li>`;
        });
}

function viewDetails(index) {
    localStorage.setItem("student", JSON.stringify(students[index]));
    window.location.href = "student-details.html";
}

function deleteStudent(index) {
    students.splice(index, 1);
    displayStudents();
}

function editStudent(index) {
    let newName = prompt("Enter new name:", students[index].name);
    let newId = prompt("Enter new ID:", students[index].id);
    let newYear = prompt("Enter new year:", students[index].year);

    if (newName && newId && newYear) {
        students[index].name = newName;
        students[index].id = newId;
        students[index].year = newYear;
        displayStudents();
    }
}

