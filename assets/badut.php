<?php 
	$conn = mysqli_connect("n59.ultra-h.com","server_5675","ljrpdb","server_5675_ljrpdb");

	if(isset($_POST['register']))
	{	
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$ppassword = $_POST['ppassword'];

		$email_query = "SELECT * FROM players WHERE email='$email'";

		$username_query = "SELECT * FROM players WHERE username = '$username'";

		$username_query_run = mysqli_query($conn,$username_query);

		$email_query_run = mysqli_query($conn,$email_query);
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
			$panjanghash = 16;
			$salt = base64_encode(random_bytes(ceil(0.75*$panjanghash)));
			$password = $this->Sha256($password, $salt);
			$token= md5(rand(0,1000));
			if ($result) 
			{
				$select = "INSERT INTO `server_5675_ljrpdb`.`players` (`reg_id`, `username`, `password`, `salt`, `email`, `reg_date`, `last_login`, `last_ip`, `session_id`, `gender`, `verifkey`) VALUES (NULL, '".$character."', '".$password."', '".$salt."', '".$email."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '".$this->getRealIpAddr()."', '".session_id()."', '".$gender."', '".$token."');");
				$result = mysqli_query($conn,$select);

				$lastId = mysqli_insert_id($conn);
				$url = "http://".$_SERVER['HTTP_HOST'].'/verify.php?id='.$lastId.'&token='.$token;                                // Set email format to HTML
				
				$output = '<div>Hello,<br>
				Thanks for registering with Lost Java Indonesia UCP. Please click this link to confirm your registration <br>
				<br>'.$url.'
				<br>
				<br>
				Regards,
				<br>
				LJRP TEAM</div>';

				$mail = new PHPMailer();
				$mail->isSMTP();  
				$mail->SMTPAuth = true;
				$mail->SMTPSecure = 'ssl'; 
				$mail->Host = 'smtp.gmail.com';
				$mail->Port = 25; 
				$mail->isHTML();
				$mail->Username = EMAIL;
				$mail->Password = PASS;
				$mail->setFrom('noreply');
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
			public function GetFullURL()
			{
				$fullurl = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
				return $fullurl;
			}
			
			public function Sha256($value, $salt)
			{
				return strtoupper(hash('sha256', $value . $salt));
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
                            <button type="Submit" name="register" id="tombolreg" onclick="validasi()">Daftar</button>
                            <div class="loginkan">
                            <a href="login.php">Login</a>
                            </div>
                        </form>
                    
						<script src="js/jquery-3.4.1.min.js"></script>
						<script src="js/sweetalert2.all.min.js"></script>
						<script> 
							$("#tombolreg").click(function(e){
							var username = $("#username").val();
							var email = $("#email").val();
							var password = $("#password").val();
							var ppassword = $("#ppassword").val();

							if(username == '' && email == '' && password == '' && ppassword == ''){
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
							else if(repassword == ''){
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