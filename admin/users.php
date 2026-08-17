<?php

session_start();

require_once "../config/db.php";


/* ADMIN ACCESS CHECK */

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION["role"] !== "Super Admin") {
    header("Location: ../student/dashboard.php");
    exit;
}


/* GET STUDENTS  */

$sql = "
    SELECT
        user_id,
        student_id,
        first_name,
        last_name,
        email,
        created_at

    FROM users

    WHERE role = 'Student'

    ORDER BY created_at DESC
";

$result = mysqli_query($conn, $sql);


/* MESSAGES  */

$success = $_GET["success"] ?? "";
$error = $_GET["error"] ?? "";

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Manage Users - NSBM EventHub
    </title>


    <!-- FontAwesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- Admin CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/admin-dashboard.css">

</head>


<body>


<!-- HEADER -->

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



<!-- MAIN -->

<main class="admin-main">


    <!-- PAGE HEADER -->

    <section class="welcome-section">

        <div>

            <h1>
                Manage Users
            </h1>



        </div>


        <a
            href="add-user.php"
            class="add-event-button">

            <i class="fa-solid fa-user-plus"></i>

            Add Student

        </a>

    </section>



    <!-- BACK -->

    <a
        href="dashboard.php"
        class="back-button">

        <i class="fa-solid fa-arrow-left"></i>

        Back to Dashboard

    </a>



    <!-- SUCCESS / ERROR -->

    <?php if ($success === "deleted"): ?>

        <div class="dashboard-alert success-alert">

            <i class="fa-solid fa-circle-check"></i>

            User deleted successfully.

        </div>

    <?php elseif ($success === "updated"): ?>

        <div class="dashboard-alert success-alert">

            <i class="fa-solid fa-circle-check"></i>

            User details updated successfully.

        </div>

    <?php elseif ($success === "added"): ?>

        <div class="dashboard-alert success-alert">

            <i class="fa-solid fa-circle-check"></i>

            Student account created successfully.

        </div>

    <?php elseif ($error === "delete_failed"): ?>

        <div class="dashboard-alert error-alert">

            <i class="fa-solid fa-circle-exclamation"></i>

            Could not delete the user.

        </div>

    <?php elseif ($error === "email_exists"): ?>

        <div class="dashboard-alert error-alert">

            <i class="fa-solid fa-circle-exclamation"></i>

            That email address is already in use.

        </div>

    <?php endif; ?>



    <!-- USERS -->

    <section class="admin-users-list">


        <?php if (mysqli_num_rows($result) > 0): ?>


            <?php while (
                $user = mysqli_fetch_assoc($result)
            ): ?>


                <div class="admin-user-card">


                    <!-- USER INFO -->

                    <div class="admin-user-info">


                        <div class="user-avatar">

                            <?php
                            echo strtoupper(
                                substr(
                                    $user["first_name"],
                                    0,
                                    1
                                )
                            );
                            ?>

                        </div>


                        <div>

                            <h3>

                                <?php
                                echo htmlspecialchars(
                                    $user["first_name"]
                                    . " "
                                    . $user["last_name"]
                                );
                                ?>

                            </h3>


                            <p>

                                <?php
                                echo htmlspecialchars(
                                    $user["email"]
                                );
                                ?>

                            </p>


                            <?php if (
                                !empty(
                                    $user["student_id"]
                                )
                            ): ?>

                                <span class="student-id">

                                    <?php
                                    echo htmlspecialchars(
                                        $user["student_id"]
                                    );
                                    ?>

                                </span>

                            <?php endif; ?>


                        </div>

                    </div>



                    <!-- USER ACTIONS -->

                    <div class="admin-user-actions">


                        <!-- EDIT -->

                        <a
                            href="edit-user.php?id=<?php echo $user["user_id"]; ?>"
                            class="edit-user-btn">

                            <i class="fa-solid fa-pen"></i>

                            Edit

                        </a>


                        <!-- DELETE -->

                        <form
                            action="user-action.php"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this student account?');">


                            <input
                                type="hidden"
                                name="user_id"
                                value="<?php
                                    echo $user["user_id"];
                                ?>">


                            <input
                                type="hidden"
                                name="action"
                                value="delete">


                            <button
                                type="submit"
                                class="remove-user-btn">

                                <i class="fa-solid fa-trash"></i>

                                Delete

                            </button>

                        </form>


                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="empty-events">

                <div class="empty-events-icon">

                    <i class="fa-solid fa-users"></i>

                </div>

                <h2>
                    No Students
                </h2>

                <p>
                    There are currently no registered students.
                </p>

            </div>


        <?php endif; ?>


    </section>


</main>


</body>

</html>