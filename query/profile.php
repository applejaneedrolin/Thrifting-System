<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["profile-save-btn"])) {
    $getProfileName = $_POST["profile-name"] ?? null;
    $getProfilePassword = $_POST["profile-password"] ?? null;
    $getProfileAddress = $_POST["profile-address"] ?? null;
    $getProfilePhoneNumber = $_POST["profile-phone-number"] ?? null;
    $getProfileUserId = $_POST["profile-user-id"] ?? null;

    if (strlen($getProfileName) <= 4 || strlen($getProfilePassword) <= 4) {
        echo "
        <script>
        alert('The fields are not valid! Must be more than 4 characters.');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/profile-page.php';
        </script>
        ";
        exit();
    }

    $sqlUpdate = "UPDATE registered_users SET name = ?, password = ?, address = ?, phone_number = ? WHERE user_id = ?";
    $sqlStmt = $conn->prepare($sqlUpdate);
    $sqlStmt->bind_param("ssssi", $getProfileName, $getProfilePassword, $getProfileAddress, $getProfilePhoneNumber, $getProfileUserId);

    if ($sqlStmt->execute()) {
        echo "
        <script>
        alert('Saved changes!');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/profile-page.php';
        </script>
        ";
    } else {
        echo "<script>alert('Update failed. Please try again.');</script>";
    }

    $sqlStmt->close();
}

$conn->close();
?>
