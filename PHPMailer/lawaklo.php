<?php 
$conn = mysqli_connect("n59.ultra-h.com", "server_5675", "ljrpdb", "server_5675_ljrpdb");

if(isset($_POST['register']))
{	
    $username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
    $repassword = $_POST['ppassword'];
    $IP = $_SERVER['REMOTE_ADDR'];

    $email_query = "SELECT * FROM players WHERE email='$email'";

    $username_query = "SELECT * FROM players WHERE username = '$username'";

    $username_query_run = mysqli_query($conn,$username_query);

    $email_query_run = mysqli_query($conn,$email_query);
    if(mysqli_num_rows($username_query_run)&&( $email_query_run) > 0){
    ?>
        <script src="js/jquery-3.4.1.min.js"></script>
        <script src="js/sweetalert2.all.min.js"></script>
        <script>
            Swal.fire("Opss..","Username And Email Already Taken. Please Try Another One.!","warning");
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
        $token = md5(rand('10000', '99999'));
        $salt = base64_encode(random_bytes(ceil(0.75*$panjanghash)));
        if ($password == $ppassword) 
        {
            
            $password = $this->Sha256($password, $salt);
		    $select = "INSERT INTO players(username,password,ip,token,status,email)VALUES('".$username."','".$password."','".$ip."','".$token."','Inactive','".$email."')";
		    $result = mysqli_query($conn,$select);

            $lastId = mysqli_insert_id($conn);
            $url = 'https://'.$_SERVER['SERVER_NAME'].'/verify.php?id='.$lastId.'&token='.$token;                                // Set email format to HTML
            
            $output = '<div>Hello,<br>
            Thanks for registering with Lost Java Roleplay UCP. Please click this link to comfirm your registration <br>
            <br>'.$url.'
            <br>
            <br>
            Regards,
            <br>
            LJRP Team</div>';

            $mail = new PHPMailer();
            $mail->isSMTP();  
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '25'; 
            $mail->isHTML();
            $mail->Username = EMAIL;
            $mail->Password = PASS;
            $mail->setFrom(EMAIL, 'Lost Java Indonesia:UCP');
            $mail->Subject = 'Lost Java:UCP Registration';
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
    }
}
?>
    <div class="main">

        <!-- Sign up form -->
        <section class="signup" style="margin-top: -11%; margin-bottom: -10%;">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" class="register-form" id="registers">
                            <div class="form-group">
                                <label for="Username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="Username" id="username" placeholder="Masukkan Username Anda"/>
                            </div>
                            <div class="form-group">
                                <label for="Email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="Email" id="email" placeholder="Masukkan Email Anda"/>
                            </div>
                            <div class="form-group">
                                <label for="Password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="Password" id="password" placeholder="Masukkan Password Anda"/>
                            </div>
                            <div class="form-group">
                                <label for="Repassword"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="Repassword" id="repassword" placeholder="Ulangi Password Anda"/>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" />
                                <label for="agree-term" class="label-agree-term" require><span><span></span></span>I agree all statements in  <a href="#" class="term-service">Terms of service</a></label>
                            </div>
                            <button type="Submit" name="register" id="tombolreg" onclick="validasi()">Register</button>
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
                        var repassword = $("#repassword").val();

                        if(username == '' && email == '' && password == '' && repassword == ''){
                            Swal.fire("Opss..","Please Input Your Data!","warning");
                            e.preventDefault();
                        }
                        else if(email == '' && password == '' && repassword == ''){
                            Swal.fire("Opss..","Your Email or Password Is Empty!","warning");
                            e.preventDefault();
                        }
                        else if(password == '' && repassword == ''){
                            Swal.fire("Opss..","Your Password and Repassword Is Empty!","warning");
                            e.preventDefault();
                        }
                        else if(repassword == ''){
                            Swal.fire("Opss..","Your Repassword Is Empty!","warning");
                            e.preventDefault();
                        }
                        else if(password != repassword){
                            Swal.fire("Opss..","Your Password and Repassword Not Match!","warning");
                            e.preventDefault();
                        }
                    })
                    
                    </script>
                    </div>
                    <div class="signup-image">
                        <figure><img src="images/yas.png" alt="sing up image"></figure>
                    </div>
                </div>
            </div>
        </section>

    </div>