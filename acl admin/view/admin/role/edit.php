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
                                <h1 class="title">Add Role</h1>                            </div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="#"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                   
                                    <li class="active">
                                        <strong>Add Role</strong>
                                    </li>
                                </ol>
                               
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                      <form action="<?=url('admin/role/'.$role->id)?>" method="post" role="form">
                          <?= method_field('PUT') ?>
                        <input type="hidden" name="_method" value="PUT">
                          <!-- <legend>Edit your Detail </legend> -->
                      
                          <div class="form-group">
                              <label for="">Name</label>
                              <input type="text" value="" name="name" class="form-control" id="" placeholder="Input field">
                          </div>

                            <label for="permission"></label><br>
                            <button class="permission-select-all btn btn-success btn-xs "><i class="fa fa-check"></i></button>
                            <button class="permission-deselect-all btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
                            <ul class="permissions checkbox">
                               
                                <?php foreach($permissions as $table => $permission): ?>
                                    <li>
                                        <input type="checkbox" id="" class="permission-group">
                                        <label for="{{$table}}"><strong><?= title_case(str_replace('_',' ', $table)) ?></strong></label>
                                        <ul>
                                            <?php foreach($permission as $perm): ?>
                                                <li>
                                                    <input type="checkbox" id="permission-<?= $perm->id ?>" name="permissions[]" class="the-permission" <?= (in_array($perm->id, array_flatten($role->permissions->toArray())))? 'checked' :'' ?> value="<?= $perm->id ?>" >
                                                    <label for="permission-<?= $perm->id ?>"><?= title_case(str_replace('_',' ', $perm->name)) ?></label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                          
                      
                          <button type="submit" class="btn btn-primary">Submit</button>
                      </form>

                    </div>


                </section>
            </section>
            <!-- END CONTENT -->
            


              </div>
              <?php load('admin.include.scripts') ?>
              <script>
        $('document').ready(function () {
            $('.toggleswitch').toggle();

            $('.permission-group').on('change', function(){
                $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
            });

            $('.permission-select-all').on('click', function(){
                $('ul.permissions').find("input[type='checkbox']").prop('checked', true);
                return false;
            });

            $('.permission-deselect-all').on('click', function(){
                $('ul.permissions').find("input[type='checkbox']").prop('checked', false);
                return false;
            });

            function parentChecked(){
                $('.permission-group').each(function(){
                    var allChecked = true;
                    $(this).siblings('ul').find("input[type='checkbox']").each(function(){
                        if(!this.checked) allChecked = false;
                    });
                    $(this).prop('checked', allChecked);
                });
            }

            parentChecked();

            $('.the-permission').on('change', function(){
                parentChecked();
            });
        });
    </script>

    </body>
</html>