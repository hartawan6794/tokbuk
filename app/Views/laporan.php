<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<?php $session = session();
$error = $session->get('error');
?>
<section class="content">
    <div class="card">
        <div class="card-header with-border">
            <div class="row">
                <div class="col-9 mt-2">
                    <h3 class="card-title">Data Rekening</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= base_url('report/cetak') ?>" method="post" target="_blank">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="sm-3 control-label">Date</label>
                                <div class="sm-9">
                                    <input type="date" name="date1" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="sm-3 control-label">s/d</label>
                                <div class="sm-9">
                                    <input type="date" name="date2" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (session()->get('username') == 'admin') : ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="toko">Pilih Toko</label>
                                <select class="form-control" name="toko" id="toko">
                                    <option value="">--Pilih Toko--</option>
                                    <?php foreach ($toko as $value) : ?>
                                        <option value="<?= $value->id_toko ?>"><?= $value->nm_toko ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="reset" name="reset" class="btn btn-flat form-control">Reset</button>
                            <button type="submit" name="cetak" class="btn btn-info btn-flat form-control">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>

</section>

<?= $this->endSection() ?>