<?php
require_once "/xampp/htdocs/updates/thrifting_system/query/connection.php";
session_start();

$getSessionEmail = $_SESSION["email"] ?? "No session for email!";
$getSessionName = $_SESSION["name"] ?? "No session for name!";
$getSessionPassword = $_SESSION["password"] ?? "No session for password!";
$getSessionUserId = $_SESSION["user_id"] ?? "No session for user id!";
$getSessionRole = $_SESSION["role"] ?? "No session for role!";

if (!isset($_SESSION["role"])) {
    header("Location: ../sign-in-page.php");
    exit();
}

// Prepare SQL query
$sqlFetchCart = "SELECT * FROM ordered WHERE user_id = ?";
$sqlStmt = $conn->prepare($sqlFetchCart);
$sqlStmt->bind_param("i", $getSessionUserId);
$sqlStmt->execute();
$result = $sqlStmt->get_result();

function isActive($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../sign-in-page.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <a href=""></a>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($getSessionRole == "Admin") : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('shop-page.php') ?>" href="shop-page.php"><i class="fa-solid fa-house text-white"></i> Shop</a>
                        </li>
                        <li>
                            <a class="nav-link <?= isActive('cart-page.php') ?>" href="cart-page.php"><i class="fa-solid fa-cart-plus text-white"></i> Cart</a>
                        </li>
                        <li>
                            <a class="nav-link <?= isActive('orders-page.php') ?>" href="orders-page.php"><i class="fa-solid fa-check text-white"></i> Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('../admin/inventory-page.php') ?>" href="../admin/inventory-page.php"><i class="fa-solid fa-list-check text-white"></i> Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('../admin/users-page.php') ?>" href="../admin/users-page.php"><i class="fa-solid fa-user text-white"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('profile-page.php') ?>" href="profile-page.php"><i class="fa-solid fa-address-card"></i> Profile</a>
                        </li>
                    <?php elseif ($getSessionRole == "Customer") : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('shop-page.php') ?>" href="shop-page.php"><i class="fa-solid fa-cart-shopping text-white"></i> Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('cart-page.php') ?>" href="cart-page.php"><i class="fa-solid fa-cart-plus text-white"></i> Cart</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('orders-page.php') ?>" href="orders-page.php"><i class="fa-solid fa-check text-white"></i> Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('profile-page.php') ?>" href="profile-page.php"><i class="fa-solid fa-address-card"></i> Profile</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($getSessionRole != "No session for role!") : ?>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="?logout=true"><i class="fa-solid fa-right-from-bracket text-danger"></i> Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="m-4">
        <div class="container-fluid p-4 rounded-2 shadow border">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="fw-500 fs-5 m-2">Order History</h1>
                <form action="http://localhost/updates/thrifting_system/query/cart.php" method="post">
                    <input type="hidden" name="initiate-cart-user-id" value="<?= htmlspecialchars($getSessionUserId) ?>">
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
                        <th>Ordered Date</th>
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
                                <td>₱<?= htmlspecialchars($row["subtotal"]) ?></td>
                                <td><?= htmlspecialchars($row["ordered_date"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center bg-danger text-light">You haven't ordered anything yet!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
