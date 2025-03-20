<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add-to-cart-btn"])) {
    $getAddToCartUserId = $_POST["add-to-cart-user-id"] ?? null;
    $getAddToCartId = $_POST["add-to-cart-id"] ?? null;
    $getAddToCartName = $_POST["add-to-cart-name"] ?? null;
    $getAddToCartPrice = $_POST["add-to-cart-price"] ?? null;
    $getAddToCartType = $_POST["add-to-cart-type"] ?? null;
    $getAddToCartCategory = $_POST["add-to-cart-category"] ?? null;
    $getAddToCartSize = $_POST["add-to-cart-size"] ?? null;
    $getAddToCartStock = $_POST["add-to-cart-stock"] ?? null;
    $getAddToCartStatus = $_POST["add-to-cart-status"] ?? null;
    $getAddToCartQuantity = $_POST["add-to-cart-quantity"] ?? null;

    if ($getAddToCartStatus == "Active") {
        if ($getAddToCartStock != 0 && $getAddToCartQuantity > 0 && $getAddToCartQuantity <= $getAddToCartStock) {
            $sqlCheck = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bind_param("ii", $getAddToCartUserId, $getAddToCartId);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
    
            if ($resultCheck->num_rows > 0) {
                echo "
                <script>
                alert('This product is already in your cart!');
                window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/shop-page.php';
                </script>
                ";
            } else {
                $sqlInsert = "INSERT INTO cart (user_id, product_id, name, price, type, category, size, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $sqlStmt = $conn->prepare($sqlInsert);
                $sqlStmt->bind_param("iisdsssi", $getAddToCartUserId, $getAddToCartId, $getAddToCartName, $getAddToCartPrice, $getAddToCartType, $getAddToCartCategory, $getAddToCartSize, $getAddToCartQuantity);
    
                if ($sqlStmt->execute()) {
                    echo "
                    <script>
                    alert('Product added!');
                    window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/shop-page.php';
                    </script>
                    ";
                }
            }
        } else {
            echo "
            <script>
            alert('Failed to get this item!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/shop-page.php';
            </script>
            ";
        }
    } elseif ($getAddToCartStatus == "Inactive") {
        echo "
        <script>
        alert('This item is inactive!');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/shop-page.php';
        </script>
        ";
    }
}
$conn->close();
?>
