<?php

require_once 'config/auth.php';
require_once 'config/db.php';


if(isset($_POST['save'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['reminder_date'];


    $stmt = mysqli_prepare($conn,"
        INSERT INTO reminders
        (title,description,reminder_date,status)
        VALUES (?,?,?,'pending')
    ");


    mysqli_stmt_bind_param(
        $stmt,
        "sss",
        $title,
        $description,
        $date
    );


    mysqli_stmt_execute($stmt);


    header("Location: reminders.php");
    exit;

}

?>


<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

<meta charset="UTF-8">

<title>ثبت یادآوری</title>

<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/reminders.css">

</head>


<body>


<?php include 'includes/sidebar.php'; ?>


<div class="main-content">


<?php include 'includes/header.php'; ?>


<div class="glass-box add-reminder-box">


<h2>
ثبت یادآوری جدید
</h2>



<form method="POST">


<div class="form-group">

<label>
عنوان
</label>

<input 
type="text"
name="title"
placeholder="مثلا تماس با مشتری"
required>

</div>



<div class="form-group">

<label>
توضیحات
</label>

<textarea
name="description"
placeholder="توضیحات یادآوری">
</textarea>

</div>



<div class="form-group">

<label>
تاریخ یادآوری
</label>

<input 
type="date"
name="reminder_date"
required>

</div>



<button class="save-btn" name="save">
ثبت یادآوری
</button>



</form>


</div>


</div>


</body>

</html>