<?php

require_once 'config/db.php';


$id=intval($_GET['id']);


$inv=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
invoices.*,
customers.full_name,
customers.phone

FROM invoices

LEFT JOIN customers

ON invoices.customer_id=customers.id

WHERE invoices.id=$id
"));



$items=mysqli_query($conn,"
SELECT *
FROM invoice_items
WHERE invoice_id=$id
");

function jalaliDate($date){

    $time = strtotime($date);

    $gy = date("Y",$time);
    $gm = date("m",$time);
    $gd = date("d",$time);


    $g_d_m = array(
        0,31,59,90,120,151,181,212,243,273,304,334
    );


    $gy2 = ($gm > 2)? ($gy + 1) : $gy;

    $days = 355666 
    + (365 * $gy)
    + floor(($gy2 + 3) / 4)
    - floor(($gy2 + 99) / 100)
    + floor(($gy2 + 399) / 400)
    + $gd
    + $g_d_m[$gm-1];


    $jy = -1595 + (33 * floor($days / 12053));

    $days %= 12053;


    $jy += 4 * floor($days / 1461);

    $days %= 1461;


    if($days > 365){

        $jy += floor(($days-1)/365);

        $days = ($days-1)%365;

    }


    if($days < 186){

        $jm = 1 + floor($days/31);

        $jd = 1 + ($days%31);

    }else{

        $jm = 7 + floor(($days-186)/30);

        $jd = 1 + (($days-186)%30);

    }


    return $jy."/".$jm."/".$jd;

}



?>

<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>

<meta charset="UTF-8">


<title>
<?= $inv['invoice_number'] ?>
</title>


<link rel="stylesheet" href="css/invoice_print.css">


</head>



<body>



<div class="invoice-page">



<div class="invoice-title">

فون استار

</div>



<div class="invoice-subtitle">

فاکتور فروش

</div>

<div class="info-box">


<div>

<b>مشتری:</b>

<?= $inv['full_name'] ?>

<br><br>

<b>تلفن:</b>

<?= $inv['phone'] ?>


</div>



<div>


<b>شماره فاکتور:</b>

<?= $inv['invoice_number'] ?>


<br><br>


<b>تاریخ:</b>

<?= jalaliDate($inv['created_at']) ?>


</div>


</div>

<table>


<tr>

<th>ردیف</th>

<th>شرح کالا</th>

<th>تعداد</th>

<th>قیمت واحد</th>

<th>جمع</th>

</tr>



<?php 

$n=1;

while($i=mysqli_fetch_assoc($items)): ?>


<tr>


<td>

<?= $n++ ?>

</td>


<td>

<?= $i['product_name'] ?>

</td>


<td>

<?= $i['quantity'] ?>

</td>


<td>

<?= number_format($i['price']) ?>

</td>


<td>

<?= number_format($i['total']) ?>

</td>


</tr>



<?php endwhile; ?>


</table>

<div class="bottom-area">



<div class="description-box">


<b>
توضیحات:
</b>


<br><br>





<br><br>


<br><br>


</div>





<div class="summary">


<div>

<span>
جمع:
</span>

<b>
<?= number_format($inv['subtotal']) ?>
</b>

</div>



<div>

<span>
تخفیف:
</span>

<b>
<?= number_format($inv['discount']) ?>
</b>

</div>



<div>

<span>
مالیات:
</span>

<b>
<?= $inv['tax'] ?> %
</b>

</div>



<div class="final-total">


<span>
مبلغ نهایی:
</span>


<b>
<?= number_format($inv['total']) ?>
</b>


</div>



</div>



</div>

<br><br><br><br><br><br><br><br><br>

<div class="sign-area">


<div class="sign-box">


<div class="sign-line">

امضا خریدار

</div>


</div>



<div class="sign-box">


<div class="sign-line">

مهر و امضا فروشگاه

</div>


</div>


</div>

<div class="footer">

با تشکر از خرید شما ❤️

</div>




</div>




<script>

window.onload=function(){

window.print();

}

</script>


</body>


</html>