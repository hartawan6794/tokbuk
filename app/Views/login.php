<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url() ?>/asset/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <!-- <link rel="stylesheet" href="<?= base_url() ?>/asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() ?>/asset/css/adminlte.min.css">

    <link rel="stylesheet" href="<?= base_url() ?>/asset/css/sweetalert2-dark.min.css">
    <script src="<?= base_url() ?>/asset/js/sweetalert2.min.js"></script>
</head>

<body class="hold-transition login-page">

    <?php
    $session = session();

    // $success = $session->get('success');
    $error = $session->get('error');

    if ($error) : ?>
        <div class="alert alert-danger alert-dismissible">
            <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
            <h5><i class="icon fas fa-ban"></i> Error</h5>
            <?= $error ?>
        </div>
    <?php endif; ?>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="<?= base_url() ?>" class="h1"><b>TOKBUK</b></a>
                <p>Sistem Informasi Toko Buku Bekas </p>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan Login</p>

                <form action="<?= base_url('login/prosses') ?>" method="post">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="form-control btn btn-primary btn-block">Masuk</button>
                    </div>
                    <!-- /.col -->
            </div>
            </form>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url() ?>/asset/js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url() ?>/asset/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url() ?>/asset/js/adminlte.min.js"></script>
</body>

</html>