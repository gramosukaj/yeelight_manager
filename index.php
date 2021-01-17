<?php
session_start();
require('Yeelight.php');
$db_dir = "sqlite:c:/wamp64/www/yeelight_api/yeelight.sqlite";
$db = new PDO($db_dir);
$results_show = $db->query('SELECT yeelight.id as id, yeelight.name as name, ip_address, type.name as type_name, yeelight.type as type_id FROM yeelight JOIN type ON type.id = yeelight.type')->fetchAll();

foreach($results_show as $row)
    {
		if(isset($_SESSION['yeelight' . $row['id']]))
        {
            ${'yeelight' . $row['id']} = unserialize($_SESSION["yeelight{$row['id']}"]);
            ${'yeelight' . $row['id']}->refresh_data();
        }
		else
        {
            ${'yeelight' . $row['id']} = new Yeelight($row['id'], $row['ip_address'], $row['name'], $row['type_name']);
        }
		
		${'yeelight' . $row['id'] . '_data'} = json_decode(${'yeelight' . $row['id']}->get_json_data(), true);
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>Yeelight Manager</title>
		<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/>
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.0/dist/spectrum.min.css">
		<link href="css/sb-admin-2.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/main.css">
  	</head>

	<body>
		<div id="wrapper">
			<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
				<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
					<div class="sidebar-brand-icon rotate-n-15">
						<i class="fas fa-laugh-wink"></i>
					</div>
					<div class="sidebar-brand-text mx-3">Yeelight Manager<sup>1</sup></div>
				</a>
				<hr class="sidebar-divider my-0">
				<li class="nav-item active">
					<a class="nav-link" href="index.php">
						<i class="fas fa-fw fa-tachometer-alt"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<hr class="sidebar-divider">
				<div class="sidebar-heading">
				Interface
				</div>
				<li class="nav-item">
					<a class="nav-link" href="manage.php">
						<i class="fas fa-fw fa-tasks"></i>
						<span>Products</span>
					</a>
				</li>
				<hr class="sidebar-divider">
				<div class="text-center d-none d-md-inline">
				<button class="rounded-circle border-0" id="sidebarToggle"></button>
				</div>
			</ul>

			<div id="content-wrapper" class="d-flex flex-column">
				<div id="content">
					<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"></nav>
					<div class="container-fluid">
						<div class="d-sm-flex align-items-center justify-content-between mb-4">
							<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
							<a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="dark_mode()" >Dark mode</a>
						</div>
						<div class="row">
							<?php 
							if(!$results_show)
								{
									echo("<p>Oups.. il semble qu'il n'y ai aucun produit enregistré, consulter la page de gestion</p>");
								}
                            foreach($results_show as $row):?>
							<div class="col-xl-3 col-md-6 mb-4">
								<div class="card border-left-primary shadow h-100 py-2">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?= ${'yeelight' . $row['id'] . '_data'}['name'] ?></div>
												<div class="h5 mb-0 font-weight-bold text-gray-600">Mode : <span id="yeelight<?= $row['id'] ?>_current_mode"><?= ${'yeelight' . $row['id'] . '_data'}['mode'] ?></div>
												<div class="h5 mb-0 font-weight-bold text-gray-600">Power status : <span id="yeelight<?=  $row['id'] ?>y_power_status"><?= ${'yeelight' . $row['id'] . '_data'}['power'] == "on" ? "On" : "Off" ?></span></div>
												<div class="h5 mb-0 font-weight-bold text-gray-600">Brightness : <span class="yeelight<?= $row['id'] ?>_current_brightness"><?= ${'yeelight' . $row['id'] . '_data'}['brightness'] ?></span></div>
												<div class="h5 mb-0 font-weight-bold text-gray-600">Type : <?= ${'yeelight' . $row['id'] . '_data'}['type'] ?></div>
												<div class="onoffswitch">
													<input type="checkbox" name="yeelight<?= $row['id'] ?>onoffswitch" class="onoffswitch-checkbox <?= ${'yeelight' . $row['id'] . '_data'}['power'] == "on" ? 'checked' : '' ?>" onclick="on_off('yeelight<?= $row['id'] ?>')" id="yeelight<?= $row['id'] ?>onoffswitch" >
													<label class="onoffswitch-label" for="yeelight<?= $row['id'] ?>onoffswitch">
														<span class="onoffswitch-inner"></span>
														<span class="onoffswitch-switch"></span>
													</label>
												</div>
												<div class="dropdown no-arrow">
													<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													Color Mode
													</button>
													<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														<a class="dropdown-item" href="#" onclick="change_color_request('full_white', 'yeelight<?= $row['id'] ?>')">Full white</a>
														<a class="dropdown-item" href="#">Another action</a>
														<a class="dropdown-item" href="#">Something else here</a>
													</div>
												</div>
											</div>
											<div class="col-auto">
												<input id="yeelight<?= $row['id'] ?>_color_picker" value='<?= ${'yeelight' . $row['id'] . '_data'}['color'] ?>' />
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php endforeach ?>
						</div>
					</div>
				</div>
			</div>
		</div>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
		<script src="js/sb-admin-2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.0/dist/spectrum.min.js"></script>
        <script>
			<?php foreach($results_show as $row):?>
			$('#yeelight<?= $row['id'] ?>_color_picker').spectrum({
				type: "color",
				preferredFormat: "rgb",
				color: "<?= ${'yeelight' . $row['id'] . '_data'}['color'] ?>",
				showPalette: false,
				showAlpha: false,
				allowEmpty: false,
				containerClassName: 'yeelight<?= $row['id'] ?>_color_picker'
			});

			$('<div><i class="fas fa-sun fa-2x mr-1"></i><input type="range" id="yeelight<?= $row['id'] ?>_current_brightness" class="input_brightness" name="brightness" min="1" max="100" value="<?= ${'yeelight' . $row['id'] . '_data'}['brightness'] ?>"></div>').insertBefore('.yeelight<?= $row['id'] ?>_color_picker .sp-button-container');
			
			$('.yeelight<?= $row['id'] ?>_color_picker .sp-choose').click(function(){
				let color = $("#yeelight<?= $row['id'] ?>_color_picker").spectrum("get");
				let red = Math.round(color['_r']);
				let green = Math.round(color['_g']);
				let blue = Math.round(color['_b']);
				let color_choice = Math.round(red*65536)+(green*256)+blue;
				let choice_brightness = $('#yeelight<?= $row['id'] ?>_current_brightness').val();
				change_color_request('set_color_brightness', 'yeelight<?= $row['id'] ?>', color_choice, choice_brightness);
			});
			<?php endforeach ?>
		</script>
        <script src="js/main.js"></script>
	</body>
</html>
<?php
foreach($results_show as $row)
    {
		$_SESSION['yeelight' . $row['id']] = serialize(${'yeelight' . $row['id']});
    }
?>

