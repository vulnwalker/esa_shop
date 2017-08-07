<div class="col-wd-3">
	<div class="col" id="judulsub">
		<i class="fa fa-bars"></i> Kategori
	</div>
<?php
	$sql = query("SELECT *FROM kategori order by id_kategori");
	while ($data = fetch($sql)) {
		if ($id == base64($data['nama_kat'])) {
			echo'
				<a href="index.php?page=kategori&id='.base64($data['nama_kat']).'">	
				<div class="col" id="active">
					<i class="fa fa-circle-o"></i> '.$data['nama_kat'].'
				</div>
				</a>';
		} else {
				echo'
				<a href="index.php?page=kategori&id='.base64($data['nama_kat']).'">	
				<div class="col">
					<i class="fa fa-circle-o"></i> '.$data['nama_kat'].'
				</div>
				</a>';
				}
	}
?>
</div>