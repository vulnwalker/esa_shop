<?php
/***
	salinan dari fnuseraktivitas.php
	requirement:
	 - daftarobj2 di DaftarObj2.php
	 - global variable di vars.php
	 - library fungsi di fnfile.php
	 - connect db  di config.php
***/

class PanitiaPemeriksaObj  extends DaftarObj2{	
	var $Prefix = 'PanitiaPemeriksa';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'panitia_pemeriksa'; //daftar
	var $TblName_Hapus = 'panitia_pemeriksa';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Panitia Pemeriksa';
	var $PageIcon = 'images/penghapusan_ico.gif';
	var $FormName = 'PanitiaPemeriksafm';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='usulanhapusba.xls';
	var $Cetak_Judul = 'Panitia Pemeriksa';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $arrjabatan = array(
			array('1','Ketua'), //$arrjabatan[0][1] =$dt['jabatan']=1-1
			array('2','Wk.Ketua'), 
			array('3','Sekretaris'), 
			array('4','Anggota'), 
		);
	
	function setPage_HeaderOther(){
		return "";
		
	}
	
	function setTitle(){
		return "Panitia Pemeriksa";
	}

	function setMenuEdit(){
			return
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","new_f2.png","Baru",'Baru')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";		
	}
	
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		$fmSEKSI = cekPOST($this->Prefix.'SkpdfmSEKSI');
		return'';
		   
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
								
		$jabatan = $_REQUEST['jabatan'];				
		$nama = $_REQUEST['nama'];				
		$nip = $_REQUEST['nip'];				
		$dinas = $_REQUEST['dinas'];	
		$idUsul =$_REQUEST['PanitiaPemeriksa_idUsul1'];			
		$cek .=$idplh;				
					
			if( $err=='' && $jabatan =='' ) $err= 'Jabatan belum diisi!';
			if( $err=='' && $nama =='' ) $err= 'Nama belum diisi!';
			if( $err=='' && $nip =='' ) $err= 'Nip belum diisi!';
			if( $err=='' && $dinas =='' ) $err= 'Dinas belum diisi!';
								
