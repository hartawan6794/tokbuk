<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-3 col-6">

        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $jml_order ?></h3>
                <p>Pesanan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="<?= base_url('order') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">

        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $jml_product ?></h3>
                <p>Produk</p>
            </div>
            <div class="icon">
                <i class="nav-icon fas fa-archive"></i>
            </div>
            <a href="<?= base_url('product') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <?php if (session()->get('username') == 'admin') : ?>

        <div class="col-lg-3 col-6">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?= $jml_user ?></h3>
                    <p>Pengguna</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="<?= base_url('userbiodata') ?>" class="small-box-footer">Selangkapnya <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-lg-3 col-6">

        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $jml_pengiriman ?></h3>
                <p>Pengiriman</p>
            </div>
            <div class="icon">
                <i class="nav-icon fa fa-truck"></i>
            </div>
            <a href="<?= base_url('pengiriman') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>