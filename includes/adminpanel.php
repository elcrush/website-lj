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
	
	if(isset($_POST['pasang_info']))
	{
		if(empty($_POST['judul_info']))
			$_SESSION['error_msg'] = "Judul info tidak boleh kosong!";
		else if(empty($_POST['obavijest_text']))
			$_SESSION['error_msg'] = "Isi text informai tidak boleh kosong!";
		else
			$ucp_user->CreateAnnouncement($_SESSION['username'], $_POST['judul_info'], $_POST['obavijest_text']);
	}

	if(isset($_POST['verif_char']))
	{
		if(empty($_POST['verifcharacter']))
			$_SESSION['error_msg'] = "Nama character tidak boleh kosong";
		else
			$ucp_user->VerifCharacter($_POST['verifcharacter']);
	}
	if($ucp_user->IsLogged()) 
?>
	<head>
		<!-- Title -->
		<title>Admin Panel:UCP</title>
	
		<!-- Loading Favicon -->
		<link rel="icon" href="favicon.ico" type="image/x-icon">
	
		<!-- Including CSS -->
		<link rel="stylesheet" href="assets/css/style.css">
	
		<!-- Including Font Awesome -->
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	</head>
			<?php if(isset($_SESSION['error_msg'] )){?>
			<?php echo '<div class="box-alert">'.$_SESSION['error_msg'] .'</div>';unset($_SESSION['error_msg']);?>
			<?php } ?>		
			<?php if(isset($_SESSION['success_msg'] )){?>
			<?php echo '<div class="box-success">'.$_SESSION['success_msg'] .'</div>';unset($_SESSION['success_msg']);?>
			<?php } ?>
	<div class="info-box">
		<h2>Verif Character</h2>
		<form class="login-place" method="POST">
			<div class="register-holder">
				<label for="verifcharacter">Masukan character yang ingin di verifikasi</label><br>
					<input type="text" id="verifcharacter" name="verifcharacter" required="required"><br>
			</div>
			<div class="register-holder">
				<input type="submit" name="verif_char" value="verifikasi">
			</div>
		</form>						
	</div>
