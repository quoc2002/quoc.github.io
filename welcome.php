<?php

session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?>
    </b>. Welcome to our site.</h1>
<br>
    <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
    <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a></br>
    <a href="manageBooks/dashboard.php" class="btn btn-primary">Quản lý sách</a></br></br>
    <a href="" class="btn btn-primary">Mượn Sách</a></br></br>
    <a href="manageReaders/dashboard.php" class="btn btn-primary">Quản lý người đọc</a></br></br>
    <a href="specialized/specialized.php" class="btn btn-primary">Quản lý chuyên ngành</a></br></br>
</p>
</body>
</html>