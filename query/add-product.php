<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-product-btn"])) {
    $getProductImage = file_get_contents($_FILES["add-product-image"]["tmp_name"]);
    $getProductName = $_POST["add-product-name"];
    $getProductPrice = $_POST["add-product-price"];
    $getProductType = $_POST["add-product-type"];
    $getProductCategory = $_POST["add-product-category"];
    $getProductSize = $_POST["add-product-size"];
    $getProductStock = $_POST["add-product-stock"];
    $getProductStatus = $_POST["add-product-status"];

    if ($getProductImage && $getProductName && $getProductPrice && $getProductType && $getProductCategory && $getProductSize && $getProductStock && $getProductStatus) {
        $sqlSelect = "SELECT COUNT(*) FROM products WHERE name = ? AND type = ? AND category = ?";
        $sqlStmt = $conn->prepare($sqlSelect);
        $sqlStmt->bind_param("sss", $getProductName, $getProductType, $getProductCategory);
        $sqlStmt->execute();
        $sqlStmt->bind_result($count);
        $sqlStmt->fetch();
        $sqlStmt->close();

        if ($count > 0) {
            echo "
            <script>
            alert('This product was added already!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
            </script>
            ";
            exit();
        }

        $sqlInsert = "INSERT INTO products (image, name, price, type, category, size, stock, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $sqlStmt = $conn->prepare($sqlInsert);
        $sqlStmt->bind_param("ssisssis", $getProductImage, $getProductName, $getProductPrice, $getProductType, $getProductCategory, $getProductSize, $getProductStock, $getProductStatus);
        $sqlStmt->execute();

        if ($sqlStmt->affected_rows > 0) {
            echo "
            <script>
            alert('Product added!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
            </script>
            ";
        } else {
            echo "
            <script>
            alert('Failed to add product.');
            window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
            </script>
            ";
        }

        $sqlStmt->close();
    } else {
        echo "
        <script>
        alert('Please fill in all fields.');
        window.location.href = 'http://localhost/updates/thrifting_system/models/admin/inventory-page.php';
        </script>
        ";
    }
}
$conn->close();
?>