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
   ONLY POST REQUESTS
========================================== */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: events.php");
    exit;
}


$event_id = intval($_POST["event_id"] ?? 0);
$action   = $_POST["action"] ?? "";


/* ==========================================
   VALID EVENT ID
========================================== */

if ($event_id <= 0) {
    header("Location: events.php?error=invalid_event");
    exit;
}


/* ==========================================
   DELETE EVENT
========================================== */

if ($action === "delete") {

    $sql = "
        DELETE FROM events
        WHERE event_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        header("Location: events.php?error=delete_failed");
        exit;
    }

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $event_id
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        header(
            "Location: events.php?success=deleted"
        );

        exit;
    }

    mysqli_stmt_close($stmt);

    header(
        "Location: events.php?error=delete_failed"
    );

    exit;
}


/* ==========================================
   INVALID ACTION
========================================== */

header("Location: events.php");

exit;

?>