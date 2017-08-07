<?

function mosRedirect($url, $msg='' ) {

  	// Strip out any line breaks and throw away the rest
	$url = preg_split("/[\r\n]/", $url);
	$url = $url[0];

	if (trim( $msg )) {
	 	if (strpos( $url, '?' )) {
			$url .= '&mosmsg=' . urlencode( $msg );
		} else {
			$url .= '?mosmsg=' . urlencode( $msg );
		}
	}

	if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: ". $url );
	}
		
	exit();
}

function merah_op() {
	echo "<font color=\"#FF0000\">";
}

function merah_cl() {
	echo "</font>";
}

function kembali() {
	echo "<a href=\"#\" onclick=\"javascript:history.back()\" class=\"link\">Kembali</a>";
}

function icon($nama_file) {
	$balik = strtolower(strrev("$nama_file"));
	$hasil = substr("$balik", 0, 3);
	switch ($hasil) {
		case "cod" :
			echo  "Microsoft Word Dokumen";
			break;
		case "tpp" :
			echo  "Microsoft Power Point Dokumen";
			break;
			
		case "fdp" :
			echo "Adobe Acrobat Document";
			break;
		case "piz" :
			echo "Zip Compres";
			break;
		default :
			echo "Dokumen";
	}
}

function gambar($nama_file) {
	$balik = strtolower(strrev("$nama_file"));
	$hasil = substr("$balik", 0, 3);
	switch ($hasil) {
		case "cod" :
			echo "<img src=\"images/Word.gif\">";
			break;
		case "fdp" :
			echo "<img src=\"images/PDF.gif\">";
			break;
		case "piz" :
			echo "<img src=\"images/WinZip.gif\">";
			break;
		case "tpp" :
			echo "<img src=\"images/PowerPoint.gif\">";
			break;			
		default :
			echo "<img src=\"images/Style.gif\">";
	}
}

function getfsize($filein) {
	$size = filesize($filein);
	$exp = 1;
	while ($size >= pow(1024, $exp)) {
		$exp++;
	}
	$ext = array(" B", " KB", " MB", " GB", " TB");
	$summary = round(($size * 100) / pow(1024, ($exp - 1))) / 100;
	$strsize = sprintf("%.2f%s", $summary, $ext[$exp - 1]);
	$strps = strpos ($strsize, '.00');
	if ($strps > 0) {
		$strsize = substr_replace($strsize, " ", $strps, 3);
	}
	return $strsize;
}

function explode_str($string, $pemisah) {
	$data  = $string;
	$pecah = explode("$pemisah", $data);
	return $pecah;
}


function get_Group($id)
{
global $conn;

$sql = "select * from tbl_usertypes where id='".$id."'";
$rs=$conn->Execute($sql);

return $rs->fields['name'];
}

function dateOLAP($dateolap) {
global $conn;

	$arr = split('[/.-]', $dateolap);
	$day   = $arr[0];
	$month = $arr[1];
	$year  = $arr[2];

	$dateolap2 = $year."-".$month."-".$day;
	$sql_ = "select count(*) as counter from `tbl_master_dimensi_waktu` where the_date ='$dateolap2'";
	$rec = $conn->execute($sql_);
    if ($rec->fields[counter] == '1') {
		$sql = "select time_id from `tbl_master_dimensi_waktu` where the_date ='$dateolap2'";
		$rs = $conn->execute($sql);
		$sddate = $rs->fields[time_id];
	}
	else {
		if ($day!='' && $month!='' && $year!='') {

		$sql  = "select concat($year,'-',$month,'-',$day) as the_date,";
		$sql .= "dayname(concat($year,'-',$month,'-',$day)) as the_day,";
		$sql .= "monthname(concat($year,'-',$month,'-',$day)) as the_month,$year as year,";
		$sql .= "$day as day_of_month,week(concat($year,'-',$month,'-',$day)) as week_of_year, ";
		$sql .= "month(concat($year,'-',$month,'-',$day)) as month_of_year,";
		$sql .= "CONCAT('Q',quarter(concat($year,'-',$month,'-',$day))) as quarter ";
		$recordSet = $conn->execute($sql);
			while($arr = $recordSet->FetchRow()){
			  $sql1	 = "insert into `tbl_master_dimensi_waktu`(time_id,the_date,the_day,the_month,";
			  $sql1	.= "the_year,day_of_month,week_of_year,month_of_year,quarter) ";
			  $sql1	.= "values('$arr[id_waktu]','$arr[the_date]','$arr[the_day]','$arr[the_month]','";
			  $sql1	.= "$arr[year]','$arr[day_of_month]','$arr[week_of_year]',";
			  $sql1	.= "'$arr[month_of_year]','$arr[quarter]') ";
		      $recordSet1 = $conn->execute($sql1);
			  $sddate = $conn->Insert_ID();
			}
		}

	}
return $sddate;
}

