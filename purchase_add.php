<?php

require_once 'config/auth.php';
require_once 'config/db.php';


if(isset($_POST['save'])){


$customer = $_POST['customer_name'];
$phone = $_POST['phone_number'];
$model = $_POST['mobile_model'];
$code = $_POST['ownership_code'];
$price = $_POST['purchase_price'];
$desc = $_POST['description'];


$stmt = mysqli_prepare($conn,"
INSERT INTO phone_purchases
(customer_name,phone_number,mobile_model,ownership_code,purchase_price,description)

VALUES (?,?,?,?,?,?)
");


mysqli_stmt_bind_param(
$stmt,
"ssssss",
$customer,
$phone,
$model,
$code,
$price,
$desc
);


mysqli_stmt_execute($stmt);


header("Location:purchases.php");
exit;


}


?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<title>ثبت خرید گوشی</title>


<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/purchases.css">


</head>


<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<?php include 'includes/header.php'; ?>


<div class="glass-box add-purchase">


<h2>
ثبت خرید گوشی
</h2>


<form method="POST">



<div class="form-group">
<label>نام مشتری</label>
<input name="customer_name" required>
</div>



<div class="form-group">
<label>شماره تماس</label>
<input name="phone_number">
</div>



<div class="form-group">
<label>مدل گوشی</label>
<input name="mobile_model" required>
</div>



<div class="form-group">
<label>کد مالکیت</label>
<input name="ownership_code">
</div>



<div class="form-group">
<label>قیمت خرید</label>
<input name="purchase_price" required>
</div>



<div class="form-group">
<label>توضیحات</label>
<textarea name="description"></textarea>
</div>


<button class="save-btn" name="save">
ثبت خرید
</button>


</form>


</div>


</div>


</body>

</html>