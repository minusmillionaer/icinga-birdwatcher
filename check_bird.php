#!/usr/bin/php
<?php

    $val = getopt("H:p:");
    if(!isset($val['H']) and !isset($val['p'])){
        echo "Argument missing!";
        exit(3);
    } else {
        $hostname = $val['H'];
        $port = $val['p'];

        $url = $hostname.':'.$port;
    }

    $curl = curl_init('http://'.$url.'/status');
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
        echo "OK - bird-".$jsonDecode->status->version." - ".$jsonDecode->status->message;
        exit(0);
    }