function get_bulan($bln)
{

switch ($bln)
	{
		case "01":
			$bulan2 = "Januari";	break;
		case "02":
			$bulan2 = "Februari";  break;
		case "03":
			$bulan2 = "Maret";	break;
		case "04":
			$bulan2 = "April"; break;
		case "05":
			$bulan2 = "Mei"; break;
		case "06":
			$bulan2 = "Juni"; break;
		case "07":
			$bulan2 = "Juli"; break;
		case "08":
			$bulan2 = "Agustus"; break;
		case "09":
			$bulan2 = "September"; break;
		case "10":
			$bulan2 = "Oktober"; break;	
		case "11":
			$bulan2 = "November"; break;
		case "12":
			$bulan2 = "Desember"; break;
	}
	return $bulan2;
}

function get_header_statistik($val)
{
	switch ($val)
	{
		case "1":
			$nama = "Perikanan Budidaya-Produksi perikanan menurut jenis budidaya";	break;
		case "2":
			$nama = "Perikanan Budidaya-Jumlah rumah tangga budidaya menurut jenis budidaya";  break;
		case "3":
			$nama = "Perikanan Budidaya-Jumlah pembudidaya menurut jenis budidaya";	break;
		case "4":
			$nama = "Perikanan Budidaya-Luas usaha budidaya menurut jenis budidaya"; break;
		case "5":
			$nama = "Perikanan Budidaya-Nilai produksi perikanan budidaya menurut jenis budidaya"; break;
		case "6":
			$nama = "Perikanan Budidaya-Pendapatan kotor rata-rata pembudidaya menurut jenis budidaya"; break;	
	}
	return $nama;
}

function get_header_ikan($val)
{
	switch ($val)
	{
		case "1":
			$nama = "Perikanan Budidaya-Tambak menurut jenis Ikan ";	break;
		case "2":
			$nama = "Perikanan Budidaya-Kolam menurut jenis Ikan  ";  break;
		case "3":
			$nama = "Perikanan Budidaya-Keramba menurut jenis Ikan  ";	break;
		case "4":
			$nama = "Perikanan Budidaya-Sawah menurut jenis Ikan  "; break;
		case "5":
			$nama = "Perikanan Budidaya-Kolam Air Deras menurut jenis Ikan  "; break;
		case "6":
			$nama = "Perikanan Budidaya-Jaring Apung menurut jenis Ikan  "; break;
		case "7":
			$nama = "Perikanan Budidaya-Laut menurut jenis Ikan  "; break;	
	}
	
	return $nama;
}


function get_header_perikanan($val)
{
	switch ($val)
	{
		case "1":
			$nama = "Perikanan Tangkap-Jumlah Nelayan ";	break;
		case "2":
			$nama = "Perikanan Tangkap-Jumlah Unit Alat Penangkap ";  break;
		case "3":
			$nama = "Perikanan Tangkap-Produksi Perikanan Tangkap";	break;
		case "4":
			$nama = "Perikanan Tangkap-Nilai Produksi"; break;
		case "5":
			$nama = "Perikanan Tangkap-Rata2 Rata Pendapatan Kotor"; break;
		
	}
	
	return $nama;
}

function get_header_tangkap($val)
{
	switch ($val)
	{
		case "1":
			$nama = "Perikanan Tangkap-Jumlah Perahu/Kapal Menurut Kategori Kapal";	break;
		case "2":
			$nama = "Perikanan Tangkap-Jumlah RTP(Perusahaan Perikanan) Menurut Besarnya Usaha";  break;
	}
	
	return $nama;
}


function get_header_produksi($val)
{
	switch ($val)
	{
		case "1":
			$nama = "Produksi Penangkapan-Perairan Laut Menurut Jenis Ikan";	break;
		case "2":
			$nama = "Produksi Penangkapan-Perairan Umum Menurut Jenis Ikan ";  break;
	}
	
	return $nama;
}

function get_header_produksi_ikan($val)
{
	switch ($val)
	{
		case "1":
			$nama = "Produksi Perikanan-Menurut Jenis Ikan";	break;
		case "2":
			$nama = "Produksi Perikanan-Menurut Komoditi Utama";  break;
	}
	
	return $nama;
}



function get_value_stat($id,$th)
{	global $conn;

	$sql_  = "select * from tbl_budidaya_produksi where id_sub_sektor='".$id."' ";
	$sq1_ .= "and tahun='".$th."'";

	$rs_stat=$conn->Execute($sql_);
	$val_stat = $rs_stat->fields['total'];	

	return $val_stat;
}
?>