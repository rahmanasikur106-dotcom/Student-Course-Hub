<?php
include 'dbconnection.php';

// Capture filters
$level_filter = isset($_GET['level']) ? mysqli_real_escape_string($conn, $_GET['level']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Query logic: Only fetch results if a filter or search is active
$result = null;
if ($level_filter != '' || $search != '') {
    $query = "SELECT p.*, l.LevelName FROM Programmes p 
              JOIN Levels l ON p.LevelID = l.LevelID WHERE 1=1"; 
    
    if ($level_filter != '') {
        $query .= " AND l.LevelName = '$level_filter'";
    }
    if ($search != '') { 
        $query .= " AND p.ProgrammeName LIKE '%$search%'"; 
    }
    $result = $conn->query($query);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Institute of Technology</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
   <a href="index.php" class="logo-wrapper">
    <div class="logo-icon">N</div> 
    
    <div class="logo-text-group">
        <span class="logo-main">Nexus Institute</span>
        <span class="logo-sub">OF TECHNOLOGY</span>
    </div>
</a>
        <a href="admin/login.php" class="admin-link">Administration Portal</a>
    </nav>

    <header class="hero-slider">
        <div class="slide-container">
            <img src="images/Campus1.jpg" class="slide-image active">
            <img src="images/Campus2.jpg" class="slide-image">
            <img src="images/Campus3.jpg" class="slide-image">
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <p class="hero-sub">SHAPE THE FUTURE</p>
            <h1>Find Your Programme</h1>
            <p>Explore our world-class technology and engineering courses.</p>
        </div>
    </header>

    <div class="container">
        <form method="GET" action="index.php" class="floating-search">
            <input type="text" name="search" placeholder="What do you want to learn today?" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>

        <div class="info-section">
            <div class="program-links">
                <h2>Our study programmes</h2>
                <ul>
                    <li><a href="index.php?level=Undergraduate"><span>→</span> Undergraduate Programmes</a></li>
                    <li><a href="index.php?level=Postgraduate"><span>→</span> Postgraduate Programmes</a></li>
                    <li><a href="index.php?level=International Summer School"><span>→</span> International Summer School</a></li>
                </ul>
            </div>

            <div class="campus-info">
                <span class="label">Campus</span>
                <h3>Nexus campus cities</h3>
                <p>Welcome to the heart of our technology hub. Explore our vibrant campus life across our primary locations.</p>
                <a href="#" class="read-more">→ Read more</a>
            </div>
        </div>

        <?php if ($level_filter != '' || $search != ''): ?>
        <div class="results-area">
            <div class="results-header">
                <h3>Showing Results for: <?php echo htmlspecialchars($level_filter ?: $search); ?></h3>
                <a href="index.php" class="clear-btn">Clear Filter ×</a>
            </div>

            <div class="grid">
                <?php if($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="card">
                            <span class="card-badge"><?php echo $row['LevelName']; ?></span>
                            <h3><?php echo htmlspecialchars($row['ProgrammeName']); ?></h3>
                            <p><?php echo substr($row['Description'], 0, 110); ?>...</p>
                            <a href="programme_details.php?id=<?php echo $row['ProgrammeID']; ?>" class="view-more">View Modules →</a>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Hero Slider Logic
        const slides = document.querySelectorAll('.slide-image');
        let current = 0;
        if(slides.length > 0) {
            setInterval(() => {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 4000);
        }
    </script>
</body>
</html>