<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>NSBM EventHub</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    <!--NavBar-->
    <?php include 'includes/navbar.php'; ?>

    <!--Hero sec--> 

    <section class="hero">

    <div class="container">

        <div class="row align-items-center">

            <!-- Left -->

            <div class="col-lg-6">

                <span class="hero-badge">
                    🎓 University Event Management
                </span>

                <h1 class="hero-title">

                    Plan.<br>

                    Discover.<br>

                    <span>Experience.</span>

                </h1>

                <p class="hero-text">

                    The modern platform for discovering, organizing,
                    and managing university events with ease.

                </p>

                <div class="hero-buttons">

                    <a href="#events" class="btn btn-primary-custom">

                        Explore Events

                    </a>

                    <a href="#" class="btn btn-outline-custom">

                        Learn More

                    </a>

                </div>

            </div>

            <!-- Right -->

            <div class="col-lg-6">

                <div class="dashboard-preview">

                    <div class="preview-header">

                        EventHub Dashboard

                    </div>

                    <div class="preview-card">

                        Upcoming Events

                        <span>12</span>

                    </div>

                    <div class="preview-card">

                        Registrations

                        <span>358</span>

                    </div>

                    <div class="preview-card">

                        Announcements

                        <span>5</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!--Hero section bottom-->
<section class="stats-section">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-3">

                <div class="stat-card">

                    <h2>50+</h2>

                    <p>University Events</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <h2>15+</h2>

                    <p>Student Clubs</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <h2>2500+</h2>

                    <p>Registrations</p>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <h2>100%</h2>

                    <p>Digital Experience</p>

                </div>

            </div>

        </div>

    </div>

</section>

<!--Upcoming Events-->

<section id="events" class="events-section">

    <div class="container">

        <div class="section-header">

            <span class="section-badge">
                Upcoming Events
            </span>

            <h2>
                Explore What's Happening
            </h2>

            <p>
                Discover workshops, competitions, seminars and student activities across NSBM.
            </p>

        </div>

        <!-- Leaderboard Banner -->

        <div class="leaderboard">

            <a href="#">

                <img src="assets/images/banners/explore-events.png"
                     alt="Explore Events">

            </a>

        </div>

        <!-- Event Cards -->

        <div class="row g-4 mt-2">

            <!-- Cards will come here -->
            <div class="col-lg-3 col-md-6">

                 <div class="event-card">

                    <div class="event-image">

                     <img src="assets/images/events/ai-workshop.png" alt="AI Workshop">

                     <span class="event-category">
                           Workshop
                     </span>

                     <span class="event-date">
                         30 Jul
                     </span>

                    </div>

                <div class="event-content">

                    <h4>
                     AI & Cybersecurity Workshop
                    </h4>

                    <p>
                     Learn ethical hacking, artificial intelligence and modern cyber defence techniques.
                    </p>

                    <div class="event-info">

                      <div>
                         <i class="fa-solid fa-location-dot"></i>
                         Faculty of Computing
                       </div>

                   <div>
                    <i class="fa-solid fa-users"></i>
                    87 / 120 Registered
                    </div>

                </div>

                <div class="progress">

                  <div class="progress-bar"
                    style="width:72%"></div>

                </div>

                <a href="#" class="event-link">

                  View Details

                <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

        </div>


    </div>





    <div class="col-lg-3 col-md-6">

    <div class="event-card">

        <div class="event-image">

            <img src="assets/images/events/hackathon.png" alt="Hackathon">

            <span class="event-category">
                Competition
            </span>

            <span class="event-date">
                05 Aug
            </span>

        </div>

        <div class="event-content">

            <h4>
                24-Hour Hackathon
            </h4>

            <p>
                Build innovative software solutions with your team and compete for exciting prizes.
            </p>

            <div class="event-info">

                <div>
                    <i class="fa-solid fa-location-dot"></i>
                    Innovation Lab
                </div>

                <div>
                    <i class="fa-solid fa-users"></i>
                    142 of 150 Registered
                </div>

            </div>

            <div class="progress">
                <div class="progress-bar" style="width:95%"></div>
            </div>

            <a href="#" class="event-link">
                View Details
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>

    </div>

</div>

