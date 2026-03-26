<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
include '../dbconnection.php';

$message = "";
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1. Handle the Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Make sure these names match the 'name' attribute in your HTML below
    $name = $_POST['name'];
    $level = intval($_POST['level']);
    $leader = intval($_POST['leader']); // This was causing your "leader" error
    $desc = $_POST['description'];
    $published = isset($_POST['published']) ? 1 : 0;

    // Use prepared statements for Security marks
    $stmt = $conn->prepare("UPDATE Programmes SET ProgrammeName=?, LevelID=?, ProgrammeLeaderID=?, Description=?, IsPublished=? WHERE ProgrammeID=?");
    $stmt->bind_param("siisii", $name, $level, $leader, $desc, $published, $id);

    if ($stmt->execute()) {
        $message = "<div style='background:#dcfce7; color:#166534; padding:15px; border-radius:10px; margin-bottom:20px;'>✔ Programme updated successfully!</div>";
    } else {
        $message = "<div style='background:#fef2f2; color:#991b1b; padding:15px; border-radius:10px; margin-bottom:20px;'>❌ Error: " . $conn->error . "</div>";
    }
}

// 2. Fetch data
$stmt = $conn->prepare("SELECT * FROM Programmes WHERE ProgrammeID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();

if (!$current) { die("Programme not found."); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Programme | Nexus Institute</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f9fafb; padding: 40px; color: #0f172a; }
        .card { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 24px; box-shadow: 0 10px 15px rgba(0,0,0,0.05); }
        h2 { margin-top: 0; font-weight: 800; }
        label { display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 8px; color: #64748b; text-transform: uppercase; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 20px; font-family: inherit; box-sizing: border-box; }
        .btn-save { background: #4f46e5; color: white; border: none; padding: 15px; width: 100%; border-radius: 12px; font-weight: 700; cursor: pointer; font-size: 1rem; }
    </style>
</head>
<body>

<div class="card">
    <h2>Edit Programme</h2>
    <?php echo $message; ?>

    <form method="POST">
        <label>Programme Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($current['ProgrammeName']); ?>" required>

        <label>Level</label>
        <select name="level">
            <?php
            $levels = $conn->query("SELECT * FROM Levels");
            while($l = $levels->fetch_assoc()) {
                $sel = ($l['LevelID'] == $current['LevelID']) ? "selected" : "";
                echo "<option value='{$l['LevelID']}' $sel>{$l['LevelName']}</option>";
            }
            ?>
        </select>

        <label>Programme Leader</label>
        <select name="leader"> <?php
            $staff = $conn->query("SELECT * FROM Staff");
            while($s = $staff->fetch_assoc()) {
                $sel = ($s['StaffID'] == $current['ProgrammeLeaderID']) ? "selected" : "";
                echo "<option value='{$s['StaffID']}' $sel>{$s['Name']}</option>";
            }
            ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="5"><?php echo htmlspecialchars($current['Description']); ?></textarea>

        <label style="display:flex; align-items:center; gap:10px; text-transform:none; margin-bottom:20px;">
            <input type="checkbox" name="published" style="width:20px; margin:0;" <?php echo ($current['IsPublished'] ?? 1) ? 'checked' : ''; ?>> 
            Published (Visible to students)
        </label>

        <button type="submit" class="btn-save">Save Changes</button>
        <a href="manage_programmes.php" style="display:block; text-align:center; margin-top:15px; color:#64748b; text-decoration:none;">Cancel</a>
    </form>
</div>

</body>
</html>