<?php 
	include 'includes/config.php'; 
    $koneksi = mysqli_connect("103.186.1.212", "ljrp", "ljrp2020", "ljrp");
     
    if (mysqli_connect_errno()){
        echo "Koneksi database gagal : " . mysqli_connect_error();
    }
	define ('ENVIRONMENT', 'development');
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Activation</title>
        <!-- Font Icon -->
        <link href="assets2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="assets2/css/sb-admin-2.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    </head>
    <body class="bg-gradient-primary">
	   	<div class="container">
	        <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
	            <div class="card-body p-0">
	                <!-- Nested Row within Card Body -->
	                <div class="row">
	                    <div class="col-lg">
	                        <div class="p-5">
                            <div class="text-center">
                            <br>
                                <?php
                                    $verifcode=$_GET['t'];
                                    $sql_cek=mysqli_query($koneksi,"SELECT * FROM ucp WHERE verifcode='".$verifcode."'and verifemail='0'");
                                    $jml_data=mysqli_num_rows($sql_cek);
                                    if ($jml_data>0) 
                                    {
                                        //update data users aktif
                                        mysqli_query($koneksi,"UPDATE ucp SET verifemail='1' WHERE verifcode='".$verifcode."' and verifemail='0'");
                                        echo '<div class="alert alert-success">
                                                   Akun anda sudah aktif, silahkan <a href="index.php">Login</a>
                                                   </div>';
                                    }
                                    else
                                    {
                                               //data tidak di temukan
                                        echo '<div class="alert alert-warning">
                                        Invalid Token!
                                        </div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>