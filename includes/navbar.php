<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-3">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
            <img src="assets/images/logo.png" alt="EventHub Logo" height="42" class="me-2">

            <span class="text-dark">Event</span>
            <span style="color: var(--primary);">Hub</span>
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0 shadow-none" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar">

            <i class="fa-solid fa-bars"></i>

        </button>

        <div class="collapse navbar-collapse" id="navbar">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="#">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#events">Events</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Clubs</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>

            </ul>

            <div class="d-flex gap-2">

                <a href="auth/login.php" class="btn btn-outline-custom">
                    Login
                </a>

                <a href="auth/register.php" class="btn btn-primary-custom">
                    Register
                </a>

            </div>

        </div>

    </div>
</nav>