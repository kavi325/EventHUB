<?php

session_start();

require_once "../config/db.php";



// CHECK LOGIN


if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;

}



// CHECK ROLE


if ($_SESSION["role"] !== "Student") {

    header("Location: ../auth/login.php");
    exit;

}


$user_id = $_SESSION["user_id"];

$event_id = intval($_POST["event_id"] ?? 0);



// VALID EVENT ID


if ($event_id <= 0) {

    header("Location: dashboard.php");
    exit;

}



// CHECK EVENT


$sql = "
    SELECT
        event_id,
        title,
        capacity
    FROM events
    WHERE event_id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $event_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) !== 1) {

    mysqli_stmt_close($stmt);

    header("Location: dashboard.php");
    exit;

}


$event = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);



// CHECK CURRENT REGISTRATIONS


$count_sql = "
    SELECT COUNT(*) AS registered_count
    FROM registrations
    WHERE event_id = ?
";

$count_stmt = mysqli_prepare(
    $conn,
    $count_sql
);

mysqli_stmt_bind_param(
    $count_stmt,
    "i",
    $event_id
);

mysqli_stmt_execute($count_stmt);

$count_result =
    mysqli_stmt_get_result($count_stmt);

$count_data =
    mysqli_fetch_assoc($count_result);

$registered_count =
    (int) $count_data["registered_count"];

mysqli_stmt_close($count_stmt);


// CHECK CAPACITY


if ($registered_count >= $event["capacity"]) {

    header("Location: dashboard.php?error=full");
    exit;

}



// CHECK ALREADY REGISTERED


$check_sql = "
    SELECT registration_id
    FROM registrations
    WHERE user_id = ?
    AND event_id = ?
";

$check_stmt = mysqli_prepare(
    $conn,
    $check_sql
);

mysqli_stmt_bind_param(
    $check_stmt,
    "ii",
    $user_id,
    $event_id
);

mysqli_stmt_execute($check_stmt);

$check_result =
    mysqli_stmt_get_result($check_stmt);


if (mysqli_num_rows($check_result) > 0) {

    mysqli_stmt_close($check_stmt);

    header("Location: dashboard.php?error=already");
    exit;

}

mysqli_stmt_close($check_stmt);



// REGISTER STUDENT


$insert_sql = "
    INSERT INTO registrations
    (user_id, event_id)
    VALUES (?, ?)
";

$insert_stmt = mysqli_prepare(
    $conn,
    $insert_sql
);

mysqli_stmt_bind_param(
    $insert_stmt,
    "ii",
    $user_id,
    $event_id
);

mysqli_stmt_execute($insert_stmt);

mysqli_stmt_close($insert_stmt);



// RETURN TO DASHBOARD


header("Location: my-events.php?registered=1");
exit;

?>