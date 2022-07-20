<?php
    require_once 'classes/ucp_user.class.php';
    $ucp_user = new User();
    

    if($ucp_user->IsLogged())
    {
        header('location: dashboard.php');
        exit();
    }
    
    
    if(isset($_POST['submitlogin']))
    {
        if(empty($_POST['username']))
            $_SESSION['error_msg'] = "Username tidak boleh kosong!";
        else if(empty($_POST['password']))
            $_SESSION['error_msg'] = "Password tidak boleh kosong!";
        else
            $ucp_user->login(
                    $_POST['username'],
                    $_POST['password']
                );  
    }
    if(isset($_POST['submitregister']))
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
                    $_POST['last_ip'],
                    $_POST['captcha']
                );  
    }
    if(isset($_POST['submitresetpw']))
    {
        if(empty($_POST['email']))
            $_SESSION['error_msg'] = "Username tidak boleh kosong!";
        else
            $ucp_user->ForgotPassword(
                    $_POST['email']
                );  
    }
?>
    <div class="container">
        <?php 
                if(empty($_GET['page']))
                {
            ?>
        <!-- Outer Row -->

        <div class="card o-hidden border-0 shadow-lg my-5 mx-auto ">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                            </div>
                            <form method="POST" class ="user">
                                <div class="form-group">
                                    <input type="username" class="form-control form-control-user"
                                        id="username" name="username" required="required" aria-describedby="emailHelp"
                                        placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user"
                                        id="password" required="required" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="togglePassword" name="togglePassword">
                                        <label class="custom-control-label" for="togglePassword">Show Password</label>
                                    </div>
                                </div>
                                <button type="submit" name="submitlogin" id="tombollogin" class="btn btn-primary btn-user btn-block">Login
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="?page=register">Create an Account!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="?page=forgot-password">Forgot Password?</a>
                            </div>
                                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
            else if($_GET['page'] == 'register')
            {
        ?>
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
                                         required="required" maxlength="10" autocomplete="off" minlength="4" placeholder="Gunakan Username Bukan Nama IC">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email"class="form-control form-control-user" required="required" id="email"
                                        placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="password" required="required" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" name="ppassword" class="form-control form-control-user"
                                            id="ppassword" required="required" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                        <label for="captcha">ketik captcha dibawah bahwa anda bukan robot!:</label><br>
                                        <img src="includes/captcha.php" style="margin-left: 35%; width: 30%;"><br>
                                        <input class="form-control form-control-user" type="number" name="captcha" id="captcha" required="required"><br>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox small">
                                        <input type="checkbox" class="custom-control-input" id="agreeterm" name="agreeterm" required="required">
                                        <label class="custom-control-label" for="agreeterm">I Have Read <a onclick ="myWindow()">Term And Service</a></label>
                                    </div>
                                </div>
                                <button type="submit" name="submitregister" id="tombolregister" class="btn btn-primary btn-user btn-block">Register</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="/">Already have an account? Login!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="?page=forgot-password">Forgot Password?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
                }
                else if($_GET['page'] == 'forgot-password')
                {
            ?>
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block"></div>
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                            and we'll send you a link to reset your password!</p>
                                    </div>
                                    <form method="POST" class="user">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="email" name="email" aria-describedby="emailHelp" required="required"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <button type="submit" name="submitresetpw" id="tombollogin" class="btn btn-primary btn-user btn-block">
                                            Reset Password
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="?page=register">Create an Account!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="/">Already have an account? Login!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?PHP
        }
        else if($_GET['page'] == 'termservice')
        {
        ?>
        <div class="card o-hidden border-0 shadow-lg my-5 col-lg-7 mx-auto">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Term & Service!</h1>
                            </div>
                            <p><span><bold>1.</bold></span> Tetap Patuhi Semua Rules Server!</p>
                            <p><span><bold>2.</bold></span> Setiap user/players hanya diperkenankan register 1x dan dilarang keras untuk register akun lagi!.</p>
                            <p><span><bold>3.</bold></span> Wajib join discord ljrp official maupun whitelist discord!</p>
                            <p><span><bold>4.</bold></span> Tetap mengingat tuhan walaupun sedang RP!</p>
                                <br>
                                <p><center>ENJOY</center></p>
                            <button onclick="myWindowClose()" class="btn btn-primary btn-user btn-block">
                                    Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="assets2/vendor/jquery/jquery.min.js"></script>
    <script src="assets2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets2/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets2/js/sb-admin-2.min.js"></script>

    <script type="text/javascript">
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e){
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript"> 
        $("#tombollogin").click(function(e){
        var username = $("#username").val();
        var password = $("#password").val();
        var password = $("#email").val();
                            
        if(username == '' && password == ''){
            Swal.fire("Opss..","Please Input Your Data!","warning");
            e.preventDefault();
        }
        else if(username == ''){
            Swal.fire("Opss..","Your Username Is Empty!","warning");
            e.preventDefault();
        }
        else if(password == ''){
            Swal.fire("Opss..","Your Password Is Empty!","warning");
            e.preventDefault();
        }
        else if(email == ''){
            Swal.fire("Opss..","Your Email Is Empty!","warning");
            e.preventDefault();
        }
    })
    </script>
    <script>
    function myWindow()
    {
     var myWindow = window.open("https://ucp.lostjavaindonesia.com/index?page=termservice","","width=1200,height=600");
    }
    function myWindowClose()
    {
     var myWindow = window.close();
    }
    </script>
    <script type="text/javascript">
    var input = document.getElementById("username");

    // Get the warning text
    var text = document.getElementById("text");

    // When the user presses any key on the keyboard, run the function
    input.addEventListener("keyup", function(event) {

      // If "caps lock" is pressed, display the warning text
      if (event.getModifierState("CapsLock")) {
        text.style.display = "block";
      } else {
        text.style.display = "none"
      }
    });
    </script>