
<?php

session_start();

require_once "../config/db.php";

// ==========================================
// CHECK LOGIN
// ==========================================

if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;
}


// ==========================================
// CHECK ROLE
// ==========================================

if ($_SESSION["role"] !== "Student") {

    header("Location: ../auth/login.php");
    exit;
}


// ==========================================
// GET SESSION DATA
// ==========================================

$user_id = $_SESSION["user_id"];
$first_name = $_SESSION["first_name"];
$last_name = $_SESSION["last_name"];


// ==========================================
// GET UPCOMING EVENTS
// ==========================================

$sql = "
    SELECT
        e.event_id,
        e.title,
        e.description,
        e.venue,
        e.event_date,
        e.event_time,
        e.capacity,
        c.club_name,

        (
            SELECT COUNT(*)
            FROM registrations r
            WHERE r.event_id = e.event_id
        ) AS registered_count

    FROM events e

    INNER JOIN clubs c
        ON e.club_id = c.club_id

    WHERE e.event_date >= CURDATE()

    ORDER BY e.event_date ASC, e.event_time ASC
";

$result = mysqli_query($conn, $sql);


// ==========================================
// GET STUDENT'S REGISTRATIONS
// ==========================================

$registration_sql = "
    SELECT COUNT(*) AS total
    FROM registrations
    WHERE user_id = ?
";

$registration_stmt = mysqli_prepare(
    $conn,
    $registration_sql
);

mysqli_stmt_bind_param(
    $registration_stmt,
    "i",
    $user_id
);

mysqli_stmt_execute(
    $registration_stmt
);

$registration_result =
    mysqli_stmt_get_result($registration_stmt);

$registration_data =
    mysqli_fetch_assoc($registration_result);

$total_registrations =
    $registration_data["total"];

mysqli_stmt_close($registration_stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard - NSBM EventHub</title>

    <!-- FontAwesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Dashboard CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/student-dashboard.css">

</head>


<body>


<!-- ==========================================
     NAVBAR
========================================== -->

<header class="dashboard-header">

    <div class="logo">
        <img src="../assets/images/logo.png" alt="EventHub Logo" height="42" class="me-2">

        <!--<i class="fa-solid fa-calendar-check"></i>-->

        <span>NSBM EventHub</span>

    </div>


    <div class="user-area">

        <span>
            <?= htmlspecialchars($first_name . " " . $last_name) ?>
        </span>

        <a href="../auth/logout.php">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</header>





<main class="dashboard-container">


    <!-- Welcome -->

    <section class="welcome-section">

        <h1>
            Welcome, <?= htmlspecialchars($first_name) ?> 
        </h1>

        <p>
            Discover and participate in upcoming NSBM events.
        </p>

    </section>




    <section class="stats">

        <div class="stat-card">

            <i class="fa-solid fa-calendar-days"></i>

            <div>

                <span>Upcoming Events</span>

                <strong>
                    <?= mysqli_num_rows($result) ?>
                </strong>

            </div>

        </div>


        <div class="stat-card">

            <i class="fa-solid fa-ticket"></i>

            <div>

                <span>My Registrations</span>

                <strong>
                    <?= $total_registrations ?>
                </strong>

            </div>

        </div>

    </section>



    <!--QUICK ACTIONS-->

    <section class="quick-actions">

        <a href="my-events.php">

            <i class="fa-solid fa-ticket"></i>

            My Registrations

        </a>


        <a href="request-event.php">

            <i class="fa-solid fa-plus"></i>

            Request an Event

        </a>

    </section>



    <!--EVENTS-->

    <section class="events-section">

        <div class="section-header">

            <div>

                <h2>Upcoming Events</h2>

                <p>
                    Check out what's happening at NSBM.
                </p>

            </div>

        </div>


        <div class="event-grid">


        <?php if ($result && mysqli_num_rows($result) > 0): ?>


            <?php while ($event = mysqli_fetch_assoc($result)): ?>

                <?php

                $registered =
                    (int) $event["registered_count"];

                $capacity =
                    (int) $event["capacity"];

                $is_full =
                    $registered >= $capacity;

                ?>


                <div class="event-card">


                    <div class="event-card-content">


                        <h3>
                            <?= htmlspecialchars($event["title"]) ?>
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
                                    strtotime($event["event_date"])
                                ) ?>

                            </div>


                            <div>

                                <i class="fa-regular fa-clock"></i>

                                <?= date(
                                    "h:i A",
                                    strtotime($event["event_time"])
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


                            <span
                                class="<?= $is_full ? 'full' : 'available' ?>">

                                <i class="fa-solid fa-user-group"></i>

                                <?= $registered ?>
                                /
                                <?= $capacity ?>

                            </span>


                            <?php if ($is_full): ?>

                                <span class="full-text">

                                    Event Full

                                </span>

                            <?php else: ?>

                                <form
                                    action="register-event.php"
                                    method="POST">

                                    <input
                                        type="hidden"
                                        name="event_id"
                                        value="<?= $event["event_id"] ?>">

                                    <button
                                        type="submit"
                                        class="register-btn">

                                        Register

                                        <i class="fa-solid fa-arrow-right"></i>

                                    </button>

                                </form>

                            <?php endif; ?>


                        </div>


                    </div>

                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="no-events">

                <i class="fa-regular fa-calendar-xmark"></i>

                <h3>No upcoming events</h3>

                <p>
                    There are currently no upcoming events.
                </p>

            </div>


        <?php endif; ?>


        </div>

    </section>


</main>


</body>

</html>