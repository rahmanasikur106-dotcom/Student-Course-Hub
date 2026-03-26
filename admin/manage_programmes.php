<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }
include '../dbconnection.php';

// Handle Deletion using correct table and column name
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Note: Database uses 'Programmes' (capital P)
    $stmt = $conn->prepare("DELETE FROM Programmes WHERE ProgrammeID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_programmes.php?msg=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Programmes | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h2 { margin: 0; color: #1a202c; font-size: 24px; }
        
        .btn-add {
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-add:hover { background: #1d4ed8; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8fafc;
            color: #64748b;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
            text-align: left;
            padding: 15px;
            border-bottom: 2px solid #e2e8f0;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #edf2f7;
            font-size: 14px;
        }
        tr:hover { background-color: #fcfcfd; }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-active { background: #dcfce7; color: #166534; }

        .action-links a {
            text-decoration: none;
            font-weight: 500;
            margin-right: 10px;
            font-size: 13px;
        }
        .edit-link { color: #2563eb; }
        .delete-link { color: #dc2626; }
        
        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h2>Manage University Programmes</h2>
        <a href="add_programme.php" class="btn-add">+ Add New Programme</a>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div style="padding: 12px; background: #f0fdf4; color: #166534; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
            Action completed successfully.
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Level</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Queries match the exact table and column names from your SQL schema
            $sql = "SELECT p.*, l.LevelName 
                    FROM Programmes p 
                    JOIN Levels l ON p.LevelID = l.LevelID";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td style='color: #94a3b8;'>#{$row['ProgrammeID']}</td>
                            <td style='font-weight: 500;'>".htmlspecialchars($row['ProgrammeName'])."</td>
                            <td>".htmlspecialchars($row['LevelName'])."</td>
                            <td><span class='badge badge-active'>Active</span></td>
                            <td class='action-links'>
                                <a href='edit_programme.php?id={$row['ProgrammeID']}' class='edit-link'>Edit</a>
                                <a href='manage_programmes.php?delete={$row['ProgrammeID']}' 
                                   onclick='return confirm(\"Delete this programme?\")' 
                                   class='delete-link'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' style='text-align:center; padding: 30px;'>No programmes found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>