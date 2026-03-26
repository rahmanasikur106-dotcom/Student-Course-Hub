<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Check if admin is logged in
if (!isset($_SESSION['admin_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "student_course_hub";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optimized Query joining InterestedStudents with Programmes to show the course name
$sql = "SELECT i.StudentName, i.Email, p.ProgrammeName, i.RegisteredAt 
        FROM InterestedStudents i 
        JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID 
        ORDER BY i.RegisteredAt DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prospective Students | Nexus Institute</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #4f46e5;
            --brand-soft: rgba(79, 70, 229, 0.1);
            --bg: #f9fafb;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            margin: 0;
            color: var(--text-main);
        }

        /* --- Navigation (Updated Branding) --- */
        .navbar {
            background: #ffffff;
            padding: 1rem 8%;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-text-group {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .logo-main {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--text-main);
            letter-spacing: -0.02em;
        }

        .logo-sub {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* --- Content --- */
        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 0 40px;
        }

        .page-header {
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .page-header h1 { font-size: 2rem; font-weight: 800; margin: 0; letter-spacing: -0.03em; }
        .page-header p { color: var(--text-muted); margin: 8px 0 0 0; }

        .back-link {
            text-decoration: none;
            color: var(--brand);
            font-weight: 700;
            font-size: 0.9rem;
            transition: opacity 0.2s;
        }
        .back-link:hover { opacity: 0.8; }

        /* --- Table Styling --- */
        .table-container {
            background: white;
            border-radius: 24px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: #f8fafc;
            padding: 18px 24px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 20px 24px;
            font-size: 0.95rem;
            border-bottom: 1px solid var(--border);
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #fcfdfe; }

        .student-name { font-weight: 600; color: var(--text-main); }
        .email-cell { color: var(--text-muted); font-family: inherit; }
        
        .programme-badge {
            display: inline-block;
            padding: 6px 14px;
            background: var(--brand-soft);
            color: var(--brand);
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        .empty-state {
            padding: 80px;
            text-align: center;
            color: var(--text-muted);
        }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="dashboard.php" class="brand">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="8" fill="#4f46e5"/>
                <path d="M10 10V22M10 10L22 22M22 22V10" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <div class="logo-text-group">
                <span class="logo-main">Nexus Institute</span>
                <span class="logo-sub">OF TECHNOLOGY</span>
            </div>
        </a>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </nav>

    <div class="container">
        <div class="page-header">
            <div>
                <h1>Prospective Students</h1>
                <p>Mailing list of individuals interested in academic programmes.</p>
            </div>
        </div>

        <div class="table-container">
            <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email Address</th>
                        <th>Programme of Interest</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="student-name"><?php echo htmlspecialchars($row['StudentName']); ?></td>
                        <td class="email-cell"><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><span class="programme-badge"><?php echo htmlspecialchars($row['ProgrammeName']); ?></span></td>
                        <td><?php echo date('M d, Y', strtotime($row['RegisteredAt'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <p>No student interests found in the database.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>