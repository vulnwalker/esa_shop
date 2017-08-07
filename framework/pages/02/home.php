<?php
$Main->Isi="
<table border=0 cellspacing=4 width=50%>
	<tr>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
			<tr><th colspan=2>INPUT DATA</th></tr>
			<tr><td valign=top>
				".PanelIcon($Link="?Pg=$Pg&SPg=01",$Image="sections.png",$Isi="Data Pengadaan Barang")."
				".PanelIcon($Link="?Pg=$Pg&SPg=02",$Image="sections.png",$Isi="Data Pengadaan Pemeliharaan Barang")."
			</td></tr>
		</table>
	</td>
	<td>&nbsp</td>
	<td>&nbsp</td>
	<td>&nbsp</td>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
		<tr><th colspan=2>DAFTAR</th>
		</tr>
		<td valign=top>
		".PanelIcon($Link="?Pg=$Pg&SPg=03",$Image="module.png",$Isi="Daftar Pengadaan Barang")."
		".PanelIcon($Link="?Pg=$Pg&SPg=04",$Image="module.png",$Isi="Daftar Pengadaan Pemeliharaan Barang")."
		</td>
		</tr>
		</table>
	</td>
	</tr>
</table>

		";
?>