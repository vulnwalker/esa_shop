<?php

class PindahTanganObj extends DaftarObj{	
	var $Prefix = 'Pindahtangan';
	//var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	
	var $TblName = 'v1_pindahtangan'; //daftar
	var $TblName_Hapus = 'pemindahtanganan';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id'); //daftar/hapus
	var $FieldSum = array('jml_harga');
	var $SumValue = array();
	//var $totalCol = 21;
	var $FieldSum_Cp1 = array( 9,8,8);//berdasar mode
	var $FieldSum_Cp2 = array( 8,7,7);	
	//var $fieldSum_lokasi = array(11,18);
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Pemindahtanganan ';
	var $pagePerHal ='';
	var $cetak_xls=TRUE ;
	var $fileNameExcel='Pemindahtanganan.xls';
	var $Cetak_Judul = 'Daftar Pemindahtanganan Barang Milik Daerah';
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	//form
	var $form_width = 650;
	var $form_height = 360;		
	//cetak 
	
	
	function setKolomHeader($Mode=1, $Checkbox=''){
		$SSPg = $_GET['SSPg'];
		$tampilNoHeader = $cetak?
			"<th class=\"th01\" width='30' colspan=2>No.</th>"
			: "<th class=\"th01\" width='30'>No.</th>$Checkbox";
		if($SSPg=='04' || $SSPg=='06' || $SSPg=='07' || $SSPg=='09' ){
			$tampilMerk = "<th class='th01' style='width:200'>Alamat</th>";
		}else{
			$tampilMerk = "<th class='th01' width='70'>Merk / Tipe</th>";	
		}
		if($SSPg=='04' ){
			$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat / Tanggal / Hak</th>";
		}else if( $SSPg=='06' || $SSPg=='07' || $SSPg=='09' ){
			$tampilSert = "<th class=\"th01\" width='70'>No. Dokumen / Tanggal </th>";
		}else{
			$tampilSert = "<th class=\"th01\" width='70'>No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin</th>";	
		}
		
		if($Mode==1){
			$cspanno = 4;
		}else{
			$cspanno = 3;
		}
		$headerTable = 
			"<thead><tr>
			<th class=\"th02\" colspan='$cspanno'>Nomor</th>
			<th class=\"th02\" colspan='3'>Spesifikasi Barang</th>	
			<th class=\"th01\" rowspan='2' width='50' >Tahun <br>Perolehan</th>	
			<th class=\"th01\" rowspan='2' width='70'>Kondisi <br>Barang <br>(B,KB,RB)</th>
			<th class=\"th01\" rowspan='2' width='150'>Harga <br>Perolehan</th>
			<th class=\"th01\" rowspan='2' width='150'>Harga Akumulasi Penyusutan</th>
			<!--<th class=\"th01\" rowspan='2' >Bentuk <br> Pemindah tanganan</th>	-->
			<th class=\"th02\" colspan='2'>Kepada</th>
			<th class=\"th01\" rowspan='2' width='150'>Uraian</th>	
			<th class=\"th01\" rowspan='2'>Keterangan/<br>Tgl. Pemindah tanganan</th>	
			<th class=\"th01\" rowspan='2' width='100'>No. SK/<br> Tgl. SK</th>
			<th class=\"th01\" rowspan='2' width='100'>Penilaian</th>
			<th class=\"th01\" rowspan='2' width='100'>Nama Pengguna</th>
			</tr>
			<tr>
			$tampilNoHeader
			<th class=\"th01\" width='100'>Kode <br>Barang/<br>ID Barang</th>
			<th class=\"th01\" width='30'>Reg.</th>
			<th class=\"th01\" width='200'>Nama / Jenis Barang</th>
			$tampilMerk
			$tampilSert			
			<th class=\"th01\" width='100'>Nama</th>
			<th class=\"th01\" width='200'>Alamat</th>
			</tr></thead>";
			
		
		return $headerTable;
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main;// $fmUNIT,$fmSKPD, $fmSUBUNIT;
		$Kondisi=''; $Order = ''; $Limit=''; $NoAwal = 0;$cek = '';
				
		//Kondisi---------------------
		$arrKondisi= array();
		
		$SSPg = !empty($_GET[SSPg])? $_GET['SSPg']: $_POST['fmSSPg'];
		$fmcr_data = $_REQUEST['fmcr_data'];
		$fmPILCARIvalue = $_REQUEST['fmPILCARIvalue'];
		switch($SSPg){
			case '04': $arrKondisi[] =" f='01'"; break;
			case '05': $arrKondisi[] =" f='02'"; break;
			case '06': $arrKondisi[] =" f='03'"; break;
			case '07': $arrKondisi[] =" f='04'"; break;
			case '08': $arrKondisi[] =" f='05'"; break;
			case '09': $arrKondisi[] =" f='06'"; break;				
			case '10': $arrKondisi[] =" f='07'"; break;				
		}		
		if (!empty($_POST['fmIDBARANGCARI'])) $arrKondisi[] = " id_bukuinduk like '%".$_POST['fmIDBARANGCARI']."%'";
		if (!empty($_POST['fmFiltThnBuku'])) $arrKondisi[] = " year(tgl_buku) = '".$_POST['fmFiltThnBuku']."'";
		if (!empty($_POST['fmFiltThnHapus'])) $arrKondisi[] = " year(tgl_pemindahtanganan) = '".$_POST['fmFiltThnHapus']."'";
		if(!empty($fmcr_data)){
			switch($fmcr_data){			
				case 'selectKode': $arrKondisi[] .= " concat(f,'.',g,'.',h,'.',i,'.',j) like '%$fmPILCARIvalue%' "; break;
				case 'selectNama': $arrKondisi[] .= " alamat like '%$fmPILCARIvalue%'"; break;								 	
			}
			
		}
		//setWilSKPD();	
		$fmUNIT = $_POST['fmUNIT'];
		$fmSKPD = $_POST['fmSKPD'];
		$fmSUBUNIT = $_POST['fmSUBUNIT'];
		$fmSEKSI = $_POST['fmSEKSI'];
		if (!empty($fmSKPD) && $fmSKPD!='00' ) $arrKondisi[] = " c = '".$fmSKPD."'";
		if (!empty($fmUNIT) && $fmUNIT!='00' ) $arrKondisi[] = " d = '".$fmUNIT."'";
		if (!empty($fmSUBUNIT) && $fmSUBUNIT!='00' ) $arrKondisi[] = " e = '".$fmSUBUNIT."'";
		if (!empty($fmSEKSI) && $fmSEKSI!='00' && $fmSEKSI!='000' ) $arrKondisi[] = " e1 = '".$fmSEKSI."'";
		$bentuk = $_REQUEST['bentuk'];
		if (!empty($bentuk) ) $arrKondisi[] = " bentuk_pemindahtanganan = '".$bentuk."'";
		//$cek= 'fmSKPD'.$fmSKPD;
		$Kondisi = join(' and ',$arrKondisi); 
		if($Kondisi !='') $Kondisi = ' Where '.$Kondisi;
		//echo $Kondisi;
		//order -------------------------
		$AcsDsc1 = $_POST['AcsDsc1'];
		$Asc1 = $AcsDsc1 == 1? 'desc' : '';
		$fmORDER1 = $_POST['odr1'];
		$OrderArr= array();		
		switch($fmORDER1){
			case '1': $OrderArr[] =  " year(tgl_pemindahtanganan) $Asc1 "; break;			
		}
		$Order = join(', ',$OrderArr); 
		if($Order !='') $Order = ' Order by '.$Order;
		
		//limit -----------------		
		$lmt = $this->getDaftar_limit($Mode);
		$Limit = $lmt['Limit'];
		$NoAwal = $lmt['NoAwal'];
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order, 'Limit'=>$Limit,'NoAwal'=>$NoAwal, 'cek'=>$cek);
	
	}
	
	function genDaftarOpsi(){
		global $Main;
		//order by ----------------------------------------------
		$ArrCaridata = array(
			//array("0","Semua"), default
			array("selectKode","Kode Barang"),
			array("selectNama","Nama Barang"),
		);
		$AcsDsc1 = cekPOST("AcsDsc1")==1? 'checked':'';
		$odr1 = cekPOST("odr1");
		$selThnHapus = $odr1 == "1" ? " selected " :  ""; //selected
		$fmcr_data = cekPOST("fmcr_data");
		$fmPILCARIvalue = cekPOST("fmPILCARIvalue");
		$opt1 = 
			"<option value=''>--</option>
			<option $selThnHapus value='1'>Thn. Pindah</option>";
		$ListOrderBy = " 
			Urutkan berdasar : 
				<select name=odr1>$opt1</select><input $AcsDsc1 type=checkbox name=AcsDsc1 value='1'>Desc. 		
			";
		//filter ------------------------------------------------
		$SSPg = !empty($_GET[SSPg])? $_GET['SSPg']: $_POST['fmSSPg'];
		switch($SSPg){
			case '04': $KondisiKib =" where f='01'"; break;
			case '05': $KondisiKib =" where f='02'"; break;
			case '06': $KondisiKib =" where f='03'"; break;
			case '07': $KondisiKib =" where f='04'"; break;
			case '08': $KondisiKib =" where f='05'"; break;
			case '09': $KondisiKib =" where f='06'"; break;	
			case '10': $KondisiKib =" where f='07'"; break;	
			default : $KondisiKib =''; break; 
		}	
		$bentuk = $_REQUEST['bentuk'];
		$TampilOpt =	
			"<input type='hidden' id='bentuk' name='bentuk' value='$bentuk'>".
			"<table class='adminform' width=\"100%\" height=\"100%\" style='margin:4 0 0 0'>
			<tr valign=\"middle\">  <td> 
				".cmb2D_v2('fmcr_data',$fmcr_data,$ArrCaridata,'','--- Cari Data ---').
" <input type=text name='fmPILCARIvalue' value='$fmPILCARIvalue' size='30'>
&nbsp;&nbsp ID Barang :			
				<input type=text name='fmIDBARANGCARI' value='".$_POST['fmIDBARANGCARI']."' size='7'>&nbsp
				<input type=button value='Cari' onclick=\"adminForm.target='';adminForm.submit()\">
			</td></tr>
			</table>".
			"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin:4 0 0 0'>
				<tr valign=\"top\"> 		
					<td> 
					&nbsp;&nbsp Tampilkan : ".		
					comboBySQL2('fmFiltThnHapus', $_POST['fmFiltThnHapus'],
						'select year(tgl_pemindahtanganan)as thn from v1_pindahtangan 
						group by thn desc',
						'thn', 'thn', 'Semua Tahun Pindah'
					).
					"&nbsp;&nbsp".
					comboBySQL2('fmFiltThnBuku', $_POST['fmFiltThnBuku'],
						"select year(tgl_buku)as thnbuku from v1_pindahtangan $KondisiKib  group by thnbuku desc",
						'thnbuku', 'thnbuku', 'Semua Thn. Buku'
					).
					"&nbsp;&nbsp".
					$ListOrderBy.
					"<input type=button onClick=\"adminForm.action='';adminForm.target='_self';adminForm.submit();\" value='Tampilkan'>
					</td>
				</tr>
			</table>";
		
		return array('TampilOpt'=>$TampilOpt);
		
	
	}
	
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		/*require $this-> :
			MaxFlush, TblStyle,  ColStyle, KeyFields, Prefix, FieldSum, SumValue,
			genDaftarHeader(), setDaftar_query(), setDaftar_before_getrow(), 
			setKolomData(), setDaftar_after_getrow(), genSumHal(), genRowSum(),
		*/
		$cek = '';
		//$aqry2 = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;			
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class=$TblStyle border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		
		//$aqry2 = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry;
		$qry = mysql_query($aqry);
		while ( $isi=mysql_fetch_array($qry)){
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox = //$Cetak? '' : 
				"<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
			
			
			//sum halaman
			for ($i=0; $i< sizeof($this->FieldSum) ; $i++) {
				$this->SumValue[$i] += $isi[$this->FieldSum[$i]];
			}
			
			//---------------------------
			$rowatr_ = $RowAtr." valign='top' id='$cb' value='".$isi['Id']."'";
			$bef= $this->setDaftar_before_getrow(
					$no,$isi,$Mode, $TampilCheckBox,  
					$rowatr_,
					$ColStyle
					);
			$ListData .= $bef['ListData'];
			$no = $bef['no'];
			//get row
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);			
			$list_row = genTableRow(
				$Koloms, 
				$rowatr_,
				$ColStyle
			);		
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi);
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}
		}
		
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
			//$SumHal = $this->genSumHal($Kondisi); 			
		}
			$SumHal = $this->genSumHal($Kondisi); 			
		
		$ContentSum = $this->genRowSum($ColStyle, $Mode, $SumHal['sum']);
		/*$TampilTotalHalRp = number_format($TotalHalRp,2, ',', '.');		
		$TotalColSpan = $this->FieldSum_Cp1[$Mode-1];//$Mode ==1 ? 5 : 4;
		$ContentTotalHal =
			"<tr>
				<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total per Halaman</td>
				<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>
				<td class='$ColStyle' colspan='4'></td>
			</tr>" ;
			
		$ContentTotal = 
				"<tr>
					<td class='$ColStyle' colspan='$TotalColSpan' align='center'><b>Total</td>
					<td class='$ColStyle' align='right'><b><div  id='cntDaftarTotal'>".$SumHal['sum']."</div></td>
					<td class='$ColStyle' colspan='4'></td>
				</tr>" ;
		
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){
			$ContentTotalHal='';			
		}
		$ContentSum=$ContentTotalHal.$ContentTotal;
		*/
		
		$ListData .= 
				//$ContentTotalHal.$ContentTotal.
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>";
					
		return $ListData;
		//return array('list'=>$ListData, 'cek'=>$cek);
	}
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main;
		global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		//$SSPg = $_GET['SSPg'];
		$SSPg = !empty($_GET[SSPg])? $_GET['SSPg']: $_POST['fmSSPg'];
		
		/*$KondisiKIB = "	where a1= '{$isi['a1']}' and a = '{$isi['a']}' and 	c = '{$isi['c']}' and d = '{$isi['d']}' and e = '{$isi['e']}' and 
				f = '{$isi['f']}' and g = '{$isi['g']}' and h = '{$isi['h']}' and i = '{$isi['i']}' and j = '{$isi['j']}' and 
				tahun = '{$isi['tahun']}' and noreg = '{$isi['noreg']}'  ";*/
		$KondisiKIB = "	where idbi = '".$isi['id_bukuinduk']."'";
		//echo $KondisiKIB;
		if($SSPg=='03' || $SSPg==''){
			Penatausahaan_BIGetKib($isi['f'], $KondisiKIB );
		}else{
			Penatausahaan_BIGetKib_hapus($isi['f'], $KondisiKIB );	
		}
		
		$namapengguna = '';
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_skpd where c='{$isi['c']}' and d='00' and e='00'"
		));
		$namapengguna .= $get['nm_skpd'].' - ';
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_skpd where c='{$isi['c']}' and d='{$isi['d']}' and e='00'"
		));
		$namapengguna .= $get['nm_skpd'].' - ';
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_skpd where c='{$isi['c']}' and d='{$isi['d']}' and e='{$isi['e']}' and e1='$kdSubUnit0'"
		));
		$namapengguna .= $get['nm_skpd'];
		$get = mysql_fetch_array(mysql_query(
			"select * from ref_skpd where c='{$isi['c']}' and d='{$isi['d']}' and e='{$isi['e']}' and e1='{$isi['e1']}'"
		));
		$namapengguna .= $get['nm_skpd'];
		$nilai_buku = getNilaiBuku($isi['id_bukuinduk'],$isi['tgl_pemindahtanganan'],0);
		$nilai_susut = getAkumPenyusutan($isi['id_bukuinduk'],$isi['tgl_pemindahtanganan']);
			
		$tampilMerk = $ISI5;
		$tampilSert = $ISI6;		
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array(" align='center' ", "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}/<br>".$isi['id_bukuinduk']);
		$Koloms[] = array(" align='center' ", $isi['noreg'] );
		$Koloms[] = array(" align='left' ", $isi['nm_barang'] );
		$Koloms[] = array(" align='left' ", $tampilMerk );
		$Koloms[] = array(" align='left' ", $tampilSert );		
		$Koloms[] = array(" align='center' ", $isi['thn_perolehan'] );
		$Koloms[] = array(" align='center' ", $Main->KondisiBarang[$isi['kondisi']-1][1] );
		$Koloms[] = array(" align='right' ", number_format($nilai_buku,2,'.',',' ) );
		$Koloms[] = array(" align='right' ", number_format($nilai_susut,2,'.',',' ) );
		//$Koloms[] = array(" align='center' ", $Main->BentukPemindahtanganan[$isi['bentuk_pemindahtanganan']-1][1] );
		$Koloms[] = array(" align='left' ", $isi['kepada_nama'] );
		$Koloms[] = array(" align='left' ", $isi['kepada_alamat']);
		$Koloms[] = array(" align='left' ", $isi['uraian'] );
		$Koloms[] = array(" align='left' ", $isi['ket'].'<br>'.TglInd($isi['tgl_pemindahtanganan']) );
		$Koloms[] = array(" align='left' ", $isi['nosk'].'<br>'. TglInd($isi['tglsk']) );
		$Koloms[] = array(" align='right' ", number_format($isi['penilaian'] ,2,'.',',' ) );
		$Koloms[] = array(" align='left' ", $namapengguna );
		
		
		
		//return 'tes';
		return $Koloms;
	}
	
	function genDaftarInitial(){
		$Opsi = $this->getDaftarOpsi($Mode=1);
		$vOpsi = $this->genDaftarOpsi();
		$vsumhal = $this->genSumHal($Opsi['Kondisi']);
			/*"<tr>
			<td colspan=16 align=center>
			".Halaman($jmlDataHPS,$Main->PagePerHal,"HalHPS","?Pg=$Pg&SPg=$SPg&SSPg=$SSPg")."
			</td>
			</tr>";*/
		return
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".									
				$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".
				$vsumhal['hal'].
				//"<input type='hidden' id='$this->elCurrPage' name='$this->elCurrPage' value='1'>".
			"</div>";
	}
	
	function setCetak_Header(){
		global $Main;
		setWilSKPD();
		$subpagetitle = 'Buku Inventaris';
		$SSPg = !empty($_GET[SSPg])? $_GET['SSPg']: $_POST['fmSSPg'];
		switch($SSPg){
			case '03': break;
			case '04': $subpagetitle ='KIB A'; break;
			case '05': $subpagetitle ='KIB B'; break;
			case '06': $subpagetitle ='KIB C'; break;
			case '07': $subpagetitle ='KIB D'; break;
			case '08': $subpagetitle ='KIB E'; break;
			case '09': $subpagetitle ='KIB F'; break;	
			case '10': $subpagetitle ='ATB'; break;	
		}
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>PEMINDAHTANGANAN BARANG MILIK DAERAH <br>$subpagetitle</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD()."</td>
				</tr>
			</table><br>";
	}
	
}
$PindahTangan = new PindahTanganObj();

