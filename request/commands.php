<?php
session_start();
require('../Yeelight.php');
$current_yeelight = $_POST['current_yeelight'];
$yeelight = unserialize($_SESSION[$current_yeelight]);
$yeelight->new_socket();
$action = $_POST['action'];
$value = $_POST['value'];

if($action == "power")
    {
        $toggle = $yeelight->toggle_power();
        if(!$toggle)
        {
            echo("Error writing to socket [toggle_power] <br />");
        }
        else
        {
            $data = $yeelight->get_json_data($current_yeelight);
            echo($data);
        }
    }
else
    {
        if($action == "set_color_brightness")
        {
            $color = $value[0];
            $brightness = $value[1];
            if($yeelight->get_power() != "on")
            {
                $yeelight->toggle_power();
            }
            $yeelight->set_color($color);
            $brightness = $yeelight->set_brightness($brightness);
            $data = $yeelight->get_json_data($current_yeelight);
            echo($data);
        }
        else
        {
            if($action == 'full_white')
            {
                $color = '8355711';
                $brightness = 100;
                if($yeelight->get_power() != "on")
                {
                    $yeelight->toggle_power();
                }
                $yeelight->set_color($color);
                $yeelight->set_brightness($brightness);
                $yeelight->set_temperature(100);
                $yeelight->set_mode(Yeelight::FULL_WHITE);
                $data = $yeelight->get_json_data($current_yeelight);
                echo($data);
            }
        }
    }

$yeelight->close_socket();
$_SESSION["$current_yeelight"] = serialize($yeelight);
