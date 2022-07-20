<head>
	<!-- Title -->
	<title><?php echo $config['ime-servera'];?></title>
	
	<!-- Loading Favicon -->
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	
	<!-- Including CSS -->
	<link rel="stylesheet" href="assets/css/style.css">
	
	<!-- Including Font Awesome -->
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

	<!-- ADSENSE SCRIPT -->

	<link rel="stylesheet" href="../style/jquery.scrollbar.css?v=2">
	<script type="text/javascript" src="../style/js/jquery.scrollbar.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($){
		window.prettyPrint && prettyPrint();
		$('.textnews').scrollbar();
	});
	</script><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<script src="../style/js/jquery.knob.js"></script>
	<script src="https://www.google.com/recaptcha/api.js"></script>
	<meta name="keywords" content="gta, ucp ljrp, lost java indonesia, ljrp, ljrp ucp, samp">
	<meta name="description" content="Lost Java Indonesia adalah server Grand Theft Auto San Andreas yang telah dimodifikasi untuk bisa dimainkan multiplayer atau biasa disebut SAMP, Lost Java Indonesia sendiri merupakan server roleplay SAMP Indonesia.">	
</head>
<body oncontextmenu="return false" onkeydown="return false;" onmousedown="return false;">
	<div class="wrapper">
		<div class="menu">
			<ul class="navigation">
				<li><a <?php if(basename($_SERVER["PHP_SELF"]) == 'index.php'){ echo 'class="active firstlevel"';}?> href="index.php">Home</a></li>
				<li><a class="firstlevel" href="<?php echo $config['discord-group'];?>">Discord</a></li>
				<li><a <?php if(basename($_SERVER["PHP_SELF"]) == 'about.php'){ echo 'class="active firstlevel"';}?> href="about.php">About</a>
				<ul>
					<li><a <?php if(basename($_SERVER["PHP_SELF"]) == 'about.php'){ echo 'class="active firstlevel"';}?> href="about.php?action=about">Informasi</a></li>
					<li><a <?php if(basename($_SERVER["PHP_SELF"]) == 'about.php'){ echo 'class="active firstlevel"';}?> href="about.php?action=team">LJRP Administration</a></li>					
				</ul>
				</li>
			</ul>
			<ul class="social-icons">
				<li><a href="<?php echo $config['facebook-page'];?>" class="fa fa-facebook"></a></li>
				<li><a href="<?php echo $config['instagram-page'];?>" class="fa fa-instagram"></a></li>
            </ul>
		</div>
		<div class="cover">
			<center><img class="logo" src="assets/images/logo-3.png"/></center>
		</div>
		<iframe id="youtube-player" frameborder="0" allowfullscreen="1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" title="YouTube video player" width="0" height="0" src="https://www.youtube.com/embed/_eoFEGa3V18?autoplay=1&amp;loop=1&amp;enablejsapi=1&amp;origin=https%3A%2F%2Fstateofindonesia.id&amp;widgetid=1"></iframe>