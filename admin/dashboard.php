<?php

session_start();

require_once "../config/db.php";

/*ADMIN ACCESS CHECK*/

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION["role"] !== "Super Admin") {
    header("Location: ../student/dashboard.php");
    exit;
}


/*ADMIN DETAILS*/

$admin_name = $_SESSION["first_name"];


/*USER COUNT*/

$user_query = "
    SELECT COUNT(*) AS total_users
    FROM users
    WHERE role = 'Student'
";

$user_result = mysqli_query($conn, $user_query);

$user_data = mysqli_fetch_assoc($user_result);

$total_users = $user_data["total_users"];


/*EVENT COUNT*/

$event_query = "
    SELECT COUNT(*) AS total_events
    FROM events
    WHERE event_date >= CURDATE()
";

$event_result = mysqli_query($conn, $event_query);

$event_data = mysqli_fetch_assoc($event_result);

$total_events = $event_data["total_events"];


/*PENDING EVENT REQUEST COUNT*/

$request_count_query = "
    SELECT COUNT(*) AS pending_requests
    FROM event_requests
    WHERE status = 'Pending'
";

$request_count_result = mysqli_query(
    $conn,
    $request_count_query
);

$request_count_data = mysqli_fetch_assoc(
    $request_count_result
);

$pending_requests =
    $request_count_data["pending_requests"];


/*GET PENDING EVENT REQUESTS*/

$request_query = "
    SELECT
        er.request_id,
        er.title,
        er.description,
        er.event_date,
        er.event_time,
        er.venue,
        er.capacity,

        u.first_name,
        u.last_name,

        c.category_name

    FROM event_requests er

    INNER JOIN users u
        ON er.user_id = u.user_id

    LEFT JOIN categories c
        ON er.category_id = c.category_id

    WHERE er.status = 'Pending'

    ORDER BY er.created_at DESC
";

