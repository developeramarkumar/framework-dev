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
                        <table class="table">
                           <tr><th>id</th><th>Name</th><th>Email</th><th>Mobile</th><th>Action</th></tr>
                            <?php
                                $i=1;
                             foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td><?= ucfirst($user->fname) . $user->lname ?></td>
                                    <td><?= $user->email ?></td>
                                    <td><?= $user->mobile ?></td>
                                    <td>
                                        <a href="<?=url('admin/user/'.$user->id)?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>

                                        <a href="<?=url('admin/user/'.$user->id.'/edit')?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a href="<?=url('admin/user/delete/'.$user->id)?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                            </tr>
                            <?php $i++; endforeach ?>
                       </table>        
                    </div>


                </section>
            </section>
            <!-- END CONTENT -->
            


              </div>
              <?php load('admin.include.scripts') ?>

    </body>
</html>