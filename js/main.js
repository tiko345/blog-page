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



// Dark mode toggle

const toggle= document.getElementById("theme-toggle");

toggle.addEventListener("click", () => {
    const html = document.documentElement;
    const isDark= html.getAttribute("data-theme") === "dark";

    html.setAttribute("data-theme", isDark ? "light" : "dark");
    toggle.textContent = isDark ? "◑" : "☀";
});