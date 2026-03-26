<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
include '../dbconnection.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['job_title'];
    $bio = $_POST['bio'];

    $stmt = $conn->prepare("UPDATE Staff SET JobTitle = ?, Bio = ? WHERE StaffID = ?");
    $stmt->bind_param("ssi", $title, $bio, $id);
    
    if ($stmt->execute()) {
        $msg = "Profile updated successfully!";
    }
}

$stmt = $conn->prepare("SELECT * FROM Staff WHERE StaffID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$staff = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff | Nexus Institute</title>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f9fafb; padding: 40px; }
        .form-card { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        input, textarea { width: 100%; padding: 12px; margin: 10px 0 20px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; }
        .btn-save { background: #4f46e5; color: white; border: none; padding: 14px; width: 100%; border-radius: 8px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Edit Profile: <?php echo htmlspecialchars($staff['Name']); ?></h2>
        <?php if($msg) echo "<p style='color:green;'>$msg</p>"; ?>
        
        <form method="POST">
            <label>Job Title (e.g. Senior Lecturer)</label>
            <input type="text" name="job_title" value="<?php echo htmlspecialchars($staff['JobTitle'] ?? ''); ?>" required>

            <label>Staff Biography</label>
            <textarea name="bio" rows="6" placeholder="Describe the staff member's experience..."><?php echo htmlspecialchars($staff['Bio'] ?? ''); ?></textarea>

            <button type="submit" class="btn-save">Update Rich Profile</button>
            <a href="manage_staff.php" style="display:block; text-align:center; margin-top:15px; color:#666; text-decoration:none;">Cancel</a>
        </form>
    </div>
</body>
</html>