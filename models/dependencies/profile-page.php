<?php
require_once "/xampp/htdocs/updates/thrifting_system/query/connection.php";
session_start();

$getSessionEmail = $_SESSION["email"] ?? "No session for email!";
$getSessionName = $_SESSION["name"] ?? "No session for name!";
$getSessionPassword = $_SESSION["password"] ?? "No session for password!";
$getSessionUserId = $_SESSION["user_id"] ?? "No session for user id!";
$getSessionRole = $_SESSION["role"] ?? "No session for role!";

$sqlSelect = "SELECT * FROM registered_users WHERE user_id = ?";
$sqlStmt = $conn->prepare($sqlSelect);
$sqlStmt->bind_param("i", $getSessionUserId);
$sqlStmt->execute();
$result = $sqlStmt->get_result();
$userData = $result->fetch_assoc();

if (!isset($_SESSION["role"])) {
    header("Location: ../sign-in-page.php");
    exit();
}

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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

    <div class="container mt-5 mb-5">
        <div class="card shadow-lg p-4 profile-card">
            <div class="text-center">
                <img class="profile-pic shadow" src="http://localhost/updates/thrifting_system/includes/images/blank-pfp-template.jpg" alt="Profile Picture">
                <h3 class="mt-3"><?= htmlspecialchars($userData["name"]) ?></h3>
                <p class="text-muted"><?= htmlspecialchars($userData["role"]) ?></p>
            </div>
            <hr>
            <form action="http://localhost/updates/thrifting_system/query/profile.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="profile-email" value="<?= htmlspecialchars($userData["email"]) ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="profile-name" value="<?= htmlspecialchars($userData["name"]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="password" class="form-control" name="profile-password" id="profile-password" value="<?= htmlspecialchars($userData["password"]) ?>" required>
                        <button type="button" onclick="togglePasswordBtnOnProfile()"><i class="fa-solid fa-eye text-dark fs-5"></i></button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="profile-address" id="profile-address" class="form-control text-left" required><?= htmlspecialchars($userData["address"]) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" name="profile-phone-number" value="<?= htmlspecialchars($userData["phone_number"]) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">User ID</label>
                    <input type="text" class="form-control" name="profile-user-id" value="<?= htmlspecialchars($userData["user_id"]) ?>" readonly>
                </div>
                <div class="text-center">
                    <button type="submit" name="profile-save-btn" class="btn btn-primary w-100">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>