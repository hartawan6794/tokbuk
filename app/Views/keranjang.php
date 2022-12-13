<?= $this->extend("layout/master") ?>

<?= $this->section("content") ?>

<!-- Main content -->
<div class="card">
  <div class="card-header">
    <div class="row">
      <div class="col-10 mt-2">
        <h3 class="card-title">Keranjang</h3>
      </div>
      <!-- <div class="col-2">
        <button type="button" class="btn float-right btn-success" onclick="save()" title="<?= lang("App.new") ?>"> <i class="fa fa-plus"></i> <?= lang('App.new') ?></button>
      </div> -->
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="data_table" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Product</th>
          <th>QTY</th>
          <th>Harga buku</th>
          <th>Total harga</th>
          <th>Kostumer</th>
          <!-- <th></th> -->
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
            <input type="hidden" id="id_cart" name="id_cart" class="form-control" placeholder="Id cart" maxlength="6" required>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_product" class="col-form-label"> Id product: <span class="text-danger">*</span> </label>
                <input type="number" id="id_product" name="id_product" class="form-control" placeholder="Id product" minlength="0" maxlength="6" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="qty" class="col-form-label"> Qty: </label>
                <input type="number" id="qty" name="qty" class="form-control" placeholder="Qty" minlength="0" maxlength="6">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="harga_buku" class="col-form-label"> Harga buku: </label>
                <input type="text" id="harga_buku" name="harga_buku" class="form-control" placeholder="Harga buku" minlength="0" maxlength="10">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="total_harga" class="col-form-label"> Total harga: </label>
                <input type="text" id="total_harga" name="total_harga" class="form-control" placeholder="Total harga" minlength="0" maxlength="10">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group mb-3">
                <label for="id_user_bio" class="col-form-label"> Id user bio: <span class="text-danger">*</span> </label>
                <input type="number" id="id_user_bio" name="id_user_bio" class="form-control" placeholder="Id user bio" minlength="0" maxlength="6" required>
              </div>
            </div>
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

  function save(id_cart) {
    // reset the form 
    $("#data-form")[0].reset();
    $(".form-control").removeClass('is-invalid').removeClass('is-valid');
    if (typeof id_cart === 'undefined' || id_cart < 1) { //add
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
          id_cart: id_cart
        },
        dataType: 'json',
        success: function(response) {
          $('#model-header').removeClass('bg-success').addClass('bg-info');
          $("#info-header-modalLabel").text('<?= lang("App.edit") ?>');
          $("#form-btn").text(submitText);
          $('#data-modal').modal('show');
          //insert data to form
          $("#data-form #id_cart").val(response.id_cart);
          $("#data-form #id_product").val(response.id_product);
          $("#data-form #qty").val(response.qty);
          $("#data-form #harga_buku").val(response.harga_buku);
          $("#data-form #total_harga").val(response.total_harga);
          $("#data-form #id_user_bio").val(response.id_user_bio);
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



  function remove(id_cart) {
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
            id_cart: id_cart
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