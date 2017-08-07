<?php
/***
	salinan dari fnuseraktivitas.php
	requirement:
	 - daftarobj2 di DaftarObj2.php
	 - global variable di vars.php
	 - library fungsi di fnfile.php
	 - connect db  di config.php
***/

class UsulanHapusbacariObj  extends DaftarObj2{	
	var $Prefix = 'UsulanHapusbacari';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'penghapusan_usul'; //daftar
	var $TblName_Hapus = 'penghapusan_usul';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array('Jml');//array('jml_harga');	
	var $SumValue = array();
	var $fieldSum_lokasi = array(6);
	var $FieldSum_Cp1 = array( 4, 3, 3);//berdasar mode
	var $FieldSum_Cp2 = array( 3, 3, 3);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Penghapusan';
	var $PageIcon = 'images/penghapusan_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usulanhapusba.xls';
	var $Cetak_Judul = 'Berita Acara Usulan Penghapusan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $pagePerHal='6';
	var $FormName = 'UsulanHapussk_formbacari';
	
	
	function genSumHal($Kondisi){		
		global $Main;		
		$cek = '';
		$jmlData = 0;
		$jmlTotal = 0;
		$SumArr=array();
		$vSum = array();
		$fsum_ = array();
		
		$fsum_[] = "count(*) as cnt";
		
		$fsum = join(',',$fsum_);	
		
		//jml data --------------------------------------------------	
		$aqry = "select count(*) as cnt from penghapusan_usul $Kondisi" ; $cek .= $aqry;
		$qry = mysql_query($aqry); 
		if ($isi= mysql_fetch_array($qry)){			
			$jmlData = $isi['cnt'];			
			foreach($this->FieldSum as &$value){
				$SumArr[] = $isi["sum_$value"];				
				$vSum[] = $this->genSum_setTampilValue(0, $isi["sum_$value"]);//Fmt($isi["sum_$value"],1);
			}
			if(sizeof($this->FieldSum)>0 )$Sum = $this->genSum_setTampilValue(0, $SumArr[0]);//number_format($SumArr[0], 2, ',' ,'.');			
			
		}			
		
		//jml harga -------------------------------------------------
		//$Sum = 300;		
		//$vSum = array('300,00');
		$aqry = 
			"select sum(jml_harga) sumhrg from v1_penghapusan_usul_det_bi where Id in ".
				"(select Id from penghapusan_usul  ".
				$Kondisi.
				//"Where a='10' and b='00'  and c='04'   and d='01'   and e='02' ".
				//"and (no_ba!='' OR no_ba !=NULL) ".
				//"and Id NOT IN (select ref_idusulan as id from penghapusan_usulsk_det) ".
				" ) ".
				"and (tindak_lanjut!=1 and tindak_lanjut!=0) and status=1 "; $cek .= 'cek jml='.$aqry;
		$get = mysql_fetch_array( mysql_query($aqry) );
		$Sum = $get['sumhrg'];
		$vSum[0] = number_format($Sum,2,',','.');
		
		
		
		/*for($i=0;$i<6;$i++) {
			$Kondisi2 = $Kondisi != '' ?  $Kondisi . " and f='0".($i+1)."'"  : " where f='0".($i+1)."'" ;
			$aqry = "select count(*) as cnt, sum(jml_harga) as jml_harga from v2_penghapusan_usul $Kondisi2 "; $cek.= $aqry;
			$get = mysql_fetch_array(mysql_query($aqry));
			$vSum[$i*2] = number_format($get['cnt'],0,',','.');
			$vSum[$i*2+1] = number_format($get['jml_harga'],2,',','.');				
		}
		$Kondisi2 = $Kondisi != '' ?  $Kondisi . " and id_bukuinduk !=''"  : " where id_bukuinduk !=''";			
		$aqry = "select count(*) as cnt, sum(jml_harga) as jml_harga from v2_penghapusan_usul $Kondisi2 "; $cek.= $aqry;
		$get = mysql_fetch_array(mysql_query($aqry));
		$vSum[12] = number_format($get['cnt'],0,',','.');
		$vSum[13] = number_format($get['jml_harga'],2,',','.');
		*/
		//total 
		
		$Hal = $this->setDaftar_hal($jmlData);
		if ($this->WITH_HAL==FALSE) $Hal = ''; 		
		//$cek = '';	
		return array('sum'=>$Sum, 'hal'=>$Hal, 'sums'=>$vSum, 'jmldata'=>$jmlData, 'cek'=>$cek );
	}	
	/*function genRowSum_setTampilValue($i, $value){
		if($i % 2 ==0){
			return number_format($value,0, ',', '.'); 
		}else{
			return number_format($value,2, ',', '.'); 
		}
	}*/	
	function genRowSum($ColStyle, $Mode, $Total){
		//--- total hal
		$ContentTotalHal=''; $ContentTotal='';		
		$colspanarr=$this->fieldSum_lokasi;
		$ContentTotalHal =	"<tr>";
		$ContentTotal =	"<tr>";		
		
		for ($i=0; $i<sizeof($this->FieldSum);$i++){
			
			if($i==0){
				$TotalColSpan1 = $this->genRowSum_setColspanTotal($Mode, $colspanarr );
				$Kiri1 = $TotalColSpan1 > 0 ? 
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalhalstr</td>": '';
				$ContentTotalHal .=	$Kiri1;
				$ContentTotal .= $TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'>$this->totalAllStr</td>":'';
			}else{
				$TotalColSpan1 = $colspanarr[$i] - $colspanarr[$i-1]-1; 				
				$ContentTotalHal .=	$TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
				$ContentTotal .= $TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td>": '';
			}
			
			$TampilTotalHalRp = //number_format($this->SumValue[$i],2, ',', '.');
				$this->genRowSum_setTampilValue($i, $this->SumValue[$i]);
			$vTotal = number_format($Total[$i],2, ',', '.');
			$ContentTotalHal .= //$i==0?				
				"<td class='$ColStyle' align='right'><b>$TampilTotalHalRp</td>"	;
			$ContentTotal .= $i==0?
				"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum'>".$Total[$i]."</div></td>":
				"<td class='$ColStyle' align='right'><b><div  id='{$this->Prefix}_cont_sum$i'>".$Total[$i]."</div></td>";
		}
		
		//$totrow = $Mode == 1? $this->totalRow : $this->totalRow;
		$TotalColSpan1 = $this->totalCol - $colspanarr[sizeof($this->FieldSum)-1];					
		$ContentTotalHal .=	$TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
		$ContentTotal .= $TotalColSpan1 > 0 ?
					"<td class='$ColStyle' colspan='$TotalColSpan1' align='center'></td></tr>": '</tr>';					
		
		
	
		$ContentTotal = $this->withSumAll? $ContentTotal: '';
		$ContentTotalHal = $this->withSumHal? $ContentTotalHal: '';
		if($Mode == 2){			
			$ContentTotal = '';
		}else if($Mode == 3){				
			if ($this->withSumAll) {
				$ContentTotalHal = '';
			}
		}
		return $ContentTotalHal.$ContentTotal;
	}
	
		
	function setPage_HeaderOther(){
		return "";
	}
	
