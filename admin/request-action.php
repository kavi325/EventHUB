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


/*  ONLY POST REQUESTS*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php");
    exit;
}


/* GET FORM DATA */

$request_id = intval($_POST["request_id"] ?? 0);

$action = $_POST["action"] ?? "";

$club_id = intval($_POST["club_id"] ?? 0);


/*BASIC VALIDATION */

if ($request_id <= 0) {
    header("Location: dashboard.php");
    exit;
}


/* APPROVE REQUEST*/

if ($action === "approve") {


    /*Club must be selected */

    if ($club_id <= 0) {

        header(
            "Location: dashboard.php?error=no_club"
        );

        exit;
    }


    /* Check that selected club exists */

    $club_sql = "
        SELECT club_id
        FROM clubs
        WHERE club_id = ?
    ";

    $club_stmt = mysqli_prepare(
        $conn,
        $club_sql
    );

    mysqli_stmt_bind_param(
        $club_stmt,
        "i",
        $club_id
    );

    mysqli_stmt_execute(
        $club_stmt
    );

    $club_result = mysqli_stmt_get_result(
        $club_stmt
    );

    if (mysqli_num_rows($club_result) !== 1) {

        mysqli_stmt_close($club_stmt);

        header(
            "Location: dashboard.php?error=invalid_club"
        );

        exit;
    }

    mysqli_stmt_close($club_stmt);


    /*Get pending event request  */

    $request_sql = "
        SELECT
            request_id,
            title,
            description,
            category_id,
            event_date,
            event_time,
            venue,
            capacity

        FROM event_requests

        WHERE request_id = ?
        AND status = 'Pending'
    ";

    $request_stmt = mysqli_prepare(
        $conn,
        $request_sql
    );

    mysqli_stmt_bind_param(
        $request_stmt,
        "i",
        $request_id
    );

    mysqli_stmt_execute(
        $request_stmt
    );

    $request_result = mysqli_stmt_get_result(
        $request_stmt
    );


    /* Request doesn't exist or was already processed */

    if (mysqli_num_rows($request_result) !== 1) {

        mysqli_stmt_close($request_stmt);

        header(
            "Location: dashboard.php?error=invalid_request"
        );

        exit;
    }


    $request = mysqli_fetch_assoc(
        $request_result
    );

    mysqli_stmt_close($request_stmt);


    /* Start database transaction  */

    mysqli_begin_transaction($conn);


    try {


        /*Create the actual event */

        $event_sql = "
            INSERT INTO events
            (
                title,
                description,
                category_id,
                club_id,
                venue,
                event_date,
                event_time,
                capacity,
                banner_image,
                created_by
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                NULL,
                ?
            )
        ";


        $event_stmt = mysqli_prepare(
            $conn,
            $event_sql
        );


        mysqli_stmt_bind_param(
            $event_stmt,
            "ssiisssii",
            $request["title"],
            $request["description"],
            $request["category_id"],
            $club_id,
            $request["venue"],
            $request["event_date"],
            $request["event_time"],
            $request["capacity"],
            $_SESSION["user_id"]
        );


        if (!mysqli_stmt_execute($event_stmt)) {

            throw new Exception(
                "Could not create event."
            );
        }


        mysqli_stmt_close($event_stmt);


        /*  Mark request as approved */

        $update_sql = "
            UPDATE event_requests

            SET status = 'Approved'

            WHERE request_id = ?
            AND status = 'Pending'
        ";


        $update_stmt = mysqli_prepare(
            $conn,
            $update_sql
        );


        mysqli_stmt_bind_param(
            $update_stmt,
            "i",
            $request_id
        );


        if (!mysqli_stmt_execute($update_stmt)) {

            throw new Exception(
                "Could not update request."
            );
        }


        mysqli_stmt_close($update_stmt);


        /* Everything succeeded */

        mysqli_commit($conn);


        header(
            "Location: dashboard.php?success=approved"
        );

        exit;


    } catch (Exception $e) {


        /* Something failed */

        mysqli_rollback($conn);


        header(
            "Location: dashboard.php?error=approve_failed"
        );

        exit;
    }
}


/*REJECT REQUEST */

if ($action === "reject") {


    $reject_sql = "
        UPDATE event_requests

        SET status = 'Rejected'

        WHERE request_id = ?

        AND status = 'Pending'
    ";


    $reject_stmt = mysqli_prepare(
        $conn,
        $reject_sql
    );


    if (!$reject_stmt) {

        header(
            "Location: dashboard.php?error=reject_failed"
        );

        exit;
    }


    mysqli_stmt_bind_param(
        $reject_stmt,
        "i",
        $request_id
    );


    if (mysqli_stmt_execute($reject_stmt)) {

        mysqli_stmt_close($reject_stmt);


        header(
            "Location: dashboard.php?success=rejected"
        );

        exit;

    }


    mysqli_stmt_close($reject_stmt);


    header(
        "Location: dashboard.php?error=reject_failed"
    );

    exit;
}


/* INVALID ACTION*/

header("Location: dashboard.php");

exit;

?>