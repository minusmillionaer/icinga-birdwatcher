#!/usr/bin/php
<?php

    $val = getopt("H:p:s:");
    if(!isset($val['H']) and !isset($val['p']) and !isset($val['s'])){
        echo "Argument missing!";
        exit(3);
    } else {
        $hostname = $val['H'];
        $port = $val['p'];
        $sessionName = $val['s'];

        $url = $hostname.':'.$port;
    }

    $curl = curl_init('http://'.$url.'/protocols/bgp');
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($curl);

    if (curl_error($curl)) {
        $error_msg = curl_error($curl);
        echo "UNKNOWN - ".curl_error($curl);
        exit(3);
    } else {
        $jsonDecode= json_decode($result);
        if(!trim($jsonDecode->protocols->$sessionName->bgp_state) === 'Established'){
            echo $jsonDecode->protocols->$sessionName->bgp_state;
            exit(2);
        } else {
            echo $jsonDecode->protocols->$sessionName->bgp_state." - exported: ".$jsonDecode->protocols->$sessionName->routes->exported." imported: ".$jsonDecode->protocols->$sessionName->routes->imported." preferred: ".$jsonDecode->protocols->$sessionName->routes->preferred." | export_updates_accepted=".$jsonDecode->protocols->$sessionName->route_changes->export_updates->accepted

            ." export_updates_ignored=".$jsonDecode->protocols->$sessionName->route_changes->export_updates->ignored
            ." export_updates_received=".$jsonDecode->protocols->$sessionName->route_changes->export_updates->received
            ." export_updates_rejected=".$jsonDecode->protocols->$sessionName->route_changes->export_updates->rejected

            ." import_updates_accepted=".$jsonDecode->protocols->$sessionName->route_changes->import_updates->accepted
            ." import_updates_filtered=".$jsonDecode->protocols->$sessionName->route_changes->import_updates->filtered
            ." import_updates_ignored=".$jsonDecode->protocols->$sessionName->route_changes->import_updates->ignored
            ." import_updates_received=".$jsonDecode->protocols->$sessionName->route_changes->import_updates->received
            ." import_updates_rejected=".$jsonDecode->protocols->$sessionName->route_changes->import_updates->rejected

            ." import_withdraws_accepted=".$jsonDecode->protocols->$sessionName->route_changes->import_withdraws->accepted
            ." import_withdraws_filtered=".$jsonDecode->protocols->$sessionName->route_changes->import_withdraws->filtered
            ." import_withdraws_received=".$jsonDecode->protocols->$sessionName->route_changes->import_withdraws->received
            ." import_withdraws_rejected=".$jsonDecode->protocols->$sessionName->route_changes->import_withdraws->rejected;

            exit(0);
        }
    }
