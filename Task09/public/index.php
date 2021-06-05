<?php include 'redact_master_info.php' ?>
<?php include 'delete_master.php' ?>
<?php include 'add_master.php' ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task09</title>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<body>
<?php $database = new PDO('sqlite:../data/masters.db'); ?>

<!--<table class="table table-dark table-hover">-->
<!--    <thead>-->
<!--    <tr>-->
<!--        <th scope="col">Number</th>-->
<!--        <th scope="col">Master</th>-->
<!--        <th scope="col">Buttons</th>-->
<!--    </tr>-->
<!--    </thead>-->
<!--</table>-->
<section>
    <h1>masters</h1>
    <div>
        <table id="master_table">
            <tr>
                <th>number</th>
                <th>lastname</th>
                <th>firstname</th>
                <th>patronymic</th>
                <th>date</th>
                <th>actions</th>
            </tr>

            <!-- CREATE TABLE -->
            <?php
            $q = "SELECT id, last_name, first_name, patronymic, birthdate FROM masters
                      ORDER BY last_name";

            $data = $database->prepare($q);
            $data->execute();
            $rows = $data->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows

            as $row)
            {
            ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['first_name']; ?></td>
                <td><?= $row['last_name']; ?></td>
                <td><?= $row['patronymic']; ?></td>
                <td><?= $row['birthdate']; ?></td>
                <td>
                    <div>
                        <p><a href="?id=<?= $row['id'] ?>" class="btn btn-group-lg" data-toggle="modal" data-target="#edit<?= $row['id'] ?>">Редактировать</a>
                            <a href="?id=<?= $row['id'] ?>" class="btn btn-group-lg" data-toggle="modal" data-target="#delete<?= $row['id'] ?>">Удалить</a>
                            <a href="works.php?id=<?=$row['id']?>" class="btn btn-group-lg">Оказанные услуги</a>
                        </p>
                    </div>
                </td>
            </tr>

            <!-- Modal EDIT MASTER INFO-->
            <div class="modal fade" id="edit<?= $row['id'] ?>" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Редактировать данные</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="?id=<?= $row['id'] ?>" method="post">
                                <p><label>Lastname: <input name="last_name" type="text"
                                                           value="<?= $row['last_name']; ?>"></label></p>
                                <p><label>Firstname: <input name="first_name" type="text"
                                                            value="<?= $row['first_name']; ?>"></label></p>
                                <p><label>Patronymic: <input name="patronymic" type="text"
                                                             value="<?= $row['patronymic']; ?>"/></label></p>
                                <p><label>Date of birth: <input name="birthdate" type="text"
                                                                value="<?= $row['birthdate']; ?>"></label></p>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть
                                    </button>
                                    <button type="submit" class="btn btn-primary" name="edit">Сохранить</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END OF ------Modal  EDIT MASTER INFO--------------->

                <!------------- Modal DELETE MASTER  ------->
                <div class="modal fade" id="delete<?= $row['id'] ?>" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Удалить
                                    студента <?= $row['first_name'] . " " . $row['last_name'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-footer">
                                <form action="?id=<?= $row['id'] ?>" method="post">
                                    <button type="submit" class="btn btn-primary" name="delete">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!------------- Modal SHOW WORKS MASTER  ------->
                <div class="modal fade" id="show<?= $row['id'] ?>" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Удалить
                                    студента <?= $row['first_name'] . " " . $row['last_name'] ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-footer">
                                <form action="?id=<?= $row['id'] ?>" method="post">
                                    <button type="submit" class="btn btn-primary" name="delete">Удалить</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                }
                ?>
        </table>
    </div>
</section>
<!--  ADD NEW MASTER BUTTON   -->
<hr>
<div class="container">
    <div class="row">
        <div class="col-12">
            <button class="btn btn-success col-md-12" data-toggle="modal" data-target="#create">Добавить мастера
            </button>
        </div>
    </div>
</div>

<!-- Modal ADD NEW MASTER-->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Добавить нового мастера</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <p><label>Lastname: <input name="last_name" type="text"></label></p>
                    <p><label>Firstname: <input name="first_name" type="text"/></label></p>
                    <p><label>Patronymic: <input name="patronymic" type="text"></label></p>
                    <p><label>Gender:</label></p>
                    <p><label>Male:<input name="gender" type="radio" value="жен>"</label>
                        <label>Female:<input name="gender" type="radio" value="муж"></label>
                    </p>
                    <p><label>Date of birth: <input name="birthdate" type="date"></label></p>
                    <p><label>Salary: <input name="salary_coefficient" type="number"></label></p>
                    <p><label>Hiring date: <input name="hiring_date" type="date"/></label></p>
                    <p><label>Dismissal date: <input name="dismissal_date" type="date"/></label></p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END OF ------Modal ADD NEW STUDENT--------------->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
        crossorigin="anonymous"></script>
</body>
</html>