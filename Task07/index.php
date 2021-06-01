<?php
$db = new PDO('sqlite:masters.db');
$sql_query = "SELECT masters.id AS 'master_id',
        masters.last_name || ' ' || masters.first_name || ' ' || masters.patronymic AS 'master',
       completed_services.date AS 'date',
       services.name AS 'service_name',
       services_by_car_class.price AS 'price'
FROM completed_services
         INNER JOIN masters
                    ON completed_services.master_id = masters.id
         INNER JOIN services
                    ON completed_services.service_id = services.id
         INNER JOIN service_reservation
                    ON service_reservation.service_id = completed_services.service_id AND service_reservation.master_id = completed_services.master_id
         INNER JOIN services_by_car_class
                    ON services_by_car_class.car_class_id = service_reservation.car_class_id AND services_by_car_class.service_id = completed_services.service_id \n";
$sql_q = $sql_query . "ORDER BY 'master' ";
$st = $db->prepare($sql_q);
$st->execute();

$all_services = $st->fetchAll(PDO::FETCH_ASSOC);
$sql_master_id = 'SELECT distinct masters.id as id from masters order by id;';
$all_services_by_master = $db->prepare($sql_master_id);
$all_services_by_master->execute();
$res = $all_services_by_master->fetchAll(PDO::FETCH_ASSOC);
$array_services = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Выполненые услуги</title>
    <style>
        body{
            background-color: whitesmoke;
        }
        h1{
            font-family: 'Courier New', Courier, monospace ;
            font-size: 20px;
            font-weight: bolder;
        }
        table{
            border: 1px solid black;
            font-family: 'Courier New', Courier, monospace ;
            padding: 8px;
        }
        td{
            text-align: center;
            border: 1px solid black;
            font-family: 'Courier New', Courier, monospace ;
            padding: 4px;
        }
        input,
        select{
            padding: .50rem 1rem .50rem .50rem;
            background: none;
            border: 1px solid #ccc;
            border-radius: 2px;
            font-family: inherit;
            font-size: 1rem;
            color: #444;

        }
        option{
            font-family: inherit;
            font-size: 1rem;
            color: #444;
            border: 1px solid #ccc;
        }
        hr{
            padding: 3;
            height: 2px;
            border: none;
            background: linear-gradient(45deg, #333, #ddd);
        }

    </style>

</head>
<body>
<h1>Выполненые услуги по конкетному мастеру</h1>

<section>
    <hr>
    <form action="" method="POST">
        <label>
            <select style="width: 200px;" name="master_id">
                <option>Все услуги

                </option>
                <?php foreach($res as $key => $idObject): ?>
                    <option value=<?= $idObject['id']; ?>>
                        <?= $idObject['id']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit">Search by ID</button>
    </form>
    <hr>
    <?php
    if( $_POST["master_id"]){
        $master = $_POST["master_id"];
        ?>
        <table>
        <tbody>
        <tr style= "background-color: SteelBlue;
                    font-weight: bold;
                    font-size: 16px;">
            <td>id</td>
            <td>ФИО</td>
            <td>Дата услуги</td>
            <td>Название услуги</td>
            <td>Стоимость</td>
        </tr>
        <?php
        if($master!="Все услуги"){
            $sql_query1 = "SELECT masters.id AS 'master_id',
        masters.last_name || ' ' || masters.first_name || ' ' || masters.patronymic AS 'master',
        completed_services.date AS 'date',
        services.name AS 'service_name',
        services_by_car_class.price AS 'price'
        FROM completed_services
         INNER JOIN masters
                    ON completed_services.master_id = masters.id
         INNER JOIN services
                    ON completed_services.service_id = services.id
         INNER JOIN service_reservation
                    ON service_reservation.service_id = completed_services.service_id AND service_reservation.master_id = completed_services.master_id
         INNER JOIN services_by_car_class
                    ON services_by_car_class.car_class_id = service_reservation.car_class_id AND services_by_car_class.service_id = completed_services.service_id
         WHERE masters.id = '$master'
         ORDER BY  'master' ";
            //$sql_find_services_by_master = $sql_query1 . " ORDER BY 'master', 'date'";
            $st_master = $db->prepare($sql_query1);
            $st_master->execute();
            $results_st_master = $st_master->fetchAll(PDO::FETCH_ASSOC);
            foreach($results_st_master as $res_string){
                $value1 = sprintf(" %' -4d\t", $res_string['master_id']);
                $value2 = sprintf(" %' -56s\t", $res_string['master']);
                $value3 = sprintf(" %' -15s\t", $res_string['date']);
                $value4 = sprintf(" %' -13s\t", $res_string['service_name']);
                $value5 = sprintf(" %' -20s\t", $res_string['price']);
                ?>
                <tr style=" background-color: LightSkyBlue;
                        font-size: 14px;">
                    <td><?=$value1;?></td>
                    <td><?=$value2;?></td>
                    <td><?=$value3;?></td>
                    <td><?=$value4;?></td>
                    <td><?=$value5;?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
            <?php
        }
        else{
            foreach($all_services as $res_string){
                $value1 = sprintf(" %' -4d\t", $res_string['master_id']);
                $value2 = sprintf(" %' -56s\t", $res_string['master']);
                $value3 = sprintf(" %' -15s\t", $res_string['date']);
                $value4 = sprintf(" %' -13s\t", $res_string['service_name']);
                $value5 = sprintf(" %' -20s\t", $res_string['price']);
                ?>
                <tr style=" background-color: LightSkyBlue;
                        font-size: 14px;">
                    <td><?=$value1;?></td>
                    <td><?=$value2;?></td>
                    <td><?=$value3;?></td>
                    <td><?=$value4;?></td>
                    <td><?=$value5;?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            </table>
            <?php
        }
    }
    ?>
    <hr>
</body>
</html>