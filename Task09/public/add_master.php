<?php
$database = new PDO('sqlite:../data/masters.db');

if(isset($_POST['add'])) {

    $s = $_POST['first_name'];
    $n = $_POST['last_name'];
    $l = $_POST['patronymic'];
    $g = $_POST['gender'];
    $d = $_POST['birthdate'];
    $q = $_POST['salary_coefficient'];
    $w = $_POST['hiring_date'];
    $e = $_POST['dismissal_date'];
    var_dump($_POST);

    $sql = ("INSERT INTO masters(
                     first_name,
                     last_name,
                     patronymic,
                     gender,
                     birthdate,
                     salary_coefficient,
                     hiring_date,
                     dismissal_date) VALUES (?,?,?,?,?,?,?,?)");
    $query = $database->prepare($sql);
    $query->execute([$s,$n,$l,$g,$d,$q,$w,$e]);

    if($query){
        header("Location:".$_SERVER['HTTP_REFERER']);
    }

}