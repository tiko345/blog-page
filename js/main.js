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
const savedTheme = localStorage.getItem("theme");

if (savedTheme) {
    document.documentElement.setAttribute("data-theme", savedTheme);
}
if(toggle){
     toggle.textContent =
        document.documentElement.getAttribute("data-theme") === "dark"
            ? "☀"
            : "◑";

    toggle.addEventListener("click", () => {
        const html = document.documentElement;
        const isDark = html.getAttribute("data-theme") === "dark";

        const newTheme = isDark ? "light" : "dark";

        html.setAttribute("data-theme", newTheme);

        // Save choice
        localStorage.setItem("theme", newTheme);

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

if (signinTab) {
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
}


//dashboard navigation 
const navItems = document.querySelectorAll('.dashboard-aside .nav-item')
if (navItems.length > 0) {
    const sections = document.querySelectorAll('.dashboard-content .section')

    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault()

            // remove active from all
            navItems.forEach(n => n.classList.remove('active'))
            sections.forEach(s => s.classList.remove('active'))

            // add active to clicked item and matching section
            item.classList.add('active')
            const sectionId = item.getAttribute('data-section')
            document.getElementById(sectionId).classList.add('active')
        })
    });
}

//redirects the new article button to the upload section of the dashboard
const newArticleBtn = document.querySelector('.new-article')
if (newArticleBtn) {
    newArticleBtn.addEventListener('click', function(e) {
        e.preventDefault()
        document.querySelectorAll('.section').forEach(s => s.classList.remove('active'))
        document.getElementById('upload').classList.add('active')
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'))
        document.querySelector('.nav-item[data-section="upload"]').classList.add('active')
    })
}


//filter users in admin dashboard
const userSearch = document.getElementById('user-search')
const roleFilter = document.getElementById('role-filter')

function filterUsers() {
    const search = userSearch.value.toLowerCase()
    const role = roleFilter.value

    document.querySelectorAll('.user-row-table').forEach(row => {
        const name = row.getAttribute('data-name')
        const email = row.getAttribute('data-email')
        const userRole = row.getAttribute('data-role')

        const matchesSearch = name.includes(search) || email.includes(search)
        const matchesRole = role === '' || userRole === role

        row.style.display = matchesSearch && matchesRole ? '' : 'none'
    })
}

if (userSearch) {
    userSearch.addEventListener('input', filterUsers)
    roleFilter.addEventListener('change', filterUsers)
}



//write note modal
function openNote(userId, existingNote) {
    document.getElementById('note-user-id').value = userId
    document.getElementById('note-textarea').value = existingNote
    document.getElementById('note-modal').classList.remove('hidden')
}

function closeNote() {
    document.getElementById('note-modal').classList.add('hidden')
}

// close on backdrop click
const noteModal = document.getElementById('note-modal')
if (noteModal) {
    noteModal.addEventListener('click', (e) => {
        if (e.target === noteModal) closeNote()
    })
}


//view note modal 
function viewNote(note, username) {
    document.getElementById('view-note-username').textContent = username
    document.getElementById('view-note-content').textContent = note
    document.getElementById('view-note-modal').classList.remove('hidden')
}

function closeViewNote() {
    document.getElementById('view-note-modal').classList.add('hidden')
}

const viewNoteModal = document.getElementById('view-note-modal')
if (viewNoteModal) {
    viewNoteModal.addEventListener('click', (e) => {
        if (e.target === viewNoteModal) closeViewNote()
    })
}

//edit article modal
document.addEventListener('click', function(e) {
    const editBtn = e.target.closest('.edit-btn');
    if (editBtn) {
        document.getElementById('edit_article_id').value = editBtn.dataset.id;
        document.getElementById('edit_title').value = editBtn.dataset.title;
        document.getElementById('edit_content').value = editBtn.dataset.content;
        document.getElementById('editModal').classList.add('active');
    }

    const closeBtn = e.target.closest('.cancel-btn');
    if (closeBtn) {
        document.getElementById('editModal').classList.remove('active');
    }

    // close on backdrop click
    if (e.target.id === 'editModal') {
        document.getElementById('editModal').classList.remove('active');
    }
});