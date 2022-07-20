<?php 
	include 'includes/config.php'; 

	require_once 'classes/ucp_user.class.php';
	$ucp_user = new User();

	if($ucp_user->IsLogged())
	{
		header('location: panel.php');
		exit();
	}

	if(isset($_POST['submit']))
	{
		if(empty($_POST['username']))
			$_SESSION['error_msg'] = "Username tidak boleh kosong!";
		else if(empty($_POST['email']))
			$_SESSION['error_msg'] = "Email tidak boleh kosong!";
		else if(empty($_POST['password']))
			$_SESSION['error_msg'] = "Password tidak boleh kosong!";
		else if(empty($_POST['ppassword']))
			$_SESSION['error_msg'] = "konfirmasi password tidak boleh kosong!";
		else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			$_SESSION['error_msg'] = "Alamat email yang kamu masukan tidak valid!";
		else
			$ucp_user->Register(
					$_POST['username'],
					$_POST['email'],
					$_POST['password'],
					$_POST['ppassword'],
					$_POST['last_ip']
				);	
	}
?>
    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form method="POST" class="user">
                                <div class="form-group">
                                    <input type="username" name="username" class="form-control form-control-user" id="username"
                                        placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email"class="form-control form-control-user" id="email"
                                        placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="password" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="ppassword" class="form-control form-control-user"
                                            id="ppassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="custom-control" for="captcha">ketik captcha dibawah bahwa anda bukan robot!:</label>
                                    <img src="includes/captcha.php"><br>
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <input type="text" name="captcha" id="captcha" required="required"><br>
                                    </div>
                                </div>
                                <div class="form-group">
                                   	<div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="agreeterm" name="agreeterm">
                                        <label class="custom-control-label" for="agreeterm">I Have Read <a href="termservice.php"> Term And Service</a></label>
                                    </div>
                                </div>
                                <button type="submit" name="submit" id="tombolregister" class="btn btn-primary btn-user btn-block">
                                            Register
                                </button>
                                <hr>
                            </form>
                            <div class="text-center">
                                <a class="small" href="index.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
