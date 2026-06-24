<?php

require_once 'config/auth.php';
require_once 'config/db.php';


$result = mysqli_query($conn,"
SELECT 
invoices.*,
customers.full_name,
customers.phone

FROM invoices

LEFT JOIN customers
ON invoices.customer_id = customers.id

ORDER BY invoices.id DESC
");


?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>

<meta charset="UTF-8">

<title>فاکتورها</title>


<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet" href="css/invoices.css">


</head>


<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<?php include 'includes/header.php'; ?>



<div class="glass-box">


<div class="page-title">


<h2>
فاکتورها
</h2>


<button 
class="add-btn"
onclick="location.href='invoice_add.php'">

ثبت فاکتور

</button>


</div>



<div class="search-box">

<input 
id="searchInput"
placeholder="جستجو نام مشتری، شماره، فاکتور...">

</div>





<div class="invoice-list">



<?php while($inv = mysqli_fetch_assoc($result)): ?>

    <div class="invoice-card">


<div class="invoice-header">


<h3>

<?= $inv['invoice_number'] ?>

</h3>


<button

class="detail-btn"

onclick="showInvoice(<?= $inv['id'] ?>)">

جزئیات

</button>


</div>



<div class="invoice-line">


<span>

<?= $inv['full_name'] ?>

</span>


<span>

<?= $inv['phone'] ?>

</span>



<span>

<?= $inv['created_at'] ?>

</span>


</div>



</div>




<?php endwhile; ?>



</div>


</div>


</div>



<!-- popup -->


<div class="invoice-modal" id="invoiceModal">


<div class="modal-box">


<button 
class="close-modal"
onclick="closeModal()">

×
</button>



<div id="invoiceDetails">

</div>


</div>

</div>





<script>


// سرچ

document.getElementById("searchInput")
.addEventListener("input",function(){


let value=this.value.toLowerCase();


document.querySelectorAll(".invoice-card")
.forEach(card=>{


card.style.display =
card.innerText.toLowerCase()
.includes(value)
?
"block"
:
"none";


});


});





function showInvoice(id){


fetch("invoice_detail.php?id="+id)

.then(res=>res.text())

.then(data=>{


document.getElementById("invoiceDetails")
.innerHTML=data;


document.getElementById("invoiceModal")
.style.display="flex";


});


}



function closeModal(){

document.getElementById("invoiceModal")
.style.display="none";

}



</script>




</body>

</html>