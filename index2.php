<?php

    header('Content-Type: application/json');
    $response=array(
        'status' => 0,
        'status_message' =>'this URL is disabled'
    );
    echo json_encode($response);