$request_result = mysqli_query(
    $conn,
    $request_query
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Admin Dashboard - NSBM EventHub
    </title>


    <!-- FontAwesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- Admin Dashboard CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/admin-dashboard.css">

</head>


<body>


<!--HEADER-->

<header class="admin-header">

    <div class="header-container">


    <div class="brand">
        <img src="../assets/images/logo.png" alt="EventHub Logo" height="42" class="me-2">

        <!--<i class="fa-solid fa-calendar-check"></i>-->

        <span>NSBM EventHub Admin</span>

    </div>


        <div class="admin-profile">

            <span>
                <?php echo htmlspecialchars($admin_name); ?>
            </span>

            <a href="../auth/logout.php">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </div>


    </div>

</header>



<!--MAIN CONTENT-->

<main class="admin-main">


    <!-- Welcome -->

    <section class="welcome-section">

        <div>

            <h1>
                Welcome back,
                <?php echo htmlspecialchars($admin_name); ?>
            </h1>

            <p>
                Manage EventHub activities from your dashboard.
            </p>

        </div>

    </section>



    <!--DASHBOARD CARDS-->

    <section class="dashboard-cards">


        <!-- USERS -->

        <a
            href="users.php"
            class="dashboard-card clickable-card">

            <div class="card-icon users-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <div class="card-content">

                <h3>Users</h3>

                <p>
                    Manage registered students
                </p>

            </div>

            <i class="fa-solid fa-arrow-right card-arrow"></i>

        </a>



        <!-- EVENTS -->

        <a
            href="events.php"
            class="dashboard-card clickable-card">

            <div class="card-icon events-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>

            <div class="card-content">

                <h3>Events</h3>

                <p>
                    Manage active events
                </p>

            </div>

            <i class="fa-solid fa-arrow-right card-arrow"></i>

        </a>



        <!-- USER COUNT -->

        <div class="dashboard-card stat-card">

            <div class="card-icon count-icon">

                <i class="fa-solid fa-user-group"></i>

            </div>

            <div class="card-content">

                <h3>
                    <?php echo $total_users; ?>
                </h3>

                <p>
                    Registered Students
                </p>

            </div>

        </div>


    </section>



    <!--ADD EVENT-->

    <section class="add-event-section">

        <a
            href="add-event.php"
            class="add-event-card">

            <div class="add-event-icon">

                <i class="fa-solid fa-plus"></i>

            </div>

            <div>

                <h2>
                    Add Event
                </h2>

                <p>
                    Create a new EventHub event
                </p>

            </div>

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </section>

    <!-- MANAGE CLUBS -->

        <section class="clubs-management-section">

            <a
                href="clubs.php"
                class="clubs-management-card">

                <div class="clubs-management-icon">

                    <i class="fa-solid fa-building"></i>

                </div>

                <div>

                    <h2>
                        Manage Clubs
                    </h2>

                    <p>
                        Add, edit, or remove university clubs
                    </p>

                </div>

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </section>
        <br>



    <!--EVENT REQUESTS-->

    <section class="requests-section">


        <div class="section-header">

            <div>

                <h2>
                    Event Requests
                </h2>

                <p>
                    Review event ideas submitted by students.
                </p>

            </div>


            <div class="request-count">

                <?php echo $pending_requests; ?>

                Pending

            </div>

        </div>




        <div class="requests-list">


            <?php if (
                mysqli_num_rows($request_result) > 0
            ): ?>


                <?php while (
                    $request =
                    mysqli_fetch_assoc($request_result)
                ): ?>


                    <div class="request-card">


                        <!-- Request Header -->

                        <div class="request-header">

                            <div>

                                <h3>

                                    <?php
                                    echo htmlspecialchars(
                                        $request["title"]
                                    );
                                    ?>

                                </h3>

                                <p class="request-user">

                                    Requested by

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $request["first_name"]
                                            . " "
                                            . $request["last_name"]
                                        );
                                        ?>

                                    </strong>

                                </p>

                            </div>


                            <span class="request-status">

                                Pending

                            </span>

                        </div>



                        <!-- Description -->

                        <p class="request-description">

                            <?php
                            echo htmlspecialchars(
                                $request["description"]
                            );
                            ?>

                        </p>



                        <!-- Request Details -->

                        <div class="request-details">


                            <span>

                                <i class="fa-regular fa-calendar"></i>

                                <?php
                                echo date(
                                    "d M Y",
                                    strtotime(
                                        $request["event_date"]
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
                                        $request["event_time"]
                                    )
                                );
                                ?>

                            </span>


                            <span>

                                <i class="fa-solid fa-location-dot"></i>

                                <?php
                                echo htmlspecialchars(
                                    $request["venue"]
                                );
                                ?>

                            </span>


                            <span>

                                <i class="fa-solid fa-users"></i>

                                <?php
                                echo htmlspecialchars(
                                    $request["capacity"]
                                );
                                ?>

                            </span>


                            <?php if (
                                !empty(
                                    $request["category_name"]
                                )
                            ): ?>

                                <span>

                                    <i class="fa-solid fa-tag"></i>

                                    <?php
                                    echo htmlspecialchars(
                                        $request["category_name"]
                                    );
                                    ?>

                                </span>

                            <?php endif; ?>


                        </div>



                        <!-- Request Actions -->

                        <div class="request-actions">

                            <!-- APPROVE FORM -->

                            <form
                                action="request-action.php"
                                method="POST"
                                class="approve-form">

                                <input
                                    type="hidden"
                                    name="request_id"
                                    value="<?php echo $request["request_id"]; ?>">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="approve">


                                <div class="club-select-wrapper">

                                    <label for="club-<?php echo $request["request_id"]; ?>">
                                        Club
                                    </label>

                                    <select
                                        name="club_id"
                                        id="club-<?php echo $request["request_id"]; ?>"
                                        required>

                                        <option value="">
                                            Select a club
                                        </option>

                                        <?php

                                        $club_query = "
                                            SELECT
                                                club_id,
                                                club_name
                                            FROM clubs
                                            ORDER BY club_name ASC
                                        ";

                                        $club_result = mysqli_query(
                                            $conn,
                                            $club_query
                                        );

                                        while (
                                            $club = mysqli_fetch_assoc(
                                                $club_result
                                            )
                                        ):

                                        ?>

                                            <option
                                                value="<?php echo $club["club_id"]; ?>">

                                                <?php
                                                echo htmlspecialchars(
                                                    $club["club_name"]
                                                );
                                                ?>

                                            </option>

                                        <?php endwhile; ?>

                                    </select>

                                </div>


                                <button
                                    type="submit"
                                    class="approve-btn">

                                    <i class="fa-solid fa-check"></i>

                                    Approve

                                </button>

                            </form>


                            <!-- REJECT FORM -->

                            <form
                                action="request-action.php"
                                method="POST">

                                <input
                                    type="hidden"
                                    name="request_id"
                                    value="<?php echo $request["request_id"]; ?>">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="reject">


                                <button
                                    type="submit"
                                    class="reject-btn">

                                    <i class="fa-solid fa-xmark"></i>

                                    Reject

                                </button>

                            </form>

                        </div>


                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <div class="empty-requests">

                    <div class="empty-icon">

                        <i class="fa-solid fa-check"></i>

                    </div>

                    <h3>
                        No Pending Requests
                    </h3>

                    <p>
                        You're all caught up!
                    </p>

                </div>


            <?php endif; ?>


        </div>

    </section>

</main>


</body>

</html>