<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-10 mt-2">
        <h3 class="card-title">Product</h3>
      </div>
      <div class="col-2">
        <button type="button" class="btn float-right btn-success" onclick="save()" title="Tambah Produk"> <i class="fa fa-plus"></i> Tambah Produk</button>
      </div>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="data_table" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Nomor</th>
          <th>Judul buku</th>
          <th>Nama penerbit</th>
          <th>Nama penulis</th>
          <th>Tahun</th>
          <th>Harga buku</th>

          <th>Aksi</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->

<!-- /Main content -->

<!-- ADD modal content -->
<div id="data-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="text-center bg-info p-3" id="model-header">
        <h4 class="modal-title text-white" id="info-header-modalLabel"></h4>
      </div>
      <div class="modal-body">
        <form id="data-form" class="pl-3 pr-3" method="post" enctype="multipart/form-data">
          <div class="row">
            <input type="hidden" id="id_product" name="id_product" class="form-control" placeholder="Id product" maxlength="6" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_toko" class="col-form-label"> Nama Toko: </label>
                <select class="form-control" name="id_toko" id="id_toko">
                  <?php session()->get('username') == 'admin' ? '<option value="">-- Pilih Toko -- </option>' : '' ?>
                  <?php foreach ($toko as $data) : ?>
                    <option value="<?= $data->id_toko ?>"><?= $data->nm_toko ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="judul_buku" class="col-form-label"> Judul buku: <span class="text-danger">*</span> </label>
                <input type="text" id="judul_buku" name="judul_buku" class="form-control" placeholder="Judul buku" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_penerbit" class="col-form-label"> Nama penerbit: <span class="text-danger">*</span> </label>
                <input type="text" id="nm_penerbit" name="nm_penerbit" class="form-control" placeholder="Nama penerbit" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_penulis" class="col-form-label"> Nama penulis: </label>
                <input type="text" id="nm_penulis" name="nm_penulis" class="form-control" placeholder="Nama penulis" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="tahun_terbit" class="col-form-label"> Tahun: </label>
                <input type="text" id="tahun_terbit" name="tahun_terbit" class="form-control" placeholder="Tahun" minlength="0" maxlength="4">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="jml_halaman" class="col-form-label"> Jumlah halaman: </label>
                <input type="number" id="jml_halaman" name="jml_halaman" class="form-control" placeholder="Juml halaman" minlength="0" maxlength="6">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="deskripsi_buku" class="col-form-label"> Deskripsi buku: </label>
                <input type="text" id="deskripsi_buku" name="deskripsi_buku" class="form-control" placeholder="Deskripsi buku" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_kategori" class="col-form-label"> Kategori Buku: </label>
                <select class="form-control" name="id_kategori" id="id_kategori">
                  <option value="">-- Pilih Kategori Buku -- </option>
                  <?php foreach($kategori as $data) : ?>
                  <option value="<?= $data->id_kategori?>"><?= $data->nama_kategori?></option>
                  <?php endforeach;?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="stok" class="col-form-label"> Stok: </label>
                <input type="number" id="stok" name="stok" class="form-control" placeholder="Stok" minlength="0" maxlength="6">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="harga_buku" class="col-form-label"> Harga buku: </label>
                <input type="text" id="harga_buku" name="harga_buku" class="form-control" placeholder="Harga buku" minlength="0" maxlength="10">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group viewImage1">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="imgproduct1" class="col-form-label textimg"> Upload Produk 1: </label>
                <input type="file" id="imgproduct1" name="imgproduct1" class="form-control" placeholder="Imgproduct1" minlength="0" maxlength="255">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group viewImage2">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="imgproduct2" class="col-form-label textimg"> Upload Produk 2: </label>
                <input type="file" id="imgproduct2" name="imgproduct2" class="form-control" placeholder="Imgproduct2" minlength="0" maxlength="255">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group viewImage3">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="imgproduct3" class="col-form-label textimg"> Upload Produk 3: </label>
                <input type="file" id="imgproduct3" name="imgproduct3" class="form-control" placeholder="Imgproduct3" minlength="0" maxlength="255">
              </div>
            </div>
          </div>

          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn">Tambah</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- /ADD modal content -->

