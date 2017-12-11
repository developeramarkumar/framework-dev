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
                      <section class="box">
                        <header class="panel_header">
                                <h2 class="title pull-left">Edit User</h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class="box_close fa fa-times"></i>
                                </div>
                            </header>
                            <div class="content-body">    
                                <div class="row">
                         <form action="<?=route('user.update',$user->id)?>" method="POST" role="form">
                        <?= method_field('PUT') ?>
                      
                          <div class="form-group">
                              <label for="">fname</label>
                              <input type="text" value="<?=$user->fname?>" name="fname" class="form-control" id="" placeholder="Input field">
                          </div>

                          <div class="form-group">
                              <label for="">lname</label>
                              <input type="text" name="lname" value="<?=$user->lname?>" class="form-control" id="" placeholder="Input field">
                          </div>

                           <div class="form-group">
                              <label for="">email</label>
                              <input type="text" name="email" value="<?=$user->email?>" class="form-control" id="" placeholder="Input field">
                          </div>

                          <div class="form-group">
                              <label for="">Mobile</label>
                              <input type="text" name="mobile" class="form-control" value="<?=$user->mobile?>" id="" placeholder="Input field">
                          </div>

                          
                      
                          <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                    </div>
                  </div>
                      </section>
                    </div>


                </section>
            </section>
            <!-- END CONTENT -->
            


              </div>
            
              <?php load('admin.include.scripts') ?>

    </body>
</html>