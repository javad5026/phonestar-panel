<?php

require_once 'config/auth.php';
require_once 'config/db.php';


$result = mysqli_query($conn,"
SELECT *
FROM reminders
ORDER BY reminder_date ASC
");


?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<title>مدیریت یادآوری ها</title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/reminders.css">

</head>


<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<?php include 'includes/header.php'; ?>


<div class="glass-box">


<div class="page-title">

<h2>
یادآوری ها
</h2>


<a class="add-btn" href="reminder_add.php">
+ ثبت یادآوری
</a>


</div>



<div class="reminders-list">


<?php while($r = mysqli_fetch_assoc($result)): ?>


<div class="reminder-card">


<h3>
<?= $r['title'] ?>
</h3>


<p>
<?= $r['description'] ?>
</p>


<span>
<?= $r['reminder_date'] ?>
</span>

<div class="reminder-actions">

<a href="reminder_delete.php?id=<?= $r['id'] ?>" 
onclick="return confirm('یادآوری حذف شود؟')"
class="delete-btn">

حذف

</a>

</div>


</div>


<?php endwhile; ?>


</div>



</div>


</div>


</body>

</html>