<div class="col-lg-3 col-md-6">

    <div class="event-card">

        <div class="event-image">

            <img src="assets/images/events/career-fair.png" alt="Career Fair">

            <span class="event-category">
                Career
            </span>

            <span class="event-date">
                15 Aug
            </span>

        </div>

        <div class="event-content">

            <h4>
                Career & Internship Fair
            </h4>

            <p>
                Meet leading companies, explore internships and kick-start your professional career.
            </p>

            <div class="event-info">

                <div>
                    <i class="fa-solid fa-location-dot"></i>
                    Auditorium
                </div>

                <div>
                    <i class="fa-solid fa-users"></i>
                    230 of 300 Registered
                </div>

            </div>

            <div class="progress">
                <div class="progress-bar" style="width:77%"></div>
            </div>

            <a href="#" class="event-link">
                View Details
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>

    </div>

</div>

<div class="col-lg-3 col-md-6">

    <div class="event-card">

        <div class="event-image">

            <img src="assets/images/events/cultural-night.png" alt="Cultural Night">

            <span class="event-category">
                Cultural
            </span>

            <span class="event-date">
                28 Aug
            </span>

        </div>

        <div class="event-content">

            <h4>
                NSBM Cultural Night
            </h4>

            <p>
                Celebrate music, dance and cultural performances with students from every faculty.
            </p>

            <div class="event-info">

                <div>
                    <i class="fa-solid fa-location-dot"></i>
                    Main Grounds
                </div>

                <div>
                    <i class="fa-solid fa-users"></i>
                    410 of 500 Registered
                </div>

            </div>

            <div class="progress">
                <div class="progress-bar" style="width:82%"></div>
            </div>

            <a href="#" class="event-link">
                View Details
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>

    </div>

</div>

    </div>

    </div>

</section>


<!-- About EventHub -->
<section id="about" class="about-section py-5">

    <div class="container">

        <div class="row align-items-center g-5">

            <!-- Left Content -->
            <div class="col-lg-6">

                <span class="section-tag">
                    About EventHub
                </span>

                <h2 class="section-title mt-3">
                    Your Campus.<br>
                    Your Events.<br>
                    All in One Place.
                </h2>

                <p class="section-description mt-4">
                    EventHub is a centralized university event management platform
                    designed to simplify how students discover, register and stay
                    updated with campus events.
                </p>

                <p class="section-description">
                    From technical workshops and hackathons to cultural festivals
                    and club activities, EventHub keeps every event just one click
                    away while helping organizers manage registrations effortlessly.
                </p>

                <div class="feature-list mt-4">

                    <div>
                        <i class="fa-solid fa-circle-check"></i>
                        Browse upcoming events
                    </div>

                    <div>
                        <i class="fa-solid fa-circle-check"></i>
                        Quick online registration
                    </div>

                    <div>
                        <i class="fa-solid fa-circle-check"></i>
                        Instant announcements
                    </div>

                    <div>
                        <i class="fa-solid fa-circle-check"></i>
                        Connect with student clubs
                    </div>

                </div>

                <div class="mt-5">

                    <a href="#" class="btn btn-primary-custom me-3">
                        Login
                    </a>

                    <a href="#" class="btn btn-outline-custom">
                        Register
                    </a>

                </div>

            </div>

            <!-- Right Side -->
            <div class="col-lg-6">

                <div class="dashboard-preview">

                    <div class="dashboard-header">

                        <span></span>
                        <span></span>
                        <span></span>

                    </div>

                    <div class="dashboard-card">

                        <h5>📅 Upcoming Events</h5>

                        <div class="dashboard-item">
                            AI & Cybersecurity Workshop
                        </div>

                        <div class="dashboard-item">
                            24-Hour Hackathon
                        </div>

                        <div class="dashboard-item">
                            Career & Internship Fair
                        </div>

                        <div class="dashboard-item">
                            NSBM Cultural Night
                        </div>

                    </div>

                    <div class="dashboard-stats">

                        <div class="mini-card">

                            <h3>2,500+</h3>

                            <p>Students</p>

                        </div>

                        <div class="mini-card">

                            <h3>15+</h3>

                            <p>Student Clubs</p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
 <?php include 'includes/footer.php'; ?>




</body>

</html>