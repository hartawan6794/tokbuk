<html>

<head>
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
			font-size: 8px;
		}
		td,
		th {
			border: 1px solid #000000;
			text-align: center;
			height: 20px;
			margin: 8px;
		}
	</style>
</head>

<body>
	<?php helper('settings') ?>
	<div class="col-md-12" style="text-align: right;">Bandar Lampung, <?= tgl_indo(date('Y-m-d')) ?></div>
	<div style="font-size:54px; color:'#dddddd'"><i><?= strtoupper($toko) ?></i></div>
	<b>Laporan Penjualan</b>
	<p style="font-size:10px">Waktu : <?= tgl_indo($awal) ?> - <?= tgl_indo($akhir) ?></p>

	<hr>
	<p></p>
	<table cellpadding="4">
		<tr>
			<th style="width:3%"><strong>No</strong></th>
			<th style="width:10%"><strong>Invoice</strong></th>
			<th style="width:12%"><strong>Nama Pelanggan</strong></th>
			<th><strong>Nama Toko</strong></th>
			<th style="width:20%"><strong>Produk</strong></th>
			<th style="width:6%"><strong>Jumlah</strong></th>
			<th><strong>Harga</strong></th>
			<th style="width:15%"><strong>Total</strong></th>
			<th style="width:12%"><strong>Tanggal Penjualan</strong></th>
		</tr>
		<?php
		$no = 1;
		$oldInvoice = '';
		foreach ($data as $d) : ?>
			<tr>
				<td ><?= $d->invoice != $oldInvoice ? $no : '' ?></td>
				<td style="text-align: left;"><?= $d->invoice != $oldInvoice ? $d->invoice : '' ?></td>
				<td style="text-align: left;"><?= $d->invoice != $oldInvoice ? $d->nm_user :'' ?></td>
				<td style="text-align: left;"><?= $d->nm_toko  ?></td>
				<td style="text-align: left;"><?= $d->judul_buku ?></td>
				<td><?= $d->qty ?></td>
				<td style="text-align: right;"><?= $d->harga_buku ? rupiah($d->harga_buku) : '' ?></td>
				<td style="text-align: right;"><?= $d->total ? rupiah($d->total) : '' ?></td>
				<td ><?= $d->invoice != $oldInvoice ? ($d->tgl_order != null ? tgl_indo(date('Y-m-d', strtotime($d->tgl_order))) : '') : '' ?></td>
			</tr>
		<?php
			$d->invoice != $oldInvoice ? $no++ : '';
			$oldInvoice = $d->invoice;
		endforeach;  ?>
		<tfoot>
			<tr>
				<th colspan="7" style="text-align: right;">Total Pendapatan</th>
				<th style="text-align: right;"><?= rupiah($total) ?></th>
				<td></td>
			</tr>
		</tfoot>
	</table>
</body>

</html>