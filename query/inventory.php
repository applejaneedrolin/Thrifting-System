<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit-btn"])) {
    $getManageId = $_POST["manage-id"] ?? isset($_POST["manage-id"]);
    $getManageName = $_POST["manage-name"] ?? isset($_POST["manage-name"]);
    $getManagePrice = $_POST["manage-price"] ?? isset($_POST["manage-price"]);
    $getManageType = $_POST["manage-type"] ?? isset($_POST["manage-type"]);
    $getManageCategory = $_POST["manage-category"] ?? isset($_POST["manage-category"]);
    $getManageSize = $_POST["manage-size"] ?? isset($_POST["manage-size"]);
    $getManageStock = $_POST["manage-stock"] ?? isset($_POST["manage-stock"]);

    $sqlEdit = "UPDATE products SET name = ?, price = ?, type = ?, category = ?, size = ?, stock = ? WHERE product_id = ?";
    $sqlStmt = $conn->prepare($sqlEdit);
    $sqlStmt->bind_param("sdsssii", $getManageName, $getManagePrice, $getManageType, $getManageCategory, $getManageSize, $getManageStock, $getManageId);

    if ($sqlStmt->execute()) {
        echo "
        <script>
        alert('Update successful! Id: {$getManageId} / Name: {$getManageName} / Price: {$getManagePrice} / Type: {$getManageType} / Category: {$getManageCategory} / Size: {$getManageSize} / Stock: {$getManageStock}');
        window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
        </script>";
    } else {
        echo "
        <script>
        alert('Update failed! Please check your input.');
        window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
        </script>";
    }

    $sqlStmt->close();

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["status-btn"])) {
    $getManageId = $_POST["manage-id"] ?? isset($_POST["manage-id"]);

    $sqlSelect = "SELECT status FROM products WHERE product_id = ?";
    $sqlStmt = $conn->prepare($sqlSelect);
    $sqlStmt->bind_param("i", $getManageId);
    $sqlStmt->execute();
    $sqlStmt->bind_result($getManageStatus);
    $sqlStmt->fetch();
    $sqlStmt->close();

    if ($getManageStatus == "Active") {
        $getManageStatus = "Inactive";

        $sqlUpdate = "UPDATE products SET status = ? WHERE product_id = ?";
        $sqlStmt = $conn->prepare($sqlUpdate);
        $sqlStmt->bind_param("si", $getManageStatus, $getManageId);
        
        if ($sqlStmt->execute()) {
            echo "
            <script>
            alert('Updated Status: {$getManageStatus}');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
            </script>";
        }
    
    $sqlStmt->close();

    } elseif ($getManageStatus == "Inactive") {
        $getManageStatus = "Active";

        $sqlUpdate = "UPDATE products SET status = ? WHERE product_id = ?";
        $sqlStmt = $conn->prepare($sqlUpdate);
        $sqlStmt->bind_param("si", $getManageStatus, $getManageId);
        
        if ($sqlStmt->execute()) {
            echo "
            <script>
            alert('Updated Status: {$getManageStatus}');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
            </script>";
        }
    
        $sqlStmt->close();
    }
}

$conn->close();
?>
