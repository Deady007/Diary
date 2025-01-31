<?php
session_start();

// Set the fixed password (you can modify this as needed)
$fixed_password = 'viral@1374'; // Replace with your desired password

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password']) && $_POST['password'] === $fixed_password) {
        // Password is correct, start session and redirect to add_entry page
        $_SESSION['authenticated'] = true;
        header('Location: add_entry.php');
        exit;
    } else {
        // Incorrect password, show an error message
        $error_message = 'Incorrect password. Please try again.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="https://i.postimg.cc/DS2GG0y3/DALL-E-2024-12-19-01-53-13-A-minimalist-64x64-pixel-logo-for-an-internship-diary-website-The-desi.jpg">
    <title>Login - Internship Diary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <h1 class="card-title text-center">Login to Add/Edit Entry</h1>
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="password">Enter Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
