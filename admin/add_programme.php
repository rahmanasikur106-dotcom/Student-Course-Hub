<?php
session_start();
// Security check: Only let logged-in admins in
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
include '../dbconnection.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $level = intval($_POST['level']);
    $leader = intval($_POST['leader']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $published = isset($_POST['published']) ? 1 : 0;

    $sql = "INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, IsPublished) 
            VALUES ('$name', $level, $leader, '$desc', $published)";

    if ($conn->query($sql)) {
        $message = "<p style='color: green; font-weight: bold;'>Success! Programme added to the catalog.</p>";
    } else {
        $message = "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Programme</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Degree Programme</h2>
        <hr>
        <?php echo $message; ?>
        
        <form method="POST" class="card" style="max-width: 600px; margin-top: 20px; padding: 20px;">
            <label><strong>Programme Name:</strong></label><br>
            <input type="text" name="name" placeholder="e.g. BSc Computer Science" required style="width:100%; padding: 8px; margin: 10px 0;">

            <label><strong>Study Level:</strong></label><br>
            <select name="level" style="width:100%; padding: 8px; margin: 10px 0;">
                <?php
                $levels = $conn->query("SELECT * FROM levels");
                while($l = $levels->fetch_assoc()) {
                    echo "<option value='{$l['LevelID']}'>{$l['LevelName']}</option>";
                }
                ?>
            </select>

            <label><strong>Programme Leader:</strong></label><br>
            <select name="leader" style="width:100%; padding: 8px; margin: 10px 0;">
                <?php
                $staff = $conn->query("SELECT * FROM staff");
                while($s = $staff->fetch_assoc()) {
                    echo "<option value='{$s['StaffID']}'>{$s['Name']}</option>";
                }
                ?>
            </select>

            <label><strong>Description:</strong></label><br>
            <textarea name="description" rows="5" style="width:100%; padding: 8px; margin: 10px 0;"></textarea>

            <label style="display: block; margin: 15px 0;">
                <input type="checkbox" name="published" checked> Make visible to students immediately
            </label>

            <button type="submit" class="btn">Add to Database</button>
            <a href="dashboard.php" style="margin-left: 15px; text-decoration: none; color: #666;">Cancel</a>
        </form>
    </div>
</body>
</html>