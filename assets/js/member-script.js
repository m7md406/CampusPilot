
const form = document.getElementById("memberContactForm");
const result = document.getElementById("formResult");

if (form && result) {
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const message = document.getElementById("message").value.trim();

    if (name.length < 2 || message.length < 5) {
      result.textContent = "נא למלא שם תקין והודעה (לפחות 5 תווים).";
      result.style.color = "crimson";
      return;
    }

    result.textContent = `תודה ${name}! ההודעה נקלטה ונחזור אליך לכתובת: ${email}`;
    result.style.color = "green";

    form.reset();
  });
}
