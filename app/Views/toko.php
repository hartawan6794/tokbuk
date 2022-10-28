<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-9 mt-2">
        <h3 class="card-title">Toko</h3>
      </div>
      <div class="col-3">
        <button type="button" class="btn float-sm-end btn-success" onclick="save()" title="Tambah Data Toko"> <i class="fa fa-plus"></i> Tambah Data Toko</button>
      </div>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="data_table" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Penjual</th>
          <th>Nama toko</th>
          <th>Alamat toko</th>
          <th>Telpon</th>
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
        <form id="data-form" class="pl-3 pr-3">
          <div class="row">
            <input type="hidden" id="id_toko" name="id_toko" class="form-control" placeholder="Id toko" maxlength="6" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_user" class="col-form-label"> Nama Pemilik: </label>
                <select class="form-control" name="nm_user" id="nm_user">
                  <?php session()->get('username') != 'admin' ? '' : '<option value="">-- Pilih Pengguna -- </option>' ?>
                  <?php foreach ($userbio as $data) : ?>
                    <option value="<?= $data->id_user_bio ?>"><?= $data->nm_user ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <!-- <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_user_bio" class="col-form-label"> Id user bio: <span class="text-danger">*</span> </label>
                <input type="number" id="id_user_bio" name="id_user_bio" class="form-control" placeholder="Id user bio" minlength="0" maxlength="6" required>
              </div>
            </div> -->
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_toko" class="col-form-label"> Nama toko: <span class="text-danger">*</span> </label>
                <input type="text" id="nm_toko" name="nm_toko" class="form-control" placeholder="Nama toko" minlength="0" maxlength="20" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="alamat_toko" class="col-form-label"> Alamat toko: <span class="text-danger">*</span> </label>
                <input type="text" id="alamat_toko" name="alamat_toko" class="form-control" placeholder="Alamat toko" minlength="0" maxlength="255" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="telpon" class="col-form-label"> Telpon: </label>
                <input type="text" id="telpon" name="telpon" class="form-control" placeholder="Telpon" minlength="0" maxlength="15">
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

  function save(id_toko) {
    // reset the form 
    $("#data-form")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_toko === 'undefined' || id_toko < 1) { //add
      urlController = '<?= base_url($controller . "/add") ?>';
      submitText = 'Tambah';
      $('#model-header').removeClass('bg-info').addClass('bg-success');
      $("#info-header-modalLabel").text('Tambah Data');
      $("#form-btn").text(submitText);
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = 'Ubah';
      $.ajax({
        url: '<?php echo base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_toko: id_toko
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('Ubah Data Toko');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_toko").val(response.id_toko);
          $("#data-form #nm_user").val(response.id_user_bio);
          $("#data-form #nm_toko").val(response.nm_toko);
          $("#data-form #nm_user").prop('readonly', true);
          $("#data-form #alamat_toko").val(response.alamat_toko);
          $("#data-form #telpon").val(response.telpon);

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
        var form = $('#data-form');
        $(".text-danger").remove();
        $.ajax({
          // fixBug get url from global function only
          // get global variable is bug!
          url: getUrl(),
          type: 'post',
          data: form.serialize(),
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



  function remove(id_toko) {
    Swal.fire({
      title: "Hapus Data Toko",
      text: "Yakin ingin menghapus?",
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
            id_toko: id_toko
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
</script>


<?= $this->endSection() ?>