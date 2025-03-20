<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sign-in-btn"])) {
    $getSignInEmail = $_POST["sign-in-email"];
    $getSignInName = $_POST["sign-in-name"];
    $getSignInPassword = $_POST["sign-in-password"];

    $sqlSelect = "SELECT email, name, password, user_id, role, status FROM registered_users WHERE email = ? AND name = ? AND password = ?";
    $sqlStmt = $conn->prepare($sqlSelect);
    $sqlStmt->bind_param("sss", $getSignInEmail, $getSignInName, $getSignInPassword);
    $sqlStmt->execute();
    $sqlStmt->store_result();

    if ($sqlStmt->num_rows > 0) {
        $sqlStmt->bind_result($getSignInEmail, $getSignInName, $getSignInPassword, $getSignInUserId, $role, $status);
        $sqlStmt->fetch();

        if ($status == "Enabled") {
            session_start();
            $_SESSION["email"] = $getSignInEmail;
            $_SESSION["name"] = $getSignInName;
            $_SESSION["password"] = $getSignInPassword;
            $_SESSION["user_id"] = $getSignInUserId;
            $_SESSION["role"] = $role;
    
            if ($role == "Admin") {
                echo "<script>alert('Login granted!'); window.location.href = '../models/dependencies/shop-page.php';</script>";
            } else if ($role == "Customer") {
                echo "<script>alert('Login granted!'); window.location.href = '../models/dependencies/shop-page.php';</script>";
            } else {
                echo "<script>alert('Failed to fetch this data to a new website!'); window.location.href = '../models/sign-in-page.php';</script>";
            }
        
        } elseif ($status == "Disabled") {
            echo "<script>alert('Your account has been disabled by the admin!'); window.location.href = '../models/sign-in-page.php';</script>";
        }

    } else {
        echo "<script>alert('Login denied!'); window.location.href = '../models/sign-in-page.php';</script>";
    }

    $sqlStmt->close();
}
$conn->close();
?>