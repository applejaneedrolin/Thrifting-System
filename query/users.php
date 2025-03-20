<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["manage-user-btn"])) {
    $getManageUserEmail = $_POST["manage-user-email"] ?? null;
    $getManageUserName = $_POST["manage-user-name"] ?? null;
    $getManageUserUserId = $_POST["manage-user-user-id"] ?? null;
    $getManageRole = $_POST["manage-user-role"] ?? null;
    $getManageStatus = $_POST["manage-user-status"] ?? null;

    if ($getManageStatus == "Enabled") {
        $newStatus = "Disabled";

        $sqlUpdate = "UPDATE registered_users SET status = ? WHERE user_id = ?";
        $sqlStmt = $conn->prepare($sqlUpdate);
        $sqlStmt->bind_param("si", $newStatus, $getManageUserUserId);
        
        if ($sqlStmt->execute()) {
            echo "
            <script>
            alert('You disabled {$getManageUserName}!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/users-page.php';
            </script>
            ";
        }

        $sqlStmt->close();
    
    } elseif ($getManageStatus == "Disabled") {
        $newStatus = "Enabled";

        $sqlUpdate = "UPDATE registered_users SET status = ? WHERE user_id = ?";
        $sqlStmt = $conn->prepare($sqlUpdate);
        $sqlStmt->bind_param("si", $newStatus, $getManageUserUserId);
        
        if ($sqlStmt->execute()) {
            echo "
            <script>
            alert('You enabled {$getManageUserName}!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/users-page.php';
            </script>
            ";
        }

        $sqlStmt->close();
    }
}

$conn->close();
?>