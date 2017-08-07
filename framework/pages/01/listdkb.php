<?php
$HalDefault = cekPOST("HalDefault",1);
$LimitHal = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal;

$fmWIL = cekPOST("fmWIL");
$fmSKPD = cekPOST("fmSKPD");
$fmUNIT = cekPOST("fmUNIT");
$fmSUBUNIT = cekPOST("fmSUBUNIT");
$Cari = cekPOST("Cari");
$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",date("Y"));
setWilSKPD();

//variable pencarian
$fmCariComboIsi = cekPOST("fmCariComboIsi");
$fmCariComboField = cekPOST("fmCariComboField");
//variable pencarian ending

$Info = "";

$Qry = mysql_query("select * from ref_wilayah where b<>'00' order by nm_wilayah");
$Ops = "";
while($isi=mysql_fetch_array($Qry))
{
	$sel = $fmWIL == $isi['b'] ? "selected":"";
	$Ops .= "<option $sel value='{$isi['b']}'>{$isi['nm_wilayah']}</option>\n";
}

$KondisiD = $fmUNIT == "00" ? "":" and d='$fmUNIT' ";
$KondisiE = $fmSUBUNIT == "00" ? "":" and e='$fmSUBUNIT' ";
$Kondisi = "a='{$Main->Provinsi[0]}' and b='$fmWIL' and c='$fmSKPD' $KondisiD $KondisiE and tahun='$fmTAHUNANGGARAN'";

// copy untuk kondisi jumlah total
$KondisiTotal = $Kondisi;
if(!empty($fmCariComboIsi) && !empty($fmCariComboField))
{
	$Kondisi .= " and $fmCariComboField like '%$fmCariComboIsi%' ";
}
if(!empty($fmTahunPerolehan))
{
	$Kondisi .= " and thn_perolehan = '$fmTahunPerolehan' ";
}

$jmlTotalHarga = mysql_query("select sum(jml_harga) as total  from dkb where $KondisiTotal ");

if($jmlTotalHarga = mysql_fetch_array($jmlTotalHarga))
{
	$jmlTotalHarga = $jmlTotalHarga[0];
}
else
{$jmlTotalHarga=0;}
// copy untuk kondisi jumlah total sampai sini

$jmlTotalHargaDisplay = 0;
$ListData = "";
$cb=0;
$Qry = mysql_query("select * from view_dkb where $Kondisi order by a,b,c,d,e,f,g,h,i,j");
$jmlData = mysql_num_rows($Qry);

$Qry = mysql_query("select * from view_dkb where $Kondisi order by a,b,c,d,e,f,g,h,i,j $LimitHal");

$no=$Main->PagePerHal * (($HalDefault*1) - 1);
while($isi=mysql_fetch_array($Qry))
{
	$kdBarang = $isi['f'].$isi['g'].$isi['h'].$isi['i'].$isi['j'];
	$kdKelBarang = $isi['f'].$isi['g']."00";
	$nmBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h,i,j)='$kdBarang'"));
	$nmKelBarang = mysql_fetch_array(mysql_query("select * from ref_barang where concat(f,g,h)='$kdKelBarang'"));
	$no++;
	$jmlTotalHargaDisplay += $isi['jml_harga'];
	$clRow = $no % 2 == 0 ?"row1":"row0";
	$ListData .= "
		<tr class='$clRow'>
			<td class=\"GarisDaftar\" align=\"center\">$no.</td>
			<td class=\"GarisDaftar\">
				<a href=\"?Pg=$Pg&SPg=01&id={$isi['id']}\">{$nmBarang['nm_barang']}</a>
			</td>
            <td class=\"GarisDaftar\">{$isi['merk_barang']}</td>
            <td class=\"GarisDaftar\" align=\"right\">{$isi['jml_barang']}&nbsp;{$isi['satuan']}</td>
            <td class=\"GarisDaftar\" align=\"right\">".number_format($isi['harga'], 2, ',', '.')."</td>
            <td class=\"GarisDaftar\" align=\"right\">".number_format($isi['jml_harga'], 2, ',', '.')."</td>
            <td class=\"GarisDaftar\" align=\"center\">
				{$isi['k']}.{$isi['l']}.{$isi['m']}.{$isi['n']}.{$isi['o']}
			</td>
           	<td class=\"GarisDaftar\">{$isi['ket']}</td>
        </tr>
	";
	$cb++;
}

