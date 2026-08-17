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

$registered = isset($_GET["registered"]) &&
              $_GET["registered"] === "1";



// GET MY EVENTS

$sql = "
    SELECT
        e.event_id,
        e.title,
        e.description,
        e.venue,
        e.event_date,
        e.event_time,
        c.club_name,
        r.registered_at

    FROM registrations r

    INNER JOIN events e
        ON r.event_id = e.event_id

    INNER JOIN clubs c
        ON e.club_id = c.club_id

    WHERE r.user_id = ?

    ORDER BY e.event_date ASC, e.event_time ASC
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $user_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>My Registrations - NSBM EventHub</title>

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

        <h1>My Registrations</h1>

        <p>
            Events you have registered for.
        </p>

    </section>


    <?php if ($registered): ?>

        <div class="success-message">

            <i class="fa-solid fa-circle-check"></i>

            Successfully registered for the event!

        </div>

    <?php endif; ?>


    <div class="quick-actions">

        <a href="dashboard.php">

            <i class="fa-solid fa-arrow-left"></i>

            Back to Events

        </a>

        <a href="request-event.php">

            <i class="fa-solid fa-plus"></i>

            Request an Event

        </a>

    </div>


    <section class="events-section">


        <div class="event-grid">


            <?php if (mysqli_num_rows($result) > 0): ?>


                <?php while ($event = mysqli_fetch_assoc($result)): ?>


                    <div class="event-card">

                        <div class="event-card-content">

                            <h3>
                                <?= htmlspecialchars(
                                    $event["title"]
                                ) ?>
                            </h3>


                            <p class="event-description">

                                <?= htmlspecialchars(
                                    $event["description"]
                                ) ?>

                            </p>


                            <div class="event-details">


                                <div>

                                    <i class="fa-regular fa-calendar"></i>

                                    <?= date(
                                        "d M Y",
                                        strtotime(
                                            $event["event_date"]
                                        )
                                    ) ?>

                                </div>


                                <div>

                                    <i class="fa-regular fa-clock"></i>

                                    <?= date(
                                        "h:i A",
                                        strtotime(
                                            $event["event_time"]
                                        )
                                    ) ?>

                                </div>


                                <div>

                                    <i class="fa-solid fa-location-dot"></i>

                                    <?= htmlspecialchars(
                                        $event["venue"]
                                    ) ?>

                                </div>


                                <div>

                                    <i class="fa-solid fa-users"></i>

                                    <?= htmlspecialchars(
                                        $event["club_name"]
                                    ) ?>

                                </div>


                            </div>


                            <div class="event-footer">

                                <span class="available">

                                    <i class="fa-solid fa-circle-check"></i>

                                    Registered

                                </span>

                            </div>

                        </div>

                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="no-events">

                    <i class="fa-regular fa-calendar-xmark"></i>

                    <h3>No registrations yet</h3>

                    <p>
                        You haven't registered for any events.
                    </p>

                </div>


            <?php endif; ?>


        </div>

    </section>


</main>

</body>

</html>