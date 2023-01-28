<?= $this->extend('layout/master') ?>

<?= $this->section('content') ?>
<?php $session = session();
// var_dump($session->get('error'));
// helper('settings');
$error = $session->get('error');
// var_dump($error);die;
?>
<section class="content">
    <div class="card">
        <div class="card-header with-border">
            <div class="row">
                <div class="col-9 mt-2">
                    <h3 class="card-title">Data Rekening</h3>
                </div>
                <!-- <div class="col-3">
                    <button type="button" class="btn float-sm-end btn-success" onclick="save()" title="Cetak PDF"><i class="fa fa-print"></i> Cetak PDF</button>
                    <button type="button" class="btn float-sm btn-success" onclick="save()" title="Cetak PDF"><i class="fa fa-print"></i> Cetak PDF</button>
                </div> -->
            </div>
        </div>
        <div class="card-body">
            <form action="<?= base_url('report/cetak') ?>" method="post">
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
                    <?php if (session()->get('username') != 'admin') : ?>
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
                            <button type="submit" name="cetak" target="_blank" class="btn btn-info btn-flat form-control">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <!-- 
    <div class="card">
        <div class="card-header with-border">
            <h7 class="box-title">Filter Data</h7>
        </div>
        <div class="card-body">
            <table id="data_table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice</th>
                        <th>Nama Pelanggan</th>
                        <th>Ke Rekening</th>
                        <th>Total pembayaran</th>
                        <th>Bukti Pembayaran</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div> -->

</section>

<?= $this->endSection() ?>