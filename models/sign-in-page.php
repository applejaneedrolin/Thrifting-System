<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In to Thrifting Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="http://localhost/updates/thrifting_system/script/script.js" defer></script>
    <link rel="stylesheet" href="http://localhost/updates/thrifting_system/includes/css/style.css">
</head>
<body>
    
    <div class="container col-md-5 shadow mt-4 rounded-2 p-4 bg-dark text-light">
        <div class="d-flex flex-column justify-content-center align-items-center text-center">
            <h1 class="fw-normal">Sign In to Thrifting Store</h1>
            <p>We'll make sure you'll receive your second-hand items!</p>
        </div>
        <div class="mt-4">
            <form action="../query/sign-in.php" method="post">

                <label for="sign-in-email" class="mt-2"><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" class="form-control border border-dark" name="sign-in-email" id="sign-in-email" placeholder="Enter your email" required>
                
                <label for="sign-in-name" class="mt-2"><i class="fa-solid fa-signature"></i> Name</label>
                <input type="text" class="form-control border border-dark" name="sign-in-name" id="sign-in-name" placeholder="Enter your name" required>
                
                <label for="sign-in-password" class="mt-2"><i class="fa-solid fa-lock"></i> Password</label>
                <div class="d-flex align-items-center gap-3">
                    <input type="password" class="form-control border border-dark" name="sign-in-password" id="sign-in-password" placeholder="Enter your password" required>
                    <button type="button" onclick="togglePasswordBtnOnSignIn()"><i class="fa-solid fa-eye text-light fs-5"></i></button>
                </div>
                
                <div class="d-flex align-items-center justify-content-center gap-3 mt-4">
                    <button type="submit" class="btn btn-success fw-bold" name="sign-in-btn" id="sign-in-btn"><i class="fa-solid fa-right-to-bracket"></i> Sign In</button>
                </div>
                
            </form>
            <div class="mt-4 text-center">
                <a href="./sign-up-page.php" class="text-light text-decoration-none">You don't have an account? Click me!</a>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <a class="btn btn-primary" href="http://localhost/updates/thrifting_system/models/dependencies/shop-page.php">Visit our Products</a>
    </div>

</body>
</html>