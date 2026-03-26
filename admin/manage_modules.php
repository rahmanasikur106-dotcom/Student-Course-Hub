<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
include '../dbconnection.php';

// Handle Deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM Modules WHERE ModuleID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_modules.php?msg=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Modules | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 40px; }
        .container { max-width: 1100px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        h2 { margin: 0; color: #1a202c; font-size: 24px; }
        
        .btn-add { background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 500; transition: background 0.2s; }
        .btn-add:hover { background: #1d4ed8; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; text-align: left; padding: 15px; border-bottom: 2px solid #e2e8f0; }
        td { padding: 15px; border-bottom: 1px solid #edf2f7; font-size: 14px; vertical-align: top; }
        tr:hover { background-color: #fcfcfd; }

        .module-name { font-weight: 600; color: #1e293b; margin-bottom: 4px; display: block; }
        .description { color: #64748b; font-size: 13px; line-height: 1.5; max-width: 400px; }
        .leader-badge { background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }

        .action-links a { text-decoration: none; font-weight: 500; margin-right: 12px; font-size: 13px; }
        .edit-link { color: #2563eb; }
        .delete-link { color: #dc2626; }
        
        .back-link { display: inline-block; margin-top: 25px; color: #64748b; text-decoration: none; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h2>Course Modules</h2>
        <a href="add_module.php" class="btn-add">+ Add New Module</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div style="padding: 12px; background: #f0fdf4; color: #166534; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            Module updated successfully.
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Module Details</th>
                <th>Leader</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Join with Staff table to get the Leader's Name
            $sql = "SELECT m.*, s.Name as LeaderName 
                    FROM Modules m 
                    LEFT JOIN Staff s ON m.ModuleLeaderID = s.StaffID 
                    ORDER BY m.ModuleID ASC";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td style='color: #94a3b8;'>#{$row['ModuleID']}</td>
                            <td>
                                <span class='module-name'>".htmlspecialchars($row['ModuleName'])."</span>
                                <div class='description'>".htmlspecialchars($row['Description'])."</div>
                            </td>
                            <td><span class='leader-badge'>".htmlspecialchars($row['LeaderName'] ?? 'Unassigned')."</span></td>
                            <td class='action-links'>
                                <a href='edit_module.php?id={$row['ModuleID']}' class='edit-link'>Edit</a>
                                <a href='manage_modules.php?delete={$row['ModuleID']}' 
                                   onclick='return confirm(\"Delete this module?\")' 
                                   class='delete-link'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center; padding: 40px; color: #94a3b8;'>No modules found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>