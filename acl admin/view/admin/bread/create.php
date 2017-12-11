<!DOCTYPE html>
<html class=" ">
    	<head>
        
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Ultra Admin : Default Layout</title>
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
                                <h1 class="title">Create Bread</h1>                            </div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="index.html"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                   
                                    <li class="active">
                                        <strong>Create Bread</strong>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                         <form action="<?=url('admin/bread/store/'.$table)?>" method="POST" role="form">
                          <legend>Edit your Detail </legend>
                      
                          <div class="form-group">
                              <label for="">Name</label>
                              <input type="text"  name="name" class="form-control" id="" placeholder="Input Name">
                          </div>

                          <div class="form-group">
                              <label for="">Slug</label>
                              <input type="text" name="slug" class="form-control" id="" placeholder="Input Slug">
                          </div>

                           <div class="form-group">
                              <label for="">Icon</label>
                              <input type="text" name="icon"  class="form-control" id="" placeholder="Input Icon">
                          </div>

                          <div class="form-group">
                              <label for="">Controller Name</label>
                              <input type="text" name="controller" class="form-control" id="" placeholder="Input Controller Name">
                          </div>

                          
                      
                          <button type="submit" class="btn btn-primary">Submit</button>
                      </form>
                    </div>


                </section>
            </section>
            <!-- END CONTENT -->
            


              </div>
              <?php load('admin.include.scripts') ?>

    </body>
</html>