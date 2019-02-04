<?php
header('Content-type: application/json;charset=utf-8"');
header('Access-Control-Allow-Origin: *');
$words = json_decode(file_get_contents("./data/words.json"),true);
$keys =array_keys($words);
echo json_encode($words[$keys[rand(1,count($keys))-1]]);