<?php 
include 'dbconnection.php'; 
$p_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Fetch Programme with RICH STAFF details using Prepared Statements (Security Requirement)
$prog_sql = "SELECT p.*, s.Name as LeaderName, s.JobTitle, s.Bio, l.LevelName 
             FROM Programmes p 
             JOIN Staff s ON p.ProgrammeLeaderID = s.StaffID 
             JOIN Levels l ON p.LevelID = l.LevelID 
             WHERE p.ProgrammeID = ? AND p.IsPublished = 1";

$stmt = $conn->prepare($prog_sql);
$stmt->bind_param("i", $p_id);
$stmt->execute();
$programme = $stmt->get_result()->fetch_assoc();

if (!$programme) { 
    die("<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h2>Programme Not Available</h2>
            <p>This course is currently being updated or does not exist.</p>
            <a href='index.php'>Return to Home</a>
         </div>"); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($programme['ProgrammeName']); ?> | Nexus Institute</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Modern Staff Profile Styles */
        .staff-spotlight {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px;
            margin: 40px 0;
            border: 1px solid #f1f5f9;
            display: flex;
            gap: 30px;
            align-items: flex-start;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .staff-avatar {
            width: 100px;
            height: 100px;
            background: #4f46e5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            flex-shrink: 0;
        }
        .staff-details h3 { margin: 0; font-size: 1.5rem; color: #0f172a; }
        .staff-title { color: #4f46e5; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; display: block; }
        .staff-bio { color: #64748b; line-height: 1.6; margin-top: 15px; }
        
        .module-leader-tag { 
            font-size: 0.75rem; 
            color: #4f46e5; 
            background: #f5f3ff; 
            padding: 4px 10px; 
            border-radius: 6px; 
            font-weight: 600;
        }
    </style>
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

    <div class="container" style="margin-top: 50px;">
        <span class="category-tag"><?php echo htmlspecialchars($programme['LevelName']); ?></span>
        <h1 class="programme-title" style="font-size: 3.5rem; font-weight: 800; letter-spacing: -0.04em;">
            <?php echo htmlspecialchars($programme['ProgrammeName']); ?>
        </h1>

        <div class="info-card">
            <h2>About this Programme</h2>
            <p class="description" style="font-size: 1.1rem; line-height: 1.7; color: #334155;">
                <?php echo nl2br(htmlspecialchars($programme['Description'])); ?>
            </p>
            <a href="register.php?id=<?php echo $p_id; ?>" class="register-interest-btn">Register Interest in this Course</a>
        </div>

        <h2 class="section-title">Academic Leadership</h2>
        <div class="staff-spotlight">
            <div class="staff-avatar">
                <?php echo substr($programme['LeaderName'], 0, 1); ?>
            </div>
            <div class="staff-details">
                <span class="staff-title"><?php echo htmlspecialchars($programme['JobTitle'] ?? 'Lead Academic'); ?></span>
                <h3><?php echo htmlspecialchars($programme['LeaderName']); ?></h3>
                <p class="staff-bio">
                    <?php echo nl2br(htmlspecialchars($programme['Bio'] ?? 'Our programme leaders are dedicated to student success and academic excellence. Full biography details coming soon.')); ?>
                </p>
            </div>
        </div>

        <h2 class="section-title">Course Structure</h2>
        <div class="timeline">
            <?php
            for ($year = 1; $year <= 3; $year++) {
                // Prepared statement for modules for security consistency
                $mod_stmt = $conn->prepare("SELECT m.*, s.Name as ModuleLeader 
                                           FROM ProgrammeModules pm 
                                           JOIN Modules m ON pm.ModuleID = m.ModuleID 
                                           JOIN Staff s ON m.ModuleLeaderID = s.StaffID 
                                           WHERE pm.ProgrammeID = ? AND pm.Year = ?");
                $mod_stmt->bind_param("ii", $p_id, $year);
                $mod_stmt->execute();
                $res = $mod_stmt->get_result();

                if ($res->num_rows > 0) {
                    echo "<div class='year-marker'><h3>Year $year</h3></div>";
                    echo "<div class='grid' style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;'>";
                    while ($mod = $res->fetch_assoc()) {
                        echo "<div class='card' style='background:white; padding:25px; border-radius:18px; border:1px solid #f1f5f9;'>
                                <h4 style='margin-top:0; color:#0f172a;'>" . htmlspecialchars($mod['ModuleName']) . "</h4>
                                <p style='color:#64748b; font-size:0.9rem;'>" . htmlspecialchars($mod['Description']) . "</p>
                                <div style='margin-top:15px;'>
                                    <span class='module-leader-tag'>Leader: " . htmlspecialchars($mod['ModuleLeader']) . "</span>
                                </div>
                              </div>";
                    }
                    echo "</div><br><br>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>