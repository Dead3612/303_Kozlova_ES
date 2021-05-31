<?php

$pdo = new PDO('sqlite:masters.db');

$query_base = "SELECT masters.id AS 'master_id',
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

$query = $query_base . " ORDER BY 'master', 'date'";

$statement = $pdo->query($query);
$rows = $statement->fetchAll();
draw_table($rows);
$statement->closeCursor();

$query_master_id = "SELECT id AS 'master_id',
        last_name || ' ' || first_name || ' ' || patronymic AS 'master'
        FROM masters";

$statement = $pdo->query($query_master_id);
$masters_id = $statement->fetchAll();
draw_table($masters_id);
$statement->closeCursor();

$check_id = readline("id мастера: ");

if (!id_validation($check_id, $masters_id)) {
    echo "\n Мастера с таким id не существует \n";
} else {

    echo "\n";
    $master_query = $query_base . "WHERE masters.id = :check_id
                                   ORDER BY 'master', 'date' ";

    $statement = $pdo->prepare($master_query);
    $statement->execute(['check_id' => $check_id]);
    $rows = $statement->fetchAll();

    if (!empty($rows))
        draw_table($rows);
    else echo "Информации об оказанных услугах этого мастера нет \n\n";
    $statement->closeCursor();
}

function draw_table($table) {

    $columns_count = count($table[0])/2;
    $max_column_length = new SplFixedArray($columns_count);
    $columns_names = new SplFixedArray($columns_count);
    $table_width = 0;

    for ($i = 0; $i < $columns_count*2; $i+=2){
        $columns_names[$i/2] = array_keys($table[0])[$i];
        $max_column_length[$i/2] = iconv_strlen(array_keys($table[0])[$i]);
    }

    foreach ($table as $row) {
        for ($i=0; $i<$columns_count; $i++) {
            if (iconv_strlen($row[$i]) > $max_column_length[$i]) {
                $max_column_length[$i] = iconv_strlen($row[$i]);
            }
        }
    }

    for ($i = 0; $i < $columns_count; $i++){
        $table_width += $max_column_length[$i] + 2;
    }

    empty_line($columns_count, $max_column_length, 1);
    line_with_data($columns_count, $max_column_length, $columns_names);
    empty_line($columns_count, $max_column_length, 2);

    for ($i = 0; $i < count($table); $i++){
        line_with_data($columns_count, $max_column_length, $table[$i]);
    }

    empty_line($columns_count, $max_column_length, 3);

}

function empty_line($columns_count, $max_column_length, $mode){
    $middle_sep = '';
    $start_sep = '';
    $end_sep = '';

    switch ($mode){
        case 1:
            $middle_sep = "┬";
            $start_sep = '┌';
            $end_sep = '┐';
            break;

        case 2:
            $middle_sep = "┼";
            $start_sep = '├';
            $end_sep = '┤';
            break;

        case 3:
            $middle_sep = "┴";
            $start_sep = '└';
            $end_sep = '┘';
            break;
    }

    print_r($start_sep);
    for ($i = 0; $i < $columns_count; $i++){
        if ($i != $columns_count-1)
            print_r(str_repeat('─', $max_column_length[$i]+2) . $middle_sep );
        else
            print_r(str_repeat('─', $max_column_length[$i]+2) . $end_sep );
    }
    print_r("\n");
}

function line_with_data($columns_count, $max_column_length, $data){
    print_r("│");

    for ($i = 0; $i < $columns_count; $i++){
        $space_count = $max_column_length[$i]+1 - iconv_strlen($data[$i]);
        print_r(" " . $data[$i] . str_repeat(' ', $space_count) . "│");
    }
    print_r("\n");
}

function id_validation($id, $database){
    if (!is_numeric($id)) return FALSE;

    foreach ($database as $rows){
        if ($rows['master_id'] == $id) return True;
    }

    return FALSE;
}