<?php

require_once 'config/auth.php';
require_once 'config/db.php';


// تعداد فاکتورها
$invoiceCount = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM invoices
"))['total'];



// مجموع فروش
$sales = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total) as total 
FROM invoices
"))['total'] ?? 0;



// تعداد خرید گوشی
$purchaseCount = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total 
FROM phone_purchases
"))['total'];




// یادآوری امروز

$today = date("Y-m-d");


$reminderCount = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total
FROM reminders
WHERE DATE(reminder_date)='$today'
"))['total'];

// آخرین فاکتورها

$latestInvoices = mysqli_query($conn,"
SELECT 
invoices.*,
customers.full_name,
customers.phone

FROM invoices

LEFT JOIN customers

ON invoices.customer_id = customers.id

ORDER BY invoices.id DESC
LIMIT 5
");



// یادآوری های امروز

$todayReminders = mysqli_query($conn,"
SELECT *

FROM reminders

WHERE DATE(reminder_date)=CURDATE()

ORDER BY id DESC
");




// آخرین خرید گوشی

$latestPurchases = mysqli_query($conn,"
SELECT *

FROM phone_purchases

ORDER BY id DESC

LIMIT 5

");


?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>داشبورد | </title>

<link rel="stylesheet" href="css/dashboard.css">

</head>

<body>

<?php include 'includes/sidebar.php'; ?>

<div class="main-content">

    <?php include 'includes/header.php'; ?>

    <div class="stats-grid">

    <div class="stat-card">
        <h3>تعداد فاکتورها</h3>
        <span><?= $invoiceCount ?></span>
    </div>

    <div class="stat-card">
        <h3>مجموع فروش</h3>
        <span>
        <?= number_format($sales) ?>
        تومان
        </span>
    </div>

    <div class="stat-card">
        <h3>خرید گوشی</h3>
        <span><?= $purchaseCount ?></span>
    </div>

    <div class="stat-card">
        <h3>یادآوری امروز</h3>
        <span><?= $reminderCount ?></span>
    </div>

    </div>

    <div class="dashboard-grid">


<div class="glass-box">


<h2>آخرین فاکتورها</h2>


<?php while($i=mysqli_fetch_assoc($latestInvoices)): ?>


<div class="dash-item">


<b>
<?= $i['invoice_number'] ?>
</b>


<br>


<?= $i['full_name'] ?>


<br>


<?= number_format($i['total']) ?>

تومان


</div>


<?php endwhile; ?>


</div>





<div class="glass-box">


<h2>یادآوری‌های امروز</h2>



<?php while($r=mysqli_fetch_assoc($todayReminders)): ?>


<div class="dash-item">


<b>

<?= $r['title'] ?>

</b>


<br>


<?= $r['description'] ?>


</div>



<?php endwhile; ?>


</div>



<div class="glass-box full-width">


<h2>آخرین خریدهای ثبت شده</h2>



<?php while($p=mysqli_fetch_assoc($latestPurchases)): ?>


<div class="dash-item">


<b>

<?= $p['mobile_model'] ?>

</b>


<br>


<?= $p['customer_name'] ?>


-

<?= number_format($p['purchase_price']) ?>

تومان


</div>



<?php endwhile; ?>



</div>

</body>
</html>