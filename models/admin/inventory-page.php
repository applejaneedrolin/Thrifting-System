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

$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

$sqlFetchProducts = "SELECT * FROM products";
$sqlFetchAgainProducts = "SELECT * FROM products";
$sqlFetchOrdered = "SELECT * FROM ordered";

if (!empty($categoryFilter)) {
    $sqlFetchProducts .= " WHERE category = '$categoryFilter'";
}

$result = $conn->query($sqlFetchProducts);
$resultAgain = $conn->query($sqlFetchAgainProducts);
$resultOrdered = $conn->query($sqlFetchOrdered);

function isActive($page)
{
    return basename($_SERVER['PHP_SELF']) == $page ? 'active' : '';
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../sign-in-page.php");
    exit();
}

// Fetch all categories for the filter
$sqlFetchCategories = "SELECT DISTINCT category FROM products";
$categoriesResult = $conn->query($sqlFetchCategories);
$categories = [];
if ($categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            scroll-behavior: smooth;
        }
        td {
            vertical-align: middle;
            text-align: center;
            font-weight: 500;
            width: 150px;
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
                            <a class="nav-link <?= isActive('../dependencies/shop-page.php') ?>" href="../dependencies/shop-page.php"><i class="fa-solid fa-house text-white"></i> Shop</a>
                        </li>
                        <li>
                            <a class="nav-link <?= isActive('../dependencies/cart-page.php') ?>" href="../dependencies/cart-page.php"><i class="fa-solid fa-cart-plus text-white"></i> Cart</a>
                        </li>
                        <li>
                            <a class="nav-link <?= isActive('../dependencies/orders-page.php') ?>" href="../dependencies/orders-page.php"><i class="fa-solid fa-check text-white"></i> Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active <?= isActive('./inventory-page.php') ?>" href="./inventory-page.php"><i class="fa-solid fa-list-check text-white"></i> Inventory</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('./users-page.php') ?>" href="./users-page.php"><i class="fa-solid fa-user text-white"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isActive('../dependencies/profile-page.php') ?>" href="../dependencies/profile-page.php"><i class="fa-solid fa-address-card"></i> Profile</a>
                        </li>
                    <?php elseif ($getSessionRole == "Customer") : ?>
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
        <div class="container-fluid border rounded-2 p-3 bg-light text-dark shadow">
            <h1 class="fw-500 fs-5 m-2">Add Product</h1>
            <form action="http://localhost/updates/thrifting_system/query/add-product.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-3 text-center">
                        <img class="rounded-2 shadow" src="https://media.istockphoto.com/id/1324356458/vector/picture-icon-photo-frame-symbol-landscape-sign-photograph-gallery-logo-web-interface-and.jpg?s=612x612&w=0&k=20&c=ZmXO4mSgNDPzDRX-F8OKCfmMqqHpqMV6jiNi00Ye7rE=" alt="item-model" id="preview-image" style="width: 175px; height: 175px;">
                        <h1 class="fw-normal fs-5 m-2">Preview</h1>
                        <input type="file" name="add-product-image" id="add-product-image" class="form-control" required>
                    </div>
                    <div class="col-3">
                        <h1 class="fw-normal fs-5 m-2">Name</h1>
                        <input type="text" name="add-product-name" id="add-product-name" class="form-control" placeholder="Enter product's name" required>
                        <h1 class="fw-normal fs-5 m-2">Price</h1>
                        <input type="number" name="add-product-price" id="add-product-price" class="form-control" placeholder="Enter product's price" required>
                        <h1 class="fw-normal fs-5 m-2">Type</h1>
                        <select name="add-product-type" id="add-product-type" class="form-control" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="Shirt">Shirt</option>
                            <option value="T-Shirt">T-shirt</option>
                            <option value="Pants">Pants</option>
                            <option value="Shorts">Shirts</option>
                            <option value="Underwear">Underwear</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <h1 class="fw-normal fs-5 m-2">Category</h1>
                        <select name="add-product-category" id="add-product-category" class="form-control" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="Adult">Adult</option>
                            <option value="Teen">Teen</option>
                            <option value="Kid">Kid</option>
                            <option value="Baby">Baby</option>
                        </select>
                        <h1 class="fw-normal fs-5 m-2">Stock</h1>
                        <input type="number" name="add-product-stock" id="add-product-stock" class="form-control" placeholder="Enter product's stock" required>
                        <h1 class="fw-normal fs-5 m-2">Status</h1>
                        <select name="add-product-status" id="add-product-status" class="form-control" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-3">
                        <h1 class="fw-normal fs-5 m-2">Size</h1>
                        <select name="add-product-size" id="add-product-size" class="form-control" required>
                            <option value="" disabled selected>Select Size</option>
                            <option value="Extra Large">XL (Extra Large)</option>
                            <option value="Large">L (Large)</option>
                            <option value="Medium">M (Medium)</option>
                            <option value="Small">S (Small)</option>
                            <option value="Extra Small">ES (Extra Small)</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" name="add-product-btn" id="add-product-btn" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>

    <div class="m-4 bg-light border p-3 rounded-2 shadow">
        <h1 class="fw-500 fs-5 m-2">Edit or Manage Product</h1>
        <form method="GET" class="mb-2">
            <div class="d-flex align-items-center gap-2">
                <select name="category" id="category" class="form-select shadow">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= htmlspecialchars($category) ?>" <?= $categoryFilter == $category ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-success flex-shrink-0 shadow"><i class="fa-solid fa-arrow-right"></i> Filter</button>
            </div>
        </form>
        <input type="text" class="form-control mb-2" id="search-box" placeholder="Enter the item name to search" onkeyup="filterTable()">
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
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
                                <form action="http://localhost/updates/thrifting_system/query/inventory.php" method="post">
                                    <input type="hidden" name="manage-id" value="<?= htmlspecialchars($row['product_id']) ?>">
                                    <td><input type="text" name="manage-name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>"></td>
                                    <td><input type="text" name="manage-price" class="form-control" value="<?= htmlspecialchars($row['price']) ?>"></td>
                                    <td>
                                    <select name="manage-type" id="manage-type" class="form-control">
                                        <option value="<?= htmlspecialchars($row['type']) ?>" disabled selected><?= htmlspecialchars($row['type']) ?></option>
                                        <option value="Shirt">Shirt</option>
                                        <option value="T-Shirt">T-shirt</option>
                                        <option value="Pants">Pants</option>
                                        <option value="Shorts">Shirts</option>
                                        <option value="Underwear">Underwear</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select name="manage-category" id="manage-category" class="form-control">
                                        <option value="<?= htmlspecialchars($row['category']) ?>" disabled selected><?= htmlspecialchars($row['category']) ?></option>
                                        <option value="Adult">Adult</option>
                                        <option value="Teen">Teen</option>
                                        <option value="Kid">Kid</option>
                                        <option value="Baby">Baby</option>
                                    </select>
                                    </td>
                                    <td>
                                    <select name="manage-size" id="manage-size" class="form-control">
                                        <option value="<?= htmlspecialchars($row['size']) ?>" disabled selected><?= htmlspecialchars($row['size']) ?></option>
                                        <option value="Extra Large">XL (Extra Large)</option>
                                        <option value="Large">L (Large)</option>
                                        <option value="Medium">M (Medium)</option>
                                        <option value="Small">S (Small)</option>
                                        <option value="Extra Small">ES (Extra Small)</option>
                                    </select>
                                    </td>
                                    <td><input type="text" name="manage-stock" class="form-control" value="<?= htmlspecialchars($row['stock']) ?>"></td>
                                    <td style="color: <?= $row['status'] === 'Active' ? 'green' : 'red' ?>;">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </td>
                                    <td style="width: 250px;">
                                        <div style="width: 250px; overflow-x: auto; white-space: nowrap;">
                                            <button name="edit-btn" class="btn btn-warning">
                                                <i class="fa-solid fa-pen-to-square"></i> Update
                                            </button>
                                            <button name="status-btn" class="btn btn-danger">
                                                <i class="fa-solid fa-repeat"></i> Revert Status
                                            </button>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="bg-danger text-light">No data was found in the database!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="m-4 bg-light border p-3 rounded-2 shadow">
        <h1 class="fw-500 fs-5 m-2">Sales Report</h1>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-bordered table-hover table-sm text-center shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Revenue</th>
                        <th>Sales</th>
                    </tr>
                </thead>
                <tbody id="product-table">
                    <?php if ($resultAgain->num_rows > 0) : ?>
                        <?php while ($row = $resultAgain->fetch_assoc()) : ?>
                            <tr>
                                <td style="width: 150px;">
                                    <?php if (!empty($row['image'])) : ?>
                                        <img class="rounded-2 shadow" src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                                    <?php else : ?>
                                        No Image
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>₱<?= htmlspecialchars($row['revenue']) ?></td>
                                <td><?= htmlspecialchars($row['purchased_counter']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="bg-danger text-light">No data was found in the database!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="m-4 bg-light border p-3 rounded-2 shadow">
        <div class="d-flex align-items-center justify-content-between">
            <h1 class="fw-500 fs-5 m-2">Order Report</h1>
            <?php 
            $overallSubtotal = 0;
            $orders = [];

            if ($resultOrdered->num_rows > 0) { 
                while ($row = $resultOrdered->fetch_assoc()) {
                    $orders[] = $row;
                    $overallSubtotal += $row["subtotal"];
                }
            }
            ?>
            <h1 class="text-muted fw-500 fs-5 m-2">₱<?= number_format($overallSubtotal, 2) ?></h1>
        </div>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-striped table-bordered table-hover table-sm text-center shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Buyer</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Ordered Date</th>
                    </tr>
                </thead>
                <tbody id="product-table">
                    <?php if (!empty($orders)) : ?>
                        <?php foreach ($orders as $row) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['buyer_name']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td>₱<?= htmlspecialchars($row['price']) ?></td>
                                <td><?= htmlspecialchars($row['quantity']) ?></td>
                                <td>₱<?= htmlspecialchars($row['subtotal']) ?></td>
                                <td><?= htmlspecialchars($row['ordered_date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="bg-danger text-light">No data was found in the database!</td>
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

    <script>
        document.getElementById("add-product-image").addEventListener("change", function() {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("preview-image").src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        })
    </script>
</body>
</html>
