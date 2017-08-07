<?php

class JurnalKondisiObj  extends DaftarObj2{	
	var $Prefix = 'JurnalKondisi';
	var $elCurrPage="HalDefault";
	var $TblName = 'ref_pegawai'; //daftar
	var $TblName_Hapus = 'ref_pegawai';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('Id');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Referensi Data';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pegawai.xls';
	var $Cetak_Judul = 'DAFTAR PEGAWAI';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	
	//function setPage_TitleDaftar(){	return 'Daftar Pegawai'; }	
	//function setPage_TitlePage(){		return 'Referensi Data';			}
	function setTitle(){
		return 'Daftar Pegawai';
	}
	function setCetak_Header(){
		global $Main, $HTTP_COOKIE_VARS;
		
		$fmSKPD = cekPOST($this->Prefix.'SkpdfmSKPD');// echo 'fmskpd='.$fmSKPD;
		$fmUNIT = cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = cekPOST($this->Prefix.'SkpdfmSUBUNIT');
		return
			"<table style='width:100%' border=\"0\">
			<tr>
				<td class=\"judulcetak\"><DIV ALIGN=CENTER>$this->Cetak_Judul</td>
			</tr>
			</table>	
			<table width=\"100%\" border=\"0\">
				<tr>
					<td class=\"subjudulcetak\">".PrintSKPD2($fmSKPD, $fmUNIT, $fmSUBUNIT)."</td>
				</tr>
			</table><br>";
	}
	//function setPage_IconPage(){		return 'images/masterData_ico.gif';	}	
	
