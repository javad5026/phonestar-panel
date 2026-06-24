<?php

require_once 'config/auth.php';
require_once 'config/db.php';


$result = mysqli_query($conn,"
SELECT *
FROM phone_purchases
ORDER BY id DESC
");


?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<title>خریدهای ثبت شده</title>


<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/purchases.css">


</head>


<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<?php include 'includes/header.php'; ?>



<div class="glass-box">



<div class="page-title">


<h2>
خریدهای ثبت شده
</h2>

<button onclick="location.href='purchase_add.php'" class="add-btn">
ثبت خرید
</button>

</div>

<div class="search-box">

<input 
type="text"
id="searchInput"
placeholder="جستجو مدل، مشتری، شماره تماس..."
>

</div>

<div class="purchase-list">

<?php while($p = mysqli_fetch_assoc($result)): ?>



<div class="purchase-card">



<h3>
<?= $p['mobile_model'] ?>
</h3>




<div class="purchase-info">



<div>

مشتری:

<br>

<span>
<?= $p['customer_name'] ?>
</span>

</div>




<div>

شماره تماس:

<br>

<span>
<?= $p['phone_number'] ?>
</span>

</div>



<div>

کد مالکیت:

<br>

<span>
<?= $p['ownership_code'] ?>
</span>

</div>



<div>

قیمت خرید:

<br>

<span>
<?= number_format($p['purchase_price']) ?>
 تومان
</span>

</div>



</div>




<?php if(!empty($p['description'])): ?>

<p style="margin-top:15px">

<?= $p['description'] ?>

</p>

<?php endif; ?>




<span>

<?= $p['created_at'] ?>

</span>




<div class="purchase-actions">


<a 
class="delete-btn"
href="purchase_delete.php?id=<?= $p['id'] ?>"
onclick="return confirm('حذف این خرید؟')">

حذف

</a>


</div>



</div>




<?php endwhile; ?>



</div>



</div>



</div>

<script>

const searchInput = document.getElementById("searchInput");

const cards = document.querySelectorAll(".purchase-card");


searchInput.addEventListener("keyup",function(){


let value = this.value.toLowerCase();


cards.forEach(card=>{


let text = card.innerText.toLowerCase();


if(text.includes(value)){

card.style.display="block";

}else{

card.style.display="none";

}

});


});

</script>


</body>



</body>

</html>