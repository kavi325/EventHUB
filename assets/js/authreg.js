

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

if (togglePassword && passwordInput) {

    togglePassword.addEventListener("click", function () {

        if (passwordInput.type === "password") {

            passwordInput.type = "text";

            togglePassword.classList.remove("fa-eye-slash");
            togglePassword.classList.add("fa-eye");

        } else {

            passwordInput.type = "password";

            togglePassword.classList.remove("fa-eye");
            togglePassword.classList.add("fa-eye-slash");

        }

    });

}



const registerForm = document.getElementById("registerForm");

if (registerForm) {

    registerForm.addEventListener("submit", function (event) {

        // Get form fields
        const firstName = document.querySelector(
            'input[name="first_name"]'
        );

        const lastName = document.querySelector(
            'input[name="last_name"]'
        );

        const studentId = document.querySelector(
            'input[name="student_id"]'
        );

        const email = document.querySelector(
            'input[name="email"]'
        );

        const password = document.querySelector(
            'input[name="password"]'
        );


        clearValidation();


        if (firstName.value.trim() === "") {

            event.preventDefault();

            showError(
                firstName,
                "Please enter your first name."
            );

            return;
        }



        if (lastName.value.trim() === "") {

            event.preventDefault();

            showError(
                lastName,
                "Please enter your last name."
            );

            return;
        }



        if (studentId.value.trim() === "") {

            event.preventDefault();

            showError(
                studentId,
                "Please enter your Student ID."
            );

            return;
        }


        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email.value.trim() === "") {

            event.preventDefault();

            showError(
                email,
                "Please enter your email address."
            );

            return;
        }

        if (!emailPattern.test(email.value.trim())) {

            event.preventDefault();

            showError(
                email,
                "Please enter a valid email address."
            );

            return;
        }


        if (password.value === "") {

            event.preventDefault();

            showError(
                password,
                "Please enter a password."
            );

            return;
        }


        if (password.value.length < 6) {

            event.preventDefault();

            showError(
                password,
                "Password must be at least 6 characters."
            );

            return;
        }


    });

}



function showError(input, message) {

    // Highlight input
    input.style.borderColor = "#dc2626";

    input.style.boxShadow =
        "0 0 0 3px rgba(220, 38, 38, 0.10)";


    // Create error message
    const error = document.createElement("small");

    error.className = "validation-error";

    error.textContent = message;

    error.style.display = "block";
    error.style.color = "#dc2626";
    error.style.fontSize = "12px";
    error.style.marginTop = "5px";



    input.parentElement.parentElement.appendChild(error);

    input.focus();

}




function clearValidation() {

    const errors = document.querySelectorAll(
        ".validation-error"
    );

    errors.forEach(function (error) {

        error.remove();

    });


    const inputs = document.querySelectorAll(
        "#registerForm input"
    );

    inputs.forEach(function (input) {

        input.style.borderColor = "";
        input.style.boxShadow = "";

    });

}