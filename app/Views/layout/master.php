<!-- Haeder -->
<?= $this->include('layout/header') ?>
<!--/Haeder -->

  <body class="layout-fixed dark-mode">
  <!--<body class="layout-fixed dark-mode">-->
    <div class="wrapper">

        <!-- Preloader 
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="<?php /*= base_url('asset/img/AdminLTELogo.png') */?>" alt="AdminLTELogo" height="60" width="60">
        </div>-->

        <!-- Navbar -->
        <?= $this->include('layout/navbar') ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
         <?= $this->include('layout/sidebar') ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
			<div class="content-header">
			  <div class="container-fluid">
				<div class="row mb-2">
				  <div class="col-sm-6">
					<div class="fs-3"><?= esc($title) ?></div>
				  </div>
				  <div class="col-sm-6">
					<ol class="breadcrumb float-sm-end">
					  <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
					  <li class="breadcrumb-item active" aria-current="page"><?= esc($controller) ?></li>
					</ol>
				  </div>
				</div>
			  </div>
			</div>			
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Area for dynamic content <div class='card'> -->
                            <?= $this->renderSection("content") ?>
                            <!-- /Area for dynamic content -->
                        </div>
                        <!--/.col-12 -->
                    </div>
                    <!--/.row -->
                </div>
                <!--/.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
      <!-- Main Footer -->
	<footer class="main-footer  layout-footer-fixed">
	  <!-- To the end -->
	  <div class="float-end d-none d-sm-inline">
		Anything you want
	  </div>
	  <!-- Default to the start -->
	  <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
	</footer>
    <!-- /Footer -->

    </div>
    <!-- ./wrapper -->

    <!-- Global Script -->
    <?= $this->include('layout/globalscript') ?>
    <!--/Global Script -->

    <!-- PageScript-->
    <?= $this->renderSection("pageScript") ?>
    <!-- /PageScript-->

</body>

</html>