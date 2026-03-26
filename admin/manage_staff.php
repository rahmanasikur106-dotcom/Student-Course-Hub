<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
include '../dbconnection.php';

// Fetch all staff
$result = $conn->query("SELECT * FROM Staff ORDER BY Name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff | Nexus Institute</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f9fafb; padding: 40px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { text-align: left; padding: 12px; border-bottom: 2px solid #eee; color: #64748b; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 15px 12px; border-bottom: 1px solid #eee; }
        .btn-edit { color: #4f46e5; text-decoration: none; font-weight: 700; }
        .staff-name { font-weight: 700; color: #1e293b; }
        .job-badge { background: #eef2ff; color: #4f46e5; padding: 4px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Staff Profiles</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Job Title</th>
                    <th>Bio Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="staff-name"><?php echo htmlspecialchars($row['Name']); ?></td>
                    <td><span class="job-badge"><?php echo htmlspecialchars($row['JobTitle'] ?? 'Faculty'); ?></span></td>
                    <td><?php echo !empty($row['Bio']) ? '✅ Complete' : '⚠️ Missing'; ?></td>
                    <td><a href="edit_staff.php?id=<?php echo $row['StaffID']; ?>" class="btn-edit">Edit Profile</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <br>
        <a href="dashboard.php" style="color: #64748b; text-decoration: none;">← Back to Dashboard</a>
    </div>
</body>
</html>