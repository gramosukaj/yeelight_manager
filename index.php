<?php
session_start();
require('Yeelight.php');
$db = new PDO('sqlite: yeelight.sqlite');
$results_yeelight = $db->query('SELECT * FROM yeelight');
$results_show = $db->query('SELECT * FROM yeelight');
$results_js = $db->query('SELECT * FROM yeelight');
$results_session = $db->query('SELECT * FROM yeelight');

foreach($results_yeelight as $row)
    {
		if(!isset($_SESSION['yeelight' . $row['id']]))
			{
				${'yeelight' . $row['id']} = new Yeelight($row['ip_address'], $row['name']);
			}
		else
			{
				${'yeelight' . $row['id']} = unserialize($_SESSION["yeelight{$row['id']}"]);
				${'yeelight' . $row['id']}->refresh_data();
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

	<body id="page-top">
		<div id="wrapper">
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
			<div class="sidebar-brand-icon rotate-n-15">
				<i class="fas fa-laugh-wink"></i>
			</div>
			<div class="sidebar-brand-text mx-3">Yeelight Manager<sup></sup></div>
			</a>

			<hr class="sidebar-divider my-0">

			<li class="nav-item active">
			<a class="nav-link" href="index.php">
				<i class="fas fa-fw fa-tachometer-alt"></i>
				<span>Dashboard</span></a>
			</li>

			<hr class="sidebar-divider">

			<div class="sidebar-heading">
			Interface
			</div>

			<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
				<i class="fas fa-fw fa-cog"></i>
				<span>Components</span>
			</a>
			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<h6 class="collapse-header">Custom Components:</h6>
				<a class="collapse-item" href="buttons.html">Gestion</a>
				<a class="collapse-item" href="cards.html">Cards</a>
				</div>
			</div>
			</li>

			<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
				<i class="fas fa-fw fa-wrench"></i>
				<span>Utilities</span>
			</a>
			<div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<h6 class="collapse-header">Custom Utilities:</h6>
				<a class="collapse-item" href="utilities-color.html">Colors</a>
				<a class="collapse-item" href="utilities-border.html">Borders</a>
				<a class="collapse-item" href="utilities-animation.html">Animations</a>
				<a class="collapse-item" href="utilities-other.html">Other</a>
				</div>
			</div>
			</li>
			<hr class="sidebar-divider">
			<div class="sidebar-heading">
			Addons
			</div>
			<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
				<i class="fas fa-fw fa-folder"></i>
				<span>Pages</span>
			</a>
			<div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
				<h6 class="collapse-header">Login Screens:</h6>
				<a class="collapse-item" href="login.html">Login</a>
				<a class="collapse-item" href="register.html">Register</a>
				<a class="collapse-item" href="forgot-password.html">Forgot Password</a>
				<div class="collapse-divider"></div>
				<h6 class="collapse-header">Other Pages:</h6>
				<a class="collapse-item" href="404.html">404 Page</a>
				<a class="collapse-item" href="blank.html">Blank Page</a>
				</div>
			</div>
			</li>

			<li class="nav-item">
			<a class="nav-link" href="charts.html">
				<i class="fas fa-fw fa-chart-area"></i>
				<span>Charts</span></a>
			</li>

			<li class="nav-item">
			<a class="nav-link" href="tables.html">
				<i class="fas fa-fw fa-table"></i>
				<span>Tables</span></a>
			</li>

			<hr class="sidebar-divider d-none d-md-block">

			<div class="text-center d-none d-md-inline">
			<button class="rounded-circle border-0" id="sidebarToggle"></button>
			</div>

		</ul>

		<div id="content-wrapper" class="d-flex flex-column">
			<div id="content">
				<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
				</nav>
				<div class="container-fluid">
					<div class="d-sm-flex align-items-center justify-content-between mb-4">
						<h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
						<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Extinct all</a>
					</div>

					<div class="row">
						<?php foreach($results_show as $row): ?>
						<div class="col-xl-3 col-md-6 mb-4">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?= ${'yeelight' . $row['id'] . '_data'}['name'] ?></div>
											<div class="h5 mb-0 font-weight-bold text-gray-600">Mode : <span id="yeelight<?= $row['id'] ?>_current_mode"><?= ${'yeelight' . $row['id'] . '_data'}['mode'] ?></div>
											<div class="h5 mb-0 font-weight-bold text-gray-600">Power status : <span id="yeelight<?=  $row['id'] ?>y_power_status"><?= ${'yeelight' . $row['id'] . '_data'}['power'] == "on" ? "On" : "Off" ?></span></div>
											<div class="h5 mb-0 font-weight-bold text-gray-600">Brightness : <span class="yeelight<?= $row['id'] ?>_current_brightness"><?= ${'yeelight' . $row['id'] . '_data'}['brightness'] ?></span></div>
											<div class="onoffswitch">
												<input type="checkbox" name="yeelight<?= $row['id'] ?>onoffswitch" class="onoffswitch-checkbox <?= ${'yeelight' . $row['id'] . '_data'}['power'] == "on" ? 'checked' : '' ?>" onclick="on_off('yeelight<?= $row['id'] ?>')" id="yeelight<?= $row['id'] ?>onoffswitch" >
												<label class="onoffswitch-label" for="yeelight<?= $row['id'] ?>onoffswitch">
													<span class="onoffswitch-inner"></span>
													<span class="onoffswitch-switch"></span>
												</label>
											</div>
											<div class="dropdown no-arrow">
												<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Options Mode
												</button>
												<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
													<a class="dropdown-item" href="#">Full white</a>
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
		<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
		</a>
		<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
			<div class="modal-footer">
				<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
				<a class="btn btn-primary" href="login.html">Logout</a>
			</div>
			</div>
		</div>
		</div>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
		<script src="js/sb-admin-2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.0/dist/spectrum.min.js"></script>
        <script>
			<?php foreach($results_js as $row): ?>
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
foreach($results_session as $row)
    {
		$_SESSION['yeelight' . $row['id']] = serialize(${'yeelight' . $row['id']});
    }
?>

