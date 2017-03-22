<?php

function get($url, $qs = null, $header = "") {
    $qs_string = "";
    if (!empty($qs)) {
        $qs_string = "?".http_build_query($qs);
    }

    return file_get_contents($url.$qs_string, false, stream_context_create(array(
        "http" => array(
            "method" => "GET",
            "header" => $header
        )
    )));
}

function post($url, $form, $qs = null, $header = "") {
    $qs_string = "";
    if (!empty($qs)) {
        $qs_string = "?".http_build_query($qs);
    }

    return file_get_contents($url.$qs_string, false, stream_context_create(array(
        "http" => array(
            "method" => "POST",
            "header" => "Content-Type: application/x-www-form-urlencoded\r\n".$header,
            "content" => http_build_query($form)
        )
    )));
}