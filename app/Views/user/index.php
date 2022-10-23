<?= $this->extend('layout/page_layout') ?>

<?= $this->section('content') ?>

<h1 class="h3 mb-2 text-gray-800">Daftar Pengguna</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Email</th>
                        <th>Level User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <tr>
                    <td>1</td>
                    <td>1808040607940004</td>
                    <td>semua@gmail.com</td>
                    <td>Admin</td>
                    <td><a href="#" class="btn btn-danger" onclick="return confirm('Yakin akan mengapus data ?')" title="Hapus"><i class="fas fa-trash"></i></a> <a href="#" class="btn btn-success" title="Ubah Kata Sandi"><i class="fas fa-key"></i></a></td>
                </tr>
                
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>