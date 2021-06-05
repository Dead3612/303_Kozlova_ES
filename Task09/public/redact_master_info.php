<?php
$database = new PDO('sqlite:../data/masters.db');
if (isset($_POST['edit'])) {
    $id = $_GET['id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $patronymic = $_POST['patronymic'];
    $birthdate = $_POST['birthdate'];

    $update_students_data = ("UPDATE masters SET first_name=?, last_name =?, patronymic=?, birthdate=? WHERE id =?");
    $q_st = $database->prepare($update_students_data);
    $q_st->execute([$first_name, $last_name, $patronymic, $birthdate, $id]);
}