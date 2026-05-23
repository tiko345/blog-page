const form = document.getElementById("subbtn").closest("form");
const emailInput = document.getElementById("email");
const errorMsg = document.getElementById("errorMsg");

form.addEventListener("submit", (e) => {
    e.preventDefault();

    const email = emailInput.value.trim();
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === "") {
        showMessage("Please enter your email address.");
    } else if (!pattern.test(email)) {
        showMessage("Please enter a valid email address.");
    } else {
        showMessage("Thank you for subscribing!");
        emailInput.value = "";
    }
});

function showMessage(msg) {
    errorMsg.textContent = msg;
    errorMsg.style.animation = "none";
    void errorMsg.offsetWidth;
    errorMsg.style.animation = "fadeIn 0.8s ease forwards";
}
