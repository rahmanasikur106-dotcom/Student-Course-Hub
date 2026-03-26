<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['admin_id'])) { 
    header("Location: login.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Admin Console</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #4f46e5;
            --brand-soft: #f5f7ff;
            --bg: #f9fafb;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #f1f5f9;
            --white: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            margin: 0;
            color: var(--text-main);
        }

        /* --- Navigation --- */
        .navbar {
            background: var(--white);
            padding: 1.2rem 8%;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        /* --- STACKED LOGO FIX --- */
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .logo-text-group {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .university-name-main {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
            color: var(--text-main);
        }

        .university-name-sub {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg);
            padding: 8px 18px;
            border-radius: 99px;
            border: 1px solid var(--border);
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
        }

        /* --- Content Area --- */
        .container {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 40px;
        }

        header.welcome { margin-bottom: 48px; }
        header.welcome h1 { 
            font-size: 2.5rem; 
            font-weight: 800; 
            margin: 0; 
            letter-spacing: -0.04em; 
        }
        header.welcome p { 
            color: var(--text-muted); 
            margin-top: 10px; 
            font-size: 1.1rem;
        }

        /* --- The Grid --- */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 28px;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 35px;
            text-decoration: none;
            color: inherit;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-8px);
            border-color: var(--brand);
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.08);
        }

        .card-icon {
            width: 54px;
            height: 54px;
            background: var(--brand-soft);
            color: var(--brand);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
        }

        .card h3 { 
            margin: 0 0 12px 0; 
            font-size: 1.3rem; 
            font-weight: 700; 
            letter-spacing: -0.02em;
        }
        
        .card p { 
            font-size: 0.95rem; 
            color: var(--text-muted); 
            line-height: 1.6; 
            margin-bottom: 28px; 
            flex-grow: 1; 
        }

        .card-link { 
            font-size: 0.9rem; 
            font-weight: 700; 
            color: var(--brand); 
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Security Card Override */
        .logout-card:hover { border-color: #ef4444; box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.08); }
        .logout-card .card-link { color: #ef4444; }
        .logout-card .card-icon { background: #fef2f2; color: #ef4444; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a href="dashboard.php" class="brand">
            <svg width="36" height="36" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="32" height="32" rx="10" fill="#4f46e5"/>
                <path d="M10 10V22M10 10L22 22M22 22V10" stroke="white" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            
            <div class="logo-text-group">
                <span class="university-name-main">Nexus Institute</span>
                <span class="university-name-sub">OF TECHNOLOGY</span>
            </div>
        </a>
        
        <div class="user-pill">
            <span class="status-dot"></span>
            Admin: <strong><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Authorized User'); ?></strong>
        </div>
    </nav>

    <div class="container">
        <header class="welcome">
            <h1>Administrative Console</h1>
            <p>Institutional management and data overview for the current academic term.</p>
        </header>

        <div class="dashboard-grid">
            <a href="manage_programmes.php" class="card">
                <div class="card-icon">🎓</div>
                <h3>Programmes</h3>
                <p>Curate undergraduate and postgraduate academic pathways. Update entry requirements and descriptions.</p>
                <div class="card-link">Manage Content →</div>
            </a>

            <a href="manage_modules.php" class="card">
                <div class="card-icon">📚</div>
                <h3>Course Modules</h3>
                <p>Assign module leaders, define credits, and update the curriculum syllabus.</p>
                <div class="card-link">Edit Modules →</div>
            </a>

            <a href="view_interests.php" class="card">
                <div class="card-icon">👥</div>
                <h3>Prospective Students</h3>
                <p>Access the Expressed Interest database to manage the primary student mailing list.</p>
                <div class="card-link">View Records →</div>
            </a>

            <a href="logout.php" class="card logout-card">
                <div class="card-icon">🔐</div>
                <h3>Security</h3>
                <p>Terminate your current administrative session and clear temporary access tokens.</p>
                <div class="card-link">Sign Out →</div>
            </a>
        </div>
    </div>

</body>
</html>