<?php
require_once "../query/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buy-btn"])) {
    $getCartUserId = $_POST["initiate-cart-user-id"] ?? null;
    $getCartMainId = $_POST["initiate-cart-main-id"] ?? null;
    $getCartId = $_POST["initiate-cart-id"] ?? null;
    $getCartBuyer = $_POST["initiate-cart-buyer"] ?? null;
    $getCartName = $_POST["initiate-cart-name"] ?? null;
    $getCartPrice = $_POST["initiate-cart-price"] ?? null;
    $getCartType = $_POST["initiate-cart-type"] ?? null;
    $getCartCategory = $_POST["initiate-cart-category"] ?? null;
    $getCartSize = $_POST["initiate-cart-size"] ?? null;
    $getCartQuantity = $_POST["initiate-cart-quantity"] ?? null;
    $getCartSubtotal = number_format(($getCartPrice * $getCartQuantity), 2);

    $sqlFetch = "SELECT stock, revenue FROM products WHERE product_id = ?";
    $stmtFetch = $conn->prepare($sqlFetch);
    $stmtFetch->bind_param("i", $getCartId);
    $stmtFetch->execute();
    $stmtFetch->bind_result($currentStock, $currentRevenue);
    $stmtFetch->fetch();
    $stmtFetch->close();

    if ($currentStock >= $getCartQuantity) {
        $newStock = $currentStock - $getCartQuantity;
        $newRevenue = $currentRevenue + ($getCartPrice * $getCartQuantity);

        $sqlUpdate = "UPDATE products SET stock = ?, revenue = ?, purchased_counter = purchased_counter + 1 WHERE product_id = ?";
        $sqlStmt = $conn->prepare($sqlUpdate);
        $sqlStmt->bind_param("idi", $newStock, $newRevenue, $getCartId);

        if ($sqlStmt->execute()) {
            $sqlInsert = "INSERT INTO ordered (user_id, product_id, buyer_name, name, price, type, category, size, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $sqlStmt = $conn->prepare($sqlInsert);
            $sqlStmt->bind_param("iissdsssid", $getCartUserId, $getCartId, $getCartBuyer, $getCartName, $getCartPrice, $getCartType, $getCartCategory, $getCartSize, $getCartQuantity, $getCartSubtotal);

            $sqlDelete = "DELETE FROM cart WHERE cart_id = ?";
            $stmt = $conn->prepare($sqlDelete);
            $stmt->bind_param("i", $getCartMainId);

            if ($stmt->execute() && $sqlStmt->execute()) {
                echo "
                <script>
                alert('Purchase successful!');
                window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
                </script>
                ";
            }

            $sqlStmt->close();
            $stmt->close();

        } else {
            echo "
            <script>
            alert('Error processing purchase!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
            </script>
            ";
        }

        $sqlStmt->close();

    } else {
        echo "
        <script>
        alert('Not enough stock available!');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
        </script>
        ";
    }
}

elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove-btn"])) {
    $getCartMainId = $_POST["initiate-cart-main-id"] ?? null;

    $sqlDelete = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $getCartMainId);

    if ($stmt->execute()) {
        echo "
        <script>
        alert('Item removed successfully!');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
        </script>
        ";

    } else {
        echo "
        <script>
        alert('Error deleting item!');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
        </script>
        ";
    }

    $stmt->close();

} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove-all-btn"])) {
    $getUserId = $_POST["initiate-cart-user-id"] ?? null;

    if ($getUserId) {
        $sqlDelete = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sqlDelete);
        $stmt->bind_param("i", $getUserId);

        if ($stmt->execute()) {
            echo "
            <script>
            alert('All items removed successfully! {$getUserId}');
            window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
            </script>
            ";
        } else {
            echo "
            <script>
            alert('Error deleting items!');
            window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
            </script>
            ";
        }

        $stmt->close();

    } else {
        echo "
        <script>
        alert('User ID not found!');
        window.location.href = 'http://localhost/updates/thrifting_system/models/dependencies/cart-page.php';
        </script>
        ";
    }
}

$conn->close();
?>
