<?php

function is_valid($r) {
    if (preg_match('/^[a-z0-9\/]+$/', $r) === 1) {
        return true;
    } else {
        return false;
    }
}

if (!empty($_REQUEST['repo'])) {
    $repo = trim($_REQUEST['repo']);
    if (!is_valid($repo)) {
        header("Content-Type: application/json");
        echo json_encode(array('r' => null));
        exit;
    }
} else {
    $repo = 'gmarik/vundle/master';
}

function get_data($url) {
    $cache = "/tmp/" + md5($url) + '.vcache';
    if (file_exists($cache)) {
        return file_get_contents($cache);
    } else {
        $data = file_get_contents($url);
        file_put_contents($cache, $data);
        return $data;
    }
}

$url     = 'https://raw.github.com/'.$repo.'/README.md';
$data    = get_data($url);
$matches = array();

preg_match_all('/(```.*?```)/ims', $data, $matches);

$trimmed = array();

foreach($matches[0] as $m) {
    $trimmed[] = str_replace('```', '', $m);
}

header("Content-Type: application/json");
echo json_encode(array('r' => $trimmed));