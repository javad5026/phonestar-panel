<?php

require_once 'config/auth.php';
require_once 'config/db.php';


if(isset($_GET['id'])){


$id = intval($_GET['id']);


mysqli_query($conn,"
DELETE FROM phone_purchases
WHERE id=$id
");


}


header("Location:purchases.php");
exit;