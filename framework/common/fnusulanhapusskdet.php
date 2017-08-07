<?php
/***
salinan dari fnuseraktivitas.php
requirement:
- daftarobj2 di DaftarObj2.php
- global variable di vars.php
- library fungsi di fnfile.php
- connect db  di config.php
***/

class UsulanHapusskdetObj  extends DaftarObj2{	
	var $Prefix = 'UsulanHapusskdet';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'v1_penghapusan_usulsk_det'; //daftar 
	var $TblName_Hapus = 'penghapusan_usulsk_det';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id','sesi','ref_idusulan');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Penghapusan';
	var $PageIcon = 'images/penghapusan_ico.gif';
	//var $cetak_xls=TRUE ;
	var $FormName = 'UsulanHapussk_form';
	var $pagePerHal = 6;
	var $fileNameExcel='usulanhapusba.xls';
	var $Cetak_Judul = 'Berita Acara Usulan Penghapusan';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	
	function setPage_HeaderOther(){
		return "";
	}
	
	
	function setTitle(){
		return '';
	}
	
	function setMenuEdit(){
		
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
		
		$status= $_REQUEST['disetujui'];
		$pejabat_pengadaan= $_REQUEST['ref_idpengadaan'];
		
		if( $err=='' && $status =='' ) $err= 'Status belum diisi!';					
		
		if($fmST == 0){
			if($err==''){
				$aqry = "insert into penghapusan_usul_det (id_bukuinduk,disetujui,tgl_update,uid)"."values('$id_bukuinduk','$disetujui',now(),'$uid')";	$cek .= $aqry;	
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
		} //end else
		
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
		$this->form_height =400;
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
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."'  and e1='".$dt['e1']."' "));
		$seksi= $get['nm_skpd'];		
		
