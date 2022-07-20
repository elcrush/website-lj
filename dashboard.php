<?php
    
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
        $ucp_user->UpdateProfile(
            $_SESSION['username'],
            $_POST['tpassword'],
            $_POST['npassword'],
            $_POST['ppassword']
        );  
    }
    
    if(isset($_POST['submit_changenicname']))
    {
            $ucp_user->ChangeUsername(
                    $_POST['usernamelama'],
                    $_POST['usernamebaru']
                );  
    }
    if(isset($_POST['submit_setdonate']))
    {
            $ucp_user->SetDonate(
                    $_POST['usernamedonate'],
                    $_POST['totalgold']
                );  
    }
    define ('ENVIRONMENT', 'development');
?>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="assets2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets2/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="vendor/jquery/jquery.min.js"></script>
    <link href="css/themes/default.css" rel="stylesheet" type="text/css">
    <link href="css/alertify.css" rel="stylesheet" type="text/css">
    <script src="js/alertify.js" type="text/javascript"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                   <i class="fab fa-accusoft"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Dashboard</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
            <?php
                if($ucp_user->IsAdmin($_SESSION['username']))
                {                       
            ?>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Admin Panel</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Admin:</h6>
                        <a class="collapse-item" href="?user=admin">Panel Admin</a>
                        <a class="collapse-item" href="?user=adminhigh">Set Donate</a>
                    </div>
                </div>
            <?php
            }
            ?>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fas fa-user"></i>
                    <span>User</span></a>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>

             

            <!-- Divider -->
            <!--<hr class="sidebar-divider">-->

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">1</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="assets/images/lostjava.png"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi <?php echo $ucp_user->Getintuser($_SESSION['username'], 'username');?>! Thanks for register in Lost Java Indonesia. Congratulations and playing the Roleplay.</div>
                                        <div class="small text-gray-500">Lost Java Indonesia · 1m</div>
                                    </div>                                    
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="?user=message">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><p><?php echo $ucp_user->Getintuser($_SESSION['username'], 'username');?></p></span>
                                <img class="img-profile rounded-circle"
                                    src="<?php echo ('http://ucp.lostjavaindonesia.com/assets/images/profile/') . $ucp_user->Getintuser($_SESSION['username'], 'avatar'); ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="?user=profile">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="?user=activity">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                <?php 
                    if(empty($_GET['user']))
                    {
                ?>
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    <!-- Content Row -->
                    <div class="card mb-3" style="max-width: 540px;">
                      <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?php echo ('http://'.$_SERVER['SERVER_NAME'].'/assets/images/skin/') . $ucp_user->Getintuser($_SESSION['username'], 'skin'); ?>" class="img-fluid rounded-start">
                        </div>
                        <div class="col-md-8">
                          <div class="card-body">
                            <h5 class="card-title"><?php echo $ucp_user->Getintuser($_SESSION['username'], 'username');?></h5>
                            <p class="card-text"><?php echo $ucp_user->Getintuser($_SESSION['username'], 'email');?></p>
                            <p class="card-text"><small class="text-muted"><?php echo $ucp_user->Getintuser($_SESSION['username'], 'reg_id');?>th Players</small></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                    }
                    else if($_GET['user'] == 'admin')
                    {
                        if(!$ucp_user->IsAdmin($_SESSION['username']))
                        {
                            header('location: index');
                            exit();
                        }
                    ?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Admin Panel</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="card-body">

                        <!-- Earnings (Monthly) Card Example -->
                        <form method="POST" class="user">
                             <label for="username" class="col-sm-2 col-form-label">Username Lama</label>
                                <div class="form-group">
                                    <input type="text" class="form-control"
                                        id="usernamelama" name="usernamelama">
                                </div>
                                <label for="username" class="col-sm-2 col-form-label">Username Baru</label>
                                <div class="form-group">
                                    <input type="text" name="usernamebaru" class="form-control"
                                        id="usernamebaru">
                                </div>
                                <button type="submit" name="submit_changenicname" id="tombolregister" class="btn btn-primary btn-user btn-block">Submit</button>
                        </form>
                    </div>
                    <?php
                    }
                    else if($_GET['user'] == 'adminhigh')
                    {
                    ?>
                    <div class="card-body">
                        <!-- Earnings (Monthly) Card Example -->
                        <form method="POST">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                                <div class="form-group">
                                    <input type="text" class="form-control"
                                        id="usernamedonate" name="usernamedonate" 
                                        aria-describedby="emailHelp">
                                </div>
                                <label for="username" class="col-sm-2 col-form-label">Total Gold</label>
                                <div class="form-group">
                                    <input type="text" name="totalgold" class="form-control"
                                        id="totalgold">
                                </div>
                                <button type="submit" name="submit_setdonate" id="tombolregister" class="btn btn-primary btn-user btn-block">Submit</button>
                        </form>
                    </div>
                    <?php
                    }
                    else if($_GET['user'] == 'profile')
                    {
                    ?>
                    <h1 class="h3 mb-0 text-gray-800">My Profile</h1>
                    <div class="card-body">
                            <img src="<?php echo ('http://'.$_SERVER['SERVER_NAME'].'/assets/images/skin/') . $ucp_user->Getintuser($_SESSION['username'], 'skin'); ?>" class="rounded mx-auto d-block">
                        <!-- Earnings (Monthly) Card Example -->
                        <form method="POST">
                            <label for="username" class="form-label">Username</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="username" name="username" value='<?php echo $ucp_user->Getintuser($_SESSION['username'], 'username');?>' readonly></input>
                                <div id="passwordHelpBlock" class="form-text">
                                    Untuk pergantian username, Anda bisa menghubungi Senior Admin untuk menggantinya
                                </div>
                            </div>
                            <label for="username" class="form-label">Email</label>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="email" name="email" value='<?php echo $ucp_user->Getintuser($_SESSION['username'], 'email');?>' readonly></input>
                            </div>
                            <label for="username" class="form-label">Password lama</label>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="tpassword" id="tpassword" autosave="tpassword">
                            </div>
                            <label for="username" class="form-label">Password baru</label>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="npassword"id="npassword" autosave="npassword">
                            </div>
                            <label for="username" class="form-label">Konfirmasi password</label>
                            <div class="mb-3">
                                <input type="password" name="ppassword" class="form-control form-control-user"
                                        id="ppassword" autosave="ppassword">
                            </div>
                            <button type="submit" name="settings_submit" id="tombolregister" class="btn btn-primary btn-user btn-block">Submit</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th><center>Discord</center></th>
                                        <th><center>Facebook</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="<?php $auth_url?>" class="btn btn-facebook btn-user btn-block">
                                        <i class="fab fa-discord"></i> Discord</a>
                                    </td>
                                        <td><a href="index.html" class="btn btn-facebook btn-user btn-block">
                                        <i class="fab fa-facebook"></i> Facebook</a>
                                    </tr>
                                </tbody>
                           </table>
                        </div>
                    </div>
                <?php
                }
                    else if($_GET['user'] == 'activity')
                {
                ?>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                               <tr>
                                    <th><b>Date</b></th>
                                    <th><b>Adress</b></th> 
                                    <th><b>Activity</b></th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($ucp_user->LastLoginLog($_SESSION['username']) as $log) { ?>
                                <tr>
                                    <td><?php echo $log['date'];?></td>
                                    <td><?php echo $log['ip'];?></td> 
                                    <td><?php echo $log['reason'];?></td> 
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
                }
                ?>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Lost Java Indonesia - 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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

    <!-- Page level plugins -->
    <script src="assets2/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets2/js/demo/chart-area-demo.js"></script>
    <script src="assets2/js/demo/chart-pie-demo.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
</body>