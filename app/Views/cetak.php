
<html>
	<head>
		<style>
			table {
			  font-family: arial, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			td, th {
			  border: 1px solid #000000;
			  text-align: center;
			  height: 20px;
			  margin: 8px;
			}

		</style>
	</head>
	<body>
      <?php helper('settings')?>
		<div class="float-sm-ed">Bandar Lampung, <?= tgl_indo(date('Y-m-d')) ?></div>  
		<div style="font-size:64px; color:'#dddddd'"><i><?= strtoupper($toko)?></i></div>
		<p>
		<b>Laporan Penjualan</b><br>
        <p>Per <?=  tgl_indo($awal)?> - <?=  tgl_indo($akhir)?></p>
		</p>
		<hr>
		<hr>
		<p></p>
		<p>
			
		</p>
		<table cellpadding="6" >
			<tr>
				<th><strong>Barang</strong></th>
				<th><strong>Harga Satuan</strong></th>
				<th><strong>Jumlah</strong></th>
				<th><strong>Ongkir</strong></th>
				<th><strong>Total Harga</strong></th>
			</tr>
			<tr>

			</tr>
		</table>
	</body>
    <script>window.print()</script>
</html>