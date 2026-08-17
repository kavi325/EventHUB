// ===============================
// Role Toggle
// ===============================

const glider = document.querySelector(".active-glider");
const studentBtn = document.getElementById("studentBtn");
const adminBtn = document.getElementById("adminBtn");
const userRoleInput = document.getElementById("userRole");

function switchRole(role) {

    if (role === "student") {

        glider.style.transform = "translateX(0)";

        studentBtn.classList.add("active");
        adminBtn.classList.remove("active");

        userRoleInput.value = "student";

    } else {

        glider.style.transform = "translateX(100%)";

        adminBtn.classList.add("active");
        studentBtn.classList.remove("active");

        userRoleInput.value = "admin";
    }
}


// ===============================
// Show / Hide Password
// ===============================

function togglePassword() {

    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("togglePassword");

    if (passwordInput.type === "password") {

        passwordInput.type = "text";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");

    } else {

        passwordInput.type = "password";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
}