			if($fmST == 0){ //input baru
				if($err==''){
					$aqry = "insert into panitia_pemeriksa (jabatan,nama,nip,dinas,tgl_update,ref_idusulan,uid)"."values('$jabatan','$nama','$nip','$dinas',now(),'$idUsul','$uid')";	
					$cek .= $aqry;	
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
						
						$arr = explode(' ',$idplh);
						$Id = $arr[0];
						$sesi = $arr[1];
						$id_bukuinduk = $arr[2];
																										
						//update tabel penghapusan_usul_det
						$sql = "update panitia_pemeriksa 
						         set "." jabatan = '$jabatan',
								 		 nama = '$nama',
								 		 nip = '$nip',
								 		 dinas = '$dinas',
										 tgl_update=now()".							
								 "where Id='".$Id."' ";	
								 $cek .= $sql; 
						$query = mysql_query($sql);
					}
		 }
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
				
	}
	
	function simpanPilih(){
		global $HTTP_COOKIE_VARS;
		global $Main;
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$idusul = $_POST['idusul']; if(empty($idusul)) $idusul= 0;
		$sesi = $_POST['sesicari']; if($idusul!=0) $sesi='';
		
		$coDaftar = $HTTP_COOKIE_VARS['penatausaha_DaftarPilih'];$cek .=$coDaftar;

		
		$ids= explode(',',$coDaftar); //$_POST['cidBI'];	//id bi barang
		
		
		
		$valuearr = array();
		for($i = 0; $i<count($ids); $i++)	{
			
			$valuearr[]= "('$idusul','".$ids[$i]."','$sesi', '$uid', now())";
			//cek id buku induk sudah ada!
			$aqry = "select count(*) as cnt from penghapusan_usul_det where Id='$idusul' and sesi='$sesi' and id_bukuinduk='".$ids[$i]."' "; $cek.= $aqry;
			$get = mysql_fetch_array(mysql_query(
				$aqry
			));
			if($get['cnt']>0){
				$bi = mysql_fetch_array(mysql_query(
					"select concat(a1,'.',a,'.',b,'.',c,'.',d,'.',substring(thn_perolehan,3,2),'.', e,'.', e1,'.',f,'.',g,'.',h,'.',i,'.',j,'.',noreg) as barcode from buku_induk where Id='".$ids[$i]."' "
				));				
				$err = 'Barang dengan kode '.$bi['barcode'].' sudah ada!';
				break;
			}
		}
		$valuestr = join(',',$valuearr);
		
		
		if($err==''){
			$aqry= "replace into penghapusan_usul_det (Id,id_bukuinduk,sesi, uid, tgl_update) values ".$valuestr; $cek .= $aqry;
			//$aqry= "delete from ".$this->TblName_Hapus.' '.$Kondisi; $cek.=$aqry;
			$qry = mysql_query($aqry);
			if ($qry==FALSE){
				$err = 'Gagal Simpan Data';
			}
			
			//delete waktu dan sesi lebih dari 3 hari
			$aqry = "delete  from penghapusan_usul_det where Id=0 and (sesi IS not null and sesi <>'') and tgl_update  < DATE_SUB(CURDATE(), INTERVAL 2 DAY) ;"; $cek .= $aqry;
			$del = mysql_query($aqry);										
					
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
	}

	
	
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){	
			case 'simpanPilih':{
				$get= $this->simpanPilih();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
				//$cek = 'trs';
				break;
			}
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
				$aqry = "select * from ref_ruang where q='0000' $kondC $kondD $kondE  $kondE1";
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
			case 'formPeriksa':{				
				$fm = $this->setFormPeriksa();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];												
				break;
			}
			case 'simpanPr':{
				
				$get= $this->simpanPr();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
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
			"<script type='text/javascript' src='js/usulanhapussk.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
		
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
		$e = $_REQUEST[$this->Prefix.'SkpdfmSEKSI'];
				
		$this->form_idplh = $cbid[0];
				
		$this->form_fmST = 1;
		
		//get data 
		$aqry = "select * from panitia_pemeriksa where Id ='".$this->form_idplh."' "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp;
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';
		$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		
		$jabatan = $_REQUEST['jabatan'];
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 640;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
		}
		
		$this->form_fields = array(				
			
			'jabatan' => array(  
				'label'=>'Jabatan', 
				'value'=> cmbArray('jabatan',$dt['jabatan'],$this->arrjabatan,'-- PILIH --','')//generate checkbox
			),
			'nama' =>array(
				'label'=>'Nama Panitia',
				'value'=>"<input type='text' name='nama' value='".$dt['nama']."' size=35>", 
				'type'=>''
			),
			
			'nip' =>array(
				'label'=>'NIP',
				'value'=>"<input type='text' name='nip' value='".$dt['nip']."' size=35>", 
				'type'=>''
			),
				
			'dinas' =>array(
				'label'=>'Dinas',
				'value'=>
					'<textarea id="dinas"  name="dinas" style="margin: 2px; width: 453px; height: 40px;">'.$dt['dinas'].'</textarea>',
				'labelWidth'=>120, 
				'type'=>'' , 
				'row_params'=> " valign='top'"
			)	
			
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
			"<input type=hidden id='e1' name='e1' value='".$dt['e1']."'> ".
			"<input type='hidden' value='' id='".$this->Prefix."_idUsul1' name='".$this->Prefix."_idUsul1'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close(1)' >";
		
		
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}

	function setCekBox($cb, $KeyValueStr, $isi){
		$hsl = '';
		if($KeyValueStr!=''){
			
		
			$hsl = "<input type='checkbox' id='".$this->Prefix."_cb$cb' name='".$this->Prefix."_cb[]' 
					value='".$KeyValueStr."' onClick=\"isChecked2(this.checked,'".$this->Prefix."_jmlcek');".$this->Prefix.".cbxPilih(this);\" />";					
		}
		return $hsl;
	}
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$NomorColSpan = $Mode==1? 2: 1;
		$daftar_mode = $_REQUEST['daftar_mode'];
		
				$headerTable =
					"<thead>
						<th class='th01' width='40' rowspan=3>No.</th>
						$Checkbox		
						<th class='th01'>Jabatan</th>
						<th class='th01'>Nama</th>
						<th class='th01' width='20'>NIP</th>
						<th class='th01'>Dinas</th>
						</tr>							
					</thead>";
		return $headerTable;
	}
	
	
	//08maret2013
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		global $Main;
		$daftar_mode = $_REQUEST['daftar_mode'];
					
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $this->arrjabatan[ $isi['jabatan']-1][1]);
		$Koloms[] = array('', $isi['nama']);
		$Koloms[] = array('', $isi['nip']);
		$Koloms[] = array('', $isi['dinas']);
		
		return $Koloms;
	}
	/**
	function setTopBar(){
		//return genSubTitle($this->setTitle(),$this->genMenu());
		return "";
	}
	**/
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
			//genFilterBar(
				//''
				//,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			//);
		//return array('TampilOpt'=>$TampilOpt);
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
		//$id = $_REQUEST['UsulanHapus_idplh']; //ambil data kondisi
		//$sesi = $_REQUEST['sesi'];
		
		$idusul = $_REQUEST['UsulanHapusba_idUsul'];//$_REQUEST['UsulanHapus_idUsul'];
		
		if($idusul != '') $arrKondisi[]='ref_idusulan='."'$idusul'".'ORDER BY jabatan ASC' ;
			 			 
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,e1,nip ";
			
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
$PanitiaPemeriksa = new PanitiaPemeriksaObj();


?>