<!-- View Modal Content -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Data Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div id="view">
        </div>

      </div>
      <div class="modal-footer">
        <input type="hidden" id="id_user_biodata" name="id_user_biodata">
        <!-- <button type="button" class="btn btn-secondary btn-exit" data-dismiss="modal">Close</button> -->
        <!-- <button type="submit" class="btn btn-primary">Save</button> -->
      </div>
    </div>
  </div>
</div>




<?= $this->endSection() ?>
<!-- /.content -->


<!-- page script -->
<?= $this->section("pageScript") ?>
<script>
  // dataTables
  $(function() {
    var table = $('#data_table').removeAttr('width').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "scrollY": '45vh',
      "scrollX": true,
      "scrollCollapse": false,
      "responsive": false,
      "ajax": {
        "url": '<?php echo base_url($controller . "/getAll") ?>',
        "type": "POST",
        "dataType": "json",
        async: "true"
      }
    });
  });

  var urlController = '';
  var submitText = '';

  function getUrl() {
    return urlController;
  }

  function getSubmitText() {
    return submitText;
  }

  function save(id_product) {
    // reset the form 
    $("#data-form")[0].reset();
      $("#data-form .viewImage1").html('')
      $("#data-form .viewImage2").html('')
      $("#data-form .viewImage3").html('')
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_product === 'undefined' || id_product < 1) { //add
      urlController = '<?= base_url($controller . "/add") ?>';
      submitText = 'Tambah';
      $('#model-header').removeClass('bg-info').addClass('bg-success');
      $("#info-header-modalLabel").text('Tambah Produk');
      $("#form-btn").text(submitText);
      $('.textimg').text("Upload Gambar :")
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = 'Ubah';
      $.ajax({
        url: '<?php echo base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_product: id_product
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('Ubah Produk');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_product").val(response.id_product);
          $("#data-form #id_toko").val(response.id_toko);
          $("#data-form #judul_buku").val(response.judul_buku);
          $("#data-form #nm_penerbit").val(response.nm_penerbit);
          $("#data-form #nm_penulis").val(response.nm_penulis);
          $("#data-form #tahun_terbit").val(response.tahun_terbit);
          $("#data-form #jml_halaman").val(response.jml_halaman);
          $("#data-form #deskripsi_buku").val(response.deskripsi_buku);
          $("#data-form #id_kategori").val(response.id_kategori);
          $("#data-form #stok").val(response.stok);
          $("#data-form #harga_buku").val(response.harga_buku);
          $("#data-form .viewImage1").append(
            (response.imgproduct1 ?
              `<img style="width: 40%;" src='/img/product/${response.imgproduct1}'>` :
              `<span class="text-danger">Belum Unggah Photo</span>`));
          $("#data-form .viewImage2").append(
            (response.imgproduct2 ?
              `<img style="width: 40%;" src='/img/product/${response.imgproduct2}'>` :
              `<span class="text-danger">Belum Unggah Photo</span>`));
          $("#data-form .viewImage3").append(
            (response.imgproduct3 ?
              `<img style="width: 40%;" src='/img/product/${response.imgproduct3}'>` :
              `<span class="text-danger">Belum Unggah Photo</span>`));
          $('.textimg').text("Ubah Gambar :")

        }
      });
    }
    $.validator.setDefaults({
      highlight: function(element) {
        $(element).addClass('is-invalid').removeClass('is-valid');
      },
      unhighlight: function(element) {
        $(element).removeClass('is-invalid').addClass('is-valid');
      },
      errorElement: 'div ',
      errorClass: 'invalid-feedback',
      errorPlacement: function(error, element) {
        if (element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else if ($(element).is('.select')) {
          element.next().after(error);
        } else if (element.hasClass('select2')) {
          //error.insertAfter(element);
          error.insertAfter(element.next());
        } else if (element.hasClass('selectpicker')) {
          error.insertAfter(element.next());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function(form) {
        // var form = $('#data-form');
        $(".text-danger").remove();
        $.ajax({
          // fixBug get url from global function only
          // get global variable is bug!
          url: getUrl(),
          type: 'post',
          data: new FormData(form),
          processData: false,
          contentType: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
          },
          success: function(response) {
            if (response.success === true) {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_table').DataTable().ajax.reload(null, false).draw(false);
                $('#data-modal').modal('hide');
              })
            } else {
              if (response.messages instanceof Object) {
                $.each(response.messages, function(index, value) {
                  var ele = $("#" + index);
                  ele.closest('.form-control')
                    .removeClass('is-invalid')
                    .removeClass('is-valid')
                    .addClass(value.length > 0 ? 'is-invalid' : 'is-valid');
                  ele.after('<div class="invalid-feedback">' + response.messages[index] + '</div>');
                });
              } else {
                Swal.fire({
                  toast: false,
                  position: 'bottom-end',
                  icon: 'error',
                  title: response.messages,
                  showConfirmButton: false,
                  timer: 3000
                })

              }
            }
            $('#form-btn').html(getSubmitText());
          }
        });
        return false;
      }
    });

    $('#data-form').validate({

      //insert data-form to database

    });
  }



  function remove(id_product) {
    Swal.fire({
      title: "Hapus Produk",
      text: "Yakin ingin menghapus ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url($controller . "/remove") ?>',
          type: 'post',
          data: {
            id_product: id_product
          },
          dataType: 'json',
          success: function(response) {

            if (response.success === true) {
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: response.messages,
                showConfirmButton: false,
                timer: 1500
              }).then(function() {
                $('#data_table').DataTable().ajax.reload(null, false).draw(false);
              })
            } else {
              Swal.fire({
                toast: false,
                position: 'bottom-end',
                icon: 'error',
                title: response.messages,
                showConfirmButton: false,
                timer: 3000
              })
            }
          }
        });
      }
    })
  }

  $('.close').on('click', function() {
    $("#viewModal").modal("hide");
  })


  function view(id_product) {
    $.ajax({
      url: '<?php echo base_url($controller . "/getOne") ?>',
      type: 'post',
      data: {
        id_product: id_product
      },
      dataType: 'json',
      beforeSend: function(x) {
        // console.log(url)
        $("#view").html("Sedang Mengambil Data. . ");
      },
      success: function(x) {
        console.log(x)
        $(".modal-body #view").html("");
        $(".modal-body #view").append(
          `
              <table id="dataTable" class="table table-bordered table-striped">
                    <tr>
                        <th>Nama Toko</th>
                        <td>${x.nm_toko}</td>
                    </tr>
                    <tr>
                        <th>Judul Buku</th>
                        <td>${x.judul_buku}</td>
                    </tr>
                    <tr>
                        <th>Nama Penerbit</th>
                        <td>${x.nm_penerbit}</td>
                    </tr>
                    <tr>
                        <th>Nama Penulis</th>
                        <td>${x.nm_penulis}</td>
                    </tr>
                    <tr>
                        <th>Tahun Terbit</th>
                        <td>${x.tahun_terbit}</td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>${x.nama_kategori}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Halaman</th>
                        <td>${x.jml_halaman} Hal.</td>
                    </tr>
                    <tr>
                        <th>Deskripsi Buku</th>
                        <td>${x.deskripsi_buku}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi Buku</th>
                        <td>` + convertToRupiah(x.harga_buku) + `</td>
                    </tr>
                    <tr>
                        <th>Stok Buku</th>
                        <td>${x.stok}</td>
                    </tr>
                    <tr>
                        <th>Gambar Buku</th>
                        <td>` + (x.imgproduct1 ? `<img style="width: 30%;" src='/img/product/${x.imgproduct1}'>` : 'Belum Upload Gambar') + `</td>
                    </tr>
                    <tr>
                        <th>Gambar Buku</th>
                        <td>` + (x.imgproduct2 ? `<img style="width: 30%;" src='/img/product/${x.imgproduct2}'>` : 'Belum Upload Gambar') + `</td>
                    </tr>
                    <tr>
                        <th>Gambar Buku</th>
                        <td>` + (x.imgproduct2 ? `<img style="width: 30%;" src='/img/product/${x.imgproduct3}'>` : 'Belum Upload Gambar') + `</td>
                    </tr>
                </table>
              `
        );
      },
      error: function(x) {
        console.log(x);
      },
    });

    $("#viewModal").modal("show");
  }

  function tgl_indo(string) {
    bulanIndo = [
      "",
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ];

    tanggal = string.split("-")[2];
    bulan = string.split("-")[1];
    tahun = string.split("-")[0];

    return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
  }

  function convertToRupiah(angka) {
    var rupiah = '';
    var angkarev = angka.toString().split('').reverse().join('');
    for (var i = 0; i < angkarev.length; i++)
      if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
    return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
  }
</script>


<?= $this->endSection() ?>