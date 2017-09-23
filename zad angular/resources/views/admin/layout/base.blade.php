
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>zad global school</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<base href="/">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="{{ asset('admin_asset/bootstrap/css/bootstrap.min.css')}}">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('admin_asset/dist/css/AdminLTE.min.css')}}">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
			 folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="{{ asset('admin_asset/dist/css/skins/_all-skins.min.css')}}">
	@stack('links')
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="hold-transition skin-red sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
				<header class="main-header">
						<!-- Logo -->
						<a href="javascript:;" class="logo">
								<!-- mini logo for sidebar mini 50x50 pixels -->
								<span class="logo-mini"><b>ZAD</b></span>
								<!-- logo for regular state and mobile devices -->
								<span class="logo-lg">zad global school</span>
						</a>
						<!-- Header Navbar: style can be found in header.less -->
						<nav class="navbar navbar-static-top">
								<!-- Sidebar toggle button-->
								<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
								</a>
								<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
						<!-- User Account: style can be found in dropdown.less -->
						<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-user"></i>
							<span class="hidden-xs">{{ Auth::guard('admin')->user()->name }}</span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
								<i class="fa fa-user fa-5x"></i>
								<p>
									{{ Auth::guard('admin')->user()->name }}
								</p>
							</li>
						 
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="#" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="{{ route('admin.logout.get') }}" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
					<!-- Control Sidebar Toggle Button -->
					
				</ul>
			</div>
						</nav>
				</header>

				<!-- =============================================== -->

				<!-- Left side column. contains the sidebar -->
				<aside class="main-sidebar">
						<!-- sidebar: style can be found in sidebar.less -->
						<section class="sidebar">
								<!-- sidebar menu: : style can be found in sidebar.less -->
							<ng-router></ng-router>
								
						</section>
						<!-- /.sidebar -->
				</aside>
				
				<!-- =============================================== -->

				<!-- Content Wrapper. Contains page content -->
				<div class="content-wrapper">
					<section class="content">
						<ng-app></ng-app>
					</section>          
						
				</div>
				<!-- /.content-wrapper -->

				<footer class="main-footer">
						<div class="pull-right hidden-xs">
					 
						</div>
						<strong>Copyright &copy; 2017-2018 <a href="https://www.innovusine.com"></a>.</strong> All rights reserved.
				</footer>

				<!-- Add the sidebar's background. This div must be placed
				immediately after the control sidebar -->
				<div class="control-sidebar-bg"></div>
		</div>
		<!-- ./wrapper -->

		<!-- jQuery 2.2.3 -->
		<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
		<script src="{{ asset('admin_asset/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="{{ asset('admin_asset/bootstrap/js/bootstrap.min.js') }}"></script>
		<!-- SlimScroll -->
		<script src="{{ asset('admin_asset/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
		<!-- FastClick -->
		<script src="{{ asset('admin_asset/plugins/fastclick/fastclick.js') }}"></script>
		<!-- AdminLTE App -->
		<script src="{{ asset('admin_asset/dist/js/app.min.js') }}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{{ asset('admin_asset/dist/js/demo.js') }}"></script>

		@stack('scripts')
</body>
</html>
