<?php

session_start();

require_once "../config/db.php";

$registered = isset($_GET["registered"]) &&
              $_GET["registered"] === "1";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get login details
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";




    if (empty($email) || empty($password)) {

        $error = "Please enter your email and password.";

    } else {


        $sql = "
            SELECT
                user_id,
                first_name,
                last_name,
                email,
                password,
                role
            FROM users
            WHERE email = ?
        ";

        $stmt = mysqli_prepare($conn, $sql);


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);


            if (mysqli_num_rows($result) === 1) {

                $user = mysqli_fetch_assoc($result);


                if (password_verify(
                    $password,
                    $user["password"]
                )) {


                    $_SESSION["user_id"] =
                        $user["user_id"];

                    $_SESSION["first_name"] =
                        $user["first_name"];

                    $_SESSION["last_name"] =
                        $user["last_name"];

                    $_SESSION["email"] =
                        $user["email"];

                    $_SESSION["role"] =
                        $user["role"];



                    if ($user["role"] === "Student") {

                        header(
                            "Location: ../student/dashboard.php"
                        );

                        exit;
                    }


                    if ($user["role"] === "Super Admin") {

                        header(
                            "Location: ../admin/dashboard.php"
                        );

                        exit;
                    }


                    $error =
                        "This account does not have access.";

                    session_unset();
                    session_destroy();

                } else {

                    $error =
                        "Invalid email or password.";

                }

            } else {

                $error =
                    "Invalid email or password.";

            }


            mysqli_stmt_close($stmt);

        } else {

            $error =
                "Database error. Please try again.";

        }

    }

}

?>

<!-- Login Content -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM EventHub - Login</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>


    <div class="login-wrapper">
        <!-- Left Side: Campus Event Banner -->
        <div class="left-banner">
            <div class="banner-overlay"></div>
            
            <div class="banner-content">
                <div class="university-badge">
                    <i class="fa-solid fa-graduation-cap"></i> NSBM Green University
                </div>

                <div class="banner-text">
                    <h1>Welcome to <br><span>NSBM EventHub</span></h1>
                    <p>Organise, manage, and participate in university club events in one seamless platform.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="right-form-side">
            <div class="form-container">
                <div class="university-tag-right">
                    <i class="fa-solid fa-calendar-check"></i> EventHub Portal
                </div>
                
            <?php if ($registered): ?>

                <div class="success-message">
                    <i class="fa-solid fa-circle-check"></i>
                    Account created successfully! Please sign in.
                </div>

            <?php endif; ?>

            <?php if (!empty($error)): ?>

                <div class="login-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>


                
                <br>

                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Enter your credentials to access your account</p>
                </div>

                

                <!-- Form -->
                <form id="loginForm" action="login.php" method="POST">
                    <input type="hidden" name="user_role" id="userRole" value="student">

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-box">
                            <i class="fa-regular fa-envelope field-icon"></i>
                            <input type="email" id="email" name="email" placeholder="student@nsbm.ac.lk" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-box">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                            <i class="fa-regular fa-eye toggle-eye" id="togglePassword" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="remember-me">
                            <input type="checkbox" name="remember"> Remember me
                        </label>
                        
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Sign In</span>
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </form>

                <div class="card-footer">
                    <p>Don't have an account? <a href="../auth/register.php">Register Here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/auth.js"></script>
</body>
</html>