$ListData .= "
		<tr class='row0'>
			<td colspan=5 class=\"GarisDaftar\">Total Harga per Halaman (Ribuan)</td>
			<td align=right class=\"GarisDaftar\"><b>".number_format($jmlTotalHargaDisplay, 2, ',', '.')."</td>
			<td colspan=2  class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		<tr class='row0'>
			<td colspan=5 class=\"GarisDaftar\">Total Harga Seluruhnya (Ribuan)</td>
			<td class=\"GarisDaftar\" align=right><b>".number_format($jmlTotalHarga, 2, ',', '.')."</td>
			<td colspan=2 class=\"GarisDaftar\">&nbsp;</td>
		</tr>
		";

// aray combo pencarian barang 
$ArFieldCari = array(
array('nm_barang','Nama Barang'),
array('thn_perolehan','Tahun Pengadaan'),
array('alamat','Letak/Alamat'),
array('ket','Keterangan')
);

$Main->Isi = "
<A Name=\"ISIAN\"></A>
<script language=\"javascript\" type=\"text/javascript\">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'hapus') {
				form.action=\"home.php?ar=7\";
				form.submit();
				return;
			}else
			{ if (pressbutton == 'edit') {
				form.action=\"home.php?ar=6\";
				form.submit();
				return;
			  }	 

			}		
		}

</script>
<div align=\"center\" class=\"centermain\">
<div class=\"main\">
<form action=\"\" method=\"post\" name=\"adminForm\" id=\"adminForm\">

	<table class=\"adminheading\">
		<tr>
			<th height=\"47\" class=\"user\">Daftar Kebutuhan Barang</th>
		</tr>
	</table>

	
	<table width=\"100%\">
		<tr>
			<td width=\"60%\" valign=\"top\">
			".WilSKPD()."


	<table width=\"100%\">
		<tr>
			<td class=\"contentheading\"><DIV ALIGN=CENTER>DAFTAR KEBUTUHAN BARANG MILIK DAERAH</td>
		</tr>
		<tr>
			<td class=\"contentheading\"><DIV ALIGN=CENTER>TAHUN ANGGARAN $fmTAHUNANGGARAN</td>
		</tr>
	</table>
<!-- show pencarian -->
<table width=\"100%\" height=\"100%\">
	<tr valign=\"top\">   
		<td>
		".CariCombo($ArFieldCari)."
		</td>
	</tr>
</table>
<!-- pencarian ending -->
	<table width=\"100%\" height=\"100%\" class=\"koptable\" BORDER=1>
		<tr>
			<th class=\"th01\">No.</th>
			<th class=\"th01\">Nama Barang</th>
			<th class=\"th01\">Merk/Type/Ukuran</th>
			<th class=\"th01\">Jumlah Barang</th>
			<th class=\"th01\" style='width:90'>Harga Satuan<br>(Rp)</th>
			<th class=\"th01\" style='width:90'>Jumlah Biaya<br>(Rp)</th>
			<th class=\"th01\" style='width:80'>Kode Rekening</th>
			<th class=\"th01\">Keterangan</th>
		</tr>
		$ListData
		<tr>
			<td colspan=12 align=center>".Halaman($jmlData,$Main->PagePerHal,"HalDefault")."</td>
		</tr>
	</table>
<br>	
	<table width=\"100%\" class=\"menudottedline\">
		<tr><td>
			<table width=\"50\"><tr>
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dkb_cetak';adminForm.target='_blank';adminForm.submit();","print_f2.png","Halaman")."</td>
			
			<td>".PanelIcon1("javascript:adminForm.action='?Pg=PR&SPg=dkb_cetak&ctk=$jmlData';adminForm.target='_blank';adminForm.submit();","print_f2.png","Semua")."</td>
			</tr></table>
		</td></tr>
	</table>

		<input type=\"hidden\" name=\"option\" value=\"com_users\" />
		<input type=\"hidden\" name=\"task\" value=\"\" />
		<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
		<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
		</form>
  </div>
</div>";
?>