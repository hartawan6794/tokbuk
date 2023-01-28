
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
				<th><strong>No</strong></th>
				<th><strong>Invoice</strong></th>
				<th><strong>Nama Pelanggan</strong></th>
				<th><strong>jumlah</strong></th>
				<th><strong>Harga</strong></th>
				<th><strong>Total</strong></th>
				<th><strong>Tanggal Penjualan</strong></th>
			</tr>
			<?php
			 $no = 1;
			 $befInvoice ='';
			  foreach($data as $d) :?>
			<tr>
					<td><?= $no ?></td>
					<td><?= $d->invoice != $befInvoice ? $d->invoice : ''?></td>
					<td class="text-left"><?= $d->nm_user ?></td>
					<td><?= $d->qty ?></td>
					<td><?= $d->harga_buku ?></td>
					<td><?= $d->total ?></td>
					<td><?= tgl_indO($d->tgl_order) ?></td>
				</tr>
				<?php 
				$befInvoice = $d->invoice;
				$no++; 
				endForeach;  ?>
			</table>
	</body>
    <script>window.print()</script>
</html>