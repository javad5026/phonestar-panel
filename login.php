<?php
session_start();
require_once 'config/db.php';

$error = '';

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users
            WHERE username='$username'
            AND password='$password'
            LIMIT 1";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];

        header("Location: dashboard.php");
        exit;

    }else{

        $error = "نام کاربری یا رمز عبور اشتباه است";

    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ورود | Phone Star</title>

<link rel="stylesheet" href="css/login.css">

</head>
<body>

<div class="login-card">
    <div class="brand">PHONESTAR</div>
    <div class="subtitle">
        سیستم مدیریت فروش و فاکتور
    </div>
    <div class="brand-line"></div>
    <!-- error -->
    <?php if(!empty($error)): ?>
        <div class="error-box">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="username" placeholder="نام کاربری">
        <input type="password" name="password" placeholder="رمز عبور">
        <button type="submit" name="login">
            ورود به پنل
        </button>
    </form>
    <div class="login-footer">
        © PHONESTAR Pannel v1.0
    </div>

</div>

</body>
</html>