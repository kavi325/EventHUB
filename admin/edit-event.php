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
   GET EVENT ID
========================================== */

$event_id = intval($_GET["id"] ?? 0);

if ($event_id <= 0) {
    header("Location: events.php");
    exit;
}


/* ==========================================
   HANDLE FORM SUBMISSION
========================================== */

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");

    $description = trim(
        $_POST["description"] ?? ""
    );

    $category_id = intval(
        $_POST["category_id"] ?? 0
    );

    $club_id = intval(
        $_POST["club_id"] ?? 0
    );

    $venue = trim(
        $_POST["venue"] ?? ""
    );

    $event_date = $_POST["event_date"] ?? "";

    $event_time = $_POST["event_time"] ?? "";

    $capacity = intval(
        $_POST["capacity"] ?? 0
    );


    /* ======================================
       VALIDATION
    ====================================== */

    if (
        empty($title) ||
        empty($description) ||
        $category_id <= 0 ||
        $club_id <= 0 ||
        empty($venue) ||
        empty($event_date) ||
        empty($event_time) ||
        $capacity <= 0
    ) {

        $error =
            "Please fill in all fields correctly.";

    } else {


        /* ==================================
           UPDATE EVENT
        ================================== */

        $sql = "
            UPDATE events

            SET
                title = ?,
                description = ?,
                category_id = ?,
                club_id = ?,
                venue = ?,
                event_date = ?,
                event_time = ?,
                capacity = ?

            WHERE event_id = ?
        ";


        $stmt = mysqli_prepare(
            $conn,
            $sql
        );


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "ssiisssii",
                $title,
                $description,
                $category_id,
                $club_id,
                $venue,
                $event_date,
                $event_time,
                $capacity,
                $event_id
            );


            if (
                mysqli_stmt_execute($stmt)
            ) {

                mysqli_stmt_close($stmt);

                header(
                    "Location: events.php?success=updated"
                );

                exit;

            } else {

                $error =
                    "Could not update the event.";
            }


            mysqli_stmt_close($stmt);

        } else {

            $error =
                "Database error.";
        }
    }
}


/* ==========================================
   GET CURRENT EVENT
========================================== */

$sql = "
    SELECT
        event_id,
        title,
        description,
        category_id,
        club_id,
        venue,
        event_date,
        event_time,
        capacity

    FROM events

    WHERE event_id = ?
";


$stmt = mysqli_prepare(
    $conn,
    $sql
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $event_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) !== 1) {

    mysqli_stmt_close($stmt);

    header("Location: events.php");

    exit;
}


$event = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/* ==========================================
   GET CATEGORIES
========================================== */

$category_result = mysqli_query(
    $conn,
    "
    SELECT
        category_id,
        category_name

    FROM categories

    ORDER BY category_name
    "
);


/* ==========================================
   GET CLUBS
========================================== */

$club_result = mysqli_query(
    $conn,
    "
    SELECT
        club_id,
        club_name

    FROM clubs

    ORDER BY club_name
    "
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
        Edit Event - NSBM EventHub
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


    <section class="welcome-section">

        <div>

            <h1>
                Edit Event
            </h1>

            <p>
                Update the details of this EventHub event.
            </p>

        </div>

    </section>


    <!-- BACK -->

    <a
        href="events.php"
        class="back-button">

        <i class="fa-solid fa-arrow-left"></i>

        Back to Events

    </a>



    <!-- ERROR -->

    <?php if (!empty($error)): ?>

        <div class="dashboard-alert error-alert">

            <i class="fa-solid fa-circle-exclamation"></i>

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php endif; ?>



    <!-- FORM -->

    <section class="edit-event-form-card">


        <form
            method="POST"
            action="edit-event.php?id=<?php echo $event_id; ?>">


            <!-- TITLE -->

            <div class="event-form-group">

                <label>
                    Event Title
                </label>

                <input
                    type="text"
                    name="title"
                    value="<?php
                        echo htmlspecialchars(
                            $event["title"]
                        );
                    ?>"
                    required>

            </div>



            <!-- DESCRIPTION -->

            <div class="event-form-group">

                <label>
                    Description
                </label>

                <textarea
                    name="description"
                    rows="5"
                    required><?php
                        echo htmlspecialchars(
                            $event["description"]
                        );
                    ?></textarea>

            </div>



            <!-- CATEGORY -->

            <div class="event-form-row">


                <div class="event-form-group">

                    <label>
                        Category
                    </label>

                    <select
                        name="category_id"
                        required>

                        <option value="">
                            Select Category
                        </option>


                        <?php while (
                            $category =
                            mysqli_fetch_assoc(
                                $category_result
                            )
                        ): ?>

                            <option
                                value="<?php
                                    echo $category[
                                        "category_id"
                                    ];
                                ?>"
                                <?php
                                if (
                                    $category[
                                        "category_id"
                                    ]
                                    ==
                                    $event[
                                        "category_id"
                                    ]
                                ) {
                                    echo "selected";
                                }
                                ?>>

                                <?php
                                echo htmlspecialchars(
                                    $category[
                                        "category_name"
                                    ]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>



                <!-- CLUB -->

                <div class="event-form-group">

                    <label>
                        Club
                    </label>

                    <select
                        name="club_id"
                        required>

                        <option value="">
                            Select Club
                        </option>


                        <?php while (
                            $club =
                            mysqli_fetch_assoc(
                                $club_result
                            )
                        ): ?>

                            <option
                                value="<?php
                                    echo $club[
                                        "club_id"
                                    ];
                                ?>"
                                <?php
                                if (
                                    $club[
                                        "club_id"
                                    ]
                                    ==
                                    $event[
                                        "club_id"
                                    ]
                                ) {
                                    echo "selected";
                                }
                                ?>>

                                <?php
                                echo htmlspecialchars(
                                    $club["club_name"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

            </div>



            <!-- VENUE -->

            <div class="event-form-group">

                <label>
                    Venue
                </label>

                <input
                    type="text"
                    name="venue"
                    value="<?php
                        echo htmlspecialchars(
                            $event["venue"]
                        );
                    ?>"
                    required>

            </div>



            <!-- DATE / TIME -->

            <div class="event-form-row">


                <div class="event-form-group">

                    <label>
                        Event Date
                    </label>

                    <input
                        type="date"
                        name="event_date"
                        value="<?php
                            echo htmlspecialchars(
                                $event["event_date"]
                            );
                        ?>"
                        required>

                </div>


                <div class="event-form-group">

                    <label>
                        Event Time
                    </label>

                    <input
                        type="time"
                        name="event_time"
                        value="<?php
                            echo htmlspecialchars(
                                $event["event_time"]
                            );
                        ?>"
                        required>

                </div>

            </div>



            <!-- CAPACITY -->

            <div class="event-form-group">

                <label>
                    Capacity
                </label>

                <input
                    type="number"
                    name="capacity"
                    min="1"
                    value="<?php
                        echo htmlspecialchars(
                            $event["capacity"]
                        );
                    ?>"
                    required>

            </div>



            <!-- ACTIONS -->

            <div class="edit-event-actions">

                <a
                    href="events.php"
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