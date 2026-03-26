<?php 
include 'dbconnection.php'; 
$message = "";
$p_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['student_name']);
    $email = htmlspecialchars($_POST['email']);
    $prog = intval($_POST['programme_id']);

    // Note: Ensure your table name matches your DB (InterestedStudents)
    $stmt = $conn->prepare("INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $prog, $name, $email);
    
    try {
        if ($stmt->execute()) { 
            $message = "<div class='alert success'>Registration successful! We will contact you soon.</div>"; 
        }
    } catch (Exception $e) { 
        $message = "<div class='alert error'>You have already registered for this programme.</div>"; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Interest | Nexus Institute</title>
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
            /* Background matches your Dashboard */
            background: linear-gradient(rgba(249, 250, 251, 0.8), rgba(249, 250, 251, 0.8)), 
                        url('https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1986&auto=format&fit=crop'); 
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            padding: 50px;
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        /* Stacked Logo Style */
        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .logo-icon {
            background: var(--brand);
            color: white;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .logo-text-group {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .logo-main { font-weight: 800; font-size: 1.1rem; color: var(--text-main); }
        .logo-sub { font-size: 0.6rem; font-weight: 700; color: var(--text-muted); letter-spacing: 0.1em; text-transform: uppercase; }

        h2 { font-weight: 800; font-size: 1.8rem; margin-bottom: 10px; letter-spacing: -0.02em; }
        p.subtitle { color: var(--text-muted); margin-bottom: 30px; font-size: 0.95rem; }

        /* Form Styling */
        .form-group { text-align: left; margin-bottom: 20px; }
        label { display: block; font-weight: 700; font-size: 0.85rem; margin-bottom: 8px; color: var(--text-main); }
        
        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-family: inherit;
            font-size: 1rem;
            box-sizing: border-box;
            transition: 0.2s;
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
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: var(--brand-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .back-link:hover { color: var(--brand); }

        /* Messages */
        .alert { padding: 12px; border-radius: 10px; font-size: 0.9rem; font-weight: 600; margin-bottom: 20px; }
        .success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .error { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="logo-section">
            <div class="logo-icon">N</div>
            <div class="logo-text-group">
                <span class="logo-main">Nexus Institute</span>
                <span class="logo-sub">OF TECHNOLOGY</span>
            </div>
        </div>

        <h2>Register Interest</h2>
        <p class="subtitle">Join the next generation of tech leaders.</p>

        <?php echo $message; ?>

        <form method="POST">
            <input type="hidden" name="programme_id" value="<?php echo $p_id; ?>">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="student_name" placeholder="e.g. John Doe" required>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" required>
            </div>

            <button type="submit">Submit Registration</button>
        </form>

        <a href="index.php" class="back-link">← Back to Programmes</a>
    </div>

</body>
</html>