<?php

include __DIR__ . '/../config/headers.php';


use Models\Rate;


switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        rateUser();
        break;
}


function rateUser()
{
    $data = file_get_contents('php://input', true);
    $data = json_decode($data, true);
    $array = json_decode($data['data'], true);

    foreach ($array as $row) {
        $rate = new Rate();
        $rate->user_id = $data['user_id'];
        //$rate->ticket_id = $data['ticket_id'];
        $rate->rated_by = $data['name_of_client'];
        $rate->feedback = $data['feedback'];
        $rate->question = $row['question'];
        $rate->rate = $row['rate'];
        $rate->save();
    }
}
