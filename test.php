<?php

try
    {
        $db = new PDO('sqlite: yeelight.sqlite');
        /*$db->beginTransaction();

        $db->exec("CREATE TABLE type(id INTEGER PRIMARY KEY, name VARCHAR(64))");
        $db->exec("INSERT INTO type VALUES(1, 'Smart LED Bulb Color');");

        $db->exec("CREATE TABLE yeelight(id INTEGER PRIMARY KEY, name VARCHAR(64), ip_address VARCHAR(11), type INTEGER REFERENCES type(id))");
        $db->exec("INSERT INTO yeelight VALUES(1, 'Ampoule Gramos', '192.168.1.7', 1)");
        $db->exec("INSERT INTO yeelight VALUES(2, 'Ampoule Couloir', '192.168.1.73', 1)");

        $db->commit();*/

    }
catch (Exception $e) 
    {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }

$results = $db->query('SELECT * FROM yeelight');

foreach($results as $row)
    {
        echo('id : ' . $row['id'] . ', name : ' . $row['name'] . ' ip : ' . $row['ip_address'] . ' type : ' . $row['type'] . '<br>');
    }