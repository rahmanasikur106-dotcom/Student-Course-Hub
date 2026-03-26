<?php
session_start();
// Security: Only logged-in admins can delete
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

include '../dbconnection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Delete the programme from the database
    $sql = "DELETE FROM programmes WHERE ProgrammeID = $id";
    
    if ($conn->query($sql)) {
        // Redirect back to the management page with a success message
        header("Location: manage_programmes.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    header("Location: manage_programmes.php");
}
exit();
?>