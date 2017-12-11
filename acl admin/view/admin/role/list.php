

<!DOCTYPE html>
<html class=" ">
    	<head>
        
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
        <meta charset="utf-8" />
        <title>Admin : Role</title>
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
                                <h1 class="title">Role List</h1>                            </div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="#"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                   
                                    <li class="active">
                                        <strong>Role List</strong>
                                    </li>
                                </ol>
                               <a href="<?=url('admin/role/create')?>" class="btn btn-primary btn-xs">Add</a>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                        <table class="table">
                           <tr><th>id</th><th>Name</th><th>Action</th></tr>
                            <?php foreach ($roles as $data): ?>
                                <tr>
                                    <td><?= $data->id ?></td>
                                    <td><?= $data->name ?></td>
                                    <td>
                                        
                                        <a href="<?=url('admin/role/'.$data->id)?>" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>
                                        <a href="<?=url('admin/role/'.$data->id.'/edit')?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a href="<?=url('admin/role/'.$data->id)?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                            </tr>
                            <?php endforeach ?>
                       </table>        
                    </div>


                </section>
            </section>
            <!-- END CONTENT -->
            


              </div>
              <?php load('admin.include.scripts') ?>

    </body>
</html>