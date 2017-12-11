

<!-- SIDEBAR - START -->
            <div class="page-sidebar ">

                <!-- MAIN MENU - START -->
                <div class="page-sidebar-wrapper" id="main-menu-wrapper"> 

                    <!-- USER INFO - START -->
                    <div class="profile-info row">

                        <div class="profile-image col-md-4 col-sm-4 col-xs-4">
                            <a href="#">
                                <img src="data/profile/profile.png" class="img-responsive img-circle">
                            </a>
                        </div>

                        <div class="profile-details col-md-8 col-sm-8 col-xs-8">

                            <h3>
                                <a href="#">name</a>

                                <!-- Available statuses: online, idle, busy, away and offline -->
                                <span class="profile-status online"></span>
                            </h3>

                            <p class="profile-title"></p>

                        </div>

                    </div>
                    <!-- USER INFO - END -->



                    <ul class='wraplist'>	


                        <li class=""> 
                            <a href="<?= route('dashboard.index') ?>">
                                <i class="fa fa-dashboard"></i>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>
                        <?php //\App\Model\Permission::where('name','read_'.$page)->whereHas('permission',function($query){ $query->where('role_id',\Core\Auth::guard('admin')->user()->role_id);})->count() ?>
                        <?php  foreach (App\Model\Menu::whereHas('permission',function($query){
                            $query->whereHas('permission',function($query){ 
                                $query->where('role_id',\Core\Auth::guard('admin')->user()->role_id);
                            });
                             $query->where('name','like','read%');
                            })->get() as $menu) { ?>
                        <li class=""> 
                            <a href="<?= url('admin/'.str_slug($menu->slug)) ?>">
                                <i class="<?= $menu->icon ?>"></i>
                                <span class="title"><?= $menu->name ?></span>
                            </a>
                           
                        </li>
                        <?php  } ?>

                    </ul>

                </div>
                <!-- MAIN MENU - END -->




            </div>