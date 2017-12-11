  <!-- END CONTAINER -->
        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->


        <!-- CORE JS FRAMEWORK - START --> 
        <script src="<?= assets('admin/js/jquery-1.11.2.min.js')?>" type="text/javascript"></script> 
        <script src="<?= assets('admin/js/jquery.easing.min.js')?>" type="text/javascript"></script> 
        <script src="<?= assets('admin/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script> 
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script type="text/javascript">
            <?php if(error('message')): ?>
                Command: toastr["<?= error('status') ?>"]("<?= error('message') ?>");
            <?php endif; ?>
        </script>
        <script src="<?= assets('admin/plugins/pace/pace.min.js')?>" type="text/javascript"></script>  
        <script src="<?= assets('admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js')?>" type="text/javascript"></script> 
        <script src="<?= assets('admin/plugins/viewport/viewportchecker.js')?>" type="text/javascript"></script>  
        <!-- CORE JS FRAMEWORK - END --> 
 <script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= assets('admin/js/datatable.init.js')?>"></script> 


        <!-- CORE TEMPLATE JS - START --> 
        <script src="<?= assets('admin/js/scripts.js')?>" type="text/javascript"></script> 
        <!-- END CORE TEMPLATE JS - END --> 

        <!-- Sidebar Graph - START --> 
        <script src="<?= assets('admin/plugins/sparkline-chart/jquery.sparkline.min.js')?>" type="text/javascript"></script>
        <script src="<?= assets('admin/js/chart-sparkline.js')?>" type="text/javascript"></script>
        <!-- Sidebar Graph - END --> 
     