<?php

class GantiRugiObj extends DaftarObj{	
	var $Prefix = 'Gantirugi';
	var $elCurrPage="HalDefault";
	var $TblName = 'v1_gantirugi'; //daftar
	var $TblName_Hapus = 'gantirugi';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id'); //daftar/hapus
	var $FieldSum = array();
	var $SumValue = array();
	var $FieldSum_Cp1 = array( );//berdasar mode
	var $FieldSum_Cp2 = array( );	
	var $checkbox_rowspan = 1;
	//form
	var $form_width = 650;
	var $form_height = 300;		
	//cetak 
	var $Cetak_WIDTH = '30cm';
	
	
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
			<!--<th class=\"th01\" rowspan='2' >Bentuk <br> Pemindah tanganan</th>	-->
			<th class=\"th02\" colspan='2'>Kepada</th>
			<th class=\"th01\" rowspan='2' width='150'>Uraian</th>	
			<th class=\"th01\" rowspan='2'>Keterangan/<br>Tgl. Ganti Rugi</th>	
			</tr>
			<tr>
			$tampilNoHeader
			<th class=\"th01\" width='100'>Kode <br>Barang</th>
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
		switch($SSPg){
			case '04': $arrKondisi[] =" f='01'"; break;
			case '05': $arrKondisi[] =" f='02'"; break;
			case '06': $arrKondisi[] =" f='03'"; break;
			case '07': $arrKondisi[] =" f='04'"; break;
			case '08': $arrKondisi[] =" f='05'"; break;
			case '09': $arrKondisi[] =" f='06'"; break;				
		}		
		if (!empty($_POST['fmBARANGCARIHPS'])) $arrKondisi[] = " nm_barang like '%".$_POST['fmBARANGCARIHPS']."%'";
		if (!empty($_POST['fmFiltThnBuku'])) $arrKondisi[] = " year(tgl_buku) = '".$_POST['fmFiltThnBuku']."'";
		if (!empty($_POST['fmFiltThnHapus'])) $arrKondisi[] = " year(tgl_gantirugi) = '".$_POST['fmFiltThnHapus']."'";
		//setWilSKPD();	
		$fmUNIT = $_POST['fmUNIT'];
		$fmSKPD = $_POST['fmSKPD'];
		$fmSUBUNIT = $_POST['fmSUBUNIT'];
		$fmSEKSI = $_POST['fmSEKSI'];
		if (!empty($fmSKPD) && $fmSKPD!='00' ) $arrKondisi[] = " c = '".$fmSKPD."'";
		if (!empty($fmUNIT) && $fmUNIT!='00' ) $arrKondisi[] = " d = '".$fmUNIT."'";
		if (!empty($fmSUBUNIT) && $fmSUBUNIT!='00' ) $arrKondisi[] = " e = '".$fmSUBUNIT."'";
		if (!empty($fmSEKSI) && $fmSEKSI!='00' && $fmSEKSI!='000' ) $arrKondisi[] = " e1 = '".$fmSEKSI."'";
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
			case '1': $OrderArr[] =  " year(tgl_gantirugi) $Asc1 "; break;			
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
		$AcsDsc1 = cekPOST("AcsDsc1")==1? 'checked':'';
		$odr1 = cekPOST("odr1");
		$selThnHapus = $odr1 == "1" ? " selected " :  ""; //selected
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
			case 'kibg': $KondisiKib =" where f='07'"; break;	
			default : $KondisiKib =''; break; 
		}	
		
		$TampilOpt =
			"<table class='adminform' width=\"100%\" height=\"100%\" style='margin:4 0 0 0'>
			<tr valign=\"middle\">  <td> 
				&nbsp;&nbsp Nama Barang :			
				<input type=text name='fmBARANGCARIHPS' value='".$_POST['fmBARANGCARIHPS']."'>&nbsp
				<input type=button value='Cari' onclick=\"adminForm.target='';adminForm.submit()\">
			</td></tr>
			</table>".
			"<table width=\"100%\" height=\"100%\" class=\"adminform\" style='margin:4 0 0 0'>
				<tr valign=\"top\"> 		
					<td> 
					&nbsp;&nbsp Tampilkan : ".		
					comboBySQL2('fmFiltThnHapus', $_POST['fmFiltThnHapus'],
						'select year(tgl_gantirugi)as thn from v1_gantirugi 
						group by thn desc',
						'thn', 'thn', 'Semua Tahun Tuntutan'
					).
					"&nbsp;&nbsp".
					comboBySQL2('fmFiltThnBuku', $_POST['fmFiltThnBuku'],
						"select year(tgl_buku)as thnbuku from v1_gantirugi $KondisiKib  group by thnbuku desc",
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
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Main;
		global $ISI5, $ISI6, $ISI7, $ISI10, $ISI12, $ISI15;
		
		$SSPg = !empty($_GET[SSPg])? $_GET['SSPg']: $_POST['fmSSPg'];		
		$KondisiKIB = "	where idbi = '".$isi['id_bukuinduk']."'";
		if($SSPg=='03' || $SSPg==''){
			Penatausahaan_BIGetKib($isi['f'], $KondisiKIB );
		}else{
			Penatausahaan_BIGetKib_hapus($isi['f'], $KondisiKIB );	
		}
		
		$tampilMerk = $ISI5;
		$tampilSert = $ISI6;			
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array(" align='center' ", "{$isi['f']}.{$isi['g']}.{$isi['h']}.{$isi['i']}.{$isi['j']}");
		$Koloms[] = array(" align='center' ", $isi['noreg'] );
		$Koloms[] = array(" align='left' ", $isi['nm_barang'] );
		$Koloms[] = array(" align='left' ", $tampilMerk );
		$Koloms[] = array(" align='left' ", $tampilSert );		
		$Koloms[] = array(" align='center' ", $isi['thn_perolehan'] );
		$Koloms[] = array(" align='center' ", $Main->KondisiBarang[$isi['kondisi']-1][1] );
		$Koloms[] = array(" align='right' ", number_format($isi['jml_harga'] ,2,'.',',' ) );		
		$Koloms[] = array(" align='left' ", $isi['kepada_nama'] );
		$Koloms[] = array(" align='left' ", $isi['kepada_alamat'] );
		$Koloms[] = array(" align='left' ", $isi['uraian'] );
		$Koloms[] = array(" align='left' ", $isi['ket'].'<br>'.TglInd($isi['tgl_gantirugi']) );
		
		return $Koloms;
	}
	
	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		/*require $this-> :
			MaxFlush, TblStyle,  ColStyle, KeyFields, Prefix, FieldSum, SumValue,
			genDaftarHeader(), setDaftar_query(), setDaftar_before_getrow(), 
			setKolomData(), setDaftar_after_getrow(), genSumHal(), genRowSum(),
		*/
		$cek = '';
					
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
		
		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
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
			$SumHal = $this->genSumHal($Kondisi); 			
		}
		
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
					
		// return $ListData;
		return array('list'=>$ListData, 'cek'=>$cek);
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
			case 'kibg': $subpagetitle ='ASET TAK BERWUJUD'; break;	
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
	
	function Hapus(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 //get data -----------------
	 $cbid = $_REQUEST[$this->Prefix.'_cb']; $cek .= $cbid;
 	 
	 for($i = 0; $i<count($cbid); $i++)	{
			//Validasi Hapus
			$kueri="select * from $this->TblName_Hapus 
					where id = '".$cbid[$i]."' "; //echo "$kueri";
			$data=mysql_fetch_array(mysql_query($kueri));
			if($errmsg=='' && sudahClosing($data['tgl_gantirugi'],$data['c'],$data['d']))$err = "Id ".$cbid[$i].", Tanggal Sudah Closing!";
			//cek sudah ada penyusutan / tdk untuk data baru			
			$oldthn_gantirugi = substr($data['tgl_gantirugi'],0,4);
			$query_susut = "select count(*)as jml_susut from penyusutan where idbi='".$data['id_bukuinduk']."' and tahun>='$oldthn_gantirugi'";
			$get_susut = mysql_fetch_array(mysql_query($query_susut));
			/*if($get_susut['jml_susut']>0){
				$errmsg="Id ".$cbid[$i].", Sudah ada penyusutan !";
			}*/
			
			if($err ==''){
				$aqry = "DELETE FROM $this->TblName_Hapus WHERE id='".$cbid[$i]."'";	$cek .= $aqry;	
				$qry = mysql_query($aqry);
				if($qry == FALSE) $err='Gagal Hapus '.mysql_error();
				if ($err != '') break;
			}else{
				break;
			}			
		}
		
					
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function Hapus_Data_After($id){//id -> multi id with space delimiter
		global $Main, $HTTP_COOKIE_VARS;
		$UID =  $HTTP_COOKIE_VARS['coID'];
		$errmsg = ''; $content=''; $cek='';
		//kondisi key -----------------
		$KeyValue = explode(' ',$id);
		$arrKondisi = array();
		for($i=0;$i<sizeof($this->KeyFields);$i++){
			$arrKondisi[] = $this->KeyFields[$i]."='".$KeyValue[$i]."'";
		}
		$Kondisi = join(' and ',$arrKondisi);
		if ($Kondisi !='')$Kondisi = ' Where '.$Kondisi;
		
		//action --------------------
		if($Main->MODUL_JURNAL) $ins= jurnalGantiRugi($id,$UID,3);
		
		return $errmsg;
		//return array('err'=>$errmsg, 'content'=>$content, 'cek'=>$cek);
	}
	
	function set_ajxproses_other($idprs, $Opt){
		$ErrNo=0; $ErrMsg = ''; $content='tes'; $json=FALSE;
		global $Main, $HTTP_COOKIE_VARS;
		$UID =  $HTTP_COOKIE_VARS['coID'];
		switch ($idprs){
			case 'formbaru':{		
				$cbid= $_POST['cidBI'];
				$this->form_fields = array(
					'tgl_gantirugi' => array( 'label'=>'Tgl. Tuntutan', 'value'=>'', 'type'=>'date','labelWidth'=>150 ),					
					'kepada' => array( 'label'=>'Kepada', 'value'=>'', 'type'=>'', 'ttkDua'=>' ' ),
					'kepada_nama' => array( 'label'=>'&nbsp;&nbsp;Nama', 'value'=>'', 'type'=>'text', 'param'=>'size=63', 'valign'=>'middle' ),						
					'kepada_alamat' => array( 'label'=>'&nbsp;&nbsp;Alamat', 'value'=>'', 'type'=>'memo','param'=>'cols=60' ),
					'uraian' => array( 'label'=>'Uraian', 'value'=>'', 'type'=>'memo','param'=>'cols=60' ),
					'ket' => array( 'label'=>'Keterangan', 'value'=>'', 'type'=>'memo','param'=>'cols=60' ),
				);		
				$this->form_caption = 'Tuntutan Ganti Rugi - Baru';				
				$this->form_idplh = '';
				$this->form_fmST = '';
				$this->form_menubawah =
					"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanBaru()' >".
					"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >".
					"<input type=hidden value='".$cbid[0]."' name='idbi' id='idbi'>";
				$content= $this->genForm();
				//echo 'tes';
				$json=TRUE;	
				break;		
			}
			
			case 'formedit':{
				$cbid= $_POST[$this->Prefix.'_cb'];						
				$sqry ="select * from gantirugi where id ='".$cbid[0]."'"; //$ErrMsg = $sqry;
				$get = mysql_fetch_array(mysql_query($sqry));
				$this->form_fields = array(
					'tgl_gantirugi' => array( 'label'=>'Tgl. Tuntutan', 'value'=>$get['tgl_gantirugi'], 'type'=>'date','labelWidth'=>150 ),					
					'kepada' => array( 'label'=>'Kepada', 'value'=>'', 'type'=>'', 'ttkDua'=>' ' ),
					'kepada_nama' => array( 'label'=>'&nbsp;&nbsp;Nama', 'value'=>$get['kepada_nama'], 'type'=>'text', 'param'=>'size=63', 'valign'=>'middle'  ),						
					'kepada_alamat' => array( 'label'=>'&nbsp;&nbsp;Alamat', 'value'=>$get['kepada_alamat'], 'type'=>'memo','param'=>'cols=60' ),
					'uraian' => array( 'label'=>'Uraian', 'value'=>$get['uraian'], 'type'=>'memo','param'=>'cols=60' ),
					'ket' => array( 'label'=>'Keterangan', 'value'=>$get['ket'], 'type'=>'memo','param'=>'cols=60' ),
				);		
				$this->form_caption = 'Tuntutan Ganti Rugi - Edit';				
				$this->form_idplh = $cbid[0];
				$this->form_fmST = 1;
				$this->form_menubawah =
					"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
					"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >".
					"<input type=hidden value='".$get['id_bukuinduk']."' name='idbi' id='idbi'>";
				$content= $this->genForm();
				//echo 'tes';
				$json=TRUE;	
				break;		
			}
			case 'simpan':{
				//get data lama ---------------------------
				
				$ID = $_POST[$this->Prefix.'_idplh'];
				$old = mysql_fetch_array( mysql_query(
					"select * from gantirugi where id = '$ID'"
				));				
				
				//validasi --------------------------------		
				if($ErrMsg =='' && cektanggal($_POST['tgl_gantirugi'])==FALSE) $ErrMsg = "Tanggal Tuntutan Salah!";	
				if($ErrMsg =='' && compareTanggal($_POST['tgl_gantirugi'], date('Y-m-d'))==2  ) $ErrMsg = 'Tanggal Tuntutan tidak lebih besar dari Hari ini!';				
				
				if ($ErrMsg==''){
					if ($_POST[$this->Prefix.'_fmST']==0){//baru
						$idbi = $_POST['idbi'];
						$bi =  mysql_fetch_array( mysql_query(
							"select * from buku_induk where id = '$idbi'"
						));	
						if($ErrMsg=='' && $bi['thn_perolehan']< 1900) $ErrMsg = "Tahun Perolehan tidak lebih kecil dari 1900!";
						if ($bi['status_barang'] == 3) $ErrMsg = 'Barang sudah di Penghapusan!';
						if ($bi['status_barang'] == 4) $ErrMsg = 'Barang sudah di Pemindah Tanganan!';
						if ($bi['status_barang'] == 5) $ErrMsg = 'Barang sudah di Tuntutan Ganti Rugi!';
						$staset=$bi['staset'];
				
				
					}else{//edit
						$old = mysql_fetch_array(mysql_query("select * from gantirugi where id='{$_POST[$this->Prefix.'_idplh']}'" ));
						$idbi = $old['id_bukuinduk'];
						if ($idbi == '') $ErrMsg = "Data dengan id '{$_POST[$this->Prefix.'_idplh']}' tidak ada!";
					}							
					$pelihara = mysql_fetch_array( mysql_query ("select max(tgl_pemeliharaan) as maxtgl from pemeliharaan where id_bukuinduk = '$idbi'"	));
					if ($ErrMsg =='' && (compareTanggal($_POST['tgl_gantirugi'], $pelihara['maxtgl'])==0 || 	compareTanggal($_POST['tgl_gantirugi'], $pelihara['maxtgl'])==1  )  ) 
						$ErrMsg = 'Tanggal Tuntutan harus lebih besar dari Tanggal Pemeliharaan!';
					$pengaman = mysql_fetch_array( mysql_query ("select max(tgl_pengamanan) as maxtgl from pengamanan where id_bukuinduk = '$idbi'"	));
					if ($ErrMsg =='' && (compareTanggal($_POST['tgl_gantirugi'], $pengaman['maxtgl'])==0 || compareTanggal($_POST['tgl_gantirugi'], $pengaman['maxtgl'])==1 ) ) 
						$ErrMsg = 'Tanggal Tuntutan harus lebih besar dari Tanggal Pengamanan!';
					$pemanfaat = mysql_fetch_array( mysql_query ("select max(tgl_pemanfaatan) as maxtgl from pemanfaatan where id_bukuinduk = '$idbi'"	));
					if ($ErrMsg =='' && (compareTanggal($_POST['tgl_gantirugi'], $pemanfaat['maxtgl'])==0 || compareTanggal($_POST['tgl_gantirugi'], $pemanfaat['maxtgl'])==1 )  ) 
						$ErrMsg = 'Tanggal Tuntutan harus lebih besar dari Tanggal Pemanfaatan!';						
					$penatausaha = mysql_fetch_array( mysql_query ("select tgl_buku from buku_induk where id = '$idbi'"	));
					if ($ErrMsg=='' &&  compareTanggal($_POST['tgl_gantirugi'], $penatausaha['tgl_buku'] )==0 ) 
						$ErrMsg = 'Tanggal Tuntutan tidak lebih kecil dari Tanggal Buku!'; 	
						
					$hps = mysql_fetch_array(mysql_query("select max(tgl_penghapusan) as maxtgl from penghapusan_sebagian where id_bukuinduk ='$idbi'" ));
					if ($ErrMsg=='' && compareTanggal($hps['maxtgl'] , $_POST['tgl_gantirugi'] )==2  ) $ErrMsg = 'Tanggal Tuntutan tidak lebih kecil dari Tanggal Penghapusan Sebagian!';
					
				}	
				
				$fmTANGGALgantirugi  = $_POST['tgl_gantirugi'];
				$thn_gantirugi = substr($fmTANGGALgantirugi,0,4);
				$query_susut = "select count(*)as jml_susut from penyusutan where idbi='$idbi' and tahun>='$thn_gantirugi'";
				$get_susut = mysql_fetch_array(mysql_query($query_susut));
				$get_cd = mysql_fetch_array(mysql_query("select c,d from buku_induk where id='$idbi'"));
				if ($_POST[$this->Prefix.'_fmST']==0){
					//cek sudah ada penyusutan / tdk untuk data baru			
					/*if($get_susut['jml_susut']>0){
						$ErrMsg='Sudah ada penyusutan, data tidak bisa di masukan !';
					}*/
					//cek sudah ada Closing untuk data baru
					if(sudahClosing($fmTANGGALgantirugi,$get_cd['c'],$get_cd['d'])){
						$ErrMsg = 'Tanggal sudah Closing !';
					}
				}else{
					//cek sudah ada penyusutan / tdk untuk data edit	
					$idplh = $_POST[$this->Prefix.'_idplh'];
					$old_gantirugi = mysql_fetch_array(mysql_query("select * from gantirugi where id='$idplh'"));
					$oldthn_gantirugi = substr($old_gantirugi['tgl_gantirugi'],0,4);
					/*if($get_susut['jml_susut']>0 && $oldthn_gantirugi!=$thn_gantirugi){//jika ada penyusutan dan thn_pelihara lama berubah
						$ErrMsg='Sudah ada penyusutan, data tidak bisa dirubah !';
					}*/
					//cek sudah ada Closing untuk data edit
					if(sudahClosing($fmTANGGALgantirugi,$get_cd['c'],$get_cd['d']) && $oldthn_gantirugi!=$thn_gantirugi){//jika sudah Closing dan thn_pelihara lama berubah
						$ErrMsg = 'Tanggal sudah Closing !';
					}
				}	 
						
				//simpan ----------------------------------
				if ($ErrMsg == ''){
					if ($_POST[$this->Prefix.'_fmST']==0){
					 	$fieldstaset=",staset";
					 	$nilaistaset=",".$staset;	
					} else {
					 	$fieldstaset="";
					 	$nilaistaset="";							
					}
					
					$get = mysql_fetch_array(mysql_query("select * from buku_induk where id ='".$_POST['idbi']."'"));
					$idbi_awal= $get['idawal'];
					
					$get = $this->simpan(
						$_POST[$this->Prefix.'_fmST'], 'gantirugi', 
						'id', $_POST[$this->Prefix.'_idplh'], 
						'id_bukuinduk,tgl_gantirugi,kepada_nama,kepada_alamat,uraian,ket,idbi_awal'.$fieldstaset,
						$_POST['idbi'].','.$_POST['tgl_gantirugi'].','.
						$_POST['kepada_nama'].','.$_POST['kepada_alamat'].','.
						$_POST['uraian'].','.$_POST['ket'].','.$idbi_awal.$nilaistaset 
					);					
					$ErrMsg = $get['err'];
					$newID = mysql_insert_id();
					if ($ErrMsg=='') {
						$content = $get['content'];
						if($_POST[$this->Prefix.'_fmST']==0){//baru
							if($Main->MODUL_JURNAL) $ins= jurnalGantiRugi($newID,$UID,1);	
						}else{
							if($Main->MODUL_JURNAL) $ins= jurnalGantiRugi( $_POST[$this->Prefix.'_idplh'],$UID,2);	
						}
					}
					
				}
				$json=TRUE;	
				break;				
			}	
		}
		return array('ErrNo'=>$ErrNo, 'ErrMsg'=>$ErrMsg, 'content'=> $content, 'json'=>$json );
	}
	
	function setPage(){
		global $Main;
		
		$fmWIL = cekPOST("fmWIL");
		$fmSKPD = cekPOST("fmSKPD");
		$fmUNIT = cekPOST("fmUNIT");
		$fmSUBUNIT = cekPOST("fmSUBUNIT");
		$fmSEKSI = cekPOST("fmSEKSI");
		$fmTAHUNANGGARAN =cekPOST("fmTAHUNANGGARAN",$fmTahunPerolehan);
		
		if (empty($disModul09) && empty($ridModul09)){
			$toolbar_atas_edit = 
				"<!--<td>".PanelIcon1("javascript:adminForm.action='?Pg=$Pg&SPg=$SPg#FORMENTRY';adminForm.Act.value='Add';adminForm.submit()","new_f2.png","Tambah")."</td>-->
				<td>".PanelIcon1("javascript:".$this->Prefix.".Edit()","edit_f2.png","Ubah")."</td>
				<td>".PanelIcon1("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus")."</td>";				
		}	
		$toolbar_atas = 
			"<div style='float:right;'>					
			<table ><tr>
			$toolbar_atas_edit
			<td>".PanelIcon1("javascript:".$this->Prefix.".cetakAll()","print_f2.png","Cetak")."</td>					
			</tr></table>			
			</div>";
		//title -----------------------------------------------------------------------------
		$subpagetitle = 'Buku Inventaris';
		switch($_GET['SSPg']){
			case '03': break;
			case '04': $subpagetitle ='KIB A'; break;
			case '05': $subpagetitle ='KIB B'; break;
			case '06': $subpagetitle ='KIB C'; break;
			case '07': $subpagetitle ='KIB D'; break;
			case '08': $subpagetitle ='KIB E'; break;
			case '09': $subpagetitle ='KIB F'; break;	
			case 'kibg': $subpagetitle ='ASET TAK BERWUJUD'; break;	
		}		
		$Page_Title = 
			"<table class=\"adminheading\">
			<tr>
  			<th height=\"47\" class=\"user\" ><div style='padding:0 0 0 8;'>
				Daftar Tuntutan Ganti Rugi Barang Milik Daerah <br> $subpagetitle
			</div></th>
  			<th>$toolbar_atas</th>
			</tr>
			</table>";
		//page --------------------------------------------------------------------------------
		$Page_Hidden = "
			<input type='hidden' name='fmTAHUNANGGARAN' value='$fmTAHUNANGGARAN'>
			<input type='hidden' name='fmTAHUNPEROLEHAN' value='$fmTAHUNPEROLEHAN'>
			<input type='hidden' name='fmWILSKPD' value='$fmWILSKPD'>
			<input type='hidden' name='fmIDBUKUINDUK' value='$fmIDBUKUINDUK'>
			<input type='hidden' name='fmSSPg' id='fmSSPg' value='".$_GET['SSPg']."'>
			<input type='hidden' name='Act'>
			<input type='hidden' name='Penghapusan_Baru' value='$Penghapusan_Baru'>	
			<input type=\"hidden\" name=\"fmID\" value=\"$fmID\" />
			<input type=\"hidden\" name=\"option\" value=\"com_users\" />
			<input type=\"hidden\" name=\"task\" value=\"\" />
			<input type=\"hidden\" name=\"boxchecked\" value=\"0\" />
			<input type=\"hidden\" name=\"hidemainmenu\" value=\"0\" />
			<!--<input type=text name='PageMode' value='$PageMode' >-->
			<input type=hidden name='ViewList' value='$ViewList' >
			<input type=hidden name='ViewEntry' value='$ViewEntry' >
			";
		$cek ='';
		$Main->Isi .= 
			"<script src='js/gantirugi.js' type='text/javascript'></script>".			
			"<form name=\"adminForm\" id=\"adminForm\" method=\"post\" action=\"?Pg=$Pg&SPg=$SPg\">		
				$script 
				<table width=\"100%\" id='tbljustify_content' >	<tr><td width=\"60%\" valign=\"top\">".
					$Page_Title.			
					"<table class='adminform'><tr><td> ".WilSKPD1b($fmSKPD, $fmUNIT, $fmSUBUNIT,'100',$fmSEKSI)."	</td></tr></table>".
					$this->genDaftarInitial().		
				"</td></tr></table>
				$Page_Hidden	
			</form>".
			$Info.
			"<script>Gantirugi.loading()</script>".
			$cek;
		
	}
	
}
$Gantirugi = new GantiRugiObj();
?>