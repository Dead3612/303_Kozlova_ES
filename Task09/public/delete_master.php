<?php
$database = new PDO('sqlite:../data/masters.db');

if(isset($_POST['delete'])){
    $q_delete =("DELETE FROM masters WHERE id = ?");
    $q = $database->prepare($q_delete);
    print_r($_GET['id']);
    $id = $_GET['id'];
    var_dump($id);
    $q->execute([$id]);
    if($q){
        var_dump($_GET);
       // header("Location:".$_SERVER['HTTP_REFERER']);
    }
}