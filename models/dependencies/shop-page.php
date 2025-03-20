<?php
require_once "/xampp/htdocs/updates/thrifting_system/query/connection.php";
session_start();

// Fetch session details safely
$getSessionEmail = $_SESSION["email"] ?? "No session for email!";
$getSessionName = $_SESSION["name"] ?? "No session for name!";
$getSessionPassword = $_SESSION["password"] ?? "No session for password!";
$getSessionUserId = $_SESSION["user_id"] ?? "No session for user id!";
$getSessionRole = $_SESSION["role"] ?? "No session for role!";

// Handle filters
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$typeFilter = isset($_GET['type']) ? $_GET['type'] : '';

// Build the SQL query with filters
$sqlFetchProducts = "SELECT product_id, image, name, price, type, category, size, stock, status, date_added FROM products";
$conditions = [];

if (!empty($categoryFilter)) {
    $conditions[] = "category = '$categoryFilter'";
}
if (!empty($typeFilter)) {
    $conditions[] = "type = '$typeFilter'";
}
if (!empty($conditions)) {
    $sqlFetchProducts .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sqlFetchProducts);

// Fetch all categories for the filter
$sqlFetchCategories = "SELECT DISTINCT category FROM products";
$categoriesResult = $conn->query($sqlFetchCategories);
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row['category'];
}

// Fetch all types for the filter
$sqlFetchTypes = "SELECT DISTINCT type FROM products";
$typeResult = $conn->query($sqlFetchTypes);
$types = [];
while ($row = $typeResult->fetch_assoc()) {
    $types[] = $row['type'];
}

// Logout handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../sign-in-page.php");
    exit();
}

function isActive($page) {
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="http://localhost/updates/thrifting_system/script/script.js" defer></script>
    <link rel="stylesheet" href="http://localhost/updates/thrifting_system/includes/css/style.css">
    <style>
        td {
            vertical-align: middle;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body class="bg-light">

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
                            <a class="nav-link active <?= isActive('shop-page.php') ?>" href="shop-page.php"><i class="fa-solid fa-house text-white"></i> Shop</a>
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
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('../sign-in-page.php') ?>" href="../sign-in-page.php"><i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('../sign-up-page.php') ?>" href="../sign-up-page.php"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
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
        <h1 class="fw-500 fs-5 m-2">Our Products</h1>
            <form method="GET" class="mb-2">
                <div class="d-flex align-items-center gap-2">
                    <select name="category" class="form-select shadow">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= htmlspecialchars($category) ?>" <?= $categoryFilter == $category ? 'selected' : '' ?>><?= htmlspecialchars($category) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="type" class="form-select shadow">
                        <option value="">All Types</option>
                        <?php foreach ($types as $type) : ?>
                            <option value="<?= htmlspecialchars($type) ?>" <?= $typeFilter == $type ? 'selected' : '' ?>><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn btn-success shadow w-25"><i class="fa-solid fa-arrow-right"></i> Filter</button>
                </div>
            </form>
            <input type="text" class="form-control mb-2" id="search-box" placeholder="Enter the item name to search" onkeyup="filterTable()">
            <table class="table table-striped table-bordered table-hover table-sm text-center shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="product-table">
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td style="width: 150px;">
                                    <?php if (!empty($row['image'])) : ?>
                                        <img class="rounded-2 shadow" src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                                    <?php else : ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>₱<?= number_format($row["price"], 2) ?></td>
                                <td><?= htmlspecialchars($row['type']) ?></td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td><?= htmlspecialchars($row['size']) ?></td>
                                <td><?= htmlspecialchars($row['stock']) ?></td>
                                <td style="color: <?= $row['status'] === 'Active' ? 'green' : 'red' ?>;">
                                    <?= htmlspecialchars($row['status']) ?>
                                </td>
                                <td><?= htmlspecialchars($row['date_added']) ?></td>
                                <td style="width: 150px;">
                                <?php if ($getSessionRole == "Customer" || $getSessionRole == "Admin") : ?>
                                    <form action="http://localhost/updates/thrifting_system/query/add-to-cart.php" method="post">
                                        <input type="hidden" name="add-to-cart-user-id" value="<?= htmlspecialchars($getSessionUserId) ?>">
                                        <input type="hidden" name="add-to-cart-id" value="<?= htmlspecialchars($row['product_id']) ?>">
                                        <input type="hidden" name="add-to-cart-name" value="<?= htmlspecialchars($row['name']) ?>">
                                        <input type="hidden" name="add-to-cart-price" value="<?= htmlspecialchars($row['price']) ?>">
                                        <input type="hidden" name="add-to-cart-type" value="<?= htmlspecialchars($row['type']) ?>">
                                        <input type="hidden" name="add-to-cart-category" value="<?= htmlspecialchars($row['category']) ?>">
                                        <input type="hidden" name="add-to-cart-size" value="<?= htmlspecialchars($row['size']) ?>">
                                        <input type="hidden" name="add-to-cart-stock" value="<?= htmlspecialchars($row['stock']) ?>">
                                        <input type="hidden" name="add-to-cart-status" value="<?= htmlspecialchars($row['status']) ?>">
                                        <input type="number" name="add-to-cart-quantity" class="form-control mb-2" placeholder="Quantity" required>
                                        <button name="add-to-cart-btn" class="btn btn-primary"><i class="fa-solid fa-cart-arrow-down"></i> Add to Cart</button>
                                    </form>
                                <?php else : ?>
                                    <a href="../sign-in-page.php" class="btn btn-danger"><i class="fa-solid fa-lock"></i> Locked</a>
                                <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-light bg-danger">There's no available products appeared!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function filterTable() {
            let input = document.getElementById("search-box").value.toLowerCase();
            let table = document.getElementById("product-table");
            let rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                let columns = rows[i].getElementsByTagName("td");
                let match = false;

                for (let j = 0; j < columns.length; j++) {
                    if (columns[j].innerText.toLowerCase().includes(input)) {
                        match = true;
                        break;
                    }
                }

                rows[i].style.display = match ? "" : "none";
            }
        }
    </script>

</body>
</html>