<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-10 mt-2">
        <h3 class="card-title">Daftar Pemesanan</h3>
      </div>
      <div class="col-2">
        <!-- <button type="button" class="btn float-right btn-success" onclick="save()" title="Tambah"> <i class="fa fa-plus"></i>   <?= lang('App.new') ?></button> -->
      </div>
    </div>
  </div>
  <!-- /.card-header -->
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
          <th>Validasi</th>
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
            <input type="hidden" id="id_order" name="id_order" class="form-control" placeholder="Id order" maxlength="6" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="invoice" class="col-form-label"> Invoice: </label>
                <input type="text" id="invoice" name="invoice" class="form-control" placeholder="Invoice" minlength="0" maxlength="20">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_user_bio" class="col-form-label"> Nama Pelanggan: <span class="text-danger">*</span> </label>
                <input type="number" id="id_user_bio" name="id_user_bio" class="form-control" placeholder="Nama Pelanggan" minlength="0" maxlength="6" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_rekening" class="col-form-label"> Rekening: <span class="text-danger">*</span> </label>
                <input type="number" id="id_rekening" name="id_rekening" class="form-control" placeholder="Rekening" minlength="0" maxlength="4" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="bukti_order" class="col-form-label"> Bukti order: </label>
                <input type="text" id="bukti_order" name="bukti_order" class="form-control" placeholder="Bukti order" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="noresi" class="col-form-label"> Noresi: </label>
                <input type="text" id="noresi" name="noresi" class="form-control" placeholder="Noresi" minlength="0" maxlength="255">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="tgl_order" class="col-form-label"> Tgl order: <span class="text-danger">*</span> </label>
                <input type="date" id="tgl_order" name="tgl_order" class="form-control" dateISO="true" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="sub_total" class="col-form-label"> Sub total: <span class="text-danger">*</span> </label>
                <input type="text" id="sub_total" name="sub_total" class="form-control" placeholder="Sub total" minlength="0" maxlength="10" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="sub_total_pengiriman" class="col-form-label"> Sub total pengiriman: <span class="text-danger">*</span> </label>
                <input type="text" id="sub_total_pengiriman" name="sub_total_pengiriman" class="form-control" placeholder="Sub total pengiriman" minlength="0" maxlength="10" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="total_pembayaran" class="col-form-label"> Total pembayaran: <span class="text-danger">*</span> </label>
                <input type="text" id="total_pembayaran" name="total_pembayaran" class="form-control" placeholder="Total pembayaran" minlength="0" maxlength="10" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="validasi" class="col-form-label"> Validasi: <span class="text-danger">*</span> </label>
                <input type="number" id="validasi" name="validasi" class="form-control" placeholder="Validasi" minlength="0" maxlength="3" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="created_at" class="col-form-label"> Created at: </label>
                <input type="date" id="created_at" name="created_at" class="form-control" dateISO="true">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="updated_at" class="col-form-label"> Updated at: </label>
                <input type="date" id="updated_at" name="updated_at" class="form-control" dateISO="true">
              </div>
            </div>
          </div>

          <div class="form-group text-center">
            <div class="btn-group">
              <button type="submit" class="btn btn-success mr-2" id="form-btn"><?= lang("App.save") ?></button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= lang("App.cancel") ?></button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal fade" id="lihatBukti" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bukti Pemesanan</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="form-group">
        <div id="detail">

        </div>
      </div>
      <div class="modal-footer">
        <!-- <input type="hidden" id="id_cuti" name="id_cuti" > -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
        <!-- <button type="submit" class="btn btn-primary">Save</button> -->
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="detailpemesanan" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Pemesanan</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body table-responsive">
        <table class="table table-bordered no-margin">
          <tbody>
            <tr>
              <th style="width: 20%;">Invoice</th>
              <td style="width: 30%;"><span id="invoice"></span></td>
              <th style="width: 20%;">Waktu Penjualan</th>
              <td style="width: 30%;"><span id="date"></span></td>
            </tr>
            <tr>
              <th>Pelanggan</th>
              <td><span id="cust"></span></td>
              <th>Sub Total</th>
              <td><span id="sub"></span></td>

            </tr>
            <tr>
              <th>Ke Rekening</th>
              <td><span id="rek"></span></td>

              <th>Sub Total Pengiriman</th>
              <td><span id="subpengiriman"></span></td>
            </tr>

            <tr>
              <th>jenis Pengiriman</th>
              <td><span id="jenis"></span></td>
              <th>Total Pembayaran</th>
              <td><span id="total"></span></td>
            </tr>
            <tr>
              <th style="text-transform:capitalize">Alamat</th>
              <td><span id="alamat"></span></td>
            </tr>
            <tr>
              <th>Produk</th>
              <td colspan="3"><span id="product"></span></td>
            </tr>
          </tbody>
        </table>

      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
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

  function save(id_order) {
    // reset the form 
    $("#data-form")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_order === 'undefined' || id_order < 1) { //add
      urlController = '<?= base_url($controller . "/add") ?>';
      submitText = '<?= lang("App.save") ?>';
      $('#model-header').removeClass('bg-info').addClass('bg-success');
      $("#info-header-modalLabel").text('<?= lang("App.add") ?>');
      $("#form-btn").text(submitText);
      $('#data-modal').modal('show');
    } else { //edit
      urlController = '<?= base_url($controller . "/edit") ?>';
      submitText = '<?= lang("App.update") ?>';
      $.ajax({
        url: '<?php echo base_url($controller . "/getOne") ?>',
        type: 'post',
        data: {
          id_order: id_order
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('<?= lang("App.edit") ?>');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_order").val(response.id_order);
          $("#data-form #invoice").val(response.invoice);
          $("#data-form #id_user_bio").val(response.id_user_bio);
          $("#data-form #id_rekening").val(response.id_rekening);
          $("#data-form #bukti_order").val(response.bukti_order);
          $("#data-form #noresi").val(response.noresi);
          $("#data-form #tgl_order").val(response.tgl_order);
          $("#data-form #sub_total").val(response.sub_total);
          $("#data-form #sub_total_pengiriman").val(response.sub_total_pengiriman);
          $("#data-form #total_pembayaran").val(response.total_pembayaran);
          $("#data-form #validasi").val(response.validasi);
          $("#data-form #created_at").val(response.created_at);
          $("#data-form #updated_at").val(response.updated_at);

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


  function remove(id_order) {
    Swal.fire({
      title: "<?= lang("App.remove-title") ?>",
      text: "<?= lang("App.remove-text") ?>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<?= lang("App.confirm") ?>',
      cancelButtonText: '<?= lang("App.cancel") ?>'
    }).then((result) => {

      if (result.value) {
        $.ajax({
          url: '<?php echo base_url($controller . "/remove") ?>',
          type: 'post',
          data: {
            id_order: id_order
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

  function lihat(id_order) {
    $('#lihatBukti').modal('show');
    $('.form-group #detail').html("")
    $.ajax({
      url: '<?php echo base_url($controller . "/getOne") ?>',
      type: 'post',
      data: {
        id_order: id_order
      },
      dataType: 'json',
      success: function(response) {
        // console.log(response.bukti_order);
        $('.form-group #detail').html(`<img style='width: 100%;' src='/img/bukti/${response.bukti_order}'>`)
      }
    });
  }

  function detail(id_order) {
    $('#detailpemesanan').modal('show');
    $('.modal-body #detail').html("")
    $.ajax({
      url: '<?php echo base_url($controller . "/getOne") ?>',
      type: 'post',
      data: {
        id_order: id_order
      },
      dataType: 'json',
      success: function(response) {
        // console.log(response);
        var alamat = response.alamat_rumah + '<br>Kelurahan ' + response.kelurahan + ', Kecamatan ' + response.kecamatan + ',Kabupaten ' + response.kabupaten + ',' + response.provinsi+'<br> No. Telp - '+response.telp_penerima;
        $('.modal-body #invoice').text(response.invoice)
        $('.modal-body #cust').text(response.nm_user)
        $('.modal-body #date').text(response.tgl_order)
        $('.modal-body #sub').text(convertToRupiah(response.sub_total))
        $('.modal-body #rek').text(response.nm_bank + ' - ' + response.no_rek)
        $('.modal-body #subpengiriman').text(convertToRupiah(response.sub_total_pengiriman))
        $('.modal-body #jenis').text(response.jns_pengiriman)
        $('.modal-body #total').text(convertToRupiah(response.total_pembayaran))
        $('.modal-body #alamat').html(alamat)
        var product = '<table class="table table-bordered no-margin"> <tr><th>Barang</th><th>Harga</th><th>Qty</th><th>Total</th></tr>'
        $.ajax({
          url: '<?php echo base_url($controller . "/getOrderDetail") ?>',
          type: 'post',
          data: {
            id_order: id_order
          },
          dataType: 'json',
          success: function(r) {
            $.each(r, function(key, val) {
              product += '<tr><td>' + val.judul_buku + '</td><td>' + convertToRupiah(val.harga_product) + '</td><td>' + val.qty + '</td><td>' + convertToRupiah(val.total) + '</td></tr>'
            })
            product += '</table>'
            $('#product').html(product)
          }
        })
      }
    });

    function convertToRupiah(angka) {
      var rupiah = '';
      var angkarev = angka.toString().split('').reverse().join('');
      for (var i = 0; i < angkarev.length; i++)
        if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
      return 'Rp. ' + rupiah.split('', rupiah.length - 1).reverse().join('');
    }
  }

  function valid(id_order, validasi) {
    $.ajax({
      url: '<?php echo base_url($controller . "/sendemail") ?>',
      type: 'post',
      data: {
        id_order: id_order,
        validasi: validasi
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
</script>


<?= $this->endSection() ?>