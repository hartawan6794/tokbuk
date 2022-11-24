<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-9 mt-2">
        <h3 class="card-title">Alamat Kirim</h3>
      </div>
      <div class="col-3">
        <button type="button" class="btn float-sm-end btn-success" id="btn-add" onclick="save()" title="Tamaba Alamat"> <i class="fa fa-plus"></i> Tambah Alamat</button>
      </div>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="data_table" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama penerima</th>
          <th>Telpon penerima</th>
          <th>Provinsi</th>
          <th>Kabupaten</th>
          <th>Kecamatan</th>
          <th>Kelurahan</th>
          <th>Alamat rumah</th>
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
        <form id="data-form" class="pl-3 pr-3">
          <div class="row">
            <input type="hidden" id="id_alamat" name="id_alamat" class="form-control" placeholder="Id alamat" maxlength="6" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_user_bio" class="col-form-label"> Nama Pengguna: </label>
                <select class="form-control" name="id_user_bio" id="id_user_bio">
                  <?php session()->get('username') == 'admin' ? '<option value="">-- Pilih Pengguna -- </option>' : '' ?>
                  <?php foreach ($user as $data) : ?>
                    <option value="<?= $data->id_user_bio ?>"><?= $data->nm_user ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="nm_penerima" class="col-form-label"> Nama penerima: <span class="text-danger">*</span> </label>
                <input type="text" id="nm_penerima" name="nm_penerima" class="form-control" placeholder="Nm penerima" minlength="0" maxlength="255" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="telp_penerima" class="col-form-label"> Telp penerima: <span class="text-danger">*</span> </label>
                <input type="text" id="telp_penerima" name="telp_penerima" class="form-control" placeholder="Telp penerima">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="provinsi" class="col-form-label"> Provinsi: <span class="text-danger">*</span> </label>
                <select class="form-control" name="provinsi" id="provinsi" onchange="showKab(this.value)">
                  <option value="">-- Pilih Provinsi -- </option>
                  <?php foreach ($provinsi as $data) : ?>
                    <option value="<?= $data['province_id'] . "-" . $data['province'] ?>"><?= $data['province'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-12" id="kabData">
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="kecamatan" class="col-form-label"> Kecamatan: <span class="text-danger">*</span> </label>
                <input type="text" id="kecamatan" name="kecamatan" class="form-control" placeholder="Kecamatan">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="kelurahan" class="col-form-label"> Kelurahan: <span class="text-danger">*</span> </label>
                <input type="text" id="kelurahan" name="kelurahan" class="form-control" placeholder="Kelurahan">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="alamat_rumah" class="col-form-label"> Alamat rumah: <span class="text-danger">*</span> </label>
                <input type="text" id="alamat_rumah" name="alamat_rumah" class="form-control" placeholder="Alamat rumah">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="postalcode" class="col-form-label"> Postalcode: </label>
                <input type="text" id="postalcode" name="postalcode" class="form-control" placeholder="Postalcode">
              </div>
            </div>
          </div>

          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn"> Simpan</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"> Batal</button>
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

  $('#edit-alamat').on('click',function(){
    $('#kabData').html("")
  })

  function save(id_alamat) {
    // reset the form 
    $("#data-form")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_alamat === 'undefined' || id_alamat < 1) { //add
      urlController = '<?= base_url($controller . "/add") ?>';
      submitText = 'Simpan';
      $('#model-header').removeClass('bg-info').addClass('bg-success');
      $("#info-header-modalLabel").text('Tambah Alamat');
      $("#form-btn").text(submitText);
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = 'Ubah';
      $.ajax({
        url: '<?php echo base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_alamat: id_alamat
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('Ubah Alamat');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_alamat").val(response.id_alamat);
          $("#data-form #id_user_bio").val(response.id_user_bio);
          $("#data-form #nm_penerima").val(response.nm_penerima);
          $("#data-form #telp_penerima").val(response.telp_penerima);
          $("#data-form #provinsi").val(response.provinsi);
          $("#data-form #kecamatan").val(response.kecamatan);
          $("#data-form #kelurahan").val(response.kelurahan);
          $("#data-form #alamat_rumah").val(response.alamat_rumah);
          $("#data-form #postalcode").val(response.postalcode);
          $("#data-form #status").val(response.status);
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



  function remove(id_alamat) {
    Swal.fire({
      title: "Hapus Data Alamat",
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
            id_alamat: id_alamat
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

  function showKab(id_prov) {
    var id = id_prov.split(' ');
    // console.log(id[0])
    $.ajax({
      url: "alamatkirim/getapikab",
      type: "POST",
      dataType: "json",
      data: {
        url: "https://api.rajaongkir.com/starter/city?province=" + id[0],
        key: "1774d6ce35f0c2b1ca03fcbfa8c98dcf",
        // province: id_prov,
      },
      beforeSend: function() {
        $('#kabData').html(`<option value="">Sedang Mengambil Datas</option>`)
      },
      success: function(x) {
        $('#kabData').html(`<div class="form-group mb-3">
                <label for="kabupaten" class="col-form-label"> Kabupaten: <span class="text-danger">*</span> </label>
                <select class="form-control" name="kabupaten" id="kabupaten">
                  <option value="">-- Pilih Kabupaten --</option>
                </select>
              </div>`)
        $.each(x, function(k, v) {
          $('#kabupaten').append(`<option value="${v.city_id}-${v.city_name}">${v.city_name}</option>`)
          // console.log(v)
        })
      },
      error: function(x) {

      }
    });
  }
</script>


<?= $this->endSection() ?>