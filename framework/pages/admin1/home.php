<?php
$Main->Isi="
<table border=0 cellspacing=4 width=50%>
	<tr>
	<td valign=top>
		<table border=0 cellspacing=0 width=100% class=\"adminform\">
			<tr><th colspan=4>INPUT DATA</th></tr>
			<tr><td valign=top>
				".PanelIcon($Link="?Pg=$Pg&SPg=01#ISIAN",$Image="sections.png",$Isi="Administrasi User").
				PanelIcon($Link="pages.php?Pg=useraktivitas",$Image="sections.png",$Isi="User Online")."				
				</td>
				<td valign=top>
				".PanelIcon($Link="pages.php?Pg=backup",$Image="sections.png",$Isi="Backup Data")."
				".PanelIcon($Link="?Pg=$Pg&SPg=03#ISIAN",$Image="sections.png",$Isi="Import/Restore Data")."
				</td>
			</tr>
		</table>
	</td>
	</tr>
</table>

		";
?>