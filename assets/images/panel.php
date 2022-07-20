<?php	
	include 'includes/config.php'; 
	
	/* UCP User class */
	require_once 'classes/ucp_user.class.php';
	$ucp_user = new User();
	

	if(!$ucp_user->IsLogged())
	{
		header('location: index.php');
		exit();
	}
	

	if($ucp_user->IsBanned($_SESSION['username']))
	{
        session_destroy();
		header('location: index.php');
		exit();
	}

	if(isset($_POST['settings_submit']))
	{
		if(empty($_POST['tpassword']))
			$_SESSION['error_msg'] = "form password kosong!";
		else if(empty($_POST['npassword']))
			$_SESSION['error_msg'] = "kode baru tidak boleh kosong!";
		else
			$ucp_user->UpdateProfile(
					$_SESSION['username'],
					$_POST['tpassword'],
					$_POST['npassword']
				);	
	}	

	if(isset($_POST['verif_char']))
	{
		if(empty($_POST['verifcharacter']))
			$_SESSION['error_msg'] = "Nama character tidak boleh kosong";
		else
			$ucp_user->VerifCharacter($_POST['verifcharacter']);
	}

?>
<html>
	<head>
		<!-- Title -->
		<title>LJI:UCP</title>
		<link rel="stylesheet" href="css/style.css">
	
		<!-- Loading Favicon -->
		<link rel="icon" href="favicon.ico" type="image/x-icon">
	
	
		<!-- Including Font Awesome -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="atas">
    		<h3> Welcome to Lost Java Indonesia</h3>
    		<div class="akun">
        		<p><?php echo '' . $_SESSION['username']; ?></p><img src="assets/images/okee.png" alt="profile">
    		</div>
		</div>
			<?php
				if($ucp_user->IsLogged()) 
				{
			?>
				<div id="login-place" class="login-place">
					<a href="?page=settings"><div class="button">Pengaturan</div></a>
				</div>
				<div class="keluar">
					<a href="logout.php">Logout<img src="assets/images/logout.png" alt="logout"></a>
					<br>
					<a href="?page=settings">Setting<img src="assets/images/settings.png" alt="settings"></a>
				</div>
			<?php 
				}
			?>
			<?php if(isset($_SESSION['error_msg'] )){?>
			<?php echo '<div class="box-alert">'.$_SESSION['error_msg'] .'</div>';unset($_SESSION['error_msg']);?>
			<?php } ?>		
			<?php if(isset($_SESSION['success_msg'] )){?>
			<?php echo '<div class="box-success">'.$_SESSION['success_msg'] .'</div>';unset($_SESSION['success_msg']);?>
			<?php } ?>
			
			<?php 
				if(empty($_GET['page']))
				{
			?>
			<div class="konten">
				<h4>Informasi Karakter : </h4>
				<table>
				<tbody>
				<tr>
				<td><i class="fa fa-id-card-o" aria-hidden="true"> Name: <p><?php echo $ucp_user->Getintuser($_SESSION['username'], 'username');?></p></i></td>
				</tr>
				<tr>
				<td><br><i class="fa fa-venus-mars" aria-hidden="true"> Gender: <p><?php echo $ucp_user->GetGender($_SESSION['username']);?></p></i></td>
				</tr>
				<tr>
				<td><br><i class="fa fa-address-card-o" aria-hidden="true"> Age: <p><?php echo $ucp_user->Getumur($_SESSION['username']);?></p></i></td>
				</tr>
				<tr>
				<td><br><i class="fa fa-line-chart" aria-hidden="true"> Level: <p><?php echo $ucp_user->Getintuser($_SESSION['username'], "level");?></p></i></td>
				</tr>
				<tr>
				<td><br><i class="fa fa-credit-card-alt" aria-hidden="true"> Cash: <p><?php echo $ucp_user->dolarformat($_SESSION['username'], "money");?></p></i></td>
				</tr>
				<tr>
				<td><br><i class="fa fa-university" aria-hidden="true"> Bank: <p><?php echo $ucp_user->dolarformat($_SESSION['username'], "bmoney");?></p></i></td>
				</tr>
				<tr>
				<td><br><b><i class="fa fa-warning" aria-hidden="true"> Warn: <p><?php echo $ucp_user->Getintuser($_SESSION['username'], "warn");?> / 20</p></i></b></td>
				</tr>
				<tr>
				<td><br><i class="fa fa-info-circle" aria-hidden="true"> Status Account: <p><?php echo $ucp_user->Getverif($_SESSION['username']);?></p></i></td>
				</tr>
				</tbody>
				</table>
			</div>
				<?php
					}
					else if($_GET['page'] == 'settings')
					{
				?>
				<div class="info-box">
					<h2>Pengaturan Akun</h2>
					<form id="register-place" class="login-place" method="POST">
					<div class="register-holder">
							<label for="tpassword">Masukan password saat ini</label><br>
							<input type="password" id="tpassword" name="tpassword" placeholder="password saat ini" required="required"><br>	
						</div>
						<div class="register-holder">
							<label for="tpassword">Password baru</label><br>
							<input type="password" id="npassword" name="npassword" placeholder="password baru" required="required"><br>
						</div>
						<div class="register-holder">
							<input type="submit" name="settings_submit" value="Send">
						</div>
					</form>
					<table class="server-info">
						<tbody>
							<?php foreach($ucp_user->LastLoginLog($_SESSION['username']) as $log) { ?>
							<tr>
								<td><?php echo $log['date'];?></td>
								<td><?php echo $log['ip'];?></td> 
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php 
				}
			?>
		<footer>
			<small>Copyright &copy; ( 2020 - 2021 ) - LOST JAVA INDONESIA - ALL RIGHT RESERVED</small>
		</footer>
	</body>
</html>