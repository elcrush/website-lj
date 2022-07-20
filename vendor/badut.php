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
		else if(empty($_POST['gender']))
			$_SESSION['error_msg'] = "Gender tidak boleh kosong";
		else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
			$_SESSION['error_msg'] = "Alamat email yang kamu masukan tidak valid!";
		else
			$ucp_user->Register(
					$_POST['username'],
					$_POST['email'],
					$_POST['password'],
					$_POST['ppassword'],
					$_POST['gender'],
					$_POST['verifkey']
				);	
	}

	if(isset($_POST['submit']))
	{	
		$email = $_POST['email'];
		$email_query = "SELECT * FROM players WHERE email='$email'";

		$username_query = "SELECT * FROM players WHERE username = '$username'";

		$username_query_run = mysqli_query($connection,$username_query);

		$email_query_run = mysqli_query($connection,$email_query);
		if(mysqli_num_rows($username_query_run)&&( $email_query_run) > 0){
		?>
			<script src="js/jquery-3.4.1.min.js"></script>
			<script src="js/sweetalert2.all.min.js"></script>
			<script>
				Swal.fire("Opss..","Username And Email Already Taken. Please Try Another One.!","error");
				e.preventDefault();
			</script>
		<?php
		}
		else if(mysqli_num_rows($username_query_run) > 0){
		?>
			<script src="js/jquery-3.4.1.min.js"></script>
			<script src="js/sweetalert2.all.min.js"></script>
			<script>
				Swal.fire("Opss..","Username Already Taken. Please Try Another One.!","warning");
				e.preventDefault();
			</script>
		<?php
		}
		else if(mysqli_num_rows($email_query_run) > 0){
		?>
			<script src="js/jquery-3.4.1.min.js"></script>
			<script src="js/sweetalert2.all.min.js"></script>
			<script>
				Swal.fire("Opss..","Email Already Taken. Please Try Another One.!","warning");
				e.preventDefault();
			</script>
		<?php
		}
		else
		{
			$verifkey= md5(rand(0,1000));
			$hash = md5(rand('10000', '99999'));
			$code = md5(rand('10000', '99999'));

			if ($password == $ppassword) 
			{
				$url = 'http://'.$_SERVER['SERVER_NAME'].'/verify.php?user='.$username.'&token='.$verifkey;                                // Set email format to HTML
				
				$output = '<div>Hello,<br>
				Thanks for registering with Lost Java Indonesia UCP. Please click this link to confirm your registration <br>
				<br>'.$url.'
				<br>
				<br>
				Regards,
				<br>
				Lost Java Indonesia</div>';

				$mail = new PHPMailer();
				$mail->isSMTP();
				$mail->SMTPDebug = 2;  
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'tls'; 
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 587; 
				$mail->isHTML(true);
				$mail->Username = "lostjavaindonesia@gmail.com";
				$mail->Password = "ljrp2020";
				$mail->setFrom("lostjavaindonesia@gmail.com", 'noreply');
				$mail->Subject = 'UCP Registration';
				$mail->Body    = $output;
				$mail->AddAddress($email);
				if($mail->send())
				{
					?>
					<script src="js/jquery-3.4.1.min.js"></script>
					<script src="js/sweetalert2.all.min.js"></script>
					<script>
						Swal.fire("Congratulations","Please Verify Your Email!","success");
						e.preventDefault();
					</script>
					<meta http-equiv='refresh' content='5; url="index.php'>;
					<?php
				}
				else if(!$mail->send())
				{
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} 
			}    
			else
			{
			?>
				<script src="js/jquery-3.4.1.min.js"></script>
				<script src="js/sweetalert2.all.min.js"></script>
				<script>
					Swal.fire("Opss..","Your Password and Repassword Not Match!","warning");
					e.preventDefault();
				</script>
			<?php
			}
		}
	}
?>
    <div class="main">
        <!-- Sign up form -->
        <section class="signup" style="margin-top: -11%; margin-bottom: -10%;">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Registration</h2>
                        <form method="POST" class="register-form" id="registers">
                            <div class="form-group">
                                <label for="username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username" id="username" placeholder="Masukkan Username Anda"/>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Masukkan Email Anda"/>
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="Masukkan Password Anda"/>
                            </div>
                            <div class="form-group">
                                <label for="ppassword"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="ppassword" id="ppassword" placeholder="Ulangi Password Anda"/>
                            </div>
                            <div class="form-group">
							<label for="gender">Pilih gender character:</label><br>
							<tr>
								<td>
									<input type="radio" name="gender" id="genderMale" value="1"><label for="gender">Pria</label>
									<input type="radio" name="gender" id="genderFemale" value="2"><label for="gender">Wanita</label>		  
								</td>
							</tr>
							</div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term" require><span><span></span></span>I agree all statements in  <a href="termservice.php" class="term-service">Terms of service</a></label>
                            </div>
                            <button type="Submit" name="submit" id="tombolreg" onclick="validasi()">Daftar</button>
                            <div class="loginkan">
                            <a href="login.php">Login</a>
                            </div>
                        </form>
                    
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script> 
							$("#tombolreg").click(function(e){
							var fname = $("#fname").val();
							var lname = $("#lname").val();
							var email = $("#email").val();
							var password = $("#password").val();
							var ppassword = $("#ppassword").val();

							if(fname == '' && lname == '' && email == '' && password == '' && ppassword == ''){
								Swal.fire("Opss..","Please Input Your Data!","warning");
								e.preventDefault();
							}
							else if(email == '' && password == '' && ppassword == ''){
								Swal.fire("Opss..","Your Email or Password Is Empty!","warning");
								e.preventDefault();
							}
							else if(password == '' && ppassword == ''){
								Swal.fire("Opss..","Your Password and Repassword Is Empty!","warning");
								e.preventDefault();
							}
							else if(ppassword == ''){
								Swal.fire("Opss..","Your Repassword Is Empty!","warning");
								e.preventDefault();
							}
							else if(password != ppassword){
								Swal.fire("Opss..","Your Password and Repassword Not Match!","warning");
								e.preventDefault();
							}
						})						
						</script>
                    </div>
                    <div class="signup-image">
                        <figure><img src="assets/images/lostjava.png" alt="sing up image"></figure>
                    </div>
                </div>
            </div>
        </section>
    </div>