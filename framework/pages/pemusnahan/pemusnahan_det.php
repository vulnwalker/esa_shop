<?php
class pemusnahan_detObj extends DaftarObj2{
	var $Prefix = 'pemusnahan_det';
	var $SHOW_CEK = TRUE;	
	var $TblName = 'pemusnahan_det';//'v1_rkb'
	var $TblName_Hapus = 'pemusnahan_det';
	var $TblName_Edit = 'pemusnahan_det';
	var $KeyFields = array('Id');
	var $FieldSum = array('jumlah_harga');
	var $fieldSum_lokasi = array(7);
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 6, 5, 5);
	var $FieldSum_Cp2 = array( 4, 4, 4);	
	var $totalCol = 11; //total kolom daftar
	var $FormName = 'pemusnahan_detForm';
	//var $pagePerHal = 7;
	//var $thnsensus_default = 2013;
	//var $periode_sensus = 5;// tahun	
	var $PageTitle = 'PEMUSNAHAN DETAIL';
	//var $PageIcon = 'images/pengadaan_ico.png';
	var $PageIcon = '';
	var $ico_width = '';
	var $ico_height = '';
	
	var $checkbox_rowspan = 2;
	var $fileNameExcel='pemusnahan.xls';
	var $Cetak_Judul = 'PEMUSNAHAN DETAIL';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $fmST = 0;
	var $idpilih = '';
	
	function setPage_OtherScript(){
		$scriptload = 

					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".	
			 "<script type='text/javascript' src='js/pemusnahan/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			 "<script type='text/javascript' src='js/master/ref_aset/refbarang.js' language='JavaScript' ></script>".	
			 "<script type='text/javascript' src='js/dkb.js' language='JavaScript' ></script>".
			$scriptload;
	}
	function setTitle(){
		return 'PEMUSNAHAN DETAIL';
		//return 'Rencana Kebutuhan Barang Milik D';
	}
	function setNavAtas(){
		return "";
			/*'<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr>
			<td class="menudottedline" width="60%" height="20" style="text-align:right"><b>
				<a style="color:blue;" href="pages.php?Pg=pemusnahan_ba" title="Berita Acara">Berita Acara</a>  |  
				<a  href="pages.php?Pg=pemusnahan" title="Pemusnahan">Pemusnahan</a>  |
				
				<a href="pages.php?Pg=pemusnahanrekap" title="Rekap">Rekap</a>  |  
				<a href="pages.php?Pg=pemusnahanrekap_skpd" title="Rekap (SKPD)">Rekap (SKPD)</a>  
				  &nbsp;&nbsp;&nbsp;
			</b></td>
			</tr>
			</table>';*/
	}
	function setMenuEdit(){		
		return
			"";
			/*"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru2()","new_f2.png","Baru",'DPBMD Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit2()","edit_f2.png","Edit", 'Edit DPBMD')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus DPBMD')."</td>";*/
			
			
	}
	
	function setKolomHeader($Mode=1, $Checkbox=''){
	 $NomorColSpan = $Mode==1? 2: 1;
	 $Pelihara_Menu=
			"<table class='' width='100%' cellspacing='0' cellpadding='0' border='0'><tr><td style='padding:0'>
			<div class='menuBar2' style='' >
			<ul>
			$Pelihara_Menu
			<!--<li><a  href='javascript:pemusnahan_det.Refresh()' title='Refresh Pemeliharaan' class='btrefresh'></a></li>-->
			<li><a href='javascript:pemusnahan_det.Hapus()' title='Hapus' class='btdel'></a></li>
			<!--<li><a href='javascript:pemusnahan_det.Edit2()' title='Edit' class='btedit'></a>-->
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'></li>
				</ul>
			</li>
			<li><a  href='javascript:pemusnahan_det.caribarang1()' title='Tambah' class='btadd'></a>
				<ul id='bgn_ulEntry'>
					<li style='width:470;top:-4;z-index:99;'>	</li>
				</ul>
			</li>
			<!--<li><a style='padding:2;width:55;color:white;font-size:11;' href='javascript:PeliharaRefresh.Refresh()' title='Refresh Pemeliharaan' class=''>[ Refresh ]</a></li>-->
			</ul>	
			<!--<a id='pemusnahan_det_jmldata' style='cursor:default;position:relative;left:2;top:2;color:gray;font-size:11;' title='Jumlah Pemeliharaan'>[ $jmlTampilPLH ]</a>
			-->
			</div>
			</td></tr></table>";
	$TampilCheckBox = $Cetak? '' : "<TH class=\"th01\" rowspan=2 style='width:30'>
									<input type=\"checkbox\" name=\"pemusnahan_det_toggle\" id=\"pemusnahan_det_toggle\" value=\"\" onclick=\"checkAll4(25,'pemusnahan_det_cb','pemusnahan_det_toggle','pemusnahan_det_jmlcek');\">";
	
	  $headerTable =
	 "<thead>
	 <tr>
	   <th colspan='11'>$Pelihara_Menu</th>
	  </tr>
	  <tr>
	   <th class='th01' width='20' rowspan=2>No.</th>
	 	<!--$Checkbox-->
		$TampilCheckBox		
	   <th class='th01'  rowspan=2 width=300>Kode Barang/ID Barang/<br>Nama Barang</th>
	   <th class='th01'  rowspan=2 width=200>No. Reg/Tahun</th>
	   <th class='th02'  colspan=2 width=400>Spesifikasi</th>
	   <th class='th01'  rowspan=2 width=200>Harga Perolehan</th>
	   <th class='th01'  rowspan=2 width=200>Harga Akumulasi Penyusutan</th>
	   <th class='th01'  rowspan=2 width=300>Unit Pengguna</th>
	   <th class='th01'  rowspan=2 width=100>Kondisi Barang</th>
	   <th class='th01'  rowspan=2 width=200>Keterangan</th>
	  </tr>
	  <tr>
	  	   <th class='th01' width=200>Merk/Type/Alamat</th>
		   <th class='th01' width=200>No. Sertifikat / No. Pabrik / No. Chasis / No. Mesin / No. Polisi</th>		   
	   </tr>
	   </thead>";
	
		return $headerTable;
	}
	
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
	 global $Ref;
	//get dinas		
	 $get_c = mysql_fetch_array(mysql_query("select * from v_bidang where c='".$isi['c']."'"));	
	 $get_d = mysql_fetch_array(mysql_query("select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."'"));	
	 $get_e = mysql_fetch_array(mysql_query("select * from v_unit where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."'"));
	 $get_e1 = mysql_fetch_array(mysql_query("select nm_skpd as nmseksi from ref_skpd where c='".$isi['c']."' and d='".$isi['d']."' and e='".$isi['e']."' and e1='".$isi['e1']."'"));		
			
	 $nama_skpd =$get_c['nmbidang'].'/<br>'.$get_d['nmopd'];
	 $nama_unit =$get_e['nmunit'].'/<br>'.$get_e1['nmseksi'];
	 $get_brg = mysql_fetch_array(mysql_query("select * from ref_barang where f='".$isi['f']."' and g='".$isi['g']."' and h='".$isi['h']."' and i='".$isi['i']."' and j='".$isi['j']."'"));
	 $bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='".$isi['id_bukuinduk']."'")) ;
		if($bi['f']=='01'){
			$aqry = "select * from kib_a where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['sertifikat_no'];
			$ukuran=$arrdet['luas']." m2";
		}
		if($bi['f']=='02'){
			$aqry = "select * from kib_b where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['merk'];
			$no_det=$arrdet['no_pabrik']."/".$arrdet['no_rangka']."/".$arrdet['no_mesin']."/".$arrdet['no_polisi'];
			$ukuran=$arrdet['ukuran']." ";
		}
		if($bi['f']=='03'){
			$aqry = "select * from kib_c where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['dokumen_no'];
			$ukuran=$Main->Bangunan[$arrdet['kondisi_bangunan']-1][1];
		}
		if($bi['f']=='04'){
			$aqry = "select * from kib_d where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['dokumen_no'];
			$ukuran=$Main->Bangunan[$arrdet['konstruksi']-1][1];
		}
		if($bi['f']=='05'){
			$aqry = "select * from kib_e where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['buku_judul']."/".$arrdet['buku_spesifikasi'];
			$no_det="";
			$ukuran=$arrdet['hewan_ukuran'];
		}
		if($bi['f']=='06'){
			$aqry = "select * from kib_f where 
				idbi='".$bi['id']."' ";
				$qry=mysql_query($aqry);			
				$arrdet=mysql_fetch_array($qry);
			$merk=$arrdet['alamat'];
			$no_det=$arrdet['dokumen_no'];
			$ukuran=$Main->Bangunan[$arrdet['bangunan']-1][1];
		}	 
		//1.baik, 2.kurang baik, 3.rusak berat
		if($isi['kondisi']=1){
			$kondisi = 'Baik';
		}elseif($isi['kondisi']=2){
			$kondisi = 'Kurang Baik';
		}elseif($isi['kondisi']=3){
			$kondisi = 'Rusak Berat';
		}else{
			$kondisi='';
		}
	 $Koloms = array();
	 $Koloms[] = array('align="center"', $no.'.' );
	  if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 $Koloms[] = array('', 
			$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j'].'/'.$isi['id_bukuinduk'].'/<br>'.$get_brg['nm_barang']				
		);
	 $Koloms[] = array("align='left'", $isi['noreg'].'/'.$isi['thn_perolehan']  );
	 $Koloms[] = array("align='left'", $merk) ;
	 $Koloms[] = array("align='left'", $no_det);
	 $Koloms[] = array("align='right'", number_format($isi['jumlah_harga'],2,',','.'));
	 $Koloms[] = array("align='right'", number_format($isi['nilai_susut'],2,',','.'));
	 $Koloms[] = array("align='left'", $nama_unit);
	 $Koloms[] = array("align='left'", $kondisi);
	 $Koloms[] = array("align='left'", $isi['ket']);
		 			
	 return $Koloms;
	}
	
	function setCekBox($cb, $KeyValueStr, $isi){
		return "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' stat='".$isi['stat']."'  onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');\" />";					
	}
	
	function cmbQueryBidang($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
     global $Ref,$Main,$HTTP_COOKIE_VARS;
			
	 $aqry="select * from ref_skpd where c!='00' and d='00'  GROUP by c";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDBidang='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['c'] ==  $value ? "selected" : "";
				if ($nmSKPDBidang=='' ) $nmSKPDBidang =  $value == $Hasil['c'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[c]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDBidang <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySKPD($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE) {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 
		$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d!='00' and e='00' GROUP by d";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDskpd='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['d'] ==  $value ? "selected" : "";
				if ($nmSKPDskpd=='' ) $nmSKPDskpd =  $value == $Hasil['d'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[d]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDskpd <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQueryUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('fmSKPDBidang')!=$vAtas? cekPOST('fmSKPDBidang'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('fmSKPDskpd')!=$vAtas? cekPOST('fmSKPDskpd'): $HTTP_COOKIE_VARS['cofmUNIT'];
	 }else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
	 }
		
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e!='00' and e1='000' GROUP by e";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function cmbQuerySubUnit($name='txtField', $value='', $query='', $param='', $Atas='Pilih', $vAtas='',$readonly=FALSE,$edit='') {
	 global $Ref,$Main,$HTTP_COOKIE_VARS;
	 if($edit==""){
	 	$fmSKPDBidang = cekPOST('c')!=$vAtas? cekPOST('c'): $HTTP_COOKIE_VARS['cofmSKPD'];
		$fmSKPDSkpd = cekPOST('d')!=$vAtas? cekPOST('d'): $HTTP_COOKIE_VARS['cofmUNIT'];
		$fmSKPDUnit = cekPOST('fmSKPDUnit_form')!=$vAtas? cekPOST('fmSKPDUnit_form'): $HTTP_COOKIE_VARS['cofmSUBUNIT'];
	}else{
	 	$xplode=explode('.',$edit);
		$fmSKPDBidang=$xplode[0];
		$fmSKPDSkpd=$xplode[1];
		$fmSKPDUnit=$xplode[2];
	 }
			
	 $aqry="select * from ref_skpd where c='$fmSKPDBidang' and d='$fmSKPDSkpd' and e='$fmSKPDUnit' and e1!='000' GROUP by e1";
	 $Input = "<option value='$vAtas'>$Atas</option>";
	 $Query = mysql_query($aqry);
	 $nmSKPDUnit='';
    	while ($Hasil = mysql_fetch_array($Query)) {
        	$Sel = $Hasil['e1'] ==  $value ? "selected" : "";
				if ($nmSKPDUnit=='' ) $nmSKPDUnit =  $value == $Hasil['e1'] ? $Hasil['nm_skpd'] : '';
			$Input .= "<option $Sel value='{$Hasil[e1]}'>{$Hasil[nm_skpd]}";
    	}
     $Input = $readonly == false ?
	 "<select $param name='$name' id='$name'> $Input</select>":
	 "$nmSKPDUnit <input type='hidden' name='$name' id='$name' value='". $value."' >";
     return $Input;
	}
	
	function genDaftarInitial(){
		global $HTTP_COOKIE_VARS;
		$vOpsi = $this->genDaftarOpsi();
		$fmThnAnggaran=  $_COOKIE['coThnAnggaran'];
		$fmSKPDBidang = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST('fmSKPDBidang');
		$fmSKPDskpd = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST('fmSKPDskpd');
		
		$divHal = "<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
							"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
						"</div>";
		switch($this->daftarMode){						
			case '1' :{ //detail horisontal
				$vdaftar = 
					"<table width='100%'><tr valign=top>
					<td style='width:$this->containWidth;'>".
						"<div id='{$this->Prefix}_cont_daftar' style='position:relative;width:$this->containWidth;overflow:auto' >"."</div>".
						$divHal.
					"</td>".
					"<td>".
						"<div id='{$this->Prefix}_cont_daftar_det' style=''>".$this->genTableDetail()."</div>".
					"</td>".
					"</tr></table>";
				break;
			}
			default :{
				$vdaftar = 
					"<div id='{$this->Prefix}_cont_daftar' style='position:relative;' >"."</div>".
					$divHal;					
				break;
			}
		}
		
		return		
			//$NavAtas.	
			//"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			//"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				"<input type='hidden' value='$fmThnAnggaran' id='fmThnAnggaran' name='fmThnAnggaran' >".
				"<input type='hidden' value='$fmSKPDBidang' id='fmSKPDBidang' name='fmSKPDBidang' >".
				"<input type='hidden' value='$fmSKPDskpd' id='fmSKPDskpd' name='fmSKPDskpd' >".
			"</div>".	
			//"<div style='position:relative'>".
			$vdaftar.
			//"</div>".
			'';
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main,$HTTP_COOKIE_VARS;
		$idp = $_REQUEST['idp'];		
			
		$TampilOpt =
			"<input type='hidden' value='".$idp."' name='idp' id='idp'>";
		return array('TampilOpt'=>$TampilOpt);
	}	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		
		//kondisi -----------------------------------
		$arrKondisi = array();		
		$idp = $_REQUEST['idp'];
		if(!empty($idp)) $arrKondisi[]= " refid_pemusnahan='$idp'";	
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');		
		
		$arrOrders = array();
		//$arrOrders[] = " a,b,c,d,e,nip ";
		switch($fmORDER1){
			case '1': $arrOrders[] = " tahun $Asc1 " ;break;
			case '2': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '3': $arrOrders[] = " concat(k,l,m,n,o) $Asc1 " ;break;
		}	
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		//limit --------------------------------------
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $Main->PagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	function getJmlDKB($tahun, $c, $d, $e,$e1, $f,$g,$h,$i,$j){
		$aqry = "select * from dkb where tahun='$tahun' and c='$c' and d='$d' and e='$e' and e1='$e1' and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' ";
		$get =  mysql_fetch_array(mysql_query($aqry));
		if($get['jml_barang']== NULL) $get['jml_barang']=0;
		return $get['jml_barang'];
	}
	
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST['fmSKPDBidang'];
		$dt['d'] = $_REQUEST['fmSKPDskpd'];
		$dt['tahun'] = $_COOKIE['coThnAnggaran'];
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setFormEdit(){		
		global $Main;
		$cek = ''; $err=''; $content='';// $json=FALSE;
		$form = '';
		
		//$err = $_REQUEST['rkbSkpdfmSKPD'];
		$cbid = $_POST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];		
		$this->form_fmST = 1;
		$form_name = $this->Prefix.'_form';
		
		$aqry = "select * from $this->TblName where Id='$this->form_idplh'";
		$dt = mysql_fetch_array(mysql_query($aqry));
		$fm = $this->setForm($dt);
		
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err']	, 'content'=> $fm['content']);
	}
	
	function setForm($dt){	
		global $fmIDBARANG,$fmIDREKENING,$Main;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	 	
	 $form_name = $this->Prefix.'_form';
	 $sw=$_REQUEST['sw'];
	 $sh=$_REQUEST['sh'];				
	 $this->form_width = 350;
	 $this->form_height = 80;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'BARU';
	  }else{
		$this->form_caption = 'EDIT';
	  }		
		 $this->form_fields = array(									 
			
			
			'ket' => array( 'label'=>'Keterangan', 
				'value'=>"<textarea name=\"fmKET\" cols=\"60\" style='margin: 2px;width: 200px; height: 51px;' >".$dt['ket']."</textarea>", 
				'type'=>'', 'row_params'=>"valign='top'"
			),

		);
		
				
		//tombol
		$this->form_menubawah = 
			"<input type='hidden' id='c' name='c' value='".$dt['c']."'> ".
			"<input type='hidden' id='d' name='d' value='".$dt['d']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan2()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >"
			;
		
		//$this->genForm_nodialog();
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function simpan2(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$id = $_REQUEST[$this->Prefix.'_idplh'];
		$UID = $HTTP_COOKIE_VARS['coID'];		
		
		$ket = $_REQUEST['fmKET'];	
		
		
		if($err==''){
			if($fmST == 0){//baru				
				
			}else{				
				$aqry ="update $this->TblName_Edit set ket='$ket' where id='$id'";$cek .= $aqry;
			}			
			$qry = mysql_query($aqry);
			if($qry == FALSE) $err='Gagal SQL'.mysql_error();
		}		
		//$err=$cek;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}
	
	/*function Hapus_Validasi($id){
		$err ='';
		//$KeyValue = explode(' ',$id);
		$get = mysql_fetch_array(mysql_query(
			"select count(*) as cnt from penerimaan where id_pengadaan ='$id' "
		));
		if($err=='' && $get['cnt']>0 ) $err = 'Data Tidak Bisa Dihapus, Sudah ada di Penerimaan!';
		
		return $err;
	}*/
	
	
	function getStat(){
		$cek=''; $err=''; $content='';
		//global $Main;
		$cbid = $_POST[$this->Prefix.'_cb'];
		$id = $cbid[0];		
		$aqry = "select * from $this->TblName where id='$id' ";
		$isi = mysql_fetch_array(mysql_query($aqry));
		$content->stat = $isi['stat'];
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}	
	
	
	function getPengadaanSebelumnya($tahun, $c, $d, $e ,$e1 , $f, $g, $h, $i, $j, $id=''){
		$idstr = $id==''? '' : " and id<>'$id' ";
		$aqry = "select sum(jml_barang) as tot from pengadaan where ".
				"tahun='$tahun'".
				" and c='$c' and d='$d' and e='$e'  and e1='$e1'  ".
				" and f='$f' and g='$g' and h='$h' and i='$i' and j='$j' $idstr "; $cek.=$aqry;
		$get = mysql_fetch_array( mysql_query(
			$aqry
		));	
		if($get['tot']==NULL) $get['tot'] = 0;
		return $get['tot'];
	}
	function hitPengadaanSebelumnya(){
		$cek=''; $err=''; $content='';
		$tahun = $_REQUEST['tahun'];
		$c = $_REQUEST['c'];		
		$d = $_REQUEST['d'];		
		$e = $_REQUEST['e'];	
		$e1 = $_REQUEST['e1'];	
		$fmIDBARANG = $_REQUEST['kdbrg'];		
		$f = $fmIDBARANG['0'].$fmIDBARANG['1'];
		$g = $fmIDBARANG['3'].$fmIDBARANG['4'];
		$h = $fmIDBARANG['6'].$fmIDBARANG['7'];
		$i = $fmIDBARANG['9'].$fmIDBARANG['10'];
		$j = $fmIDBARANG['12'].$fmIDBARANG['13'].$fmIDBARANG['14'];	
		$id = $_REQUEST['id'];
		
		
		$content->jmlada = $this->getPengadaanSebelumnya( $tahun , $c, $d, $e ,$e1 , $f, $g, $h, $i, $j, $id);		
		$content->jmldkb = $this->getJmlDKB($tahun , $c, $d, $e ,$e1 , $f, $g, $h, $i, $j); 
		if($content->jmlada == NULL) $content->jmlada=0;
		if($content->jmldkb == NULL) $content->jmldkb =0;
		
		$content->jml = $content->jmldkb - $content->jmlada;
		$content->jmlsisa = 0;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);					
	}
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formBaru2':{				
				//echo 'tes';
				$get=$this->setFormBaru();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];			
				break;
			}
			case 'formEdit2':{								
				$get=$this->setFormEdit();				
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];				
				break;
			}
			case 'simpan2' : {
				$get = $this->simpan2();
				$cek = $get['cek']; 
				$err = $get['err']; 
				$content=$get['content']; 
				break;
			}
			case 'getJmlBrgBI':{
				$get = $this->getJmlBrgBI();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			case 'formDKB':{
				$get = $this->setFormDKB();
				$json = FALSE;
				break;
			}
			case 'simpanDKB' : {
				$get = $this->simpanDKB();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}
			case 'getsat': {
				$get = $this->getStat();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}	
			case 'hitPengadaanSebelumnya':{
				$get = $this->hitPengadaanSebelumnya();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 				
				break;
			}
			case 'BidangAfter':{
			$content= $this->cmbQuerySKPD('fmSKPDskpd',$fmSKPDskpd,'','onchange=dpb.refreshList(true)','--- Pilih SKPD ---','00');
			$fmSKPDBidang = cekPOST('fmSKPDBidang');
			setcookie('cofmSKPD',$fmSKPDBidang);
			setcookie('cofmUNIT','00');
			setcookie('cofmSUBUNIT','00');
			setcookie('cofmSEKSI','000');
			break;
		    }
			case 'SKPDAfter':{
				$fmSKPDBidang = cekPOST('fmSKPDBidang');
				$fmSKPDskpd = cekPOST('fmSKPDskpd');
				setcookie('cofmSKPD',$fmSKPDBidang);
				setcookie('cofmUNIT',$fmSKPDskpd);
				setcookie('cofmSUBUNIT','00');
				setcookie('cofmSUBUNIT','000');
			break;
		    }
			case 'UnitRefresh':{
				$fmc = cekPOST('c');
				$fmd = cekPOST('d');
				$fme = cekPOST('fmSKPDUnit_form');
				$ref_iddkb = cekPOST('ref_iddkb');
				
				$editunit= $fmc != '' ? $fmc.".".$fmd:'';
				if($ref_iddkb != ''){
					$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange=dpb.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',TRUE,$editunit);	
				}else{
					$content=$this->cmbQueryUnit('fmSKPDUnit_form',$fme,'','onchange=dpb.UnitAfter() '.$disabled1,'--- Pilih Unit ---','00',FALSE,$editunit);
				}
				
			break;
		    }
			case 'UnitAfter':{
			//$fmSKPDUnit = cekPOST('fmSKPDUnit_form');
			//setcookie('cofmSUBUNIT',$fmSKPDUnit);
			$ref_iddkb = cekPOST('ref_iddkb');
			$fme1 = cekPOST('fmSKPDSubUnit_form');
			if($ref_iddkb != ''){
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','onchange=dpb.SubUnitAfter()','--- Pilih Sub Unit ---','000',TRUE);	
				}else{
					$content= $this->cmbQuerySubUnit('fmSKPDSubUnit_form',$fme1,'','onchange=dpb.SubUnitAfter()','--- Pilih Sub Unit ---','000',FALSE);
				}
			
			break;
		    }
			case 'formCari':{				
				$fm = $this->SetFormCari();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			
			case 'pilihcaribi':{				
			global $HTTP_COOKIE_VARS;
			global $Main;
			$err='';
	 		$uid = $HTTP_COOKIE_VARS['coID'];
			$idp = $_REQUEST['idp']; 
			$ref_pilih = $_REQUEST['cidBI'];
			$ket = $_REQUEST['ket'];
			$tgl = $_REQUEST['tgl'];
			$a1 = $Main->DEF_KEPEMILIKAN;
			$a = $Main->DEF_PROPINSI; //$_REQUEST[''];
			$b = $Main->DEF_WILAYAH;
			//$arrpb = explode(',',$ref_pilih);
			$valuearr = array();
			for($x = 0; $x<sizeof($ref_pilih); $x++){ 

				$get = "select * from buku_induk where Id = '".$ref_pilih[$x]."' ";//$cek.=$get;				
				$qry = mysql_query($get);
				while($bi = mysql_fetch_array($qry)){
					$id_bukuinduk = $bi['id'];
					$c = $bi['c'];
					$d = $bi['d'];
					$e = $bi['e'];
					$e1 = $bi['e1'];
					$f = $bi['f'];
					$g = $bi['g'];
					$h = $bi['h'];
					$i = $bi['i'];
					$j = $bi['j'];
					$idawal = $bi['idawal'];
					$noreg = $bi['noreg'];
					$thn_perolehan = $bi['thn_perolehan'];
					$jml_harga = $bi['jml_harga'];
					$staset = $bi['staset'];
					$kondisi = $bi['kondisi'];
					$nilai_buku = getNilaiBuku($id_bukuinduk,$tgl,0);
					$nilai_susut = getAkumPenyusutan($id_bukuinduk,$tgl);
					$query_brg="select * from ref_barang where concat(f,g,h,i,j)='".$bi['f'].$bi['g'].$bi['h'].$bi['i'].$bi['j']."'";
					$brg = mysql_fetch_array(mysql_query($query_brg)) ;
					$query_jurnal="select thn_akun,nm_account from ref_jurnal where ka='".$brg['ka']."' and kb='".$brg['kb']."' 
					and kc='".$brg['kc']."' and kd='".$brg['kd']."'
					and ke='".$brg['ke']."' and kf='".$brg['kf']."'";//$cek.='jurnal='.$query_jurnal;
					$jurnal = mysql_fetch_array(mysql_query($query_jurnal)) ;					
					$ka = $brg['ka'];
					$kb = $brg['kb'];
					$kc = $brg['kc'];
					$kd = $brg['kd'];
					$ke = $brg['ke'];
					$kf = $brg['kf'];
					$thn_akun = $jurnal['thn_akun'];
				}				
				$valuearr[]="('$id_bukuinduk','$idawal','$c','$d','$e','$e1','$f','$g','$h','$i','$j',".
							"'$thn_perolehan','$noreg','$nilai_buku','$nilai_susut','$kondisi','$idp','$ket',".
							"'$ka','$kb','$kc','$kd','$ke','$kf','$thn_akun','$tgl','$staset',NOW(),'$uid',NOW(),1)";
				}//end for	
			$valuestr = join(',',$valuearr);			
			$get = mysql_fetch_array(mysql_query("select count(*)as cnt from $this->TblName where refid_pemusnahan='$idp' and id_bukuinduk='$id_bukuinduk'"));
			if($get['cnt']>0)$err="Data sudah ada !";
			if($err==''){
				$querypr = "INSERT INTO $this->TblName(id_bukuinduk,idbi_awal,c,d,e,e1,f,g,h,i,j,".
					   "thn_perolehan,noreg,jumlah_harga,nilai_susut,kondisi,refid_pemusnahan,ket,".
					   "k,l,m,n,o,p,thn_akun,tgl_buku,staset,tgl_create,uid_create,tgl_update,sttemp) VALUES".$valuestr;$cek.=$querypr;
			//}
			mysql_query($querypr);
			}
			
			
			
			
			break;
	    	}			
			/*case 'hitungSisa': {
				$get = $this->hitungSisa();
				$cek = $get['cek']; $err = $get['err']; $content=$get['content']; 
				break;
			}*/			
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function SetFormCari(){
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$cek = 'tes';
				
		$dt=array();
		$this->form_fmST = 0;
		//$dt['tgl_usul'] = date("Y-m-d"); //set waktu sekarang
		//$dt['sesi'] = gen_table_session('penghapusan_usul','uid'); //generate no sesi
		//$fm = $this->setFormCr($dt);
		
		$sw=$_REQUEST['sw'];
		$sh=$_REQUEST['sh'];
		$idp = $_REQUEST['idp'];
		$form_name = 'adminForm';	//nama Form			
		$this->form_width = $sw-50;
		$this->form_height = $sh-100;
		$this->form_caption = 'Cari Barang'; //judul form
		
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='000'"));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd']; 	
		
		$this->form_fields = array(	
		/*
			'bidang' => array( 'label'=>'BIDANG', 
								'value'=>$bidang, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'unit' => array( 'label'=>'SKPD', 
								'value'=>$unit, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'subunit' => array( 'label'=>'UNIT', 
								'value'=>$subunit, 
								'type'=>'', 'row_params'=>"height='21'"
							),
			'seksi' => array( 'label'=>'SUB UNIT', 
								'value'=>$seksi, 
								'type'=>'', 'row_params'=>"height='21'"
							),		*/
			'detailcari' => array( 
				'label'=>'',
				'value'=>"<div id='div_detailcaribi' style='height:5px'></div>", 
				'type'=>'merge'
			)
		);
		
		//tombol
		$this->form_menubawah =
			"Keterangan&nbsp;&nbsp;:&nbsp;<input type='text' value='$ket' name='ket' id='ket' >&nbsp;".
			"<input type='hidden' value='$idp' name='idp' id='idp' >".
			"<input type='button' value='Pilih' onclick ='".$this->Prefix.".Pilih()' >&nbsp;".
			"<input type='button' value='Close' onclick ='".$this->Prefix.".Closecari()' >";
		
		//$form = //$this->genForm();		
		$form= "<form name='$form_name' id='$form_name' method='post' action=''>".
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >"
					,
					$this->form_menu_bawah_height,
					'',1
					).
				"</form>";
				
		$content = $form;
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
}
$pemusnahan_det = new pemusnahan_detObj();

?>