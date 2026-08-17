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
   GET ACTIVE EVENTS
========================================== */

$sql = "
    SELECT
        e.event_id,
        e.title,
        e.description,
        e.event_date,
        e.event_time,
        e.venue,
        e.capacity,

        c.category_name,

        cl.club_name,

        COUNT(r.registration_id) AS registered_count

    FROM events e

    LEFT JOIN categories c
        ON e.category_id = c.category_id

    LEFT JOIN clubs cl
        ON e.club_id = cl.club_id

    LEFT JOIN registrations r
        ON e.event_id = r.event_id

    WHERE e.event_date >= CURDATE()

    GROUP BY
        e.event_id,
        e.title,
        e.description,
        e.event_date,
        e.event_time,
        e.venue,
        e.capacity,
        c.category_name,
        cl.club_name

    ORDER BY e.event_date ASC, e.event_time ASC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Manage Events - NSBM EventHub</title>


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
                Manage Events
            </h1>


        </div>


        <a
            href="add-event.php"
            class="add-event-button">

            <i class="fa-solid fa-plus"></i>

            Add Event

        </a>

    </section>



    <!-- BACK BUTTON -->

    <a
        href="dashboard.php"
        class="back-button">

        <i class="fa-solid fa-arrow-left"></i>

        Back to Dashboard

    </a>



    <!-- EVENTS -->

    <section class="admin-events-list">


        <?php if (
            mysqli_num_rows($result) > 0
        ): ?>


            <?php while (
                $event =
                mysqli_fetch_assoc($result)
            ): ?>


                <div class="admin-event-card">


                    <!-- EVENT HEADER -->

                    <div class="admin-event-header">

                        <div>

                            <h2>

                                <?php
                                echo htmlspecialchars(
                                    $event["title"]
                                );
                                ?>

                            </h2>

                            <p>

                                <?php
                                echo htmlspecialchars(
                                    $event["description"]
                                );
                                ?>

                            </p>

                        </div>


                        <span class="event-status">

                            <i class="fa-solid fa-circle"></i>

                            Active

                        </span>

                    </div>



                    <!-- EVENT DETAILS -->

                    <div class="admin-event-details">


                        <span>

                            <i class="fa-regular fa-calendar"></i>

                            <?php
                            echo date(
                                "d M Y",
                                strtotime(
                                    $event["event_date"]
                                )
                            );
                            ?>

                        </span>


                        <span>

                            <i class="fa-regular fa-clock"></i>

                            <?php
                            echo date(
                                "h:i A",
                                strtotime(
                                    $event["event_time"]
                                )
                            );
                            ?>

                        </span>


                        <span>

                            <i class="fa-solid fa-location-dot"></i>

                            <?php
                            echo htmlspecialchars(
                                $event["venue"]
                            );
                            ?>

                        </span>


                        <span>

                            <i class="fa-solid fa-users"></i>

                            <?php
                            echo $event["registered_count"];
                            ?>

                            /

                            <?php
                            echo $event["capacity"];
                            ?>

                        </span>


                        <?php if (
                            !empty(
                                $event["category_name"]
                            )
                        ): ?>

                            <span>

                                <i class="fa-solid fa-tag"></i>

                                <?php
                                echo htmlspecialchars(
                                    $event["category_name"]
                                );
                                ?>

                            </span>

                        <?php endif; ?>


                        <?php if (
                            !empty(
                                $event["club_name"]
                            )
                        ): ?>

                            <span>

                                <i class="fa-solid fa-users-rectangle"></i>

                                <?php
                                echo htmlspecialchars(
                                    $event["club_name"]
                                );
                                ?>

                            </span>

                        <?php endif; ?>


                    </div>



                    <!-- ACTIONS -->

                    <div class="admin-event-actions">


                        <!-- EDIT -->

                        <a
                            href="edit-event.php?id=<?php echo $event["event_id"]; ?>"
                            class="edit-event-btn">

                            <i class="fa-solid fa-pen"></i>

                            Edit

                        </a>


                        <!-- REMOVE -->

                        <form
                            action="event-action.php"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to remove this event?');">

                            <input
                                type="hidden"
                                name="event_id"
                                value="<?php
                                    echo $event["event_id"];
                                ?>">

                            <input
                                type="hidden"
                                name="action"
                                value="delete">


                            <button
                                type="submit"
                                class="remove-event-btn">

                                <i class="fa-solid fa-trash"></i>

                                Remove

                            </button>

                        </form>


                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div class="empty-events">

                <div class="empty-events-icon">

                    <i class="fa-solid fa-calendar-check"></i>

                </div>

                <h2>
                    No Active Events
                </h2>

                <p>
                    There are currently no upcoming events.
                </p>

            </div>


        <?php endif; ?>


    </section>


</main>


</body>

</html>