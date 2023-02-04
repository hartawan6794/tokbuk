<html>

<head>
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
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
	<div class="float-sm-ed">Bandar Lampung, <?= tgl_indo(date('Y-m-d')) ?></div>
	<div style="font-size:64px; color:'#dddddd'"><i><?= strtoupper($toko) ?></i></div>
	<p>
		<b>Laporan Penjualan</b><br>
	<p>Per <?= tgl_indo($awal) ?> - <?= tgl_indo($akhir) ?></p>
	</p>
	<hr>
	<hr>
	<p></p>
	<p>

	</p>
	<table cellpadding="6">
		<tr>
			<th><strong>No</strong></th>
			<th><strong>Invoice</strong></th>
			<th><strong>Nama Pelanggan</strong></th>
			<th><strong>Nama Toko</strong></th>
			<th><strong>Produk</strong></th>
			<th><strong>Jumlah</strong></th>
			<th><strong>Harga</strong></th>
			<th><strong>Total</strong></th>
			<th><strong>Tanggal Penjualan</strong></th>
		</tr>
		<?php
		$no = 1;
		$oldInvoice = '';
		foreach ($data as $d) : ?>
			<tr>
				<td><?= $d->invoice != $oldInvoice ? $no : '' ?></td>
				<td style="text-align: left;"><?= $d->invoice != $oldInvoice ? $d->invoice : '' ?></td>
				<td style="text-align: left;"><?= $d->invoice != $oldInvoice ? $d->nm_user :'' ?></td>
				<td style="text-align: left;"><?= $d->nm_toko  ?></td>
				<td style="text-align: left;"><?= $d->judul_buku ?></td>
				<td><?= $d->qty ?></td>
				<td style="text-align: right;"><?= $d->harga_buku ? rupiah($d->harga_buku) : '' ?></td>
				<td style="text-align: right;"><?= $d->total ? rupiah($d->total) : '' ?></td>
				<td style="width: 12%;"><?= $d->invoice != $oldInvoice ? ($d->tgl_order != null ? tgl_indo(date('Y-m-d', strtotime($d->tgl_order))) : '') : '' ?></td>
			</tr>
		<?php
			$d->invoice != $oldInvoice ? $no++ : '';
			$oldInvoice = $d->invoice;
		endforeach;  ?>
		<tfoot>
			<tr>
				<!-- <td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td> -->
				<th colspan="7" style="text-align: right;">Total Pendapatan</th>
				<th style="text-align: right;"><?= rupiah($total) ?></th>
				<td></td>
			</tr>
		</tfoot>
	</table>
</body>
<script>
	// window.print()
</script>

</html>