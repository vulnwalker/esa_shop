<?php
if($Main->MODUL_AKUNTANSI){
	$menuLogImport = PanelIcon($Link="pages.php?Pg=DaftarImport",$Image="sections.png",$Isi="Daftar Import");	
}

$Main->Isi="
<table border=0 cellspacing=4 width=50%>
	<tr>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
			<tr><th colspan=4>INPUT DATA</th></tr>
			<tr><td valign=top>".
				PanelIcon($Link="pages.php?Pg=usermanajemen",$Image="sections.png",$Isi="Administrasi User").
				PanelIcon($Link="pages.php?Pg=useraktivitas",$Image="sections.png",$Isi="User Online").
				"</td>
				<td valign=top>	".
					//PanelIcon($Link="?Pg=$Pg&SPg=02#ISIAN",$Image="sections.png",$Isi="Export/Backup Data").
					PanelIcon($Link="pages.php?Pg=backup",$Image="sections.png",$Isi="Backup Data").
					PanelIcon($Link="?Pg=$Pg&SPg=03#ISIAN",$Image="sections.png",$Isi="Import/Restore Data").
				"$menuLogImport
				</td>
			</tr>
		</table>
	</td>
	</tr>
</table>

		";
?>