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


/* ONLY POST */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: users.php");
    exit;
}


$user_id = intval(
    $_POST["user_id"] ?? 0
);

$action = $_POST["action"] ?? "";


/* VALID USER */

if ($user_id <= 0) {
    header("Location: users.php?error=delete_failed");
    exit;
}


/* DELETE STUDENT */

if ($action === "delete") {


    /*
       Extra safety:
       Only allow deletion of Student accounts.
       This prevents accidentally deleting
       the Super Admin.
    */

    $sql = "
        DELETE FROM users

        WHERE user_id = ?

        AND role = 'Student'
    ";


    $stmt = mysqli_prepare(
        $conn,
        $sql
    );


    if (!$stmt) {

        header(
            "Location: users.php?error=delete_failed"
        );

        exit;
    }


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user_id
    );


    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        header(
            "Location: users.php?success=deleted"
        );

        exit;
    }


    mysqli_stmt_close($stmt);


    header(
        "Location: users.php?error=delete_failed"
    );

    exit;
}


/* INVALID ACTION */

header("Location: users.php");

exit;

?>