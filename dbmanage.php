<?php
session_start();
$request_method = $_SERVER["REQUEST_METHOD"];
// method pour get ampoule, put [edit] ampoule, post (add) new ampoule, delete ampoule from SQLite 3
switch($request_method)
{
	case 'GET':
		// Recuperer
		if(!empty($_GET["action"]))
		{
			$method = $_GET['action'];
			echo $yeelight->$method();
		}
		else
		{
			echo('ok');
		}
		break;

	default:
		// Invalid Request Method
		header("HTTP/1.0 405 Method Not Allowed");
		break;
		
	case 'POST':
		// Ajouter
		break;
		
	case 'PUT':
		// Modifier
		break;
		
	case 'DELETE':
		// Supprimer
		break;

}