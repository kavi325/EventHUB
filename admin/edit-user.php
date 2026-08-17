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


/* ==========================================
   GET USER ID
========================================== */

$user_id = intval(
    $_GET["id"] ?? 0
);


if ($user_id <= 0) {

    header("Location: users.php");

    exit;
}


$error = "";


/* ==========================================
   FORM SUBMISSION
========================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $email = trim(
        $_POST["email"] ?? ""
    );

    $password = $_POST["password"] ?? "";


    /* --------------------------------------
       VALIDATION
    -------------------------------------- */

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error =
            "Please enter a valid email address.";

    } else {


        /* ----------------------------------
           CHECK EMAIL
        ---------------------------------- */

        $check_sql = "
            SELECT user_id

            FROM users

            WHERE email = ?

            AND user_id != ?
        ";


        $check_stmt = mysqli_prepare(
            $conn,
            $check_sql
        );


        mysqli_stmt_bind_param(
            $check_stmt,
            "si",
            $email,
            $user_id
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
                "That email address is already in use.";

        }


        mysqli_stmt_close(
            $check_stmt
        );
    }


    /* --------------------------------------
       UPDATE
    -------------------------------------- */

    if (empty($error)) {


        if (!empty($password)) {


            /* Password must be at least 6 */

            if (strlen($password) < 6) {

                $error =
                    "Password must be at least 6 characters.";

            } else {

                $password_hash =
                    password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );


                $sql = "
                    UPDATE users

                    SET
                        email = ?,
                        password = ?

                    WHERE user_id = ?

                    AND role = 'Student'
                ";


                $stmt = mysqli_prepare(
                    $conn,
                    $sql
                );


                mysqli_stmt_bind_param(
                    $stmt,
                    "ssi",
                    $email,
                    $password_hash,
                    $user_id
                );


                mysqli_stmt_execute(
                    $stmt
                );


                mysqli_stmt_close(
                    $stmt
                );


                header(
                    "Location: users.php?success=updated"
                );

                exit;
            }


        } else {


            /*
               Password left empty:
               keep existing password.
            */

            $sql = "
                UPDATE users

                SET email = ?

                WHERE user_id = ?

                AND role = 'Student'
            ";


            $stmt = mysqli_prepare(
                $conn,
                $sql
            );


            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $email,
                $user_id
            );


            mysqli_stmt_execute(
                $stmt
            );


            mysqli_stmt_close(
                $stmt
            );


            header(
                "Location: users.php?success=updated"
            );

            exit;
        }
    }
}


/* ==========================================
   GET USER
========================================== */

$sql = "
    SELECT
        user_id,
        first_name,
        last_name,
        email,
        student_id

    FROM users

    WHERE user_id = ?

    AND role = 'Student'
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);


mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);


mysqli_stmt_execute($stmt);


$result =
    mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) !== 1) {

    mysqli_stmt_close($stmt);

    header("Location: users.php");

    exit;
}


$user =
    mysqli_fetch_assoc($result);


mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Edit User - NSBM EventHub
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
                Edit User
            </h1>

            <p>
                Update this student's account details.
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
            action="edit-user.php?id=<?php echo $user_id; ?>">


            <!-- NAME -->

            <div class="event-form-group">

                <label>
                    Student Name
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $user["first_name"]
                            . " "
                            . $user["last_name"]
                        );
                    ?>"
                    disabled>

            </div>



            <!-- STUDENT ID -->

            <div class="event-form-group">

                <label>
                    Student ID
                </label>

                <input
                    type="text"
                    value="<?php
                        echo htmlspecialchars(
                            $user["student_id"]
                            ?? ""
                        );
                    ?>"
                    disabled>

            </div>



            <!-- EMAIL -->

            <div class="event-form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="<?php
                        echo htmlspecialchars(
                            $user["email"]
                        );
                    ?>"
                    required>

            </div>



            <!-- PASSWORD -->

            <div class="event-form-group">

                <label>
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    minlength="6"
                    placeholder="Leave blank to keep current password">

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

                    <i class="fa-solid fa-check"></i>

                    Save Changes

                </button>

            </div>


        </form>

    </section>


</main>


</body>

</html>