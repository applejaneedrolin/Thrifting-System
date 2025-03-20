<?php
require_once "../query/connection.php";

function generateUniqueUserId($conn) {
    do {
        $randomInt = rand(1, 9999);
        $count = 0;

        $sqlCheck = "SELECT COUNT(*) FROM registered_users WHERE user_id = ?";
        $stmt = $conn->prepare($sqlCheck);
        $stmt->bind_param("i", $randomInt);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

    } while ($count > 0);

    return $randomInt;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sign-up-btn"])) {
    $getSignUpEmail = $_POST["sign-up-email"];
    $getSignUpName = $_POST["sign-up-name"];
    $getSignUpPassword = $_POST["sign-up-password"];
    $defaultRole = "Customer";

    if (!empty($getSignUpEmail) && !empty($getSignUpName) && !empty($getSignUpPassword)) {
        $userId = generateUniqueUserId($conn);

        $sqlCheckEmail = "SELECT COUNT(*) FROM registered_users WHERE email = ?";
        $stmt = $conn->prepare($sqlCheckEmail);
        $stmt->bind_param("s", $getSignUpEmail);
        $stmt->execute();
        $stmt->bind_result($emailCount);
        $stmt->fetch();
        $stmt->close();

        if ($emailCount == 0) {
            $newStatus = "Enabled";
            $sqlInsert = "INSERT INTO registered_users (name, email, user_id, password, role, status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bind_param("ssisss", $getSignUpName, $getSignUpEmail, $userId, $getSignUpPassword, $defaultRole, $newStatus);

            if ($stmt->execute()) {
                echo "<script>alert('Sign-up successful!'); window.location.href = '../models/sign-in-page.php';</script>";
            } else {
                echo "<script>alert('Error: Could not sign up.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Email already exists!'); window.location.href = '../models/sign-up-page.php';</script>";
        }
    }
}
$conn->close();
?>
