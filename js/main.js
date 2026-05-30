const subBtn = document.getElementById("subbtn"); 
if(subBtn){
    const form = subBtn.closest("form");
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
}



// Dark mode toggle

const toggle= document.getElementById("theme-toggle");

if(toggle){
    toggle.addEventListener("click", () => {
        const html = document.documentElement;
        const isDark= html.getAttribute("data-theme") === "dark";

        html.setAttribute("data-theme", isDark ? "light" : "dark");
        toggle.textContent = isDark ? "◑" : "☀";
    });
}

//menu toggle for mobile version

const menuToggle = document.getElementById("menu-toggle");
const nav = document.querySelector(".nav");

if(menuToggle){
    menuToggle.addEventListener("click", () => {
        nav.classList.toggle("open");
        menuToggle.textContent = nav.classList.contains("open") ? "✕" : "☰";
    });
}


//register forms 

const signinTab = document.getElementById("signin-tab");
const registerTab = document.getElementById("register-tab");
const signinForm = document.getElementById("signin-form");
const registerForm = document.getElementById("register-form");
const resetForm = document.getElementById("reset-form");
const forgotLink = document.querySelector(".forgotpass");
const authTabs=document.querySelector(".auth-tabs");
const allTitles = ["signin-title", "register-title", "reset-password"];
const allSpans = ["signin-span", "register-span", "reset-span"];
const googleBtn = document.querySelector(".google");
const googleSpan = document.querySelector(".google-span");

function showForm(formToShow, titleId, spanId, showTabs){
    //hide all forms
    signinForm.classList.add("hidden");
    registerForm.classList.add("hidden");
    resetForm.classList.add("hidden");
    
    //hide all titles and spans
    allTitles.forEach(id => document.getElementById(id).classList.add("hidden"));
    allSpans.forEach(id => document.getElementById(id).classList.add("hidden"));

    //show the right ones
    formToShow.classList.remove("hidden");
    document.getElementById(titleId).classList.remove("hidden");
    document.getElementById(spanId).classList.remove("hidden");

    authTabs.classList.toggle("hidden", !showTabs);

    // google elements
    googleBtn.classList.toggle("hidden", !showTabs);
    googleSpan.classList.toggle("hidden", !showTabs);
}


signinTab.addEventListener("click", () => {
    showForm(signinForm, "signin-title", "signin-span", true);
    signinTab.classList.add("active");
    registerTab.classList.remove("active");
});

registerTab.addEventListener("click", () => {
    showForm(registerForm, "register-title", "register-span", true);
    registerTab.classList.add("active");
    signinTab.classList.remove("active");
});

forgotLink.addEventListener("click", () => {
    console.log("clicked");

    showForm(resetForm, "reset-password", "reset-span", false);

    console.log(googleBtn);
    console.log(googleSpan);
});

document.querySelector(".back-to-login").addEventListener("click", () => {
    showForm(signinForm, "signin-title", "signin-span", true);
    signinTab.classList.add("active");
    registerTab.classList.remove("active");
});