function Pindahtangan_CekdataCutoff($mode='insert',$id='',$tgl='',$idbi=''){
global $Main;

$usrlevel=$Main->UserLevel;

// $usrlevel='0';
$errmsg='';
if ($usrlevel!='1'){
	

$tglcutoff=$Main->TAHUN_CUTOFF."-12-31";

switch ($mode){
	

case 'insert':{
	if ($tgl<$tglcutoff) $errmsg="Tgl. Pemindahtanganan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.")";

		 	
	break;
}
case 'edit':{
//	if ($tgl<$tglcutoff) $errmsg="Data dengan tgl. penghapusan lebih kecil dari tgl. ".$tglcutoff; 
			//cek tanggal buku
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from pemindahtanganan where id='$id'"));
				$tgl=$datax['tgl_pemindahtanganan'];	
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. pemindahtanganan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat diedit";
	}
	
	break;
}
case 'hapus':{
	if ($errmsg==''){
			$datax = mysql_fetch_array(mysql_query(
				"select * from pemindahtanganan where id='$id'"));	
				$tgl=$datax['tgl_pemindahtanganan'];	
			if ($tgl<=$tglcutoff)
				$errmsg="Data dengan tgl. pemindahtanganan(".$tgl.") lebih kecil dari tgl. cut off (".$tglcutoff.") tidak dapat dihapus";
	}
	
	break;
}	
}

}
return $errmsg;

}
?>