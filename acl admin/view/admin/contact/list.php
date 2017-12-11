

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
                                <h1 class="title">Contact List</h1>                            </div>

                            <div class="pull-right hidden-xs">
                                <ol class="breadcrumb">
                                    <li>
                                        <a href="#"><i class="fa fa-home"></i>Home</a>
                                    </li>
                                   
                                    <li class="active">
                                        <strong>Contact List</strong>
                                    </li>
                                </ol>
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">

                        <table class="table datatable">
                           <thead><tr><th>id</th><th>Name</th><th>Email</th><th>Mobile</th><th>Action</th></tr></thead>
                       </table>        
                    </div>


                </section>
            </section>
            <!-- END CONTENT -->
            


            </div>
            <?php load('admin.include.scripts') ?>
            <script type="text/javascript">
                   var table = $('.datatable').DataTable({
                        "processing": true,
                        "serverSide": true,
                        'ajax' : $.fn.dataTable.pipeline({
                            'url': '<?= route('contact.index') ?>',
                            'pages':1,
           
                        }),
                        "columns": [
                            { "name": "id" },
                            { "name": "name" },
                            { "name": "email" },
                            { "name": "mobile" },
                            { 
                                'name': "action",  
                                render: function ( data, type, row ) {
                                    if ( type === 'display' ) {
                                        return '<?php if(can('delete')):?><botton type="button" onclick="deleteData('+row[0]+')" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button><?php endif;?>';
                                    }
                                    return data;
                                },
                            },
                        ],
       
                    }); 
                    function deleteData(id){
                        $.ajax({
                            url:'<?= route('contact.destroy','') ?>/'+id,
                            method: 'post',
                            data:{'id': id,'_method':'DELETE'},
                            dataType:'json',
                            success:function(response){
                                table.draw();
                                Command: toastr[response.status](response.message);
                            }
                        });
                    }  
            </script>

    </body>
</html>
<?php  ?>