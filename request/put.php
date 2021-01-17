<?php
session_start();
/*$url = "http://127.0.0.1/api/products/" . $_POST['id'];
$data = array('name' => $_POST['name'], 'ip_address' => $_POST['ip_address'], 'type' => $_POST['type']);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
$response = curl_exec($ch);

echo($response);*/
$db_dir = "sqlite:c:/wamp64/www/yeelight_api/yeelight.sqlite";
$db = new PDO($db_dir);

$id = $_POST['id'];
$name = $_POST['name'];
$ip_address = $_POST['ip_address'];
$type = $_POST['type'];
$yeelight = unserialize($_SESSION['yeelight' . $id]);
$results_product = $db->prepare('UPDATE yeelight SET name = :name, ip_address = :ip_address, type = :type WHERE id = :id');
$results_product->bindParam(':name', $name, SQLITE3_TEXT);
$results_product->bindParam(':ip_address', $ip_address, SQLITE3_TEXT);
$results_product->bindParam(':type', $type, SQLITE3_INTEGER);
$results_product->bindParam(':id', $id, SQLITE3_INTEGER);
if($results_product->execute())
{
    echo 'Produits mis a jour avec succes.';
    $yeelight->set_ipaddress($ip_address);
    $yeelight->set_name($name);
    $yeelight->set_type($type);
}

echo 'false';