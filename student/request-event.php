<?php

session_start();

require_once "../config/db.php";



// CHECK LOGIN


if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;

}


if ($_SESSION["role"] !== "Student") {

    header("Location: ../auth/login.php");
    exit;

}


$user_id = $_SESSION["user_id"];

$error = "";



// GET CATEGORIES


$category_sql = "
    SELECT
        category_id,
        category_name
    FROM categories
    ORDER BY category_name
";

$category_result =
    mysqli_query($conn, $category_sql);



// HANDLE FORM


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title =
        trim($_POST["title"] ?? "");

    $description =
        trim($_POST["description"] ?? "");

    $category_id =
        intval($_POST["category_id"] ?? 0);

    $preferred_date =
        $_POST["preferred_date"] ?? "";

    $preferred_time =
        $_POST["preferred_time"] ?? "";

    $venue =
        trim($_POST["venue"] ?? "");

    $capacity =
        intval($_POST["capacity"] ?? 0);


    
    // VALIDATION
    

    if (
        empty($title) ||
        empty($description) ||
        $category_id <= 0 ||
        empty($preferred_date) ||
        empty($preferred_time) ||
        empty($venue) ||
        $capacity <= 0
    ) {

        $error =
            "Please complete all fields.";

    } else {


        
        // INSERT REQUEST
        

        $sql = "
            INSERT INTO event_requests
            (
                user_id,
                title,
                description,
                category_id,
                event_date,
                event_time,
                venue,
                capacity
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = mysqli_prepare(
            $conn,
            $sql
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ississsi",
            $user_id,
            $title,
            $description,
            $category_id,
            $preferred_date,
            $preferred_time,
            $venue,
            $capacity
        );


        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);

            header(
                "Location: request-event.php?success=1"
            );

            exit;

        } else {

            $error =
                "Unable to submit your request.";

        }

        mysqli_stmt_close($stmt);

    }

}


$success = isset($_GET["success"]) &&
           $_GET["success"] === "1";

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Request an Event - NSBM EventHub</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="../assets/css/student-dashboard.css">

</head>

<body>


<header class="dashboard-header">

    <div class="logo">
        <img src="../assets/images/logo.png" alt="EventHub Logo" height="42" class="me-2">

        <!--<i class="fa-solid fa-calendar-check"></i>-->

        <span>NSBM EventHub</span>

    </div>


    <div class="user-area">

        <span>
            <?= htmlspecialchars(
                $_SESSION["first_name"] .
                " " .
                $_SESSION["last_name"]
            ) ?>
        </span>

        <a href="../auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</header>


<main class="dashboard-container">


<section class="welcome-section">

    <a href="dashboard.php" class="back-dashboard-btn">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Dashboard
    </a>

    <h1>Request an Event</h1>

    <p>
        Submit an event idea for the EventHub administrator to review.
    </p>

</section>


    <?php if ($success): ?>

        <div class="success-message">

            <i class="fa-solid fa-circle-check"></i>

            Your event request has been submitted successfully.

        </div>

    <?php endif; ?>


    <?php if (!empty($error)): ?>

        <div class="login-error">

            <i class="fa-solid fa-circle-exclamation"></i>

            <?= htmlspecialchars($error) ?>

        </div>

    <?php endif; ?>


    <div class="form-container"
         style="max-width: 700px;">


        <form
            method="POST"
            action="request-event.php">


            <div class="form-group">

                <label for="title">
                    Event Title
                </label>

                <div class="input-box">

                    <i class="fa-solid fa-heading field-icon"></i>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Enter event title"
                        required>

                </div>

            </div>


            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <div class="input-box">

                    <textarea
                        id="description"
                        name="description"
                        placeholder="Describe your event"
                        rows="5"
                        required></textarea>

                </div>

            </div>


            <div class="form-group">

                <label for="category">
                    Category
                </label>

                <div class="input-box">

                    <select
                        id="category"
                        name="category_id"
                        required>

                        <option value="">
                            Select a category
                        </option>


                        <?php while (
                            $category =
                            mysqli_fetch_assoc(
                                $category_result
                            )
                        ): ?>

                            <option
                                value="<?= $category["category_id"] ?>">

                                <?= htmlspecialchars(
                                    $category["category_name"]
                                ) ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

            </div>


            <div class="form-group">

                <label for="date">
                    Preferred Date
                </label>

                <div class="input-box">

                    <input
                        type="date"
                        id="date"
                        name="preferred_date"
                        required>

                </div>

            </div>


            <div class="form-group">

                <label for="time">
                    Preferred Time
                </label>

                <div class="input-box">

                    <input
                        type="time"
                        id="time"
                        name="preferred_time"
                        required>

                </div>

            </div>


            <div class="form-group">

                <label for="venue">
                    Preferred Venue
                </label>

                <div class="input-box">

                    <input
                        type="text"
                        id="venue"
                        name="venue"
                        placeholder="Example: Main Auditorium"
                        required>

                </div>

            </div>


            <div class="form-group">

                <label for="capacity">
                    Expected Capacity
                </label>

                <div class="input-box">

                    <input
                        type="number"
                        id="capacity"
                        name="capacity"
                        min="1"
                        placeholder="Example: 100"
                        required>

                </div>

            </div>


            <button
                type="submit"
                class="submit-btn">

                Submit Event Request

                <i class="fa-solid fa-paper-plane"></i>

            </button>


        </form>

    </div>


</main>

</body>

</html>