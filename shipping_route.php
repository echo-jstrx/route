<?php

$direct = schedule::getNextShipmentByLocations($pdo, $item['origin'], $user['destination']);

if ($direct) {
    $_SESSION['route'] = $direct['shipment_id'];
} else {
    $primary = schedule::getNextShipmentByDestination($pdo, $user['destination']);
    if (!$primary) {
        echo "no shipment found.";
    } else if ($primary['origin'] != $item['origin']) {
        $_SESSION['route']['primary'] = $primary['shipment_id'];
        $secondary = schedule::getNextShipmentByLocations($pdo, $item['origin'], $primary['origin']);
        if(!$secondary) {
            echo "no shipment found.";
        } else {
            $_SESSION['route']['secondary'] = [
                'shipment_id' => $secondary['shipment_id'],
                'origin' => $secondary['origin'],
                'destination' => $secondary['destination'],
                'fee' => $secondary['import_Fee'],
                'transhipper' => $secondary['transhipper_id']
            ];
        }
    } else {
        $_SESSION['route'] = [
            'shipment_id' => $primary['shipment_id'],
            'origin' => $primary['origin'],
            'destination' => $primary['destination'],
            'fee' => $primary['import_Fee'],
            'transhipper' => $primary['transhipper_id']
        ];
    }
}
