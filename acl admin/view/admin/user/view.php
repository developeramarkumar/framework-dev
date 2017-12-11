<!DOCTYPE html>
<html class=" ">
    	<head>
        
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Admin : Contact</title>
        <?php load('admin.include.links') ?>

    </head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
    <body class=" "><!-- START TOPBAR -->
        <?php load('admin.include.topmenu') ?>
        <!-- END TOPBAR -->
        <!-- START CONTAINER -->
        <div class="page-container row-fluid">

            
            <?php load('admin.include.leftmenu') ?>
            <!-- START CONTENT -->
            <section id="main-content" class=" ">
                <section class="wrapper main-wrapper" style=''>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title">User List</h1>                            </div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="#"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                   
                                    <li class="active">
                                        <strong>User List</strong>
                                    </li>
                                </ol>
                               
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                   <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-12">
                                        <div class="uprofile-image">
                                            <img src="data/students/student-1.jpg" class="img-responsive">
                                        </div>
                                        <div class="uprofile-name">
                                            <h3>
                                                <a href="#"><?= ucfirst(@$user->fname) .' '. ucfirst(@$user->lname);?></a>
                                                <!-- Available statuses: online, idle, busy, away and offline -->
                                                <span class="uprofile-status online"></span>
                                            </h3>
                                            <p class="uprofile-title"><?= $user->mobile;?></p>
                                        </div>
                                        <div class="uprofile-info">
                                            <ul class="list-unstyled">
                                               <?= @$user->address;?>
                                            </ul>
                                        </div>
                                       

                                

                                    </div>
                                    <div class="col-md-9 col-sm-8 col-xs-12">

                                        <div class="uprofile-content">

                                            <div class="">
                                                <h4>Biography:</h4>
                                                <p><strong>Name:</strong> <?= ucfirst($user->fname).' '. ucfirst(@$user->lname);?></p>
                                                <p><strong>Email:</strong> <?= ucfirst($user->email) ;?></p>
                                                <p><strong>Mobile No:</strong> <?= ucfirst($user->mobile) ;?></p>
                                                <p><strong>Address:</strong> <?= ucfirst($user->address) ;?></p>
                                               
                                            

                                            </div>                

                                        </div>



                                    </div>
                                </div>
                            </div>
                        </section></div>


                </section>
            </section>
            <!-- END CONTENT -->
            


              </div>
              <?php load('admin.include.scripts') ?>

    </body>
</html>