		$this->form_fields = array(				
		'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
		'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
		'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
		'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
		'pejabat_pengadaan' => array(  
		'label'=>'Buku Induk', 
		'value'=> 
		"<input type='text' id='Id' name='Id' value='".$Id."'> ".
		"<input type='text' id='no_usul' name='no_usul' readonly=true value='".$no_usulan."' style='width:250'> &nbsp; ".
		"NO.Usulan  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$tgl_usul."' style='width:150' > ".					
		"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihUsulan()\">"
		,
		'type'=>'' 
		),
		'status' =>array(
		'label'=>'status','value'=>$dt['disetujui'],
		'labelWidth'=>120, 
		'type'=>'text' 
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
	
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$headerTable =
		"<thead>
		<tr>
		<td colspan='8'><h3>Daftar Barang</h3</td>
		<tr>
		<tr>
		
		<th class='th01' width='20'>No.</th>
		$Checkbox		
		<!--<th class='th01' width=150>ID Usulan</th>-->
		<th class='th01' width=150>No. BA</th>
		<th class='th01' width=150>Tgl. BA</th>
		<th class='th01' width=250>Asisten/OPD</th>	
		<th class='th01' width=150>Jumlah Harga</th>
		<th class='th01' width=150>No. Usulan</th>
		<th class='th01' width=150>Tgl. Usulan</th>
		</tr>
		
		</thead>";
		return $headerTable;
	}
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		
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
			$idrefusulan = $isi['ref_idusulan'];
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
			//if($fmSUBUNIT == '00'){
				$get = mysql_fetch_array(mysql_query(
				"select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."'  and e1='".$e1."'"
				));		
				if($get['nm_skpd']<>'') $nmopdarr[] = $get['nm_skpd'];
			//}

			$nmopd = join(' - ', $nmopdarr );
			//*/
			//$nmopd = $grp.' '.$c.$d.$e;
		//}
		
	 /** Jumlah harga yang ditampilkan hanya status tindak lanjut 2 dan 3 **/
	 //kib A 
	 $totalduitkiba=  "SELECT SUM(harga) AS hargakiba FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idrefusulan ."' AND f='01' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskiba = mysql_query($totalduitkiba);
	 while($row =mysql_fetch_array($resskiba)) {
				$totetotkiba = $row['hargakiba'];
	 }
	 $totetotkiba =$totetotkiba==0?'0':$totetotkiba;
	 
	 //kib b 
	 $totalduitkibb=  "SELECT SUM(harga) AS hargakibb FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idrefusulan ."' AND f='02' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibb = mysql_query($totalduitkibb);
	 while($row =mysql_fetch_array($resskibb)) {
				$totetotkibb = $row['hargakibb'];
	 }
	 $totetotkibb =$totetotkibb==0?'0':$totetotkibb;
	
	//kib c 
	 $totalduitkibc=  "SELECT SUM(harga) AS hargakibc FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idrefusulan ."' AND f='03' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibc = mysql_query($totalduitkibc);
	 while($row =mysql_fetch_array($resskibc)) {
				$totetotkibc = $row['hargakibc'];
	 }
	 $totetotkibc =$totetotkibc==0?'0':$totetotkibc;
	
	//kib d 
	 $totalduitkibd=  "SELECT SUM(harga) AS hargakibd FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idrefusulan ."' AND f='04' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibd = mysql_query($totalduitkibd);
	 while($row =mysql_fetch_array($resskibd)) {
				$totetotkibd = $row['hargakibd'];
	 }
	 $totetotkibd =$totetotkibd==0?'0':$totetotkibd;
	 
	//kib e
	 $totalduitkibe=  "SELECT SUM(harga) AS hargakibe FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idrefusulan ."' AND f='05' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibe = mysql_query($totalduitkibe);
	 while($row =mysql_fetch_array($resskibe)) {
				$totetotkibe = $row['hargakibe'];
	 }
	 $totetotkibe =$totetotkibe==0?'0':$totetotkibe;
	
	//kib f
	 $totalduitkibf=  "SELECT SUM(harga) AS hargakibf FROM v1_penghapusan_usul_det_bi WHERE Id ='".$idrefusulan ."' AND f='06' AND tindak_lanjut!='1' and tindak_lanjut!=0  and status=1 ";
	 $resskibf = mysql_query($totalduitkibf);
	 while($row =mysql_fetch_array($resskibf)) {
				$totetotkibf = $row['hargakibf'];
	 }
	 $totetotkibf =$totetotkibf==0?'0':$totetotkibf;
	
	//total kabeh kib A - F
	$totalkabeh = 	$totetotkiba + 	$totetotkibb + $totetotkibc + $totetotkibd + $totetotkibe + $totetotkibf;
	$totalkabeh = $totalkabeh==0?'0':number_format($totalkabeh,2,',','.');
				
		/** old
		//hitung Jumlah harga BA
		$a=array();
		for($i=1;$i<=6;$i++){
			$a[] = $this->cariJml($idrefusulan,'0'.$i);
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
		//$Koloms[] = array('', $isi['ref_idusulan']);		
		$Koloms[] = array('', $isi['no_ba']);		
		$Koloms[] = array('align=center', TglInd($isi['tgl_ba']));	
		$Koloms[] = array('', $opd);		
		//$Koloms[] = array('', $vtothrg);		
		$Koloms[] = array('', $totalkabeh);		
		$Koloms[] = array('', $isi['no_usulan']);		
		$Koloms[] = array('align=center', TglInd($isi['tgl_usul']));		
		
		
		
		
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
		//return array('TampilOpt'=>$TampilOpt);
	}
	
	function setTopBar(){
		//return genSubTitle($this->setTitle(),$this->genMenu());
		return "";
	}
	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		
		
		$arrKondisi = array();		
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$fmSEKSI = isset($HTTP_COOKIE_VARS['cofmSEKSI'])? $HTTP_COOKIE_VARS['cofmSEKSI']: cekPOST($this->Prefix.'SkpdfmSEKSI');		
		
		//$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		//if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$sesi = $_REQUEST['sesi'];
		$id = $_REQUEST['UsulanHapussk_idplh']; //ambil data kondisi
		$genStr ='Id'.'='.$id; //ambil nama field untuk data kondisi
		if ($id !='')
			{$arrKondisi[]=$genStr;
		}
		else 
		{
			if($sesi !='')
			{
				$arrKondisi[]='sesi'.'='."'$sesi'";
			}
			else
			{
				$arrKondisi[]='1=0';
			}
		}
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
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		//$Limit = " limit ".(($HalDefault	*1) - 1) * $Main->PagePerHal.",".$Main->PagePerHal; //$LimitHal = '';
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;		
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
		}	
	
}
$UsulanHapusskdet = new UsulanHapusskdetObj();


?>