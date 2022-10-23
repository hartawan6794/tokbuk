$(document).ready(function () {

  $(".detail_user").on("click", function () {
    var id = $(this).data("id");
    var base_url = "userbio/view";
    $.ajax({
      url: base_url,
      type: "GET",
      dataType: "json",
      data: {
        id: id,
      },
      beforeSend: function (x) {
        // console.log(url)
        $("#view").html("Sedang Mengambil Data. . ");
      },
      success: function (x) {
        // console.log(x)
        if (x.success) {
          // $.each(x.result, function (k, v) {
          console.log(x.result);
          $(".modal-body #view").html("");
          $(".modal-body #view").append(
            `
              <table id="dataTable" class="table table-bordered table-striped">
                    <tr>
                        <th>NIK</th>
                        <td>${x.result.nik_user}</td>
                    </tr>
                    <tr>
                        <th>Nama Pengguna</th>
                        <td>${x.result.nm_user}</td>
                    </tr>
                    <tr>
                        <th>Email Pengguna</th>
                        <td>${x.result.email_user}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>` +
              (x.result.gender == "1" ? "Laki-laki" : "Perempaun") +
              `</td>
                    </tr>
                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>${x.result.tempat_lahir}, `+tgl_indo(x.result.tanggal_lahir)+`</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>${x.result.alamat}</td>
                    </tr>
                    <tr>
                        <th>No. Telpon</th>
                        <td>${x.result.telpon}</td>
                    </tr>
                    <tr>
                        <th>Gambar Pengguna</th>
                        <td style="width:100%'">` +
                        (x.result.imguser
                            ? `<img style="width: 100%;" src='/img/cuti/${x.result.imguser}'>`
                            : "Belum Upload Photo") +
                        `</td>
                    </tr>

                </table>
              `
          );
          // })
        }
      },
      error: function (x) {
        console.log(x);
      },
    });

    $("#viewModal").modal("show");
  });
  
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
});



