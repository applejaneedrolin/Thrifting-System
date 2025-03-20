<?php
require_once "/xampp/htdocs/updates/thrifting_system/query/connection.php";
session_start();

// Get session data safely
$getSessionUserId = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;
$getSessionName = $_SESSION["name"] ?? "No session for name!";
$getSessionRole = $_SESSION["role"] ?? "No session for role!";

if (!isset($_SESSION["role"])) {
    header("Location: ../sign-in-page.php");
    exit();
}

// Check if user is logged in
// if ($getSessionUserId == 0) {
//     echo "No valid session for user.";
//     exit();
// }

// Prepare SQL query
$sqlFetchCart = "SELECT cart_id, product_id, name, price, type, category, size, quantity FROM cart WHERE user_id = ?";
$sqlStmt = $conn->prepare($sqlFetchCart);
$sqlStmt->bind_param("i", $getSessionUserId);
$sqlStmt->execute();
$result = $sqlStmt->get_result();

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../sign-in-page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
         td {
            vertical-align: middle;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Thrifting Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($getSessionRole == "Admin") : ?>
                        <li class="nav-item"><a class="nav-link" href="shop-page.php"><i class="fa-solid fa-house text-white"></i> Shop</a></li>
                        <li class="nav-item"><a class="nav-link active" href="cart-page.php"><i class="fa-solid fa-cart-plus text-white"></i> Cart</a></li>
                        <li class="nav-item"><a class="nav-link" href="orders-page.php"><i class="fa-solid fa-check text-white"></i> Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/inventory-page.php"><i class="fa-solid fa-list-check text-white"></i> Inventory</a></li>
                        <li class="nav-item"><a class="nav-link" href="../admin/users-page.php"><i class="fa-solid fa-user text-white"></i> Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile-page.php"><i class="fa-solid fa-address-card"></i> Profile</a></li>
                    <?php elseif ($getSessionRole == "Customer") : ?>
                        <li class="nav-item"><a class="nav-link" href="shop-page.php"><i class="fa-solid fa-cart-shopping text-white"></i> Shop</a></li>
                        <li class="nav-item"><a class="nav-link active" href="cart-page.php"><i class="fa-solid fa-cart-plus text-white"></i> Cart</a></li>
                        <li class="nav-item"><a class="nav-link" href="orders-page.php"><i class="fa-solid fa-check text-white"></i> Orders</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile-page.php"><i class="fa-solid fa-address-card"></i> Profile</a></li>
                    <?php endif; ?>

                    <?php if ($getSessionRole != "No session for role!") : ?>
                        <li class="nav-item"><a class="nav-link text-danger" href="?logout=true"><i class="fa-solid fa-right-from-bracket text-danger"></i> Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="m-4">
        <div class="container-fluid p-4 rounded-2 shadow border">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="fw-500 fs-5 m-2">Your Cart</h1>
                <form action="http://localhost/updates/thrifting_system/query/cart.php" method="post">
                    <input type="hidden" name="initiate-cart-user-id" value="<?= htmlspecialchars($getSessionUserId) ?>">
                    <button class="btn btn-danger" name="remove-all-btn"><i class="fa-solid fa-xmark"></i> Remove All</button>
                </form>
            </div>
                
            <table class="table table-striped table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row["name"]) ?></td>
                                <td>₱<?= number_format($row["price"], 2) ?></td>
                                <td><?= htmlspecialchars($row["type"]) ?></td>
                                <td><?= htmlspecialchars($row["category"]) ?></td>
                                <td><?= htmlspecialchars($row["size"]) ?></td>
                                <td><?= htmlspecialchars($row["quantity"]) ?></td>
                                <td>₱<?= number_format($row["quantity"] * $row["price"], 2) ?></td>
                                <td style="width: 250px;">
                                    <form action="http://localhost/updates/thrifting_system/query/cart.php" method="post">
                                        <input type="hidden" name="initiate-cart-user-id" value="<?= htmlspecialchars($getSessionUserId)?>">
                                        <input type="hidden" name="initiate-cart-main-id" value="<?= htmlspecialchars($row["cart_id"]) ?>">
                                        <input type="hidden" name="initiate-cart-id" value="<?= htmlspecialchars($row["product_id"]) ?>">
                                        <input type="hidden" name="initiate-cart-buyer" value="<?= htmlspecialchars($getSessionName) ?>">
                                        <input type="hidden" name="initiate-cart-name" value="<?= htmlspecialchars($row["name"]) ?>">
                                        <input type="hidden" name="initiate-cart-price" value="<?= htmlspecialchars($row["price"]) ?>">
                                        <input type="hidden" name="initiate-cart-type" value="<?= htmlspecialchars($row["type"]) ?>">
                                        <input type="hidden" name="initiate-cart-category" value="<?= htmlspecialchars($row["category"]) ?>">
                                        <input type="hidden" name="initiate-cart-size" value="<?= htmlspecialchars($row["size"]) ?>">
                                        <input type="hidden" name="initiate-cart-quantity" value="<?= htmlspecialchars($row["quantity"]) ?>">
                                        <button name="buy-btn" class="btn btn-primary"><i class="fa-solid fa-credit-card"></i> Buy</button>
                                        <button name="remove-btn" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center bg-danger text-light">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
