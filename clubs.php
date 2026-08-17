<?php
// Include database connection
include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSBM EventHub - Student Clubs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href=".../assets/css/style.css">
</head>
<body>

    <!-- Include Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Clubs Section -->
    <section class="container py-5 mt-4">
        <div class="text-center mb-5">
            <span class="badge bg-success px-3 py-2 rounded-pill">Campus Communities</span>
            <h2 class="fw-bold mt-2">Explore Student Clubs</h2>
            <p class="text-muted">Discover and join active student clubs across NSBM.</p>
        </div>

        <div class="row g-4">
            <?php
            // Fetch clubs from database
            $sql = "SELECT * FROM clubs";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Safe variables
                    $clubName = htmlspecialchars($row['club_name']);
                    $description = htmlspecialchars($row['description']);
                    
                    // Default image fallback if none specified
                    $clubImage = !empty($row['image']) ? $row['image'] : "assets/images/1222.png"; 
            ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border border-dark rounded-4 overflow-hidden">
                            <img src="<?php echo $clubImage; ?>" class="card-img-top" alt="<?php echo $clubName; ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-4">
                                <h4 class="card-title fw-bold text-dark"><?php echo $clubName; ?></h4>
                                <p class="card-text text-muted mt-2"><?php echo $description; ?></p>
                                
                            </div>
                        </div>
                    </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center"><p class="text-muted">No clubs found in the database yet.</p></div>';
            }
            ?>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>