	function setFormKondisi(){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$form_name = $this->Prefix.'_form';				
		$this->form_width = 400;
		$this->form_height = 120;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Update Kondisi Barang';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
		
		$idbi = $_REQUEST['idbi'];
		$aqry = "select * from buku_induk where id ='$idbi'"; $cek .= $aqry;
		$bi = mysql_fetch_array(mysql_query($aqry));
		$kondAwal = $Main->KondisiBarang[$bi['kondisi']-1][1] ;
		$tglKond = Date('Y-m-d');
		$vtgl = createEntryTgl(	'tglKond', $tglKond, 	false, 	'', 	'','adminForm');
		$selKondisi = cmb2D_v2( 'kondisiSkr',$bi['kondisi'],$Main->KondisiBarang );
		$this->form_fields = array(				
			'kond_awal' => array(  'label'=>'Kondisi Awal', 'value'=> $kondAwal, 'labelWidth'=>90, 'type'=>'' ),
			'tgl' => array(  'label'=>'Tanggal', 'value'=> $vtgl,  'type'=>'' ),
			'kond' => array(  'label'=>'Kondisi Sekarang', 'value'=> $selKondisi, 'type'=>'' ),
			'ket'=> array(  'label'=>'Keterangan', 'value'=> "<textarea id='keterangan' name='keterangan' style='width: 234px;height: 35px;'></textarea>", 'type'=>'' )
		);
						
		//tombol
		$this->form_menubawah =			
			"<input type='hidden' name='idbi' id='idbi' value='$idbi'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".formKondisiSimpan()' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".formKondisiClose()' >";
				
		$form = $this->genForm();		
				
		$content = $form;//$content = 'content';
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function formKondisiSimpan(){
		global $Main, $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$tglKond = $_REQUEST['tglKond'];
		$tglArr = explode('-',$tglKond);
		$thnKond = $tglArr[0];
		$idbi = $_REQUEST['idbi'];
		$kondisiSkr = $_REQUEST['kondisiSkr'];
		$UID = $HTTP_COOKIE_VARS['coID'];
		$ket = $_REQUEST['keterangan'];
		//$jnskoreksi = $_REQUEST['jnskoreksi'];
		
		$bi = mysql_fetch_array(mysql_query("select * from buku_induk where id='$idbi'"));
		
		if($err=='' && $bi['kondisi'] == $kondisiSkr  ) $err = 'Tidak ada perubahan kondisi!';
		if($err=='' && empty($kondisiSkr)) $err = 'Kondisi sekarang belum diisi!';
		if($err=='' && empty($tglKond)) $err = 'Tanggal belum diisi!';
		if($err=='' && $thnKond <= $Main->TAHUN_CLOSING ) $err= 'Data Tidak Dapat Disimpan!, Tahun '.$Main->TAHUN_CLOSING.' sudah closing'; 
		
		if($err==''){
			//$jns=11;
			//simpan ke transaksi
			$aqry = " insert into kondisi(tgl,idbi,kond_awal,kond_akhir,ket, tgl_update,uid) values  ".
				"('$tglKond','$idbi','".$bi['kondisi']."','$kondisiSkr','$ket', now(),'$UID' ) ";$cek .= $aqry;
			$qry = mysql_query($aqry);
			if($qry == FALSE) {
				$err= 'Gagal Simpan Data!';
			} else{
				$content->nmkondisi =  $Main->KondisiBarang[$kondisiSkr-1][1] ;
				$content->kondisi =  $kondisiSkr;//$Main->KondisiBarang[$kondisiSkr-1][1] ;
			}
			
			//simpan Jurnal ke RB
			//$aqry = " insert into jurnal(jns,ref_id,tgl,ka,kb,kc,kd,ke,debet,kredit,idbi,stbatal,uid,tgl_update,kondisi,idawal) values ".
			//	"('$jns',ref_id,tgl,ka,kb,kc,kd,ke,debet,kredit,idbi,stbatal,uid,tgl_update,kondisi,idawal)";				
		}
		
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	function set_selector_other($tipe){
		global $Main;
		$cek = ''; $err=''; $content=''; $json=TRUE;
		switch($tipe){
			case 'formKondisi':{				
				$fm = $this->setFormKondisi();				
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
				//$content = 'tesssss';
				break;
			} 	
			/*case 'formBaru':{				
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
			}*/
			case 'formKondisiSimpan':{				
				$get= $this->formKondisiSimpan();
				$cek = $get['cek'];
				$err = $get['err'];
				$content = $get['content'];
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
		}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$dt['c'] = $_REQUEST[$this->Prefix.'SkpdfmSKPD'];
		$dt['d'] = $_REQUEST[$this->Prefix.'SkpdfmUNIT'];
		$dt['e'] = $_REQUEST[$this->Prefix.'SkpdfmSUBUNIT'];
		
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
		$e = $_REQUEST[$this->Prefix.'SkpdSUBUNIT'];
				
		$this->form_idplh = $cbid[0];
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		
		//get data 
		//$aqry = "select * from ref_ruang where c='$c' and d='$d' and e='$e' and p ='".$kode[0]."' and q='".$kode[1]."' "; $cek.=$aqry;
		$aqry = "select * from ref_pegawai where id ='".$this->form_idplh."'  "; $cek.=$aqry;
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
		$this->form_width = 500;
		$this->form_height = 150;
		if ($this->form_fmST==0) {
			$this->form_caption = 'Baru';
			$nip	 = '';
		}else{
			$this->form_caption = 'Edit';			
			$nip = $dt['nip'];			
		}
		
		//items ----------------------
		//$sesi = gen_table_session('sensus','uid');
		//style='width: 318px;text-transform: uppercase;'
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='00' "));
		$bidang = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='00' "));
		$unit = $get['nm_skpd'];
		$get=mysql_fetch_array(mysql_query("select * from ref_skpd where c='".$dt['c']."' and d='".$dt['d']."' and e='".$dt['e']."' "));
		$subunit = $get['nm_skpd'];		
		
		
		$this->form_fields = array(				
			'bidang' => array(  'label'=>'BIDANG', 'value'=> $bidang, 'labelWidth'=>120, 'type'=>'' ),
			'unit' => array(  'label'=>'ASISTEN / OPD', 'value'=> $unit, 'labelWidth'=>120, 'type'=>'' ),
			'subunit' => array(  'label'=>'BIRO / UPTD/B', 'value'=> $subunit, 'labelWidth'=>120, 'type'=>'' ),
			'nip' => array( 'label'=>'NIP', 'value'=> $nip, 'labelWidth'=>120, 'type'=>'text' ),
			'nama' => array( 'label'=>'Nama Pegawai', 'value'=>$dt['nama'], 'type'=>'text'  ),	
			'jabatan' => array( 'label'=>'Jabatan', 'value'=>$dt['jabatan'], 'type'=>'text')	
		);
		
				
		//tombol
		$this->form_menubawah =
			"<input type=hidden id='c' name='c' value='".$dt['c']."'> ".
			"<input type=hidden id='d' name='d' value='".$dt['d']."'> ".
			"<input type=hidden id='e' name='e' value='".$dt['e']."'> ".
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
				<th class='th01' width=150>NIP</th>
				<th class='th01' >NAMA </th>
				<th class='th01' >JABATAN </th>								
				
				</tr>
				
			</thead>";
		return $headerTable;
	}
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		$Koloms = array();
		$Koloms[] = array('align=right', $no.'.' );
		if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
		$Koloms[] = array('', $isi['nip']);		
		$Koloms[] = array('', $isi['nama'] );
		$Koloms[] = array('', $isi['jabatan']);				
		return $Koloms;
	}
	function genDaftarOpsi(){
		global $Ref, $Main;
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];		
		$TampilOpt =
			"<table width=\"100%\" class=\"adminform\">	<tr>		
			<td width=\"100%\" valign=\"top\">" . 
				//WilSKPD_ajx($this->Prefix) . 
				WilSKPD_ajx($this->Prefix.'Skpd') . 
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
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		
		$arrKondisi = array();				
		$fmSKPD = isset($HTTP_COOKIE_VARS['cofmSKPD'])? $HTTP_COOKIE_VARS['cofmSKPD']: cekPOST($this->Prefix.'SkpdfmSKPD');
		$fmUNIT = isset($HTTP_COOKIE_VARS['cofmUNIT'])? $HTTP_COOKIE_VARS['cofmUNIT']: cekPOST($this->Prefix.'SkpdfmUNIT');
		$fmSUBUNIT = isset($HTTP_COOKIE_VARS['cofmSUBUNIT'])? $HTTP_COOKIE_VARS['cofmSUBUNIT']: cekPOST($this->Prefix.'SkpdfmSUBUNIT');		
		$arrKondisi[] = getKondisiSKPD2(
			'', 
			$Main->Provinsi[0], 
			'00', 
			$fmSKPD, 
			$fmUNIT, 
			$fmSUBUNIT
		);
		 	
		$fmPILGEDUNG = $_REQUEST['fmPILGEDUNG'];
		if (!empty($fmPILGEDUNG)) $arrKondisi[] = "p='$fmPILGEDUNG'";
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');				
		$Asc = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		$arrOrders[] = " a,b,c,d,e,nip ";
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
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
		
	}
	
}
$JurnalKondisi = new JurnalKondisiObj();


?>