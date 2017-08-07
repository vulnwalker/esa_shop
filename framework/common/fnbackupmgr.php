<?php


class BackupMgrObj  extends DaftarObj2{	
	var $Prefix = 'backupmgrdb';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_ruang'; //daftar
	var $TblName_Hapus = 'ref_backup';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);		
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Backup Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='database.xls';
	var $Cetak_Judul = 'DAFTAR BACKUP DATABASE';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";
					
		return 
			/*"<script type='text/javascript' src='js/dialog1.js' language='JavaScript' ></script>
			<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>".			*/
			"<script type='text/javascript' src='js/pegawai.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
	
	function setPage_TitleDaftar(){
		return 'Daftar Backup Database';
	}	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Daftar Backup Database';
	}
	
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
/*		
		$fmSKPD = cekPOST('RuangSkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST('RuangSkpdfmUNIT');
		$fmSUBUNIT = cekPOST('RuangSkpdfmSUBUNIT');
		$fmSEKSI = cekPOST('RuangSkpdfmSEKSI');
*/		
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table><br>";
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
	case 'tampilPJruang': {
				$fmPILRUANG = $_REQUEST['fmPILRUANG'];
				
				$arrkond = array();
				$arrkond = explode(' ',$fmPILRUANG);	
				$c = $arrkond[0]; $d = $arrkond[1]; 
				$e = $arrkond[2]; $e1 = $arrkond[3]; $p = $arrkond[4];
				$q = $arrkond[5];
				$arrkond = array();				
				$arrkond[] = " c = '$c' ";
				$arrkond[] = " d = '$d' ";
				$arrkond[] = " e = '$e' ";
				$arrkond[] = " e1 = '$e1' ";
				$arrkond[] = " p = '$p' ";
				$arrkond[] = " q = '$q' ";
				$Kondisi = join(' and ',$arrkond);

				if($Kondisi != '') $Kondisi = ' where '.$Kondisi;

				$aqry = "select * from ref_ruang $Kondisi"; $cek .= $aqry;
				$ruang = mysql_fetch_array(mysql_query($aqry));
				$aqry = "select * from ref_pegawai where id = '".$ruang['ref_idpegawai']."'"; $cek .= $aqry;
				$pgw = mysql_fetch_array(mysql_query($aqry));
				
				$hsl->nip = $pgw['nip'];
				$hsl->nama = $pgw['nama'];
				$hsl->jabatan = $pgw['jabatan'];
				
				$content = $hsl;
				break;
			}
			case 'pilihRuangKIR' :{				
				$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
				
				$arrkond = array();
				$arrkond = explode(' ',$fmPILGEDUNG);	
				$c = $arrkond[0]; $d = $arrkond[1]; 
				$e = $arrkond[2];$e1 = $arrkond[3]; $p = $arrkond[4];
				
				$arrkond = array();
				$arrkond[] =  "q<>'0000'";
				$arrkond[] = " c = '$c' ";
				$arrkond[] = " d = '$d' ";
				$arrkond[] = " e = '$e' ";
				$arrkond[] = " e1 = '$e1' ";
				$arrkond[] = " p = '$p' ";
				$Kondisi = join(' and ',$arrkond);
				
				if($Kondisi != '') $Kondisi = ' where '.$Kondisi;
				$aqry = "select * from ref_ruang $Kondisi";
				$cek .= $aqry;
				$content = genComboBoxQry2( 'fmPILRUANG', $fmPILRUANG, $aqry,
						array('c','d','e','e1','p','q'), 'nm_ruang', '-- Semua Ruang --',"style=''  onChange=\"Penatausaha.refreshList(true);Penatausaha.tampilPJRuang();\" " );				
				break;
			}			
			case 'cbxgedung':{
				$c= $_REQUEST['RuangSkpdfmSKPD'];
				$d= $_REQUEST['RuangSkpdfmUNIT'];
				$e= $_REQUEST['RuangSkpdfmSUBUNIT'];
				$e1= $_REQUEST['RuangSkpdfmSEKSI'];
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
				$kode_ = $_REQUEST['kode']; $cek .='kode='. $kode_;
				
				$kode = explode('.',$kode_);$cek .='p='. $kode[0];
				$p = $kode[0]; $q = $kode[1];
				//$oldkode = explode(' ',$_REQUEST[$this->Prefix.'_idplh']);$cek .='oldp='. $oldkode[0];
				//$oldp = $oldkode[0]; $oldq = $oldkode[1];
				$nm_ruang= $_REQUEST['nm_ruang'];
				$peg_ruang = $_REQUEST['ref_idpengadaan']; //ambil id pegawai ruang
				
				if( $err=='' && strlen($p)!=3 ) $err= 'Penulisan Kode Salah! (ex: 001.0001)';
				if( $err=='' && strlen($q)!=4 ) $err= 'Penulisan Kode Salah! (ex: 001.0001)';
				if( $err=='' && $nm_ruang =='' ) $err= 'Nama Gedung/Ruang belum diisi!';
				
					
				
				if($fmST == 0){//ins
					//cek 
					if( $err=='' ){
						$get = mysql_fetch_array(mysql_query(
							"select count(*) as cnt from ref_ruang where a1='$a1' and a='$a' and b='$b'	 and c='$c' and d='$d' and e='$e' and e1='$e1' and p='$p' and q='$q' "
						));
						if($get['cnt']>0 ) $err='Kode Sudah Ada!';
					}
					if($err==''){
						$aqry = "insert into ref_ruang (a1,a,b,c,d,e,e1,p,q,nm_ruang,ref_idpegawai)"."values('$a1','$a','$b','$c','$d','$e','$e1','$p','$q','$nm_ruang','$peg_ruang')";	$cek .= $aqry;	
						$qry = mysql_query($aqry);
					}
					
				}else{
					if( $err=='' ){
						$old=mysql_fetch_array(mysql_query(
							"select * from ref_ruang where id='$idplh'"
						));
						if($p!=$old['p'] || $q!=$old['q']){
						//if($idplh){
							$get = mysql_fetch_array(mysql_query(
								"select count(*) as cnt from ref_ruang where a1='$a1' and a='$a' and b='$b'	 and c='$c' and d='$d' and e='$e' and e1='$e1' and p='$p' and q='$q' "
							));
							if($get['cnt']>0 ) $err='Kode Sudah Ada!';
						}
					}
					if($err==''){
						/*$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',
							p='$p',q='$q',nm_ruang='$nm_ruang'".
							"where a1='$a1' and a='$a' and b='$b' and c='$c' and d='$d' and e='$e' 
							and p='$oldp' and q='$oldq' ";	$cek .= $aqry;
						*/
						$aqry = "update ref_ruang set ".
							"a1='$a1', a='$a', b='$b', c='$c',d='$d',e='$e',e1='$e1',
							p='$p',q='$q',nm_ruang='$nm_ruang',ref_idpegawai='$peg_ruang'".
							"where id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry);
					}
				}
				
				//
				
				
				
				
				break;
			}
			
			case 'formPilih':{
				//$content='tes';
				$fm = $this->setFormPilih();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				break;
			}
			
			case 'getdata':{
				$id = $_REQUEST['id'];
				$aqry = "select aa.*, bb.nm_ruang as nm_gedung from ref_ruang aa 
					left join ref_ruang bb on aa.a1=bb.a1 and aa.a=bb.a and aa.b=bb.b 
					and aa.c=bb.c and aa.d=bb.d and aa.e=bb.e and aa.e1=bb.e1 
					and aa.p=bb.p and bb.q='0000'
					where aa.id='$id'";
				$get = mysql_fetch_array(mysql_query($aqry));				
				$content = array('id'=>$get['id'], 'p'=>$get['p'], 'q'=>$get['q'], 'nm_ruang'=>$get['nm_ruang'], 'nm_gedung'=>$get['nm_gedung']);
				break;
			}
			default:{
				//$err = 'tipe tidak ada!';
				//$get = set_selector_other2($tipe)
				$other = $this->set_selector_other2($tipe);
				$cek = $other['cek'];
				$err = $other['err'];
				$content=$other['content'];
				$json=$other['json'];
				break;
			}
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
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
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from ref_ruang where id ='".$this->form_idplh."'  "; $cek.=$aqry;
		$dt = mysql_fetch_array(mysql_query($aqry));
		
		
		
		//set form
		$fm = $this->setForm($dt);
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}	
	function setForm($dt){	
		global $SensusTmp,$Main;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		
		
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 700;
		$this->form_height = 250;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$kode	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$kode = $dt['p'].'.'.$dt['q'];			
		}
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );
		//items ----------------------
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$kdSubUnit0."' "));
		$subunit = $get['nm_skpd'];		
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' and e1='".$dt['e1']."' "));
		$seksi = $get['nm_skpd'];
		//ambil pegawai
	    $get = mysql_fetch_array(mysql_query("select * from ref_pegawai where Id = '".$dt['ref_idpegawai']."'"));
		$nama = $get['nama'];
		$nip = $get['nip'];
		$jabatan = $get['jabatan'];		
				
		$this->form_fields = array(		
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'SKPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'UNIT', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'seksi' => array(  'label'=>'SUB UNIT', 'value'=> $seksi, 'labelWidth'=>120, 'type'=>'' ),
			'kode' => array( 'label'=>'Kode', 'value'=> $kode, 'labelWidth'=>120, 'type'=>'text', 'param'=>" maxlength=8 size=8 title='Kode (ex: 001.0002)' "  ),
			'nm_ruang' => array( 'label'=>'Nama Gedung/Ruang', 'value'=>$dt['nm_ruang'], 'type'=>'text', 'param'=>"title='Nama Gedung/Ruang (ex: Gedung Sate/Ruang Perlengkapan)' style='width: 300px;'" ),
			'pegawai_ruangan' => array(  
				'label'=>'Penanggung Jawab Ruang', 
				'value'=> 
					"<input type='hidden' id='ref_idpengadaan' name='ref_idpengadaan' value='".$dt['ref_idpegawai']."'> ".
					"<input type='text' id='nama_pejabat_pengadaan' name='nama_pejabat_pengadaan' readonly=true value='".$nama."' style='width:250'> &nbsp; ".
					"NIP  &nbsp;<input type='text' id='nip_pejabat_pengadaan' name='nip_pejabat_pengadaan' readonly=true value='".$nip."' style='width:150' > ".					
					"<input type='button' value='Pilih' onclick=\"".$this->Prefix.".pilihPejabatPengadaan()\">"
				,
				'type'=>'' 
			),
			'jabat' => array(  'label'=>'', 
				'value'=> "JABATAN  &nbsp;<input type='text' id='jbt_pejabat_pengadaan' readonly=true value='".$jabatan."' style='width:380'> ",  
				'type'=>'' , 'pemisah'=>' '
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
		
		/*
		$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						750,
						450,
						'Sensus Barang - Baru',
						'',
						$this->form_menubawah.
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
		);
		*/
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormPilih(){
		global $SensusTmp, $RuangPilih;
		$cek = ''; $err=''; $content=''; 
		
		$json = TRUE;	//$ErrMsg = 'tes';
		//$FormContent = $this->genDaftarInitial();
		$form_name = 'RuangPilihForm';
		$FormContent = $RuangPilih->genDaftarInitial();
		$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						600,
						400,
						'Pilih Ruang',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".RuangPilihSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".RuangPilihClose()' >".
						"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
		);
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
				<th class='th01' width=150>KODE</th>
				<th class='th01' >NAMA FILE</th>								
				<th class='th01' width=200>SIZE</th>									
				<th class='th01' width=200>TGL. UPDATE</th>									
				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['nm_file'] );
		$Koloms[] = array("align='right'", number_format( $isi['size'] , 0,',','.') );		
		$Koloms[] = array("align='center'", $isi['tgl_update']);
			
		return $Koloms;
	}
	
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmFiltTglBtw = $_REQUEST['fmFiltTglBtw'];
		$fmFiltTglBtw_tgl1 = $_REQUEST['fmFiltTglBtw_tgl1'];
		$fmFiltTglBtw_tgl2 = $_REQUEST['fmFiltTglBtw_tgl2'];
		
			$vtgl ="
			<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tanggal </div>".
			createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1)."
			</td>				
			</tr>
			</tbody></table>
			</td></tr></tbody></table>
		    </div>";				
		$TampilOpt =
			genFilterBar(
				array( 
				"<div style='float:left;padding: 2 8 0 0;height:20;padding: 4 4 0 0'> Tanggal </div>".
				createEntryTglBeetwen('fmFiltTglBtw',$fmFiltTglBtw_tgl1, $fmFiltTglBtw_tgl2,'','','adminForm',1)
				)				
				,$this->Prefix.".refreshList(true)",TRUE, 'Tampilkan'
			);
		return array('TampilOpt'=>$TampilOpt);
	}			
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$cek='';
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
		/*		
		
		$arrKondisi = array();
		$arrKondisi[] = getKondisiSKPD2(
			$Main->DEF_KEPEMILIKAN, 
			$Main->Provinsi[0], 
			$Main->DEF_WILAYAH, 
			cekPOST($this->Prefix.'SkpdfmSKPD'), 
			cekPOST($this->Prefix.'SkpdfmUNIT'), 
			cekPOST($this->Prefix.'SkpdfmSUBUNIT'),
			cekPOST($this->Prefix.'SkpdfmSEKSI')
		);	
		$cek .= 'prefix='.$this->Prefix;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		*/
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		/*
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,e1,p,q ";
		*/
		/*switch($fmORDER1){
			case '1': $arrOrders[] = " no_terima $Asc " ;break;
			case '2': $arrOrders[] = " i $Asc " ;break;
		}*/		
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
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal, 'cek'=>$cek);
		
	}

	function short($mylist){
		$sort = array();
		foreach($mylist as $k=>$v) {
		    $sort['title'][$k] = $v['title'];
			$sort['event_type'][$k] = $v['event_type'];
		}
	# sort by event_type desc and then title asc
	array_multisort($sort['event_type'], SORT_DESC, $sort['title'], SORT_ASC,$mylist);
	}

	function ListFile($direktori='',$tipe='file',$ext='')
	{
	$mylist = array();
	if ($direktori=='') $direktori=dirname(__FILE__);
	$iterator = new DirectoryIterator($direktori);
	$i=0;
	foreach ($iterator as $fileinfo) {
    	echo $fileinfo->getFilename() . " " . $fileinfo->getType() . "<br>";
		if ($fileinfo->getType()=='file'){
			if ($ext!='') {
				if ($fileinfo->getExtension()==$ext){
					$i++;					
					array_push($mylist,array('ID' => $i, 'nm_file' => $fileinfo->getFilename(),
					'size' => $fileinfo->getSize(),'tgl_update'=>date("Y-m-d H:i:s",$fileinfo->getMTime())));
				
				} 
			} else {
					$i++;				
					array_push($mylist,array('ID' => $i, 'nm_file' => $fileinfo->getFilename(),
					'size' => $fileinfo->getSize(),'tgl_update'=>date("Y-m-d H:i:s",$fileinfo->getMTime())));
				
				
			}
		
		}

	}

	return $mylist;
	}

	function genDaftar($Kondisi='', $Order='', $Limit='', $noAwal = 0, $Mode=1, $vKondisi_old=''){
		//$Mode -> 1. daftar, 2. cetak hal, 3.cetak all
		$cek =''; $err='';
					
		$MaxFlush=$this->MaxFlush;		
		$headerTable = $this->genDaftarHeader($Mode);		
		$TblStyle =	$this->TblStyle[$Mode-1];//$Mode ==1 ? 'koptable': 'cetak';
		$ListData = 
			"<table class='$TblStyle' border='1'   style='margin:4 0 0 0;width:100%'>".
			$headerTable.
			"<tbody>";
				
		$ColStyle = $this->ColStyle[$Mode-1];//$Mode==1? 'GarisDaftar':'GariCetak';			
		$no=$noAwal; $cb=0; $jmlDataPage =0;
		$TotalHalRp = 0;
		$mylist = array();
		$mylist =	ListFile();	
		$numrows = count($mylist);

		//$aqry = "select * from $this->TblName $Kondisi $Order $Limit ";	//echo $aqry;
		//$qry = mysql_query($aqry);
		//$aqry = $this->setDaftar_query($Kondisi, $Order, $Limit); $cek .= $aqry.'<br>';
		//$qry = mysql_query($aqry);
		// $numrows = mysql_num_rows($qry); 

		$cek.= " jmlrow = $numrows ";
		if( $numrows> 0 ) {
		foreach ($mylist as $isi) {
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 

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
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
			$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);		
			
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
				$RowAtr, $ColStyle);
			
			$cb++;	
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}								
			
		}			
