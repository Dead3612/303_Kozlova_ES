<?php
$database = new PDO('sqlite:../data/masters.db');
$st_id = $_GET['id'];
$master = "select * from masters where id = '$st_id'";
$FIO = $database->query($master);
$st =$FIO->fetchAll(PDO::FETCH_ASSOC);
?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<style>
    table {
        margin: 0 auto;
        font-size: large;
        border: 1px solid black;
    }
    h1 {
        text-align: center;
        color: #006600;
        font-size: xx-large;
        font-family: 'Gill Sans', 'Gill Sans MT',
        ' Calibri', 'Trebuchet MS', 'sans-serif';
    }
    td {
        background-color: #E4F5D4;
        border: 1px solid black;

    }
    th,
    td {
        font-weight: bold;
        border: 1px solid black;
        padding: 10px;
        text-align: center;

    }
    td {
        font-weight: lighter;
    }
</style>

<section>

    <?php
    $FIO = $st[0]['first_name']." ".$st[0]['last_name']." ".$st[0]['patronymic'];
    $query  = "SELECT completed_services.date AS 'date', services.name AS 'service_name', 
        services_by_car_class.price AS 'price'
        FROM completed_services
         INNER JOIN masters
                    ON completed_services.master_id = masters.id AND masters.id = $st_id
         INNER JOIN services
                    ON completed_services.service_id = services.id AND completed_services.master_id = $st_id
         INNER JOIN service_reservation
                    ON service_reservation.service_id = completed_services.service_id AND service_reservation.master_id = completed_services.master_id
         INNER JOIN services_by_car_class
                    ON services_by_car_class.car_class_id = service_reservation.car_class_id AND services_by_car_class.service_id = completed_services.service_id \n";

    $st_data = $database->prepare($query);
    $st_data->execute();
    $services_info =$st_data->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h1><?=$FIO?></h1>
    <table id = "services_table">
        <tr>
            <th>date</th>
            <th>name</th>
            <th>price</th>
        </tr>
        <?php
        foreach($services_info as $s_inf){
            ?>
            <tr>
                <td><?= $s_inf['date'];?></td>
                <td><?= $s_inf['service_name'];?></td>
                <td><?= $s_inf['price'];?></td>
            </tr>
            <?php
        }
        ?>
        </table>
</section>