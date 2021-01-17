<?php
session_start();
require('Yeelight.php');
$db_dir = "sqlite:./yeelight.sqlite";
$db = new PDO($db_dir);
$results_show = $db->query('SELECT yeelight.id, yeelight.name as name, ip_address, type.id as type_id, type.name as type_name FROM yeelight JOIN type ON type.id = yeelight.type')->fetchAll();
$results_type = $db->query('SELECT * FROM type')->fetchAll();
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
							<h1 class="h2 mb-0 text-gray-800">Manage Yeelight products</h1>
						</div>
						<div class="row">
							<div class="col-6">
								<h3>Edit Yeelight products</h3>
								<div id="listYeelight">
									<?php foreach($results_show as $product): ?>
									<div class="mb-3">
										<div class="form-row align-items-center">
											<input type="number" class="form-control mb-2" id="editId<?= $product['id'] ?>" hidden value="<?= $product['id'] ?>">
											<div class="col-auto">
												<label class="sr-only" for="editName<?= $product['id'] ?>">Name</label>
												<input type="text" class="form-control mb-2" id="editName<?= $product['id'] ?>" value="<?= $product['name'] ?>">
											</div>
											<div class="col-auto">
												<label class="sr-only" for="editAddressip<?= $product['id'] ?>">IP Address</label>
												<div class="input-group mb-2">
												<input type="text" class="form-control" id="editAddressip<?= $product['id'] ?>" value="<?= $product['ip_address'] ?>">
												</div>
											</div>
											<div class="col-auto">
												<label class="sr-only" for="editType<?= $product['id'] ?>">Type</label>
												<div class="input-group mb-2">
													<select name="selectType" class="form-control" id="editType<?= $product['id'] ?>">
														<option value="<?= $product['type_id'] ?>" selected><?= $product['type_name'] ?></option>
														<?php foreach($results_type as $type):
															if($type['id'] == $product['type_id'])
															{
																continue;
															}
														?>
														<option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
														<?php endforeach ?>                         
													</select>
												</div>
											</div>
											<div class="col-auto">
												<button onclick="edit_yeelight(<?= $product['id'] ?>)" class="btn btn-primary mb-2">Edit</button>
												<button onclick="delete_yeelight(<?= $product['id'] ?>)" class="btn btn-danger mb-2">Delete</button>
											</div>
										</div>
									</div>
									<?php endforeach ?>
								</div>
							</div>
							<div class="col-6">
								<h3>Add new Yeelight products</h3>
								<div class="form-group">
									<label for="newName">Name</label>
									<input type="text" class="form-control" id="newName">
								</div>
								<div class="form-group">
									<label for="newAdresseip">IP Address</label>
									<input type="text" class="form-control" id="newAdresseip">
								</div>
								<div class="form-group">
									<label for="newType">Type</label>
									<select class="form-control" id="newType">
										<option value="0" selected>Choose...</option>
									<?php foreach($results_type as $type): ?>
										<option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
									<?php endforeach ?>
									</select>
								</div>
								<button onclick="add_yeelight()" class="btn btn-primary">Submit</button>
							</div>
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
    	<script src="js/main.js"></script>
	</body>
</html>

