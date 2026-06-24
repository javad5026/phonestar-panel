<?php

require_once 'config/auth.php';
require_once 'config/db.php';



if(isset($_POST['save'])){


    $phone = mysqli_real_escape_string($conn,$_POST['customer_phone']);
    $name = mysqli_real_escape_string($conn,$_POST['customer_name']);


    // مشتری پیدا کن
    $check = mysqli_query($conn,"
        SELECT id FROM customers
        WHERE phone='$phone'
    ");


    if(mysqli_num_rows($check) > 0){

        $customer = mysqli_fetch_assoc($check);
        $customer_id = $customer['id'];

    }else{


        mysqli_query($conn,"
            INSERT INTO customers
            (full_name,phone)
            VALUES
            ('$name','$phone')
        ");


        $customer_id = mysqli_insert_id($conn);

    }



    // شماره فاکتور

    $last = mysqli_query($conn,"
        SELECT id FROM invoices
        ORDER BY id DESC LIMIT 1
    ");


    if(mysqli_num_rows($last)){

        $l = mysqli_fetch_assoc($last);
        $number = 1000 + $l['id'] + 1;

    }else{

        $number = 1001;

    }



    $invoice_number = "INV-".$number;



    $subtotal = str_replace(',', '', $_POST['subtotal']);
    $discount = str_replace(',', '', $_POST['discount']);
    $tax = str_replace(',', '', $_POST['tax']);
    $total = str_replace(',', '', $_POST['total']);



    mysqli_query($conn,"
    INSERT INTO invoices
    (invoice_number,customer_id,subtotal,discount,tax,total)

    VALUES

    ('$invoice_number','$customer_id',
    '$subtotal','$discount','$tax','$total')
    ");



    $invoice_id = mysqli_insert_id($conn);



    foreach($_POST['product'] as $key=>$product){


        $qty = $_POST['qty'][$key];
        $unit = $_POST['unit'][$key];
        $price = str_replace(',', '', $_POST['price'][$key]);
        $sum = $qty * $price;



        mysqli_query($conn,"
        INSERT INTO invoice_items

        (invoice_id,product_name,quantity,unit,price,total)

        VALUES

        ('$invoice_id',
        '$product',
        '$qty',
        '$unit',
        '$price',
        '$sum')
        ");


    }



header("Location: invoices.php");
exit;


}


?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">


<head>

<meta charset="UTF-8">

<title>ثبت فاکتور</title>


<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet" href="css/invoices.css">


</head>



<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<?php include 'includes/header.php'; ?>

<div class="glass-box invoice-box">
<h2>
ثبت فاکتور
</h2>



<form method="POST">



<div class="form-group">

<label>نام مشتری</label>

<input name="customer_name" required>

</div>



<div class="form-group">

<label>شماره مشتری</label>

<input name="customer_phone">

</div>




<div class="items-box">


<div id="items">

<div class="invoice-row">

<input name="product[]" placeholder="نام محصول">

<input name="qty[]" value="1" class="qty">

<input name="unit[]" placeholder="عدد">

<input name="price[]" placeholder="قیمت" class="price">


<button type="button" 
class="remove-item"
onclick="removeRow(this)">
×
</button>

</div>


</div>



<button 
type="button" 
class="add-btn"
onclick="addRow()">

+ افزودن آیتم

</button>


</div>

<hr>


<div class="form-group">
<label>جمع</label>
<input id="subtotal" name="subtotal">
</div>


<div class="form-group">
<label>تخفیف</label>
<input 
name="discount" 
value=""
placeholder="مبلغ تخفیف">
</div>


<div class="form-group">
<label>مالیات</label>
<input 
name="tax" 
value=""
placeholder=" بر اساس درصد وارد شود ">
</div>


<div class="form-group">
<label>مبلغ نهایی</label>
<input name="total" id="total">
</div>



<button class="save-btn" name="save">
ثبت فاکتور
</button>



</form>

</div>
</div>

<script>


function addRow(){


let items = document.getElementById("items");


let row = document.createElement("div");

row.className = "invoice-row";


row.innerHTML = `

<input name="product[]" placeholder="نام محصول">

<input name="qty[]" value="1" class="qty">

<input name="unit[]" placeholder="عدد">

<input name="price[]" placeholder="قیمت" class="price">


<button type="button" 
class="remove-item"
onclick="removeRow(this)">
×
</button>

`;



items.appendChild(row);


listenCalc();

document.querySelectorAll(".price,[name='discount']")
.forEach(input=>{

input.addEventListener("input",function(){

let value=this.value.replace(/,/g,'');

if(value){

this.value =
Number(value).toLocaleString('en-US');

}

});

});

}




function removeRow(btn){

    btn.parentElement.remove();

    calculate();

}



function listenCalc(){

    document.querySelectorAll(".qty,.price,[name='discount'],[name='tax']")
    .forEach(input=>{

        input.addEventListener(
        "input",
        calculate
        );

    });

}

function calculate(){


let subtotal = 0;


document.querySelectorAll(".invoice-row")
.forEach(row=>{


let qty = Number(
row.querySelector(".qty")?.value || 0
);


let price = Number(
row.querySelector(".price")?.value.replace(/,/g,'') || 0
);



let sum = qty * price;


subtotal += sum;



// نمایش قیمت آیتم
if(row.querySelector(".price").value){

row.querySelector(".price").value =
Number(row.querySelector(".price").value.replace(/,/g,''))
.toLocaleString('en-US');

}


});



document.getElementById("subtotal").value =
subtotal.toLocaleString('en-US');



let discount = Number(
document.querySelector("[name='discount']")
.value.replace(/,/g,'') || 0
);



let taxPercent = Number(
document.querySelector("[name='tax']").value || 0
);



let afterDiscount = subtotal - discount;



let taxAmount =
afterDiscount * taxPercent / 100;



let total =
afterDiscount + taxAmount;



document.getElementById("total").value =
total.toLocaleString('en-US');



}

listenCalc();


</script>

</body>

</html>