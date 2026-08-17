<?php

require_once "../config/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $student_id = trim($_POST["student_id"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";


    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($student_id) ||
        empty($email) ||
        empty($password)
    ) {
        $message = "Please fill in all required fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    } else {

        $check_sql = "SELECT user_id FROM users WHERE email = ?";

        $check_stmt = mysqli_prepare($conn, $check_sql);

        if (!$check_stmt) {

            $message = "Database error.";
            $message_type = "error";

        } else {

            mysqli_stmt_bind_param(
                $check_stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($check_stmt);

            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {

                $message = "An account with this email already exists.";
                $message_type = "error";

                mysqli_stmt_close($check_stmt);

            } else {

                mysqli_stmt_close($check_stmt);

                $password_hash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


                $role = "Student";

                $sql = "
                    INSERT INTO users
                    (
                        student_id,
                        first_name,
                        last_name,
                        email,
                        password,
                        role
                    )
                    VALUES (?, ?, ?, ?, ?, ?)
                ";

                $stmt = mysqli_prepare($conn, $sql);

                if (!$stmt) {

                    $message = "Database error.";
                    $message_type = "error";

                } else {

                    mysqli_stmt_bind_param(
                        $stmt,
                        "ssssss",
                        $student_id,
                        $first_name,
                        $last_name,
                        $email,
                        $password_hash,
                        $role
                    );

                    if (mysqli_stmt_execute($stmt)) {

                        mysqli_stmt_close($stmt);

                        // Registration successful
                        header("Location: login.php?registered=1");
                        exit;

                    } else {

                        $message = "Registration failed. Please try again.";
                        $message_type = "error";

                        mysqli_stmt_close($stmt);
                    }
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>NSBM EventHub - Register</title>


    <!-- FontAwesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >


    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- Shared Auth CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/authreg.css"
    >

</head>


<body>


<div class="container">


    <!-- ========================= -->
    <!-- LEFT SIDE -->
    <!-- ========================= -->

    <div class="left-section">

        <div class="overlay">


            <div class="top-badge">

                <i class="fa-solid fa-graduation-cap"></i>

                NSBM Green University

            </div>


            <div class="welcome-content">

                <h1>

                    Welcome to <br>

                    <span>NSBM EventHub</span>

                </h1>


                <p>

                    Organise, manage, and participate in university club
                    events in one seamless platform.

                </p>

            </div>


        </div>

    </div>




    <div class="right-section">

        <div class="form-container">


            <div class="portal-badge">

                <i class="fa-solid fa-calendar-check"></i>

                EventHub Portal

            </div>


            <h2>

                Create Account

            </h2>


            <p class="subtitle">

                Fill in your details to register for an account

            </p>




            <?php if (!empty($message)): ?>

                <div
                    class="form-message <?= $message_type ?>"
                >

                    <?= htmlspecialchars($message) ?>

                </div>

            <?php endif; ?>



            <form
                action=""
                method="POST"
                id="registerForm"
            >



                <div class="name-row">


                    <div class="input-group">

                        <label>

                            First Name

                        </label>


                        <div class="input-field">

                            <i class="fa-solid fa-user"></i>


                            <input
                                type="text"
                                name="first_name"
                                placeholder="First name"
                                value="<?= htmlspecialchars($first_name ?? '') ?>"
                                required
                            >

                        </div>

                    </div>



                    <div class="input-group">

                        <label>

                            Last Name

                        </label>


                        <div class="input-field">

                            <i class="fa-solid fa-user"></i>


                            <input
                                type="text"
                                name="last_name"
                                placeholder="Last name"
                                value="<?= htmlspecialchars($last_name ?? '') ?>"
                                required
                            >

                        </div>

                    </div>


                </div>





                <div class="input-group">

                    <label>

                        Student ID

                    </label>


                    <div class="input-field">

                        <i class="fa-solid fa-id-card"></i>


                        <input
                            type="text"
                            name="student_id"
                            placeholder="NSBM Student ID"
                            value="<?= htmlspecialchars($student_id ?? '') ?>"
                            required
                        >

                    </div>

                </div>



                <div class="input-group">

                    <label>

                        Email Address

                    </label>


                    <div class="input-field">

                        <i class="fa-solid fa-envelope"></i>


                        <input
                            type="email"
                            name="email"
                            placeholder="student@nsbm.ac.lk"
                            value="<?= htmlspecialchars($email ?? '') ?>"
                            required
                        >

                    </div>

                </div>



                <div class="input-group">

                    <label>

                        Password

                    </label>


                    <div class="input-field">

                        <i class="fa-solid fa-lock"></i>


                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            required
                        >


                        <i
                            class="fa-solid fa-eye-slash toggle-password"
                            id="togglePassword"
                        ></i>

                    </div>

                </div>



                <!-- ROLE -->

                <div class="input-group">

                    <label>

                        Role

                    </label>


                    <div class="input-field">

                        <i class="fa-solid fa-user-tag"></i>


                        <select
                            name="role"
                            disabled
                        >

                            <option selected>

                                Student

                            </option>

                        </select>

                    </div>

                </div>



                <button
                    type="submit"
                    class="submit-btn"
                >

                    Register

                    <i class="fa-solid fa-arrow-right"></i>

                </button>



                <!-- LOGIN -->

                <div class="login-redirect">

                    <p>

                        Already have an account?

                        <a href="login.php">

                            Sign In

                        </a>

                    </p>

                </div>


            </form>


        </div>

    </div>


</div>



<script src="../assets/js/authreg.js"></script>


</body>

</html>