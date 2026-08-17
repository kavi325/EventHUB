<?php

session_start();

require_once "../config/db.php";


/* ==========================================
   ADMIN ACCESS CHECK
========================================== */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION["role"] !== "Super Admin") {
    header("Location: ../student/dashboard.php");
    exit;
}


$error = "";


/* ==========================================
   FORM SUBMISSION
========================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $student_id = trim(
        $_POST["student_id"] ?? ""
    );

    $first_name = trim(
        $_POST["first_name"] ?? ""
    );

    $last_name = trim(
        $_POST["last_name"] ?? ""
    );

    $email = trim(
        $_POST["email"] ?? ""
    );

    $password = $_POST["password"] ?? "";


    /* ======================================
       VALIDATION
    ====================================== */

    if (
        empty($student_id) ||
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($password)
    ) {

        $error =
            "Please fill in all fields.";

    } elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $error =
            "Please enter a valid email address.";

    } elseif (
        strlen($password) < 6
    ) {

        $error =
            "Password must be at least 6 characters.";

    } else {


        /* ==================================
           CHECK EXISTING EMAIL
        ================================== */

        $check_sql = "
            SELECT user_id

            FROM users

            WHERE email = ?
        ";


        $check_stmt = mysqli_prepare(
            $conn,
            $check_sql
        );


        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $email
        );


        mysqli_stmt_execute(
            $check_stmt
        );


        $check_result =
            mysqli_stmt_get_result(
                $check_stmt
            );


        if (
            mysqli_num_rows(
                $check_result
            ) > 0
        ) {

            $error =
                "That email address is already registered.";

        }


        mysqli_stmt_close(
            $check_stmt
        );


        /* ==================================
           INSERT STUDENT
        ================================== */

        if (empty($error)) {


            $password_hash =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


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

                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    'Student'
                )
            ";


            $stmt = mysqli_prepare(
                $conn,
                $sql
            );


            mysqli_stmt_bind_param(
                $stmt,
                "sssss",
                $student_id,
                $first_name,
                $last_name,
                $email,
                $password_hash
            );


            if (
                mysqli_stmt_execute($stmt)
            ) {

                mysqli_stmt_close($stmt);

                header(
                    "Location: users.php?success=added"
                );

                exit;

            }


            mysqli_stmt_close($stmt);


            $error =
                "Could not create the student account.";
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
        content="width=device-width, initial-scale=1.0">

    <title>
        Add Student - NSBM EventHub
    </title>


    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <link
        rel="stylesheet"
        href="../assets/css/admin-dashboard.css">

</head>


<body>


<header class="admin-header">

    <div class="header-container">

        <div class="brand">

            <img
                src="../assets/images/logo.png"
                alt="EventHub Logo"
                height="42">

            <span>
                NSBM EventHub Admin
            </span>

        </div>


        <div class="admin-profile">

            <span>
                <?php
                echo htmlspecialchars(
                    $_SESSION["first_name"]
                );
                ?>
            </span>

            <a href="../auth/logout.php">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </div>

    </div>

</header>



<main class="admin-main">


    <section class="welcome-section">

        <div>

            <h1>
                Add Student
            </h1>

            <p>
                Create a new EventHub student account.
            </p>

        </div>

    </section>



    <a
        href="users.php"
        class="back-button">

        <i class="fa-solid fa-arrow-left"></i>

        Back to Users

    </a>



    <?php if (!empty($error)): ?>

        <div class="dashboard-alert error-alert">

            <i class="fa-solid fa-circle-exclamation"></i>

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php endif; ?>



    <section class="edit-event-form-card">


        <form
            method="POST"
            action="add-user.php">


            <!-- STUDENT ID -->

            <div class="event-form-group">

                <label>
                    Student ID
                </label>

                <input
                    type="text"
                    name="student_id"
                    placeholder="NSBM / Plymouth Student ID"
                    required>

            </div>



            <!-- FIRST NAME -->

            <div class="event-form-row">

                <div class="event-form-group">

                    <label>
                        First Name
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        placeholder="First name"
                        required>

                </div>


                <!-- LAST NAME -->

                <div class="event-form-group">

                    <label>
                        Last Name
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        placeholder="Last name"
                        required>

                </div>

            </div>



            <!-- EMAIL -->

            <div class="event-form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="student@nsbm.ac.lk"
                    required>

            </div>



            <!-- PASSWORD -->

            <div class="event-form-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    minlength="6"
                    placeholder="Minimum 6 characters"
                    required>

            </div>



            <!-- ACTIONS -->

            <div class="edit-event-actions">

                <a
                    href="users.php"
                    class="cancel-event-btn">

                    Cancel

                </a>


                <button
                    type="submit"
                    class="save-event-btn">

                    <i class="fa-solid fa-user-plus"></i>

                    Create Student

                </button>

            </div>


        </form>

    </section>


</main>


</body>

</html>