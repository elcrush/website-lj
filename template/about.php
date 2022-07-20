<?php	
	include 'includes/config.php'; 
	include 'includes/header.php';


	require_once 'classes/ucp_user.class.php';
	$ucp_user = new User();
	

	if(isset($_POST['Submit']))
	{
		if(empty($_POST['username']))
			$_SESSION['error_msg'] = "nama character tidak boleh kosong!";
		else if(empty($_POST['password']))
			$_SESSION['error_msg'] = "password tidak boleh kosong!";
		else
			$ucp_user->Login($_POST['username'], $_POST['password']);	
	}
?>
		<div class="under-cover">
			<?php if($ucp_user->IsLogged()) { ?>
				<div id="login-place" class="login-place">
					<a href="panel.php?page=admin-panel"><div class="button">admin panel</div></a>
					<a href="panel.php?page=settings"><div class="button">Pengaturan</div></a>
					<a href="panel.php"><div class="button">Panel</div></a>
					<a href="logout.php"><div style="background: #bb0000;" class="button">Logout</div></a>
				</div>
				<div class="greetings">Hai <a href="#"><b><?php echo $_SESSION['username']; ?></b></a>, Selamat datang di UCP LJRP.</div>
			<?php } else { ?>
			<div id="login-place" class="login-place">
				<form action="index.php" method="post">
					<i style="color: white;" class="fa fa-user" aria-hidden="true"> <input type="text" name="username" maxlength="20"/></i>
					<i style="color: white;" class="fa fa-key" aria-hidden="true"> <input type="password" name="password" maxlength="25"/></i>
                    <input type="submit" name="Submit" value="login" class="quick-login-button" />
				</form>
			</div>
			<div class="greetings">belum punya character? daftar <a href="register.php">di sini</a></div>
			<?php } ?>
		</div>
		<?php if(isset($_SESSION['error_msg'] )){?>
			<?php echo '<div class="box-alert">'.$_SESSION['error_msg'] .'</div>';unset($_SESSION['error_msg']);?>
		<?php } ?>		
		<?php if(isset($_SESSION['success_msg'] )){?>
			<?php echo '<div class="box-success">'.$_SESSION['success_msg'] .'</div>';unset($_SESSION['success_msg']);?>
		<?php } ?>
		<div class="main-content">
			<?php if(empty($_GET['action'])) { ?>
			<a href="?action=about"><div class="redirect-btn">Informasi</div></a>
			<a href="?action=team"><div class="redirect-btn">Server Administrative</div></a>
			<?php } if($_GET['action'] == 'about') { ?>
			<center>
			<h2>Lost Java Roleplay</h2>
			<p class="about-text">Lost Java Indonesia adalah komunitas SA:MP bergenre roleplay, nama LJRP dulunya adalah Exotic Of Roleplay diubah karena nama itu sudah terkenal jelek maka dengan kepindahan OWNER nama itu diubah menjadi Lost Java Indonesia.<br>Lost Java Indonesia bukan server SA:MP saja, bahkan LJRP membuka server MTA:SA yang diperuntukan untuk player player NON ANDROID</p>
			</center>
			<p style="font-family: 'Roboto', sans-serif; text-transform: uppercase; font-weight: 300; color: #d4d4d4; margin-left: 15px; font-size: 12px;">LJRP STAFF <b>25.11.2020</b> - <b>08:10</b></p>
			<?php } else if($_GET['action'] == 'team') { ?>
			<center><h2>SERVER ADMINISTRATIVE</h2></center>
			<fieldset class="place">
				<legend class="place-title">OWNER</legend>
				<span><a href="#"><b>Fazko Riwaldy</b></a> ( PENANGGUNG JAWAB KEPERLUAN )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">DEVELOPER</legend>
				<span><a href="#"><b>Riski Evan Saputra</b></a> ( CLIENT & SCRIPTER )</span><br>
				<span><a href="#"><b>Anang Febri Pangestu</b></a> ( WEBSITE & SCRIPTER )</span><br>
				<span><a href="#"><b>Diaz Lutfi</b></a> ( MAPPING & SCRIPTER )</span><br>
				<span><a href="#"><b>Irwan Ibrahim</b></a> ( SCRIPTER )</span><br>
			</fieldset>			
			<fieldset class="place">
				<legend class="place-title">HEAD ADMIN</legend>
				<span><a href="#"><b>Bagas Aditya</b></a> ( MENGURUS ALL ADMIN )</span><br>
				<span><a href="#"><b>David</b></a> ( REPORT SCAM DAN RTM )</span><br>
				<span><a href="#"><b>Rifan</b></a> ( PENANGGUNG JAWAB REPORT PLAYER DAN MODERATOR )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">EXECUTIVE ADMIN</legend>
				<span><a href="#"><b>Faisal</b></a> ( MANAGE FAMILY )</span><br>
				<span><a href="#"><b>Diko Apriansyah</b></a> ( MANAGE FAMILY )</span><br>	
				<span><a href="#"><b>Pangestu Wibisono</b></a> ( MANAGE FAMILY )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">SENIOR ADMIN</legend>
				<span><a href="#"><b>Rayhan Firliansyah</b></a> (  REPORT SCAM DAN RTM )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">ADMIN</legend>
				<span><a href="#"><b>Dwi Rara</b></a> ( BACKUP ADMIN )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">STAFF</legend>
				<span><a href="#"><b>Rijal</b></a> ( HELPING NEW PLAYER )</span><br>
				<span><a href="#"><b>Reza</b></a> ( HELPING NEW PLAYER )</span><br>
				<span><a href="#"><b>Rizki</b></a> ( HELPING NEW PLAYER )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">HELPER</legend>
				<span><a href="#"><b>Marvin</b></a> ( HELPING NEW PLAYER )</span><br>
				<span><a href="#"><b>Fera</b></a> ( HELPING NEW PLAYER )</span><br>
				<span><a href="#"><b>Indah</b></a> ( HELPING NEW PLAYER )</span><br>
			</fieldset>
			<fieldset class="place">
				<legend class="place-title">VOLUNTEER</legend>
				<span><a href="#"><b>Rajib Hasyim Mubarok</b></a> ( HELPING NEW PLAYER )</span><br>
				<span><a href="#"><b>Ibnu Sarahil</b></a> ( HELPING NEW PLAYER )</span><br>
				<span><a href="#"><b>Alfarezy</b></a> ( HELPING NEW PLAYER )</span><br>
			</fieldset>
			
			<p style="font-family: 'Roboto', sans-serif; text-transform: uppercase; font-weight: 300; color: #d4d4d4; margin-left: 15px; font-size: 12px;">LJRP Staff Team update on: <b>29.11.2020</b> - <b>05:31</b></b></p>
			<?php } ?>
		</div>
		<div class="footer">
			<center><img class="logo" src="assets/images/logo-3.gif"/></center>
			<span class="credits-text">&copy; Copyright <?php echo date("Y"); ?> - Lost Java Roleplay - All rights reserved -</span>
			<!--<span class="time"><?php //echo date("H:i"); ?></span> -->
		</div>
	</div>
</body>