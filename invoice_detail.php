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


?>


<h2>

<?= $inv['invoice_number'] ?>

</h2>


<p>
مشتری:
<?= $inv['full_name'] ?>
</p>


<p>
شماره:
<?= $inv['phone'] ?>
</p>


<hr>


<?php while($i=mysqli_fetch_assoc($items)): ?>


<div>


<?= $i['product_name'] ?>

|

<?= $i['quantity'] ?>

عدد

|

<?= number_format($i['price']) ?>


</div>


<?php endwhile; ?>


<hr>


<p>

جمع:

<?= number_format($inv['subtotal']) ?>

</p>


<p>

تخفیف:

<?= number_format($inv['discount']) ?>

</p>


<p>

مالیات:

<?= $inv['tax'] ?> %

</p>


<h3>

نهایی:

<?= number_format($inv['total']) ?>

تومان

</h3>
<div class="invoice-actions">


<a 
class="print-btn"
href="invoice_pdf.php?id=<?= $inv['id'] ?>"
target="_blank">

📄 خروجی PDF

</a>



<button 
class="print-btn"
onclick="window.print()">

🖨 چاپ

</button>


</div>