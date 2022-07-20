<?php
    require_once 'classes/ucp_user.class.php';
    $ucp_user = new User();
    

    if($ucp_user->IsLogged())
    {
        header('location: dashboard.php');
        exit();
    }
    
    
    if(isset($_POST['submit']))
    {
        if(empty($_POST['username']))
            $_SESSION['error_msg'] = "Username tidak boleh kosong!";
        else if(empty($_POST['password']))
            $_SESSION['error_msg'] = "Password tidak boleh kosong!";
        else
            $ucp_user->login(
                    $_POST['username'],
                    $_POST['password'],
                );  
    }
?>
        <?php if(isset($_SESSION['error_msg'] )){?>
        <?php echo '<div class="box-alert">'.$_SESSION['error_msg'] .'</div>';unset($_SESSION['error_msg']);?>
        <?php } ?>      
        <?php if(isset($_SESSION['success_msg'] )){?>
        <?php echo '<div class="box-success">'.$_SESSION['success_msg'] .'</div>';unset($_SESSION['success_msg']);?>
        <?php } ?>
    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-7">

                <div class="card o-hidden border-0 shadow-lg my-5">
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
                                            <input type="text" class="form-control form-control-user"
                                                id="username" name="username" aria-describedby="emailHelp"
                                                placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="togglePassword" name="togglePassword">
                                                <label class="custom-control-label" for="togglePassword">Show Password</label>
                                            </div>
                                        </div>
                                        <button type="submit" name="submit" id="tombollogin" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

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
    <script> 
        $("#tombollogin").click(function(e){
        var username = $("#username").val();
        var password = $("#password").val();
                            
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
    })
    </script>