/*
		while ( $isi=mysql_fetch_array($qry)){
			if ( $isi[$this->KeyFields[0]] != '' ){
				
			
			$no++;
			$jmlDataPage++;
			if($Mode == 1) $RowAtr = $no % 2 == 1? "class='row0'" : "class='row1'";
			
			$KeyValue = array();
			for ($i=0; $i< sizeof($this->KeyFields) ; $i++) {
				$KeyValue[$i] = $isi[$this->KeyFields[$i]];
			}
			$KeyValueStr = join(' ',$KeyValue);
			$TampilCheckBox =  $this->setCekBox($cb, $KeyValueStr, $isi);//$Cetak? '' : 
				
			
			
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
			$Koloms = $this->setKolomData($no,$isi,$Mode, $TampilCheckBox);	$cek .= $Koloms;		
			$list_row = genTableRow($Koloms, 
						$rowatr_,
						$ColStyle);		
			
			
			$ListData .= $this->setDaftar_after_getrow($list_row, $isi , $no, $Mode, $TampilCheckBox,
				$RowAtr, $ColStyle);
			
			$cb++;
			
			if( ($Mode == 3 ) && ($cb % $MaxFlush==0) && $cb >0 ){				
				echo $ListData;
				ob_flush();
				flush();
				$ListData='';
				//sleep(2); //tes
			}
			}
		}
*/		
		}
		
		$ListData .= $this->setDaftar_After($no, $ColStyle);
		//total -----------------------		
		if ($Mode==3) {	//flush
			echo $ListData;
			ob_flush();
			flush();
			$ListData='';			
		//	$SumHal = $this->genSumHal($Kondisi); 			
		}
		//$SumHal = $this->genSumHal($Kondisi);
		$ContentSum = $this->genRowSum($ColStyle,  $Mode, 
			$SumHal['sums']
		);


		
		$ListData .= 
				
				$ContentSum.
				"</tbody>".
			"</table>				
			<input type='hidden' id='".$this->Prefix."_jmldatapage' name='".$this->Prefix."_jmldatapage' value='$jmlDataPage'>
			<input type='hidden' id='".$this->Prefix."_jmlcek' name='".$this->Prefix."_jmlcek' value=''>"
			.$vKondisi_old
			;
		if ($Mode==3) {	//flush
			echo $ListData;	
		}
					
		return array('cek'=>$cek,'content'=>$ListData, 'err'=>$err);
	}
	
	

}
$BackupMgr = new BackupMgrObj();

?>