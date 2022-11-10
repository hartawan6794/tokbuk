<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-9 mt-2">
        <h3 class="card-title">Userbiodata</h3>
      </div>
      <div class="col-3">
        <button type="button" class="btn float-sm-end btn-success" onclick="save()" title="Tambah Pengguna"> <i class="fa fa-plus"></i> Tambah Pengguna</button>
      </div>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="data_table" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>NIK user</th>
          <th>Nama user</th>
          <th>Email user</th>
          <!-- <th>Tempat, Tanggal lahir</th> -->
          <th>Telpon</th>
          <th style="width :10%">Alamat</th>
          <th></th>
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
            <input type="hidden" id="id_user_bio" name="id_user_bio" class="form-control" placeholder="Id user bio" maxlength="6" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nik_user" class="col-form-label">Masukan NIK : <span class="text-danger">*</span> </label>
                <input type="text" id="nik_user" name="nik_user" class="form-control" placeholder="Nik user">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="email_user" class="col-form-label">Masukan Email: <span class="text-danger">*</span> </label>
                <input type="texts" id="email_user" name="email_user" class="form-control" placeholder="Email user">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_user" class="col-form-label">Masaukan Nama: <span class="text-danger">*</span> </label>
                <input type="text" id="nm_user" name="nm_user" class="form-control" placeholder="Nm user">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_user" class="col-form-label"> Jenis Kelamin: </label>
                <select class="form-control" name="gender" id="gender">
                  <option value="">-- Pilih Jenis Kelamin -- </option>
                  <option value="1">Laki-laki</option>
                  <option value="2">perempuan</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="tanggal_lahir" class="col-form-label">Masukan Tanggal lahir: </label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" dateISO="true">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="tempat_lahir" class="col-form-label">Masukan Tempat lahir: </label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" placeholder="Tempat lahir" minlength="0" maxlength="150">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="telpon" class="col-form-label">Masukan Nomor Telpon: </label>
                <input type="text" id="telpon" name="telpon" class="form-control" placeholder="Telpon" minlength="0" maxlength="15">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="alamat" class="col-form-label">Masukan Alamat: </label>
                <input type="text" id="alamat" name="alamat" class="form-control" placeholder="Alamat" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group viewImage">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="imguser" class="col-form-label">Unggah Foto : <span class="text-danger" style="font-size: 10px">*Kosongkan tidak apa</span> </label>
                <input type="file" id="imguser" name="imguser" class="form-control" placeholder="Imguser">
              </div>
            </div>
            <div class="col-md-12" id="roleForm">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="username" class="col-form-label">> Masukan Username :</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukan Username">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="role" class="col-form-label"> Daftar Sebagai: </label>
                    <select class="form-control" name="role" id="role">
                      <option value="">-- Pilih Jenis Akun -- </option>
                      <option value="1">Penjual</option>
                      <option value="2">Pembeli</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12" id="passForm">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="pass" class="col-form-label"> Password: </label>
                    <input type="password" id="pass" name="pass" class="form-control" placeholder="Masukan password">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group mb-3">
                    <label for="confpass" class="col-form-label"> Konfirmasi Password: </label>
                    <input type="password" id="confpass" name="confpass" class="form-control" placeholder="Konfirmasi Password">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn">Tambah Data</button>
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

  function save(id_user_bio) {
    // reset the form 
    $("#data-form")[0].reset();
    $('#passForm').show()
    $('#roleForm').show()
    $("#data-form #email_user").prop('readonly',false)
    $("#data-form #nik_user").prop('readonly',false)
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_user_bio === 'undefined' || id_user_bio < 1) { //add
      urlController = '<?= base_url($controller . "/add") ?>';
      submitText = 'Tambah Data';
      $('#model-header').removeClass('bg-info').addClass('bg-success');
      $("#info-header-modalLabel").text("Tambah Data Pengguna");
      $("#form-btn").text("Tambah Data");
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = 'Ubah Data';
      $('#passForm').hide()
      $('#roleForm').hide()
      $("#data-form .viewImage").html('')
      $.ajax({
        url: '<?php echo base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_user_bio: id_user_bio
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('Ubah Data Pengguna');
          $("#form-btn").text("Ubah Data");
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_user_bio").val(response.id_user_bio);
          $("#data-form #nik_user").prop('readonly', true);
          $("#data-form #nik_user").val(response.nik_user);
          $("#data-form #nm_user").val(response.nm_user);
          $("#data-form #email_user").prop('readonly',true)
          $("#data-form #email_user").val(response.email_user);
          $("#data-form #gender").val(response.gender);
          $("#data-form #tanggal_lahir").val(response.tanggal_lahir);
          $("#data-form #tempat_lahir").val(response.tempat_lahir);
          $("#data-form #telpon").val(response.telpon);
          $("#data-form #alamat").val(response.alamat);
          $("#data-form .viewImage").append(
            (response.imguser ?
              `<img style="width: 50%;" src='/img/user/${response.imguser}'>` :
              `<span class="text-danger">Belum Unggah Photo</span>`));

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
        // var formData = new FormData(document.getElementById("data-form"))
        // form.append('File', $('#imguser')[0].files[0])
        // var imguser = new FormData($("#imguser")[0]);
        // var fileUpload = $("#imguser").get(0).files;
        // var files = fileUpload.name;
        $(".text-danger").remove();
        $.ajax({
          // fixBug get url from global function only
          // get global variable is bug!
          url: getUrl(),
          type: 'post',
          data: new FormData(form), //form.serialize(), //formData,
          processData: false,
          contentType: false,
          cache: false,
          dataType: 'json',
          beforeSend: function() {
            $('#form-btn').html('<i class="fa fa-spinner fa-spin"></i>');
            // console.log(form.serialize())
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

  function remove(id_user_bio) {
    Swal.fire({
      title: "Hapus Data",
      text: "Yakin Ingin Menghapus Data ?",
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
            id_user_bio: id_user_bio
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

  function view(id_user_bio) {
    $.ajax({
      url: '<?php echo base_url($controller . "/getOne") ?>',
      type: 'post',
      data: {
        id_user_bio: id_user_bio
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
                        <th>NIK</th>
                        <td>${x.nik_user}</td>
                    </tr>
                    <tr>
                        <th>Nama Pengguna</th>
                        <td>${x.nm_user}</td>
                    </tr>
                    <tr>
                        <th>Email Pengguna</th>
                        <td>${x.email_user}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>` +
          (x.gender == "1" ? "Laki-laki" : "Perempaun") +
          `</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>${x.tempat_lahir}, ` + tgl_indo(x.tanggal_lahir) + `</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>${x.alamat}</td>
                    </tr>
                    <tr>
                        <th>No. Telpon</th>
                        <td>${x.telpon}</td>
                    </tr>
                    <tr>
                        <th>Gambar Pengguna</th>
                        <td style="width:100%'">` +
          (x.imguser ?
            `<img style="width: 100%;" src='/img/user/${x.imguser}'>` :
            "Belum Upload Photo") +
          `</td>
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
</script>


<?= $this->endSection() ?>