	function genDaftarHeader($Mode=1){
		//mode :1.;ist, 2.cetak hal, 3. cetak semua
		global $Main;
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$rowspan_cbx = $this->checkbox_rowspan >1 ? "rowspan='$this->checkbox_rowspan'":'';
		$Checkbox = $Mode==1? 
			"<th class='th01' width='10' $rowspan_cbx>
					<input type='checkbox' name='".$this->Prefix."_toggle' id='".$this->Prefix."_toggle' value='' 
						onClick=\"checkAll4($pagePerHal,'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek');".
							$this->Prefix.".checkAll($pagePerHal,'".$this->Prefix."_cb','".$this->Prefix."_toggle','".$this->Prefix."_jmlcek')\" />
			</th>" : '';		
		$headerTable = $this->setKolomHeader($Mode, $Checkbox);
		return $headerTable;
	}	
	
	
	function setTitle(){
		return 'Berita Acara Usulan Penghapusan.....';
	}
	
	function setMenuEdit(){
		
		//return"";
		return"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
	}
	
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT,$fmSEKSI)."</td>
				</tr>
			</table><br>";
	}
	
	function setCekBox($cb, $KeyValueStr, $isi){
		$hsl = '';
		if($KeyValueStr!=''){
			
		
			$hsl = "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');".$this->Prefix.".cbxPilih(this);\" />";					
		}
		return $hsl;
	}
	
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function simpan(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$idplh = $_REQUEST[$this->Prefix.'_idplh'];
				$a1 = $Main->DEF_KEPEMILIKAN;
				$a = $Main->Provinsi[0];
				$b = $Main->DEF_WILAYAH;
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				$no_usulan= $_REQUEST['no_usulan'];
				$tgl_usul= $_REQUEST['tgl_usul'];
				$pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
				
				$get = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$pejabat_pengadaan."'"));
						$nama = $get['nama'];
						$nip = $get['nip'];
						$jabatan = $get['jabatan'];
				
				if( $err=='' && $no_usulan =='' ) $err= 'No Usulan belum diisi!';
				if( $err=='' && $tgl_usul =='' ) $err= 'Tgl Usul belum diisi!';
				//if( $err=='' && $pegawai =='' ) $err= 'Pegawai belum diisi!';
				
					
				
				if($fmST == 0){
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from penghapusan_usul where no_usulan='$no_usulan' "
						));
						if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
					}
					if($err==''){
						$aqry = "insert into penghapusan_usul (a,b,c,d,e,e1,no_usulan,tgl_usul,tgl_update,uid,ref_idpegawai_usul)"."values('$a','$b','$c','$d','$e','$e1','$no_usulan','$tgl_usul',now(),'$uid','$pejabat_pengadaan')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					$old = mysql_fetch_array(mysql_query("select * from penghapusan_usul where Id='$idplh'"));
					if( $err=='' ){
						if($no_usulan!=$old['no_usulan'] ){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from penghapusan_usul where no_usulan='$no_usulan' "
							));
							if($get['cnt']>0 ) $err='No Usulan Sudah Ada!';
						}
					}
					if($err==''){
						/*$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',
							p='$p',q='$q',nm_ruang='$nm_ruang'".
							"where a1='$a1' and a='$a' and b='$b' and c='$c' and d='$d' and e='$e' 
							and p='$oldp' and q='$oldq' ";	$cek .= $aqry;
						*/
						
						$aqry = "update penghapusan_usul 
						         set "." a='$a', 
								         b='$b', 
										 c='$c',
										 d='$d',
										 e='$e',
										 e1='$e1',
							 			 no_usulan='$no_usulan',
										 tgl_update =now(),
										 uid ='$uid',
										 tgl_usul='$tgl_usul',
										 ref_idpegawai_usul = '$pejabat_pengadaan'".
								 "where Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
				}
				
				//
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}
	
	
	
	function batal()
	{
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		//get data -----------------
				$fmST = $_REQUEST[$this->Prefix.'_fmST'];
				$cbid = $_REQUEST[$this->Prefix.'_cb']; //ambil data checkbox
				$idplh = $cbid[0]; //ambil data checkbox
				$a1 = $Main->DEF_KEPEMILIKAN;
				$a = $Main->Provinsi[0];
				$b = $Main->DEF_WILAYAH;
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				
				$no_ba= $_REQUEST['no_ba'];
				$tgl_ba= $_REQUEST['tgl_ba'];
				$pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
				
				$get = mysql_fetch_array(mysql_query("select*from ref_pegawai where Id = '".$pejabat_pengadaan."'"));
						$nama = $get['nama'];
						$nip = $get['nip'];
						$jabatan = $get['jabatan'];
				
					
				if($fmST == 0)
				{
					
					if($err=='')
					{
						$aqry = "update penghapusan_usul 
						         set	no_ba  = NULL,
						         		tgl_ba  = NULL,
								 	    uid ='$uid',
								     	ref_idpegawai_ba = '$pejabat_pengadaan'".
								 "where Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
						
					}
				}
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}	
	
	function set_selector_other2($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'cbxgedung':{
				$c= $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
				$d= $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
				$e= $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
				$e1= $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				if($c=='' || $c =='00') {
					$kondC='';
				}else{
					$kondC = "and c = '$c'";
				}
				if($d=='' || $d =='00') {
					$kondD='';
				}else{
					$kondD = "and d = '$d'";
				}
				if($e=='' || $e =='00') {
					$kondE='';
				}else{
					$kondE = "and e = '$e'";
				}
				if($e1=='' || $e1 =='00' || $e1 =='000') {
					$kondE1='';
				}else{
					$kondE1 = "and e1 = '$e1'";
				}

				$aqry = "select * from ref_ruang where q='0000' $kondC $kondD $kondE $kondE1";
				$cek .= $aqry;
				$content = genComboBoxQry( 'fmPILGEDUNG', $fmPILGEDUNG, 
						$aqry,
						'p', 'nm_ruang', '-- Semua Gedung --',"style='width:140'" );
				break;
			}		
			case 'formBaru':{				
				$fm = $this->setFormBaru();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'formEdit':{				
				$fm = $this->setFormEdit();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'simpan':{
				
				$get= $this->simpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'batal':{
				
				$get= $this->batal();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				break;
			}
			case 'getdata':{
				$id = $_REQUEST['id'];
				$aqry = "select * from ref_pegawai where id='$id' "; $cek .= $aqry;
				$get = mysql_fetch_array( mysql_query($aqry));
				if($get==FALSE) $err= "Gagal ambil data!"; 
				$content = array('nip'=>$get['nip'],'nama'=>$get['nama'],'jabatan'=>$get['jabatan']);
				break;
			}
			default:{
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
				break;
			}
			/*default:{
				$err = 'tipe tidak ada!';
				break;
			}*/
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>**/
			"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e1'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
		$dt['p'] = '';
		$dt['q'] = '';
		//$this->form_idplh ='';
		$this->form_fmST = 0;
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$c = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$d = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$e = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$e1 = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from penghapusan_usul where Id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 800;
		$this->form_height = 250;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$ref_idpegawai_usul = $dt['ref_idpegawai_usul'];			
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];		
		
		//ambil data no usulan dan tgl Usulan
		$get = mysql_fetch_array(
			   mysql_query("select Id,
			   					   no_usulan,
			   					   tgl_usul
							from penghapusan_usul
							where no_ba ='' OR no_ba IS NULL
							")
				);
	     $Id = $get['Id'];
	     $no_usulan = $get['no_usulan'];
	     $tgl_usul = TglInd($get['tgl_usul']);
			
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'no_ba' =>array(
							'label'=>'No.BA','value'=>$dt['no_ba'],
							'labelWidth'=>120, 
							'type'=>'text' 
							),
			'tgl_ba' => array( 
						'label'=>'Tgl BA',
						 'value'=>$dt['tgl_ba'], 
						 'type'=>'date'
						 ),	
			'pejabat_pengadaan' => array(  
				'label'=>'Pilih No Usulan', 
				'value'=> 
					"<input type='text' id='Id' name='Id' value='".$Id."'> ".
					"<input type='text' id='no_usul' name='no_usul' readonly=true value='".$no_usulan."' style='width:250'> &nbsp; ".
					"NO.Usulan  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$tgl_usul."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihUsulan()\">"
				,
				'type'=>'' 
			), 	
			'jbt1' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' name='jbt_pejabat_pengadaan' readonly=true value='".$jabatan."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
			),
			'no_usulan' => array( 
						'label'=>'No.Usulan', 'value'=>$no_usul, 
						'labelWidth'=>120, 
						'type'=>'text' 
			    ),
			'tgl_usul' => array( 
						'label'=>'Tgl Usul',
						 'value'=>$dt['tgl_usul'], 
						 'type'=>'date'
						 )	
			
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
			"<thead>
				<tr>
				
				<th class='th01' width='40'>No.</th>
				$Checkbox		
				<!--<th class='th01' width=150>Id</th>-->
				<th class='th01' width=150>No. Ba</th>
				<th class='th01' width=150>Tgl. Ba</th>
				<th class='th01' width=250>Asisten/OPD</th>	
				<th class='th01' width=150>Jumlah Harga</th>
				<th class='th01' width=150>No. Usulan</th>
				<th class='th01' width=150>Tgl. Usulan </th>
				<th class='th01' width=250>Pegawai</th>								
				
	     		</tr>
				
				
			</thead>";
		return $headerTable;
	}

	function cariJml($Id,$kib) {
		//-- jml buku induk
		$query ="select count(*) AS jml , sum(ifnull(jml_harga,0)+ ifnull(tot_pelihara,0)+ ifnull(tot_pengaman,0) ) AS harga 								 
				 from v1_penghapusan_usul_det_bi
				 where Id='".$Id."' and f='".$kib."'";
		$rs = mysql_fetch_array(mysql_query($query));
		$hsl->jml = $rs['jml'];
		$hsl->harga = $rs['harga'];			
		return $hsl;
	}
	
	
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		//get dinas		
		$dinas = '';
		//if($isi['group']!= '00.00.00'){
		//	$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		//	$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		//	$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
			
			//$grp = $isi['group'];
			$c=''.$isi['c'];
			$d=''.$isi['d'];
			$e=''.$isi['e'];
			$e1=''.$isi['e1'];
			$ref_idpegawai_ba=''.$isi['ref_idpegawai_ba'];
			$idusul = $isi['Id'];
			///*
			$nmopdarr=array();	
			//if($fmSKPD == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from v_bidang where c='".$c."' "
				));		
				if($get['nmbidang']<>'') $nmopdarr[] = $get['nmbidang'];
			//}
			//if($fmUNIT == '00'){//$nmopdarr[] = "select * from v_opd where c='".$isi['c']."' and d='".$isi['d']."' ";
				$get = mysql_fetch_array(mysql_query(
					"select * from v_opd where c='".$c."' and d='".$d."' "
				));		
				if($get['nmopd']<>'') $nmopdarr[] = $get['nmopd'];
				$opd = $get['nmopd'];
			//}
			//if($fmSUBUNIT == '00'){
				$get = mysql_fetch_array(mysql_query(
					"select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"
				));		
				if($get['nmunit']<>'') $nmopdarr[] = $get['nmunit'];
			//}
				$get = mysql_fetch_array(mysql_query(
					"select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."'  and e1='".$e1."'"
				));		
				if($get['nm_skpd']<>'') $nmopdarr[] = $get['nm_skpd'];

			$nmopd = join(' - ', $nmopdarr );
			//*/
			//$nmopd = $grp.' '.$c.$d.$e;
		//}
		//get Pegawai
			     $get = mysql_fetch_array(mysql_query(
					"select * from ref_pegawai where id='".$ref_idpegawai_ba."'"));		
		
		
		/** Jumlah harga yang ditampilkan hanya status tindak lanjut 2 dan 3 **/
	 //kib A 
	 $totalduitkiba=  "SELECT SUM(harga) AS hargakiba FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idusul ."' AND f='01' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskiba = mysql_query($totalduitkiba);
	 while($row =mysql_fetch_array($resskiba)) {
				$totetotkiba = $row['hargakiba'];
	 }
	 $totetotkiba =$totetotkiba==0?'0':$totetotkiba;
	 
	 //kib b 
	 $totalduitkibb=  "SELECT SUM(harga) AS hargakibb FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idusul ."' AND f='02' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibb = mysql_query($totalduitkibb);
	 while($row =mysql_fetch_array($resskibb)) {
				$totetotkibb = $row['hargakibb'];
	 }
	 $totetotkibb =$totetotkibb==0?'0':$totetotkibb;
	
	//kib c 
	 $totalduitkibc=  "SELECT SUM(harga) AS hargakibc FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idusul ."' AND f='03' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibc = mysql_query($totalduitkibc);
	 while($row =mysql_fetch_array($resskibc)) {
				$totetotkibc = $row['hargakibc'];
	 }
	 $totetotkibc =$totetotkibc==0?'0':$totetotkibc;
	
	//kib d 
	 $totalduitkibd=  "SELECT SUM(harga) AS hargakibd FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idusul ."' AND f='04' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibd = mysql_query($totalduitkibd);
	 while($row =mysql_fetch_array($resskibd)) {
				$totetotkibd = $row['hargakibd'];
	 }
	 $totetotkibd =$totetotkibd==0?'0':$totetotkibd;
	 
	//kib e
	 $totalduitkibe=  "SELECT SUM(harga) AS hargakibe FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idusul ."' AND f='05' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibe = mysql_query($totalduitkibe);
	 while($row =mysql_fetch_array($resskibe)) {
				$totetotkibe = $row['hargakibe'];
	 }
	 $totetotkibe =$totetotkibe==0?'0':$totetotkibe;
	
	//kib f
	 $totalduitkibf=  "SELECT SUM(harga) AS hargakibf FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idusul ."' AND f='06' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibf = mysql_query($totalduitkibf);
	 while($row =mysql_fetch_array($resskibf)) {
				$totetotkibf = $row['hargakibf'];
	 }
	 $totetotkibf =$totetotkibf==0?'0':$totetotkibf;
	
	//total kabeh kib A - F
	$totalkabeh = 	$totetotkiba + 	$totetotkibb + $totetotkibc + $totetotkibd + $totetotkibe + $totetotkibf;
	$this->SumValue[0] += $totalkabeh;
	$totalkabeh = $totalkabeh==0?'0':number_format($totalkabeh,2,',','.');
	
	//total halaman
	
		/**old
		//hitung Jumlah harga BA
		$a=array();
		for($i=1;$i<=6;$i++){
			$a[] = $this->cariJml($idusul,'0'.$i);
		}
		//=================== total Harga KIB A - KIB F =========
		$tothrg =0;
		for($i=0;$i<6;$i++)
		{
			$tothrg=$tothrg+$a[$i]->harga;
		}
		$vtothrg=$tothrg==0?'0':number_format($tothrg,2,',','.');
		**/	
		
							
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		//$Koloms[] = array('', $isi['Id']);		
		$Koloms[] = array('', $isi['no_ba']);		
		$Koloms[] = array('', TglInd($isi['tgl_ba']));	
		$Koloms[] = array('', $opd);	
		$Koloms[] = array(" align='right'", $totalkabeh);	
		//$Koloms[] = array('', $vtothrg);	//$Koloms[] = array('', $jumhargaba);	
		$Koloms[] = array('', $isi['no_usulan']);		
		$Koloms[] = array('', TglInd($isi['tgl_usul']));
		$Koloms[] = array('', 'Nip '.$get['nip'].'<br/>'.$get['nama']);			
		return $Koloms;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				WilSKPD_ajx3($this->Prefix.'Skpd') . 
			"</td>
			<td >" . 		
			"</td></tr>
			<tr><td>
				<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>
			</td></tr>			
			</table>";
			/*genFilterBar(
				''
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);*/
		return array('TampilOpt'=>$TampilOpt);
	}	
								   
	function setTopBar(){
		return genSubTitle($this->setTitle(),$this->genMenu());
		//return "";
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		
		$arrKondisi = array();		
		
		
		/*$arrKondisi[] = 'a='.$Main->Provinsi[0];
		$arrKondisi[] = 'b='.'00';
		
		if(!($fmSKPD=='' || $fmSKPD=='00') ) $arrKondisi[] = 'c='.$fmSKPD;
		if(!($fmUNIT=='' || $fmUNIT=='00') ) $arrKondisi[] = 'd='.$fmUNIT;
		if(!($fmSUBUNIT=='' || $fmSUBUNIT=='00') ) $arrKondisi[] = 'e='.$fmSUBUNIT;
		*/
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');	
				
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT,
			$fmSEKSI
			
		);
		
		
		$arrKondisi[] = "(no_ba!='' OR no_ba !=NULL)"; //tambah array 	
		//jika no ba sudah di usulkan maka jangan di munculkan di daftar
		$arrKondisi[] = "Id NOT IN (select ref_idusulan as id from penghapusan_usulsk_det)";
				
		//$arrKondisi[] = "tgl_ba='2013-01-01'"; //contoh tambah array 	
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,e1,nip ";
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		$Order ="";
		//limit --------------------------------------
		//limit --------------------------------------
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		/**
		$pagePerHal = $this->pagePerHal ==''?$Main->PagePerHal:$this->pagePerHal;
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		**/
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
	
	

}
$UsulanHapusbacari = new UsulanHapusbacariObj();


?>