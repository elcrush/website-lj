<?php 
	include 'includes/config.php'; 

    require_once 'classes/ucp_user.class.php';
    $ucp_user = new User();
    $koneksi = mysqli_connect("20.28.181.232", "ljrp", "lJrp1234a", "test");
     
    if (mysqli_connect_errno()){
        echo "Koneksi database gagal : " . mysqli_connect_error();
    }
	define ('ENVIRONMENT', 'development');
    if(isset($_POST['submit']))
    {
        if(empty($_POST['password']))
            $_SESSION['error_msg'] = "Username tidak boleh kosong!";
            $ucp_user->resetpassword(
                    $_POST['password'],
                    $_POST['ppassword'],
                    $_GET['verifcode']
                );  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reset Password</title>
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
                                <h1 class="h4 text-gray-900 mb-4">Reset Password!</h1>
                                <p class="mb-4">Silahkan masukan password baru dibawah ini!</p>
                            </div>
                            <form method="POST" class="user">
                                <?php
                                    $verifcode=$_GET['t'];
                                    $sql_cek=mysqli_query($koneksi,"SELECT * FROM players WHERE resetpw='".$verifcode."'and isverif='1'");
                                    $jml_data=mysqli_num_rows($sql_cek);
                                    if ($jml_data>0)  
                                    {
                                        //update data users aktif
                                        echo '<div class="form-group">
                                        <input type="password" name="password" class="form-control form-control-user" id="password"
                                            required="required" minlength="8" placeholder="Masukan password baru">
                                        </div>
                                        <div class="form-group">
                                        <input type="password" name="ppassword" class="form-control form-control-user" id="ppassword"
                                            required="required" minlength="8" placeholder="Masukan ulang password">
                                        </div>
                                        <button type="submit" name="submit" id="tombolregister" class="btn btn-primary btn-user btn-block">Submit</button>';
                                    }
                                    else
                                    {
                                        //data tidak di temukan
                                        echo '<div class="alert alert-warning">
                                        Invalid Link!
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