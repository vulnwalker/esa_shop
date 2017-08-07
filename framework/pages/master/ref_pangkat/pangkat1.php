<?php

class pangkatObj  extends DaftarObj2{	
	var $Prefix = 'pangkat';
	var $elCurrPage="HalDefault";
	var $SHOW_CEK = TRUE;
	var $TblName = 'ref_pangkat'; //bonus
	var $TblName_Hapus = 'ref_pangkat';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('f','g','h','i','j');
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'ADMINISTRASI SYSTEM';
	var $PageIcon = 'images/administrasi_ico.png';
	var $pagePerHal ='';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='barang.xls';
	var $namaModulCetak='ADMINISTRASI SYSTEM';
	var $Cetak_Judul = 'Pangkat';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	var $FormName = 'pangkatForm';
	var $noModul=1; 
	var $TampilFilterColapse = 0; //0
	
	
	function setTitle(){
	$status_filter=$_REQUEST['status_filter'];
	if ($status_filter==1){
	return "Cari Barang";
	}else{
		return 'Ref Barang (E - Inventory	)';
	}
	}
	
	function setMenuEdit(){
	$status_filter=$_REQUEST['status_filter'];
	if ($status_filter==1){
	return "";
	}else{
		return
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Baru()","sections.png","Baru", 'Baru')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Edit()","edit_f2.png","Edit", 'Edit')."</td>".
		"<td>".genPanelIcon("javascript:".$this->Prefix.".Hapus()","delete_f2.png","Hapus", 'Hapus')."</td>";
		
	}
	}
	
	function setMenuView(){
		$status_filter=$_REQUEST['status_filter'];
	if ($status_filter==1){
	return "";
	}else{
	return
			//"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png","SPPT",'Cetak SPPT')."</td>".
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakHal(\"$Op\")","print_f2.png",'Halaman',"Cetak Daftar per Halaman")."</td>".			
			"<td>".genPanelIcon("javascript:".$this->Prefix.".cetakAll(\"$Op\")","print_f2.png",'Semua',"Cetak Semua Daftar")."</td>";
			}
			}
	
	function setPage_OtherScript(){
		$scriptload = 
					"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
							//document.getElementById('".$this->Prefix."_cont_skpd').innerHTML='<div id=\"skpd_penerimaan\"></div>';
							//skpd_penerimaan.initial();
						});
					</script>";
		return 	
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".
			"<link href='css/ui-lightness/jquery-ui-1.10.3.custom.css' rel='stylesheet'>".
			"<script src='js/jquery-ui.custom.js'></script>".
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			 "<script type='text/javascript' src='js/master/ref_pangkat/".strtolower($this->Prefix).".js' language='JavaScript' ></script>
			 ".
			
			$scriptload;
	}
	
	function genDaftarOpsi(){
	 global $Ref, $Main;
		$status_filter=$_REQUEST['status_filter'];
		$fmKodeBarang = $_REQUEST ['fmKodeBarang'];
		$fmNamaBrg = $_REQUEST ['nama'];	 
		$fmBidang = $_REQUEST ['fmBidang'];
		$fmKelompok = $_REQUEST['fmKelompok'];
		$fmObjek = $_REQUEST['fmObjek'];
		$fmJenis = $_REQUEST['fmJenis'];
		$fmNmBrg = $_REQUEST['fmNmBrg'];
		$fmOrder = $_REQUEST['arrOrder'];
		
	//	$queryBidang=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='00' and h='00' and i='000' and j='0000'" ));
	
		//$queryKelompok="SELECT g,nama FROM ref_barang WHERE f='$Main->f' and g <> '00' and h='00' and i='000' and j='0000'" ;
	$queryKelompok = "SELECT g, concat(g, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' AND g <>'00' AND h='00' AND i='000' AND j='0000'";
		
		$queryObjek="SELECT h, concat(h, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h <> '00' and i='000' and j='0000'" ;
	
		$queryJenis="SELECT i,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h='$fmObjek' and i <>'000' and j='0000'" ;

		$queryNmBrg="SELECT j,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h='$fmObjek' and i='$fmJenis' and j <> '0000'" ;
	
	
	$VFilter = "<table style='width:100%'>
						<tr>
						<td width='100px'>Jenis &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:</td>
						<td> <input type='text' name='' id='' value='".$queryBidang['f'].".".$queryBidang['nama']."' size='30' readonly>
							 <input type ='hidden' name='fmBidang' id='fmBidang' value='".$queryBidang['f']."' readonly></td>
						</tr>
						<tr>
						<td width='75px'>Objek &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: </td>
						<td><div id='cont_kelompok' >".cmbQuery('fmKelompok',$fmKelompok,$queryKelompok,'style="width:210;"onchange="'.$this->Prefix.'.pilihKelompok()"','&nbsp&nbsp----- Pilih Objek -----')."</div></td>
						</tr>
						<tr>
						<td width='75px'>Rincian Objek &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:</td>
						<td><div id='cont_object'>".cmbQuery('fmObjek',$fmObjek,$queryObjek,'style="width:210;"onchange="'.$this->Prefix.'.pilihObjek()"','&nbsp&nbsp----- Pilih Rincian Objek -----')."</div></td>
						</tr>
						<tr>
						<td width='75px'>Sub Rincian Objek &nbsp:</td>
						<td><div id='cont_jenis'>".cmbQuery('fmJenis',$fmJenis,$queryJenis,'style="width:210;"onchange="'.$this->Prefix.'.pilihJenis()"','&nbsp&nbsp----- Pilih Sub Rincian Objek -----')."</div></td>
						</tr>	
						<tr>
						<td width='75px'>Nama Barang &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:</td>
						<td><div id='cont_NmBrg'>".cmbQuery('fmNmBrg',$fmNmBrg,$queryNmBrg,'style="width:210;"onchange="'.$this->prefix.'.pilihNmBrg()"','&nbsp&nbsp----- Pilih Nama Barang -----')."</div></td>
						</tr>
						</table>";		
	 
	$arrOrder = array(
			     	array('1','Kode Barang'),
			     	array('2','Nama Barang'),
					); 
	  
	$queryCari = "SELECT Id,nama FROM ref_jenis" ;
		
	$TampilOpt = 									
					"<div class='FilterBar'>
					$VFilter
						<table style='width:100%'>
						<tr>
						<td width='100 px'>Cari Data &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: </td>
							<td ><input type='text' name='fmKodeBarang' id='fmKodeBarang' value='$fmKodeBarang' placeholder='Kode Barang'>
							<input type='text' name='nama' id='fmNamaBrg' value='$fmNamaBrg'placeholder='Nama Barang'>
							
						".cmbArray('arrOrder',$fmOrder,$arrOrder,'--Urutkan--','').
						"<input $fmDESC1 type='checkbox' id='fmDESC1' name='fmDESC1' value='checked'>&nbspmenurun.
						<input type='hidden' id='status_filter' name='status_filter' value='$status_filter'>
						<input type='button' id='btTampil' value='Tampilkan' onclick='".$this->Prefix.".refreshList(true)'>	
						</td>
						</tr>
						</table>
						</div>";

		return array('TampilOpt'=>$TampilOpt);
	}			

	
	function getDaftarOpsi($Mode=1){
		global $Main, $HTTP_COOKIE_VARS;
		$UID = $_COOKIE['coID']; 
		//kondisi -----------------------------------
				
		$arrKondisi = array();
		$fmKodeBarang = $_REQUEST ['fmKodeBarang'];
		$fmNamaBrg = $_REQUEST ['nama'];		
		$fmBidang = $_REQUEST ['fmBidang'];
		$fmKelompok = $_REQUEST['fmKelompok'];
		$fmObjek = $_REQUEST['fmObjek'];
		$fmJenis = $_REQUEST['fmJenis'];
		$fmNmBrg = $_REQUEST['fmNmBrg'];
		$arrOrder = $_REQUEST['arrOrder'];
		$status_filter=$_REQUEST['status_filter'];
		//$arrKondisi[]= " f = '".$Main->f."'";
		
	//	$arrKondisi[]= " f = '".$Main->f."'";	
		if(!empty($fmBidang)) $arrKondisi[]= " f = '$fmBidang'";
		if(!empty($fmKelompok)) $arrKondisi[]= " g = '$fmKelompok'";
		if(!empty($fmObjek)) $arrKondisi[]= " h = '$fmObjek'";
		if(!empty($fmJenis)) $arrKondisi[]= " i = '$fmJenis'";
		if(!empty($fmNmBrg)) $arrKondisi[]= " j = '$fmNmBrg'";	
		if ($status_filter==1)
		//{*/
		{$arrKondisi[]= " f = '".$Main->f."'";
		//if(!empty($fmBidang)) $arrKondisi[]= " f <> '00'";
		$arrKondisi[]= " g <> '00'";
		$arrKondisi[]= " h <> '00'";
		$arrKondisi[]= " i <> '000'";
		$arrKondisi[]= " j <> '0000'";		
		}else{
			
		}
		if(!empty($fmKodeBarang)) $arrKondisi[]= " concat(f,'.',g,'.',h,'.',i,'.',j) like '%$fmKodeBarang%'";
		if(!empty($fmNamaBrg)) $arrKondisi[]= " nama like '%$fmNamaBrg%'";
		
		$Kondisi= join(' and ',$arrKondisi);		
		$Kondisi = $Kondisi =='' ? '':' Where '.$Kondisi;
		
		//Order -------------------------------------
		$fmORDER1 = cekPOST('fmORDER1');
		$fmDESC1 = cekPOST('fmDESC1');			
		$Asc1 = $fmDESC1 ==''? '': 'desc';		
		$arrOrders = array();
		
		switch($arrOrder){
			case '1': $arrOrders[] = " concat(f,g,h,i,j) $Asc1 " ;break;
			case '2': $arrOrders[] = " nama $Asc1 " ;break;
		}
		
		$Order= join(',',$arrOrders);	
		$OrderDefault = '';// Order By no_terima desc ';
		$Order =  $Order ==''? $OrderDefault : ' Order By '.$Order;
		$pagePerHal = $this->pagePerHal =='' ? $Main->PagePerHal: $this->pagePerHal; 
		$HalDefault=cekPOST($this->Prefix.'_hal',1);					
		
		$Limit = " limit ".(($HalDefault	*1) - 1) * $pagePerHal.",".$pagePerHal; //$LimitHal = '';
		$Limit = $Mode == 3 ? '': $Limit;
		//noawal ------------------------------------
		$NoAwal= $pagePerHal * (($HalDefault*1) - 1);							
		$NoAwal = $Mode == 3 ? 0: $NoAwal;	
		
		return array('Kondisi'=>$Kondisi, 'Order'=>$Order ,'Limit'=>$Limit, 'NoAwal'=>$NoAwal);
	}	
	
		
	function set_selector_other2($tipe){
	 global $Main;
	 
	 $cek = ''; $err=''; $content=''; $json=TRUE;	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihKelompok(){
	 global $Main;	 
	
		$fmKelompok = $_REQUEST['fmKelompok'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$queryObjek="SELECT h, concat(h, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h <> '00' and i='000' and j='0000'" ;
		$content->unit=cmbQuery('fmObjek',$fmObjek,$queryObjek,'style="width:210;"onchange="'.$this->Prefix.'.pilihObjek()"','&nbsp&nbsp----- Pilih Rincian Objek-----');
	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function pilihKelompok2(){
	 global $Main;	 
	
		$fmKelompok2 = $_REQUEST['fmKelompok2'];
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 	
		$queryObjek2="SELECT h, concat(h, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h <> '00' and i='000' and j='0000'" ; $cek.=$queryObjek2;
		$content->unit=cmbQuery('fmObjek2',$fmObjek2,$queryObjek2,'style="width:210;"onchange="'.$this->Prefix.'.pilihObjek2()"','&nbsp&nbsp-------- Pilih Rincian Objek -------');
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function pilihObjek(){
	 global $Main;
	
		$fmKelompok = $_REQUEST['fmKelompok'];	 
		$fmObjek = $_REQUEST['fmObjek'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryJenis="SELECT i, concat(i, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h='$fmObjek' and i <>'000' and j='0000'" ; $cek=$queryJenis;
		$content->jenis=cmbQuery('fmJenis',$fmJenis,$queryJenis,'style="width:210;"onchange="'.$this->Prefix.'.pilihJenis()"','&nbsp&nbsp----- Pilih Sub Rincian Objek -----');	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihObjek2(){
	 global $Main;
	
		$fmKelompok2 = $_REQUEST['fmKelompok2'];	 
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryJenis="SELECT i, concat(i, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i <>'000' and j='0000'" ;
		$content->unit=cmbQuery('fmJenis',$fmJenis,$queryJenis,'style="width:210;"onchange="'.$this->Prefix.'.pilihJenis2()"','&nbsp&nbsp-------- Pilih Sub Rincian Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Baru' >";$cek.=$queryJenis;
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihJenis(){
	 global $Main;
	 
		$fmKelompok = $_REQUEST['fmKelompok'];	 
		$fmObjek = $_REQUEST['fmObjek'];
		$fmJenis = $_REQUEST['fmJenis'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$queryNmBrg="SELECT j, concat(j, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h='$fmObjek' and i='$fmJenis' and j <> '0000'" ; $cek.=$queryNmBrg;
		$content->unit=cmbQuery('fmNmBrg',$fmNmBrg,$queryNmBrg,'style="width:210;"onchange="'.$this->Prefix.'.pilihNmBrg()"','&nbsp&nbsp----- Pilih Nama barang -----');
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihJenis2(){
	 global $Main;
	 
		$fmKelompok2 = $_REQUEST['fmKelompok2'];	 
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$fmJenis2 = $_REQUEST['fmJenis'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		
		$queryNmBrg2="SELECT max(j) as j ,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i='$fmJenis2' and j <> '0000'" ; $cek.=$queryNmBrg2;
		$get=mysql_fetch_array(mysql_query($queryNmBrg2));
		$lastkode=$get['j'];
		$kode = (int) substr($lastkode, 1, 3);
		$kode++;
		$no_ba = sprintf("%04s", $kode);
		$content->j=$no_ba;
			
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function pilihSatuanBesar(){
	 global $Main;
	
	 	$StBsr = $_REQUEST['StBsr'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$querypilihSatuanBesar="SELECT nama,nama FROM ref_satuan" ; $cek.=$querypilihSatuanBesar;
		$content->unit=cmbQuery('StBsr',$StBsr,$querypilihSatuanBesar,'style="width:210;"onchange="'.$this->Prefix.'.pilihStBrs()"','--PILIH Satuan Besar--')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanBesar()' title='Baru' >";
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function pilihSatuanBesar1(){
	 global $Main;
	
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$querypilihSatuanBesar="SELECT nama,nama FROM ref_satuan" ; $cek.=$querypilihSatuanBesar;
		$content->unit=cmbQuery('fmStBsr',$fmStBsr,$querypilihSatuanBesar,'style="width:210;"onchange="'.$this->Prefix.'.pilihStBrs()"','--PILIH Satuan Besar--');
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihSatuanKecil1(){
	 global $Main;
	 
		
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$querypilihSatuanKecil="SELECT nama,nama FROM ref_satuan" ; $cek.=$querypilihSatuanKecil;
		$content->unit=cmbQuery('fmStKcl',$fmStKcl,$querypilihSatuanKecil,'style="width:210;"onchange="'.$this->Prefix.'.pilihStKecil()"','--PILIH Satuan Kecil--');
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function pilihSatuanKecil(){
	 global $Main;
	 
		$StKcl = $_REQUEST['StKcl'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$querypilihSatuanKecil="SELECT nama,nama FROM ref_satuan" ; $cek.=$querypilihSatuanKecil;
		$content->unit=cmbQuery('fmStKcl',$StKcl,$querypilihSatuanKecil,'style="width:210;"onchange="'.$this->Prefix.'.pilihStKecil()"','--PILIH Satuan Kecil--')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanKecil()' title='Baru' >";
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function refreshJenis(){
	 global $Main;
	 
		$fmKelompok2 = $_REQUEST['fmKelompok2'];	 
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$fmJenis2 = $_REQUEST['fmJenis2'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
		$fmJenis= $_REQUEST['id_jenisBaru'];
	 
		$queryJenis="SELECT i,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i <>'000' and j='0000'" ;
		$content->unit=cmbQuery('fmJenis',$fmJenis,$queryJenis,'style="width:210;"onchange="'.$this->Prefix.'.pilihJenis2()"','&nbsp&nbsp-------- Pilih Objek -------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Baru' >";
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function refreshSatuanBesar(){
	 global $Main;
	
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$StBsr= $_REQUEST['SatuanBesarBaru'];
	 	$querySatuanBesar="SELECT nama,nama FROM ref_satuan " ;
		$content->unit=cmbQuery('StBsr',$StBsr,$querySatuanBesar,'onchange="'.$this->Prefix.'.pilihSatuanBesar()"','-- PILIH Satuan Besar --')."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanBesar()' title='Baru' >";
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function refreshSatuanBesar1(){
	 global $Main;
	
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$StBsr= $_REQUEST['SatuanBesarBaru1'];
	 	$querySatuanBesar1="SELECT nama,nama FROM ref_satuan " ;
		$content->unit=cmbQuery('StBsr',$StBsr,$querySatuanBesar1,'onchange="'.$this->Prefix.'.pilihSatuanBesar()"','-- PILIH Satuan Besar --')."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanBesar1()' title='Baru' >";	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function refreshSatuanKecil(){
	 global $Main;
		 
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$StKcl= $_REQUEST['SatuanKecilBaru'];
		$querySatuanKecil="SELECT nama,nama FROM ref_satuan " ;
		$content->unit=cmbQuery('StKcl',$StKcl,$querySatuanKecil,'onchange="'.$this->Prefix.'.pilihSatuanKecil()"','-- PILIH Satuan Kecil --')."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanKecil()' title='Baru' >";	
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function refreshSatuanKecil1(){
	 global $Main;
	
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$StKcl= $_REQUEST['SatuanKecilBaru1'];
	 	$querySatuanKecil="SELECT nama,nama FROM ref_satuan " ;
		$content->unit=cmbQuery('StKcl',$StKcl,$querySatuanKecil,'onchange="'.$this->Prefix.'.pilihSatuanKecil()"','-- PILIH Satuan Kecil --')."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanKecil()' title='Baru' >";
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function getKodeBarang(){
	 global $Main;
	 
	 	$fmKelompok2 = $_REQUEST['fmKelompok2'];	 
	 	$fmObjek2 = $_REQUEST['fmObjek2'];
	 	$fmJenis2 = $_REQUEST['fmJenis'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$fmJenis= $_REQUEST['id_jenisBaru'];
	 
		$queryJenis="SELECT i,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i <>'000' and j='0000'" ;
		$content->unit=cmbQuery('fmJenis',$fmJenis,$queryJenis,'onchange="'.$this->Prefix.'.pilihJenis2()"','--PILIH Jenis--')."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Baru' >";
		$aqry2="SELECT MAX(j) AS maxno FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i='$fmJenis2'"; $cek.=$aqry2;
		$get1=mysql_fetch_array(mysql_query($aqry2));
		$lastkode1=$get1['maxno'];
		$kode1 = (int) substr($lastkode1, 1, 4);
		$kode1++;
		$j = sprintf("%04s", $kode1);
		$content->j=$j;
		
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	
	}
	
	function getKodeBarang2(){
	 global $Main;
	 
	 	$fmKelompok2 = $_REQUEST['fmKelompok2'];	 
	 	$fmObjek2 = $_REQUEST['fmObjek2'];
	 	$fmJenis2 = $_REQUEST['fmJenis'];
	 	$cek = ''; $err=''; $content=''; $json=TRUE;
	 	$fmJenis= $_REQUEST['id_jenisBaru'];
	 
		$aqry2="SELECT MAX(j) AS maxno FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i='$fmJenis2'"; $cek.=$aqry2;
		$get1=mysql_fetch_array(mysql_query($aqry2));
		$lastkode1=$get1['maxno'];
		$kode1 = (int) substr($lastkode1, 1, 4);
		$kode1++;
		$j = sprintf("%04s", $kode1);
		$content->j=$j;
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	function Tambah_Id_barang(){
	 global $Main;
	 
	 	$form_name = $this->Prefix.'_formJenis';
	 	$fmKelompok3 = $_REQUEST['fmKelompok3'];
		$fmObjek3 = $_REQUEST['fmObjek3'];
		$fmJenis3 = $_REQUEST['fmJenis3'];
		
		$aqry3="SELECT MAX(j) AS maxno FROM ref_barang WHERE g='$fmKelompok3' and h='$fmObjek3' and i='$fmJenis3'";
		$get1=mysql_fetch_array(mysql_query($aqry2));
		$lastkode1=$get1['maxno'];
		$kode1 = (int) substr($lastkode1, 1, 3);
		$kode1++;
		$j = sprintf("%03s", $kode1);
		
		$this->form_fields = array(				 
			'namaBarang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='j' id='j' value='".$j."' style='width:50px;' readonly>
						<input type='text' name='j' id='j' value='".$j."' style='width:200px;'>
						</div>", 
						 ),			 
						 
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function pilihNmBrg(){
	 global $Main;
	 
	 $fmKelompok = $_REQUEST['fmKelompok'];	 
	 $fmObjek = $_REQUEST['fmObjek'];
	 $fmJenis = $_REQUEST['fmJenis'];
	 $fmNmBrg =$_REQUEST['fmNmBrg'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	 
		$queryNmBrg="SELECT j,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok' and h='$fmObjek' and i='$fmJenis' and j <> '0000'" ;
		$content->unit=cmbQuery('fmNmBrg',$fmNmBrg,$queryNmBrg,'','--PILIH fmNmBrg--');
	 return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
	
	
	function set_selector_other($tipe){
	 global $Main;
	 
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	  
	  switch($tipe){
	  	
		case 'pilihKelompok':{				
			$fm = $this->pilihKelompok();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihKelompok2':{				
			$fm = $this->pilihKelompok2();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihObjek':{				
			$fm = $this->pilihObjek();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihObjek2':{				
			$fm = $this->pilihObjek2();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihJenis':{				
			$fm = $this->pilihJenis();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihJenis2':{				
			$fm = $this->pilihJenis2();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihSatuanBesar':{				
			$fm = $this->pilihSatuanBesar();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'pilihSatuanBesar1':{				
			$fm = $this->pilihSatuanBesar1();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihSatuanKecil1':{				
			$fm = $this->pilihSatuanKecil1();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihSatuanKecil':{				
			$fm = $this->pilihSatuanKecil();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'pilihNmBrg':{				
			$fm = $this->pilihNmBrg();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaru':{				
			$fm = $this->setFormBaru();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaruJenis':{				
			$fm = $this->setFormBaruJenis();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaruSatuanBesar':{				
			$fm = $this->setFormBaruSatuanBesar();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		case 'formBaruSatuanBesar1':{				
			$fm = $this->setFormBaruSatuanBesar1();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaruSatuanKecil':{				
			$fm = $this->setFormBaruSatuanKecil();				
			$cek = $fm['cek'];
			$err = $fm['err'];
			$content = $fm['content'];												
		break;
		}
		
		case 'formBaruSatuanKecil1':{				
			$fm = $this->setFormBaruSatuanKecil1();				
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
			//}	
		break;
		}
		
		case 'formHapus':{	
			/*$cbid = $_REQUEST[$this->Prefix.'_cb'];
			$this->form_idplh = $cbid[0];	
			$pil=$this->form_idplh;
			
			$pil4=mysql_query("SELECT count(*) as cnt, ref_barang . f ,  ref_barang . g ,  ref_barang . h ,  ref_barang . i , ref_barang . j  FROM ref_barang  INNER JOIN  t_persediaan  ON  t_persediaan . f  =  ref_barang . f  AND  t_persediaan . g  =  ref_barang . g  AND  t_persediaan . h  =  ref_barang . h  AND  t_persediaan . i  =  ref_barang . i  AND  t_persediaan . j  =  ref_barang . j where concat (ref_barang.f,' ',ref_barang.g,' ',ref_barang.h,' ',ref_barang.i,' ',ref_barang.j)='$pil'");
			$pil5=mysql_fetch_array($pil4);
			
			$pil41=mysql_query("SELECT count(*) as cnt, ref_barang . f ,  ref_barang . g ,  ref_barang . h ,  ref_barang . i ,  ref_barang . j  FROM  ref_barang  left JOIN  ref_mapping_akun  ON  ref_mapping_akun . f  =  ref_barang . f  AND  ref_mapping_akun . g  =  ref_barang . g  AND  ref_mapping_akun . h  =  ref_barang . h  WHERE  ref_barang . i  = 000 AND  ref_barang . j  = 0000 and concat (ref_barang.f,' ',ref_barang.g,' ',ref_barang.h,' ',ref_barang.i,' ',ref_barang.j) ='$pil'");
			$pil51=mysql_fetch_array($pil41);
			
			
			if ($pil51['cnt'] > 0 ){
			$err ='data tidak bisa di Hapus karena sudah ada di data Mapping !!';
			}else{	*/
				$fm= $this->Hapus($pil);
				$err= $fm['err']; 
				$cek = $fm['cek'];
				$content = $fm['content'];
			//}
		break;
		}			
		case 'simpan':{
			$get= $this->simpan();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanEdit':{
			$get= $this->simpanEdit();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanJenisLvl4':{
			$get= $this->simpanJenisLvl4();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanJenis':{
			$get= $this->simpanJenis();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanSatuanBesar':{
			$get= $this->simpanSatuanBesar();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanSatuanBesar1':{
			$get= $this->simpanSatuanBesar1();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanSatuanKecil':{
			$get= $this->simpanSatuanKecil();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'simpanSatuanKecil1':{
			$get= $this->simpanSatuanKecil1();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'refreshJenis':{
			$get= $this->refreshJenis();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'refreshSatuanBesar':{
			$get= $this->refreshSatuanBesar();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'refreshSatuanBesar1':{
			$get= $this->refreshSatuanBesar1();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'refreshSatuanKecil':{
			$get= $this->refreshSatuanKecil();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		
		case 'refreshSatuanKecil1':{
			$get= $this->refreshSatuanKecil1();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'getKodeBarang':{
			$get= $this->getKodeBarang();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		case 'getKodeBarang2':{
			$get= $this->getKodeBarang2();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		
		case 'Tambah_Id_barang':{
			$get= $this->Tambah_Id_barang();
			$cek = $get['cek'];
			$err = $get['err'];
			$content = $get['content'];
		break;
	    }
		
		
		case 'test':{
			
			$cek = "cek";
			$err = "test";
			$json = FALSE;
			
			echo"<html>
				<head>
				<link type='text/css' href='css/menu.css' rel='stylesheet'></link>
				<link type='text/css' href='css/template_css.css' rel='stylesheet'></link>
				<link type='text/css' href='css/theme.css' rel='stylesheet'></link>
				<link type='text/css' href='dialog/dialog.css' rel='stylesheet'></link>
				<link type='text/css' href='lib/chatx/chatx.css' rel='stylesheet'></link>
				<link type='text/css' href='css/base.css' rel='stylesheet'></link>
				 <link href='css/metro-icons.css' rel='stylesheet'>
				<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>			
				<script type='text/javascript' src='lib/js/JSCookMenu_mini.js' language='JavaScript'></script>	
				<script type='text/javascript' src='lib/js/ThemeOffice/theme.js' language='JavaScript'></script>	
				<script type='text/javascript' src='lib/js/joomla.javascript.js' language='JavaScript'>	</script>				
				<script type='text/javascript' src='js/jquery.js' language='JavaScript' ></script>	
				<script type='text/javascript' src='js/ajaxc2.js' language='JavaScript' ></script>
				<script type='text/javascript' src='dialog/dialog.js' language='JavaScript'></script>
				<script type='text/javascript' src='js/global.js' language='JavaScript'></script>
				<script type='text/javascript' src='js/base.js' language='JavaScript'></script>
				<script type='text/javascript' src='js/encoder.js' language='JavaScript'></script>
				<script type='text/javascript' src='lib/chatx/chatx.js' language='JavaScript'>	</script>								
				<script type='text/javascript' src='js/daftarobj.js' language='JavaScript' ></script>
				<script type='text/javascript' src='js/pageobj.js'></script>		
				<script type='text/javascript' src='js/administrasi/barang.js' language='JavaScript' ></script>			
				<script>
			 	$(document).ready(function(){
					barang.initial();
				});
				</script>
				</head>
				<body>
				<form action='' method='post' id='barang_form' name='barang_form'>				
				<div id='tampil_barang'>
				</div>
				</form>
				</body>
				</html>";
			
			break;
    	}
		
		case 'windowshow':{
				$fm = $this->windowShow();
				$cek = $fm['cek'];
				$err = $fm['err'];
				$content = $fm['content'];	
		break;
		}
		
		case 'getdata':{
				$Id = $_REQUEST['id'];
				$c = $_REQUEST['c'];
				$d = $_REQUEST['d'];
				$e = $_REQUEST['e'];
				$e1 = $_REQUEST['e1'];
				//07 00 00 000 0000
				$f = substr($Id, 0,2);
				$g = substr($Id, 3,2);
				$h = substr($Id, 6,2);
				$i = substr($Id, 9,3);
				$j = substr($Id, 13,4);
				//query ambil data ref_pegawai
				$get = mysql_fetch_array( mysql_query("select *, concat(f,'.',g,'.',h,'.',i,'.',j) as kodebarang  from ref_barang where f='$f' AND g='$g' AND h='$h' AND i='$i' AND j='$j'"));
				
				$content = array(
					'nama' => $get['nama'], 
					'f' => $get['f'], 
					'g' => $get['g'], 
					'h' => $get['h'], 
					'i' => $get['i'], 
					'j' => $get['j'],  
					'ref_idsatuan' => $get['ref_idsatuan'],
					'kodebarang' => $get['kodebarang'], 
					'barcode_terbesar' => $get['barcode_terbesar'],
					'jml_barang_konversi' =>1,// $get['jml_barang_konversi'],
					'StBsr' => $get['StBsr'],
					'satuan_terkecil' => $get['satuan_terkecil'],
					'satuan_terbesar' =>$get['satuan_terbesar'],// $get['satuan_terbesar'],
					'k1' => $get['k1'], 
					'k2' => $get['k2'], 
					'k3' => $get['k3'], 
					'k4' => $get['k4'], 
					'k5' => $get['k5'], 
					'k6' => $get['k6'] );
		break;
	    }
		
		case 'refreshComboJenis':{
				$sql="SELECT Id,nama FROM ref_jenis ORDER BY Id DESC";$cek.=$sql;
				$hasil = mysql_query($sql);
					while ($data = mysql_fetch_array($hasil)){
						$opsi.="<option value='".$data['Id']."'>".$data['nama']."</option>";
					}		
				$content = "<select name='ref_idjenis' id='ref_idjenis'><option value='".$dt['ref_idjenis']."'>--PILIH--</option>".$opsi."</select>";
		break;
	    }
		
		case 'refreshComboSatuan':{
				$sql="SELECT Id,nama FROM ref_satuan ORDER BY Id DESC";$cek.=$sql;
				$hasil = mysql_query($sql);
					while ($data = mysql_fetch_array($hasil)){
						$opsi.="<option value='".$data['Id']."'>".$data['nama']."</option>";
					}		
				$content = "<select name='ref_idsatuan' id='ref_idsatuan'><option value='".$dt['ref_idsatuan']."'>--PILIH--</option>".$opsi."</select>";
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
	 }//end switch
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   }
  
	function Hapus($ids){ //validasi hapus tbl_sppd
		 $err=''; $cek='';
		 $cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		
		if ($err ==''){
			
		for($i = 0; $i<count($ids); $i++){
		$idplh1 = explode(" ",$ids[$i]);
		$data_f= $idplh1[0];
	 	$data_g= $idplh1[1];
		$data_h= $idplh1[2];
		$data_i= $idplh1[3];
		$data_j= $idplh1[4];
		
		$pil4=mysql_query("SELECT count(*) as cnt,t_persediaan . f ,  t_persediaan . g ,  t_persediaan . h , t_persediaan.i, t_persediaan.j FROM t_persediaan  LEFT JOIN ref_barang  ON  ref_barang . f  =  t_persediaan . f  AND ref_barang . g  =  t_persediaan . g AND  ref_barang . h  = t_persediaan . h and ref_barang.i = t_persediaan.i and ref_barang.j = t_persediaan.j WHERE t_persediaan.f='$data_f' and t_persediaan.g='$data_g' and t_persediaan.h='$data_h' and t_persediaan.i='$data_i' and t_persediaan.j='$data_j'");
		
		$pil5=mysql_fetch_array($pil4);
		
		$pil41=mysql_query("SELECT count(*) as cnt,ref_mapping_akun . f ,  ref_mapping_akun . g ,  ref_mapping_akun . h 
FROM ref_mapping_akun  LEFT JOIN ref_barang  ON  ref_barang . f  =  ref_mapping_akun . f  AND ref_barang . g  =  ref_mapping_akun . g AND  ref_barang . h  = ref_mapping_akun . h WHERE ref_mapping_akun.f='$data_f' and ref_mapping_akun.g='$data_g' and ref_mapping_akun.h='$data_h'");
		
		$pil51=mysql_fetch_array($pil41);
		
		$lvl1=mysql_query("SELECT count(*) as cnt, f , g , h , i, j FROM ref_barang WHERE f='$data_f'");
		$lvl2=mysql_query("SELECT count(*) as cnt, f , g , h , i, j FROM ref_barang WHERE f='$data_f' and g='$data_g'");
		$lvl3=mysql_query("SELECT count(*) as cnt, f , g , h , i, j FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='$data_h'");
		$lvl4=mysql_query("SELECT count(*) as cnt,f , g , h , i, j FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='$data_h' and i='$data_i' and j<>'0000'");
		
		$ceklvl1=mysql_fetch_array($lvl1);
		$ceklvl2=mysql_fetch_array($lvl2);
		$ceklvl3=mysql_fetch_array($lvl3);
		$ceklvl4=mysql_fetch_array($lvl4);
		
		if($ceklvl1['cnt'] > 0 && $err=='' && $data_g=='00' && $data_h=='00' && $data_i=='000' && $data_j=='0000') {$err= "Data tidak bisa di hapus ";}
		if($ceklvl2['cnt'] > 0 && $err=='' && $data_h=='00' && $data_i=='000' && $data_j=='0000') {$err= "Data tidak bisa di hapus ";}
		if($ceklvl3['cnt'] > 0 && $err=='' && $data_i=='000' && $data_j=='0000') {$err= "Data tidak bisa di hapus !!";}
		if($ceklvl4['cnt'] > 0 && $err=='' && $data_j=='0000') {$err= "Data tidak bisa di hapus !!";}
		if($pil5['cnt'] > 0 )$err ="data tidak bisa di Hapus karena sudah ada di data Transaksi !!";
		if($pil51['cnt'] > 0 )$err ="data tidak bisa di Hapus karena sudah ada di data Mapping !!";
				
		if($err=='' ){
					$qy = "DELETE FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='$data_h'  and  i='$data_i' and j='$data_j' and  concat (f,' ',g,' ',h,' ',i,' ',j) ='".$ids[$i]."' ";$cek.=$qy;
					$qry = mysql_query($qy);
					
			}else{
				break;
			}			
		}
		}
		return array('err'=>$err,'cek'=>$cek);
	}	  
	 
	 	    
   function windowShow(){		
		$cek = ''; $err=''; $content=''; 
		$json = TRUE;	//$ErrMsg = 'tes';		
		$form_name = $this->FormName;
		$ref_jenis=$_GET['status_filter'];
		$status_filter=1;
		//if($err==''){
			$FormContent = $this->genDaftarInitial(1);
			$form = centerPage(
					"<form name='$form_name' id='$form_name' method='post' action=''>".
					createDialog(
						$form_name.'_div', 
						$FormContent,
						900,
						500,
						'Pilih Barang',
						'',
						"<input type='button' value='Pilih' onclick ='".$this->Prefix.".windowSave()' >".
						"<input type='button' value='Batal' onclick ='".$this->Prefix.".windowClose()' >".
						"<input type='hidden' id='CariBarang_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >".
						"<input type='hidden' id='CariBarang_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >".
						"<input type='hidden' id='sesi' name='sesi' value='$sesi' >"
						,//$this->setForm_menubawah_content(),
						$this->form_menu_bawah_height
					).
					"</form>"
			);
			$content = $form;//$content = 'content';	
		//}
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function simpanJenisLvl4(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	$pil = $_REQUEST[$this->Prefix.'_idplh'];
	
		/*$fmST = $_REQUEST[$this->Prefix.'_fmST'];
		$pil=$this->form_idplh;*/
		$f1=substr($pil,0,2);
		$g1=substr($pil,3,2);
		$h1=substr($pil,6,2);
		$i1=substr($pil,9,3);
		$j1=substr($pil,13,4);
	 	//$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$f= $_REQUEST['f'];
		$g= $_REQUEST['g'];
		$h= $_REQUEST['h'];
		$i= $_REQUEST['i'];
		$j= $_REQUEST['j'];
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Barang Belum Di Isi !!';
		
			if($err==''){
				$aqry = "UPDATE ref_barang set nama='$nama',f='$f',g='$g',h='$h',i='$i',j='0000' WHERE f='".$f1."'and g='".$g1."'and h='".$h1."'and i='".$i1."'and j='".$j1."'";
				
				$qry = mysql_query($aqry) or die(mysql_error());	
				}
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanJenis(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$f= $_REQUEST['f'];
		$g= $_REQUEST['g'];
		$h= $_REQUEST['h'];
		$i= $_REQUEST['i'];
		$j= $_REQUEST['j'];
		$nama= $_REQUEST['nama'];
	if( $err=='' && $nama =='' ) $err= 'Nama Barang Belum Di Isi !!';
		if($fmST == 0){
			if($err==''){
				$aqry = "INSERT into ref_barang (f,g,h,i,j,nama) values('$f','$g','$h','$i','0000','$nama')";	
				$cek .= $aqry;	
				$qry = mysql_query($aqry);
				$content=$i;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_barang set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpanSatuanBesar(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $nama= $_REQUEST['nama'];
	  
	 if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_satuan (nama) values('$nama')";	
					$cek .= $aqry;	
					$qry = mysql_query($aqry);
					if($qry== FALSE) $err='gagal simpan sudah ada !';
					$content=$nama;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_barang set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpanSatuanBesar1(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $nama= $_REQUEST['nama'];
	  
	 if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_satuan (nama) values('$nama')";	
					$cek .= $aqry;	
					$qry = mysql_query($aqry);
					if($qry== FALSE) $err='gagal simpan sudah ada !';
					$content=$nama;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_barang set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpanSatuanKecil(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $nama= $_REQUEST['nama'];
	  
	 if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_satuan (nama) values('$nama')";	
					$cek .= $aqry;	
					$qry = mysql_query($aqry);
					if($qry== FALSE) $err='gagal simpan sudah ada !';
					$content=$nama;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_barang set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else	
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpanSatuanKecil1(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;
	//get data -----------------
	 $fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 $idplh = $_REQUEST[$this->Prefix.'_idplh'];
	 $nama= $_REQUEST['nama'];
	  
	 if($fmST == 0){
				if($err==''){
					$aqry = "INSERT into ref_satuan (nama) values('$nama')";	
					$cek .= $aqry;	
					$qry = mysql_query($aqry);
					if($qry== FALSE) $err='gagal simpan sudah ada !';
					$content=$nama;	
				}
			}else{						
				if($err==''){
				$aqry = "UPDATE ref_barang set nama='$nama',ref_idjenis='$ref_idjenis',ref_idsatuan='$ref_idsatuan',merk='$merk' WHERE Id='".$idplh."'";	$cek .= $aqry;
						$qry = mysql_query($aqry) or die(mysql_error());
				}
			} //end else
				
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	
	function simpan(){
	 global $HTTP_COOKIE_VARS;
	 global $Main;
	 
	 $uid = $HTTP_COOKIE_VARS['coID'];
	 $cek = ''; $err=''; $content=''; $json=TRUE;

	//get data -----------------
		$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
		$f= $_REQUEST['f'];
		$fmKelompok2= $_REQUEST['fmKelompok2'];
		$fmObjek2= $_REQUEST['fmObjek2'];
		$fmJenis= $_REQUEST['fmJenis'];
		$j= $_REQUEST['j'];
		$nama= $_REQUEST['nama'];
		$StBsr= $_REQUEST['StBsr'];
		$StKcl= $_REQUEST['fmStKcl'];
		$jumlahKonversi= $_REQUEST['jumlahKonversi'];
		$kdbarbsr= $_REQUEST['kdbarbsr'];
		$kdbarkcl= $_REQUEST['kdbarkcl'];
	
	 if( $err=='' && $fmKelompok2 =='' ) $err= 'Kelompok Barang Belum Di Isi !!';
	 if( $err=='' && $fmObjek2 =='' ) $err= 'Objek Belum Di Isi !!';
	 if( $err=='' && $fmJenis =='' ) $err= 'Jenis Belum Di Isi !!';
	 if( $err=='' && $j =='' ) $err= 'Kode Barang Belum Di Isi !!';
	 if( $err=='' && $nama =='' ) $err= 'nama Belum Di Isi !!';
	 if( $err=='' && $StBsr =='' ) $err= 'Satuan Besar Belum Di Pilih !!';
	 if( $err=='' && $StKcl =='' ) $err= 'Satuan Kecil Belum Di Pilih !!';
	 //if( $err=='' && $kdbarbsr =='' ) $err= 'Barcode Besar Belum Di Isi !!';
	
	 if( $err=='' && $StBsr==$StKcl && $jumlahKonversi <> '1') $err='Untuk satuan transaksi sama dengan satuan kecil, maka jumlah konversi harus 1 !';
	
	if( $err=='' && $jumlahKonversi =='') $err='Jumlah konversi Belum Di Isi !!';
	if( $err=='' && $StBsr<>$StKcl && $jumlahKonversi == 1) $err='Untuk satuan transaksi harus lebih dari 1 !';
	if( $err=='' && $jumlahKonversi ==0) $err="Jumlah konversi tidak boleh '0'  !!";
	
	if($fmST == 0){
		if($err==''){
				
		$dat1=mysql_query("select count(*) as cnt,f,g,h,k1,k2,k3,k4,k5,k6 from ref_mapping_akun where  f  = '".$f."' AND  g  = '".$fmKelompok2."' AND  h  = '".$fmObjek2."'");
			$dat2=mysql_fetch_array($dat1);
			$k1=$dat2['k1'];
			$k2=$dat2['k2'];
			$k3=$dat2['k3'];
			$k4=$dat2['k4'];
			$k5=$dat2['k5'];
			$k6=$dat2['k6'];
			if($dat2['cnt'] > 0 ){	
			$aqry = "INSERT into ref_barang (f,g,h,i,j,nama,satuan_terkecil,satuan_terbesar,jml_barang_konversi,barcode_terbesar,k1,k2,k3,k4,k5,k6) values('$f','$fmKelompok2','$fmObjek2','$fmJenis','$j','$nama','$StBsr','$StKcl','$jumlahKonversi','$kdbarbsr','".$dat2['k1']."','".$dat2['k2']."','".$dat2['k3']."','".$dat2['k4']."','".$dat2['k5']."','".$dat2['k6']."')";	$cek .= $aqry;
			$qry = mysql_query($aqry);
						
					}else{
						$err="Data tidak dapat disimpan, Kode Barang $f $fmKelompok2 $fmObjek2 belum di mapping dengan Kode Akun!";
					}
				}
			}
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	function simpanEdit(){
		global $HTTP_COOKIE_VARS;
		global $Main;
	
		$uid = $HTTP_COOKIE_VARS['coID'];
		$cek = ''; $err=''; $content=''; $json=TRUE;
	
	//get data -----------------
	 	$fmST = $_REQUEST[$this->Prefix.'_fmST'];
	 	$idplh = $_REQUEST[$this->Prefix.'_idplh'];
	//07 01 01 003 0001
		$f1=substr($idplh,0,2);
		$g1=substr($idplh,3,2);
		$h1=substr($idplh,6,2);
		$i1=substr($idplh,9,3);
		$j1=substr($idplh,13,4);
	
		$f= $_REQUEST['f'];
		$fmKelompok2= $_REQUEST['fmKelompok2'];
		$fmObjek2= $_REQUEST['fmObjek2'];
		$fmJenis= $_REQUEST['fmJenis'];
		$g= $_REQUEST['g'];
		$h= $_REQUEST['h'];
		$i= $_REQUEST['i'];
		$j= $_REQUEST['j'];
		$nama= $_REQUEST['nama'];
		$StBsr= $_REQUEST['StBsr'];
		$StKcl= $_REQUEST['StKcl'];
		$jumlahKonversi= $_REQUEST['jumlahKonversi'];
		$jumlahKonversi2= $_REQUEST['jumlahKonversi'];
		$kdbarbsr= $_REQUEST['kdbarbsr'];
		$kdbarkcl= $_REQUEST['kdbarkcl'];
		$k1= $_REQUEST['k1'];
		$k2= $_REQUEST['k2'];
		$k3= $_REQUEST['k3'];
		$k4= $_REQUEST['k4'];
		$k5= $_REQUEST['k5'];
		$k6= $_REQUEST['k6'];
	
	$pil2=mysql_query("SELECT count(*) as cnt,ref_barang . f ,  ref_barang . g ,  ref_barang . h ,  ref_barang . i ,ref_barang . j FROM  ref_barang  INNER JOIN  t_persediaan  ON  t_persediaan . f  =  ref_barang . f  AND t_persediaan . g  =  ref_barang . g  AND  t_persediaan . h  =  ref_barang . h  AND  t_persediaan . i  =  ref_barang . i  AND  t_persediaan . j  =  ref_barang . j  where ref_barang.f='".$f."' and ref_barang.g='".$g."' and ref_barang.h='".$h."' and ref_barang.i='".$i."' and ref_barang.j='".$j."'");
	
	$pil3=mysql_fetch_array($pil2);		
	$pil21=mysql_query("ref_mapping_akun . f ,  ref_mapping_akun . g ,  ref_mapping_akun . h , ref_mapping_akun . k1 ,  ref_mapping_akun . k2 ,  ref_mapping_akun . k3 , ref_mapping_akun . k4 ,  ref_mapping_akun . k5 ,  ref_mapping_akun . k6 FROM ref_barang  LEFT JOIN ref_mapping_akun  ON  ref_mapping_akun . f  =  ref_barang . f  AND ref_barang . g  =  ref_mapping_akun . g  AND  ref_barang . h  = ref_mapping_akun . h  AND  ref_barang . k1  =  ref_mapping_akun . k1  AND ref_barang . k2  =  ref_mapping_akun . k2  AND  ref_barang . k3  = ref_mapping_akun . k3  AND  ref_barang . k4  =  ref_mapping_akun . k4  AND ref_barang . k5  =  ref_mapping_akun . k5  AND  ref_barang . k6  = ref_mapping_akun . k6 WHERE ref_mapping_akun . f  = '".$f."' AND ref_mapping_akun . g  = '".$g."' AND ref_mapping_akun . h  = '".$h."' AND ref_mapping_akun . k1  = '".$k1."' AND ref_mapping_akun . k2  = '".$k2."' AND ref_mapping_akun . k3  = '".$k3."' AND ref_mapping_akun . k4  = '".$k4."' AND ref_mapping_akun . k5  = '".$k5."' AND ref_mapping_akun . k6  = '".$k6."'");
	
	$pil31=mysql_fetch_array($pil21);
		if($pil3['cnt'] > 0 ){
			$err ='data tidak bisa di edit karena sudah ada di data Transaksi !!';
			
			//$content ='';
			}elseif($pil31['cnt'] > 0 ){
			$err ='data tidak bisa di edit karena sudah ada di data Mapping !!';
			}
	
	 if( $err=='' && $nama =='' ) $err= 'nama Belum Di Isi !!';
	 if( $err=='' && $StBsr =='' ) $err= 'Satuan Besar Belum Di Pilih !!';
	 if( $err=='' && $StKcl =='' ) $err= 'Satuan Kecil Belum Di Pilih !!';
	 if( $StBsr==$StKcl && $jumlahKonversi <> '1') $err='Untuk satuan transaksi sama dengan satuan kecil, maka jumlah konversi harus 1 !';
	if( $err=='' && $StBsr<>$StKcl && $jumlahKonversi == 1) $err='Untuk satuan transaksi harus lebih dari 1 !';
	 if( $err=='' && $jumlahKonversi =='' ) $err= "Jumlah Konversi Belum Di Isi !!"; 
	 if( $err=='' && $jumlahKonversi == 0 ) $err= "Jumlah Konversi tidak boleh '0' !!"; 
			{						
				if($err==''){
				$dat1=mysql_query("select count(*) as cnt,f,g,h,k1,k2,k3,k4,k5,k6 from ref_mapping_akun where  f  = '".$f1."' AND  g  = '".$g1."' AND  h  = '".$h1."'");$cek.="select count(*) as cnt,f,g,h,k1,k2,k3,k4,k5,k6 from ref_mapping_akun where  f  = '".$f1."' AND  g  = '".$g1."' AND  h  = '".$h1."'";
			$dat2=mysql_fetch_array($dat1);
			$k1=$dat2['k1'];
			$k2=$dat2['k2'];
			$k3=$dat2['k3'];
			$k4=$dat2['k4'];
			$k5=$dat2['k5'];
			$k6=$dat2['k6'];
			if($dat2['cnt'] > 0 ){
				
			$aqry = "UPDATE ref_barang set  nama='$nama' , satuan_terkecil='$StKcl', satuan_terbesar='$StBsr', jml_barang_konversi='$jumlahKonversi2' , barcode_terbesar='$kdbarbsr',k1='".$dat2['k1']."',k2='".$dat2['k2']."',k3='".$dat2['k3']."',k4='".$dat2['k4']."',k5='".$dat2['k5']."',k6='".$dat2['k6']."'  WHERE f='".$f1."'and g='".$g1."'and h='".$h1."'and i='".$i1."'and j='".$j1."'";	$cek .= $aqry;
						//$qry = mysql_query($aqry) or die(mysql_error());
						$qry = mysql_query($aqry);
				}else{
						$err="Data tidak dapat disimpan, Kode Barang $f1 $g1 $h1 belum di mapping dengan Kode Akun!";
					}
			} //end else
			}		
			return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);	
    }	
	
	//form ==================================
	function setFormBaru(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruJenis(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm2($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruSatuanBesar(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setFormSatuanBesar($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruSatuanBesar1(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setFormSatuanBesar1($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruSatuanKecil(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setFormSatuanKecil($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaruSatuanKecil1(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setFormSatuanKecil1($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
	
	function setFormBaru2(){
		$dt=array();
		$this->form_fmST = 0;
		$dt['tgl'] = date("Y-m-d"); //set waktu sekarang
		$fm = $this->setForm2($dt);
		return	array ('cek'=>$fm['cek'], 'err'=>$fm['err'], 'content'=>$fm['content']);
	}
   
    function setFormEdit(){
		$cek ='';
		$cbid = $_REQUEST[$this->Prefix.'_cb'];
		$this->form_idplh = $cbid[0];
		$pil=$this->form_idplh;
		$kode = explode(' ',$this->form_idplh);
		$this->form_fmST = 1;
		//$dt = mysql_fetch_array(mysql_query($aqry));
		$data_f= $kode[0];
		$data_g= $kode[1];
		$data_h= $kode[2];
		$data_i= $kode[3];
		$data_j= $kode[4];
	
		$ceklvl4=mysql_query("SELECT * FROM  ref_barang WHERE f='".$kode[0]."' and g='".$kode[1]."' and h='".$kode[2]."'  and  i='".$kode[3]."' and j='".$kode[4]."'");
		
		$ceklevl4=mysql_fetch_array($ceklvl4);
		
		if ($ceklevl4['f'] <> '00' && $ceklevl4['g'] =='00'){
		$fm['err']="data tidak bisa di Edit !!";	
		}elseif($ceklevl4['f'] <> '00' && $ceklevl4['g'] <>'00' && $ceklevl4['h'] =='00'){
		$fm['err']="data tidak bisa di Edit !!";		
		}elseif($ceklevl4['f'] <> '00' && $ceklevl4['g'] <>'00' && $ceklevl4['h'] <>'00' && $ceklevl4['i'] =='00'){
		$fm['err']="data tidak bisa di Edit !!";	
		}elseif($ceklevl4['f'] <> '00' && $ceklevl4['g'] <>'00' && $ceklevl4['h'] <>'00' && $ceklevl4['i'] <> '00' && $ceklevl4['j'] =='00'){
		$fm= $this->setFormEditJenisLvl4($dt);	
		}else{
		$fm = $this->setFormDataEdit($dt);	
		}
		return	array ('cek'=>$cek.$fm['cek'], 'err'=>$err.$fm['err'], 'content'=>$fm['content']);
	}	
	
	function setFormDataEdit($dt){
	 global $SensusTmp ,$Main;
	 
		$cek = ''; $err=''; $content=''; 		
		$json = TRUE;	//$ErrMsg = 'tes';	 	
		$form_name = $this->Prefix.'_formEdit';				
		$this->form_width = 450;
	 	$this->form_height = 175;
	  	if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  	}else{
		$this->form_caption = 'Edit';			
		$Id = $dt['Id'];			
	  	}
	  
		$kode = explode(' ',$this->form_idplh);
	  	$data_f= $kode[0];
	 	$data_g= $kode[1];
		$data_h= $kode[2];
		$data_i= $kode[3];
		$data_j= $kode[4];
		
		$StBsr = $_REQUEST['StBsr'];
		$StKcl = $_REQUEST['StKcl'];
	
	$queryBidang=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='00' and h='00' and i='000' and j='0000'" ));
	$queryKelompok=mysql_fetch_array(mysql_query("SELECT g,nama FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='00' and i='000' and j='0000'" ));
	$queryObjek=mysql_fetch_array(mysql_query("SELECT h,nama FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='$data_h' and i='000' and j='0000'" ));
	$queryJenis=mysql_fetch_array(mysql_query("SELECT i,nama FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='$data_h' and i='$data_i' " ));
	$queryNamaBarang=mysql_fetch_array(mysql_query("SELECT j,nama,jml_barang_konversi,k1,k2,k3,k4,k5,k6 FROM ref_barang WHERE f='$data_f' and g='$data_g' and h='$data_h' and i='$data_i' and j='$data_j'" ));
		
	$dataBidang=$queryBidang['f'].".".$queryBidang['nama'];
	$datakelompok=$queryKelompok['g'].".".$queryKelompok['nama'];
	$dataobjek=$queryObjek['h'].".".$queryObjek['nama'];
	$dataJenis=$queryJenis['i'].".".$queryJenis['nama'];
	$dataNamaBarang=$queryNamaBarang['j'];
	$dataNamaBarang1=$queryNamaBarang['nama'];
	$dataKonversi=$queryNamaBarang['jml_barang_konversi'];
	$k1=$queryNamaBarang['k1'];
	$k2=$queryNamaBarang['k2'];
	$k3=$queryNamaBarang['k3'];
	$k4=$queryNamaBarang['k4'];
	$k5=$queryNamaBarang['k5'];
	$k6=$queryNamaBarang['k6'];
	$querySatuanBesar="SELECT nama, nama FROM ref_satuan" ;
	$querySatuanKecil="SELECT nama, nama FROM ref_satuan" ;  
	$jumlahKonversi=$dt['jml_barang_konversi'];
	$kdbarbsr=$dt['barcode_terbesar'];
		
	$query1=mysql_fetch_array(mysql_query("SELECT `ref_barang`.`f`, `ref_barang`.`g`, `ref_barang`.`h`, `ref_barang`.`i`,
  `ref_barang`.`j`, `ref_barang`.`satuan_terbesar`,`ref_barang`.`satuan_terkecil`FROM `ref_barang` LEFT JOIN `ref_satuan` ON `ref_satuan`.`nama` = `ref_barang`.`satuan_terbesar` AND `ref_satuan`.`nama` = `ref_barang`.`satuan_terkecil` WHERE  ref_barang . f  = '$data_f' AND  ref_barang . g  = '$data_g' AND  ref_barang . h  = '$data_h' AND  ref_barang . i  = '$data_i' AND  ref_barang . j  = '$data_j'")) ; 
 
	$StKcl=$query1['satuan_terkecil'];
	$StBsr=$query1['satuan_terbesar'];
	
	 //items ----------------------
	  $this->form_fields = array(
			'Kelompok' => array( 
						'label'=>'Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='g' id='g' value='".$datakelompok."' style='width:200px;' readonly>
						<input type='hidden' name='g' id='g' value='".$queryKelompok['g']."'>
						</div>", 
						 ),
						 	
			'objek' => array( 
						'label'=>'Objek',
						//'id'=>'cont_object',
						'value'=>"<div style='float:left;'>
						<input type='text' name='h' id='h' value='".$dataobjek."' style='width:200px;' readonly>
						<input type='hidden' name='h' id='h' value='".$queryObjek['h']."'>
						</div>", 
						 ),	
						 
			'jenis' => array( 
						'label'=>'Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='i' id='i' value='".$dataJenis."' style='width:200px;' readonly>
						<input type='hidden' name='i' id='i' value='".$queryJenis['i']."'>
						<input type ='hidden' name='f' id='f' value='".$queryBidang['f']."'>
						</div>", 
						 ),		
						 		 
			'namaBarang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='j' id='j' value='".$dataNamaBarang."' style='width:55px;' readonly>&nbsp&nbsp<input type='text' name='nama' id='nama' value='".$dataNamaBarang1."' style='width:140px;' >
						</div>", 
						 ),		
						 	 
			'satuan_besar' => array( 
						'label'=>'Satuan Besar',
						'labelWidth'=>100, 
						'value'=>"<div id='cont_satuan_besar1'>".cmbQuery('StBsr',$StBsr,$querySatuanBesar,'style="width:200;"onchange="'.$this->Prefix.'.pilihSatuanBesar1()"','-------- Pilih Satuan Besar --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanBesar1()' title='Baru' ></div>",
						 ),	
						 				 
			'satuan_kecil' => array( 
						'label'=>'Satuan Kecil',
						'labelWidth'=>100, 
						'value'=>"<div id='cont_satuan_kecil1'>".cmbQuery('StKcl',$StKcl,$querySatuanKecil,'style="width:200;"onchange="'.$this->Prefix.'.pilihSatuanKecil1()"','-------- Pilih Satuan Kecil --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanKecil1()' title='Baru' >",
						 ),			 
						 
			'jumlahKonversi' => array( 
						'label'=>'Jumlah Konversi',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='jumlahKonversi' id='jumlahKonversi' value='".$dataKonversi."' style='width:200px;'>
						</div>", 
						 ),
						 			 			 
		/*	'kdbarbsr' => array( 
						'label'=>'Kode Barcode Terbesar',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='kdbarbsr' id='kdbarbsr' value='".$kdbarbsr."' style='width:200px;'>
						</div>", 
						 ),	
				 		*/	 
						 
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='hidden' name='k1' id='k1' value='".$k1."'>".
			"<input type='hidden' name='k2' id='k2' value='".$k2."'>".
			"<input type='hidden' name='k3' id='k3' value='".$k3."'>".
			"<input type='hidden' name='k4' id='k4' value='".$k4."'>".
			"<input type='hidden' name='k5' id='k5' value='".$k5."'>".
			"<input type='hidden' name='k6' id='k6' value='".$k6."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanEdit()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genFormDataEdit();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormDataEdit($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_formEdit';	
		
		if($withForm){
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);	
		}
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
		
	function setForm($dt){
		global $SensusTmp ,$Main;
	 
		$cek = ''; $err=''; $content=''; 		
	 	$json = TRUE;	//$ErrMsg = 'tes';	 	
	 	$form_name = $this->Prefix.'_form';				
	 	$this->form_width = 450;
	 	$this->form_height = 175;
	  	if ($this->form_fmST==0) {
		$this->form_caption = 'Baru';
		$nip	 = '';
	  	}else{
		$this->form_caption = 'Edit';			
		$Id = $dt['Id'];			
	  }
	   
		$fmBidang2 = $_REQUEST ['fmBidang2'];
		$fmKelompok2 = $_REQUEST['fmKelompok2'];
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$fmJenis2 = $_REQUEST['fmJenis2'];
		$StBsr = $_REQUEST['StBsr'];
		$StKcl = $_REQUEST['StKcl'];
	
	$queryBidang2=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='00' and h='00' and i='000' and j='0000'" ));
	$queryKelompok2="SELECT g, concat(g, '. ', nama) as vnama FROM ref_barang WHERE f='$Main->f' and g <> '00' and h='00' and i='000' and j='0000'" ;
	$queryObjek2="SELECT h,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h <> '00' and i='000' and j='0000'" ;
	$queryJenis2="SELECT i,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i <>'000' and j='0000'" ;
	$queryNmBrg2="SELECT j,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i='$fmJenis2' and j <> '0000'" ;
	
	$querySatuanBesar="SELECT nama, nama FROM ref_satuan" ;
	$querySatuanKecil="SELECT nama, nama FROM ref_satuan" ;  
	  
	 //items ----------------------
	   $this->form_fields = array(
			'kelompok' => array( 
						'label'=>'Objek',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_kelompok2'>".cmbQuery('fmKelompok2',$fmKelompok2,$queryKelompok2,'style="width:210;"onchange="'.$this->Prefix.'.pilihKelompok2()"','&nbsp&nbsp-------- Pilih Objek --------')."</div>",
						 ),
						 
			'objek' => array( 
						'label'=>'Rincian Objek',
						//'id'=>'cont_object',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_object2'>".cmbQuery('fmObjek2',$fmObjek2,$queryObjek2,'style="width:210;"onchange="'.$this->Prefix.'.pilihObjek2()"','&nbsp&nbsp-------- Pilih Rincian Objek -------')."</div>",
						 ),
						 	
			'jenis' => array( 
						'label'=>'Sub Rincian Objek',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_jenis2'>".cmbQuery('fmjenis2',$fmJenis2,$queryJenis2,'style="width:210;"onchange="'.$this->Prefix.'.pilihJenis2()"','&nbsp&nbsp-------- Pilih Sub Rincian Objek --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruJenis()' title='Tambah Baru Sub Rincian Objek' ></div>",
						 ),
						 			 
			'namaBarang' => array( 
						'label'=>'Nama Barang',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='j' id='j' value='".$jj."' style='width:50px;' readonly>
						<input type='text' name='nama' id='nama' value='".$dt['nama']."' style='width:155px;'><input type ='hidden' name='f' id='f' value='".$queryBidang2['f']."'>
						
						</div>", 
						 ),			 
			'satuan_besar' => array( 
						'label'=>'Satuan Besar ',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_satuan_besar'>".cmbQuery('StBsr',$StBsr,$querySatuanBesar,'style="width:210;"onchange="'.$this->Prefix.'.pilihSatuanBesar()"','-------- Pilih Satuan Besar --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanBesar()' title='Baru' ></div>",
						 ),		
						 			 
			'satuan_kecil' => array( 
						'label'=>'Satuan Kecil',
						'labelWidth'=>100, 
						'value'=>
						"<div id='cont_satuan_kecil'>".cmbQuery('StKcl',$StKcl,$querySatuanKecil,'style="width:210;"onchange="'.$this->Prefix.'.pilihSatuanKecil()"','-------- Pilih Satuan Kecil --------')."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"."<input type='button' value='Baru' onclick ='".$this->Prefix.".BaruSatuanKecil()' title='Baru' >",
						 ),			 
						 
			'jumlahKonversi' => array( 
						'label'=>'Jumlah Konversi',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' onkeypress='return event.charCode >= 48 && event.charCode <= 57' name='jumlahKonversi' id='jumlahKonversi' value='".$dt['jumlahKonversi']."' style='width:210px;'>
						</div>", 
						 ),	
						 		 			 
		/*	'kdbarbsr' => array( 
						'label'=>'Kode Barcode Terbesar',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='kdbarbsr' id='kdbarbsr' value='".$dt['kdbarbsr']."' style='width:200px;'>
						</div>", 
						 ),	
						 	*/		 
						 
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".Simpan()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genForm();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	//--------Form Jenis Baru---------
	function setForm2($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formJenis';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Jenis';
		$nip	 = '';
		$f = $_REQUEST ['fmBidang'];
		$fmKelompok2 = $_REQUEST['fmKelompok2'];
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$fmJenis2 = $_REQUEST['fmJenis2'];
		$queryBidang = $_REQUEST['fmBidang2'];
	
		$aqry2="SELECT MAX(i) AS maxno FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2'";
		$cek.='ssssssss ='.$aqry2;
		$get=mysql_fetch_array(mysql_query($aqry2));
		$lastkode=$get['maxno'];
		$kode = (int) substr($lastkode, 1, 3);
		$kode++;
		$no_ba = sprintf("%03s", $kode);
		
		$queryBidang=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='00' and h='00' and i='000' and j='0000'" ));
		$queryKelompok=mysql_fetch_array(mysql_query("SELECT g,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='00' and i='000' and j='0000'" ));
		$queryObjek=mysql_fetch_array(mysql_query("SELECT h,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i='000' and j='0000'" ));
		$dataBidang=$queryBidang['f'].".".$queryBidang['nama'];
		$datakelompok=$queryKelompok['g'].".".$queryKelompok['nama'];
		$dataobjek=$queryObjek['h'].".".$queryObjek['nama'];
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'Kelompok' => array( 
						'label'=>'Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datakelompok."' style='width:255px;' readonly>
						<input type='hidden' name='g' id='g' value='".$queryKelompok['g']."'>
						<input type ='hidden' name='f' id='f' value='".$queryBidang['f']."'>
						</div>", 
						 ),	
			'objek' => array( 
						'label'=>'Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Objek' id='Objek' value='".$dataobjek."' style='width:255px;' readonly>
						<input type='hidden' name='h' id='h' value='".$queryObjek['h']."'>
						</div>", 
						 ),	
						 			
			'jenis' => array( 
						'label'=>'Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='i' id='i' value='".$no_ba."' style='width:50px;' readonly>
						<input type='hidden' name='i' id='i' value='".$no_ba."'>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Barang' style='width:200px;'>
						</div>", 
						 ),		
			
			
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanJenis()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close2()' >";
							
		$form = $this->genFormJenis();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setFormEditJenisLvl4($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formJenisLvl4';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==1) {
		$this->form_caption = 'Edit';
		
	
		$kode = explode(' ',$this->form_idplh);
	  	$data_f= $kode[0];
	 	$data_g= $kode[1];
		$data_h= $kode[2];
		$data_i= $kode[3];
		$data_j= $kode[4];
		
		$queryBd=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='00' and h='00' and i='000' and j='0000'" ));
		$queryBdg=mysql_fetch_array(mysql_query("SELECT g,nama FROM ref_barang WHERE f='$Main->f' and g='".$kode[1]."' and h='00' and i='000' and j='0000'" ));
		$queryObj=mysql_fetch_array(mysql_query("SELECT h,nama FROM ref_barang WHERE f='$Main->f' and g='".$kode[1]."' and h='".$kode[2]."' and i='000' and j='0000'" ));
		
		$cek.="SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='$kode[1]' and h='00' and i='000' and j='0000'";
		$datakelompok=$kode[1].".".$queryBdg['nama'];
		$dataobjek=$kode[2].".".$queryObj['nama'];
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'Kelompok' => array( 
						'label'=>'Jenis',
						'labelWidth'=>75, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='g' id='g' value='".$datakelompok."' style='width:255px;' readonly>
						<input type='hidden' name='g' id='g' value='".$queryBdg['g']."'>
						
						</div>", 
						 ),	
			'objek' => array( 
						'label'=>'Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='h' id='h' value='".$dataobjek."' style='width:255px;' readonly>
						<input type='hidden' name='h' id='h' value='".$queryObj['h']."'>
						</div>", 
						 ),	
						 			
			'jenis' => array( 
						'label'=>'Rincian Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='i' id='i' value='".$data_i."' style='width:50px;' readonly>
						<input type='hidden' name='i' id='i' value='".$data_i."'>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Barang' style='width:200px;'>
						</div>", 
						 ),		
			
			
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type ='hidden' name='f' id='f' value='".$queryBd['f']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanJenisLvl4()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close()' >";
							
		$form = $this->genFormJenis();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function setForm2($dt){	
	 global $SensusTmp, $Main;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formJenis';				
	 $this->form_width = 500;
	 $this->form_height = 100;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Jenis';
		$nip	 = '';
	}else{
		$this->form_caption = 'Edit';			
		$Id = $dt['Id'];
					
	}	
		$kode = explode(' ',$this->form_idplh);
	  	$data_f= $kode[0];
	 	$data_g= $kode[1];
		$data_h= $kode[2];
		$data_i= $kode[3];
		$data_j= $kode[4];
		
		$queryBdg=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='".$kode[1]."' and h='00' and i='000' and j='0000'" ));
		$cek.="SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='$kode[1]' and h='00' and i='000' and j='0000'";
		$datakelompok=$kode[1].".".$queryBdg['nama'];
		
		$f = $_REQUEST ['fmBidang'];
		$fmKelompok2 = $_REQUEST['fmKelompok2'];
		$fmObjek2 = $_REQUEST['fmObjek2'];
		$fmJenis2 = $_REQUEST['fmJenis2'];
		$queryBidang = $_REQUEST['fmBidang2'];
	
		$aqry2="SELECT MAX(i) AS maxno FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2'";
		//$cek.='ssssssss ='.$aqry2;
		$get=mysql_fetch_array(mysql_query($aqry2));
		$lastkode=$get['maxno'];
		$kode = (int) substr($lastkode, 1, 3);
		$kode++;
		$no_ba = sprintf("%03s", $kode);
		
		$queryBidang=mysql_fetch_array(mysql_query("SELECT f,nama FROM ref_barang WHERE f='$Main->f' and g='00' and h='00' and i='000' and j='0000'" ));
		$queryKelompok=mysql_fetch_array(mysql_query("SELECT g,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='00' and i='000' and j='0000'" ));
		$queryObjek=mysql_fetch_array(mysql_query("SELECT h,nama FROM ref_barang WHERE f='$Main->f' and g='$fmKelompok2' and h='$fmObjek2' and i='000' and j='0000'" ));
		$dataBidang=$queryBidang['f'].".".$queryBidang['nama'];
		$datakelompok=$queryKelompok['g'].".".$queryKelompok['nama'];
		$dataobjek=$queryObjek['h'].".".$queryObjek['nama'];
	 }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
		
	 //items ----------------------
	  $this->form_fields = array(
			
			'Kelompok' => array( 
						'label'=>'Kelompok',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Kelompok' id='Kelompok' value='".$datakelompok."' style='width:255px;' readonly>
						<input type='hidden' name='g' id='g' value='".$queryKelompok['g']."'>
						
						</div>", 
						 ),	
			'objek' => array( 
						'label'=>'Objek',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='Objek' id='Objek' value='".$dataobjek."' style='width:255px;' readonly>
						<input type='hidden' name='h' id='h' value='".$queryObjek['h']."'>
						</div>", 
						 ),	
						 			
			'jenis' => array( 
						'label'=>'Jenis',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'>
						<input type='text' name='i' id='i' value='".$no_ba."' style='width:50px;' readonly>
						<input type='hidden' name='i' id='i' value='".$no_ba."'>
						<input type='text' name='nama' id='nama' value='".$nama."' placeholder='Nama Barang' style='width:200px;'>
						</div>", 
						 ),		
			
			
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type ='hidden' name='f' id='f' value='".$queryBidang['f']."'>".
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanJenis()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close2()' >";
							
		$form = $this->genFormJenis();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormJenis($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_jenisform';	
		
		if($withForm){
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);
		}
		
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}	
	
	
	function setFormSatuanBesar($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formSatuanBesar';				
	 $this->form_width = 350;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Satuan Terbesar';
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'SatuanBesar' => array( 
						'label'=>'Satuan Terbesar',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='nama' id='nama' value='".$nama."' style='width:200px;' >
						</div>", 
						 ),	
						
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanSatuanBesar()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close3()' >";
							
		$form = $this->genFormSatuanBesar();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormSatuanBesar($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_formSatuanBesar';	
		
		if($withForm){
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);	
		}
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}	
	
	
	function setFormSatuanBesar1($dt){	
	 global $SensusTmp;
	 
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formSatuanBesar1';				
	 $this->form_width = 350;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Satuan Terbesar Edit';
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'SatuanBesar' => array( 
						'label'=>'Satuan Terbesar',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='nama' id='nama' value='".$nama."' style='width:200px;' >
						</div>", 
						 ),	
						
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanSatuanBesar1()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close5()' >";
							
		$form = $this->genFormSatuanBesar1();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	function genFormSatuanBesar1($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_formSatuanBesar1';	
		
		if($withForm){
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
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
					,//$this->setForm_menubawah_content(),
					$this->form_menu_bawah_height,'',$params
				);	
		}
		
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
	
	function setFormSatuanKecil($dt){	
	 global $SensusTmp;
	
		$cek = ''; $err=''; $content=''; 		
		$json = TRUE;	//$ErrMsg = 'tes';	 	
		$form_name = $this->Prefix.'_formSatuanKecil';				
		$this->form_width = 350;
		$this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Satuan Terkecil';
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
				
	 //items ----------------------
	  $this->form_fields = array(
			'SatuanKecil' => array( 
						'label'=>'Satuan TerKecil',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='nama' id='nama' value='".$nama."' style='width:200px;' >
						</div>", 
						 ),	
					
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanSatuanKecil()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close4()' >";
							
		$form = $this->genFormSatuanKecil();		
		$content = $form;
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormSatuanKecil($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_formSatuanKecil';	
		
		if($withForm){
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
					$this->form_menu_bawah_height,'',$params).
				"</form>";
		}else{
			$form= 
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
					$this->form_menu_bawah_height,'',$params
				);
		}
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
	
	function setFormSatuanKecil1($dt){	
	 global $SensusTmp;
	 $cek = ''; $err=''; $content=''; 		
	 $json = TRUE;	//$ErrMsg = 'tes';	 	
	 $form_name = $this->Prefix.'_formSatuanKecil1';				
	 $this->form_width = 350;
	 $this->form_height = 50;
	  if ($this->form_fmST==0) {
		$this->form_caption = 'Baru Satuan Terkecil';
		
	  }
	    //ambil data trefditeruskan
	  	$query = "" ;$cek .=$query;
	  	$res = mysql_query($query);
		
	 //items ----------------------
	  $this->form_fields = array(
			'SatuanKecil' => array( 
						'label'=>'Satuan TerKecil',
						'labelWidth'=>100, 
						'value'=>"<div style='float:left;'><input type='text' name='nama' id='nama' value='".$nama."' style='width:200px;' >
						</div>", 
						 ),	
						
			'Add' => array( 
						'label'=>'',
						'value'=>"<div id='Add'></div>",
						'type'=>'merge'
					 )			
			);
		//tombol
		$this->form_menubawah =
			"<input type='button' value='Simpan' onclick ='".$this->Prefix.".SimpanSatuanKecil1()' title='Simpan' >".
			"<input type='button' value='Batal' onclick ='".$this->Prefix.".Close6()' >";
							
		$form = $this->genFormSatuanKecil1();		
		$content = $form;//$content = 'content';
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content);
	}
	
	
	function genFormSatuanKecil1($withForm=TRUE, $params=NULL, $center=TRUE){	
		$form_name = $this->Prefix.'_formSatuanKecil1';	
		
		if($withForm){
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
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params).
				"</form>";
				
		}else{
			$form= 
				createDialog(
					$form_name.'_div', 
					$this->setForm_content(),
					$this->form_width,
					$this->form_height,
					$this->form_caption,
					'',
					$this->form_menubawah.
					"<input type='hidden' id='".$this->Prefix."_idplh' name='".$this->Prefix."_idplh' value='$this->form_idplh' >
					<input type='hidden' id='".$this->Prefix."_fmST' name='".$this->Prefix."_fmST' value='$this->form_fmST' >",
					$this->form_menu_bawah_height,'',$params
				);	
		}
		if($center){
			$form = centerPage( $form );	
		}
		return $form;
	}
	
		
	function setPage_HeaderOther(){           
	return 
		"<table width=\"100%\" class=\"menubar\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style='margin:0 0 0 0'>
			<tr>	
				<td class=\"menudottedline\" width=\"40%\" height=\"20\" style='text-align:right'><B>
					<A href=\"pages.php?Pg=barang\" title='Barang' style='color:blue'>Ref Barang</a> |
					<A href=\"pages.php?Pg=satuan\" title='Satuan' >Ref Satuan</a> |
					<A href=\"pages.php?Pg=akun\" title='Akun' >Ref Akun </a> |
					<A href=\"pages.php?Pg=mapingbarangakun\" title='Satuan'>Ref Mapping Akun</a> |
					<A href=\"pages.php?Pg=refpegawai\" title='Pegawai'>Ref Pegawai</a>&nbsp&nbsp&nbsp	
				</td>
			</tr>
		</table>";
	}
	
		
	//daftar =================================
	function setKolomHeader($Mode=1, $Checkbox=''){
		$status_filter=$_REQUEST['status_filter'];
		$NomorColSpan = $Mode==1? 2: 1;
	 if ($status_filter==1)
	 $headerTable =
	  "<thead>
	   <tr>
  	   <th class='th01' width='5' >No.</th>
  	   $Checkbox		
	   <th class='th01' width='450'>Kode Barang</th>
	   <th class='th01' width='450'>Nama Barang</th>
	   <th class='th01' width='300'>Satuan Terkecil</th>
	   <th class='th01' width='450'>Satuan Terbesar</th>
	   <th class='th01' width='300'>Jumlah Konversi</th>
	   </tr>
	   </thead>";
	 
		return $headerTable;
	}	
	
	
	function setKolomData($no, $isi, $Mode, $TampilCheckBox){
		global $Ref;
		
		$status_filter=$_REQUEST['status_filter'];
		$kdbrg=mysql_fetch_array(mysql_query("select * from ref_barang where f='".$isi['f']."' and g='".$isi['g']."' and h='".$isi['h']."' and i='".$isi['i']."' and j='".$isi['j']."'"));
	
		if ($status_filter==1){
			$VFilter = "";
			$Koloms = array();	
	 		$Koloms[] = array('align="center"', $no.'.' );
	  	if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="left" width="10px"',$isi['f'].'.'.$isi['g'].'.'.$isi['h'].'.'.$isi['i'].'.'.$isi['j']	);
	 		$Koloms[] = array('align="left" width="1500px"',$isi['nama']);
		}else{
		
		if($isi['g'] == '00' ||  $isi['h'] == '00' ||  $isi['i'] == '000' ||  $isi['j'] == '0000' ){
	 	$margin = '';
		if($isi['g'] != '00') $margin = 'style="margin-left:15px;"';
		if($isi['h'] != '00') $margin = 'style="margin-left:25px;"';
		if($isi['i'] != '000') $margin = 'style="margin-left:30px;"';
		if($isi['j'] != '0000') $margin = 'style="margin-left:35px;"';
		
	 }else{
	 	
		$margin = 'style="margin-left:40px;"';
	 }

	 $f = $isi['f'];
	 $g = $isi['g'];
	 $h = $isi['h'];
	 $i = $isi['i'];
	 $j = $isi['j'];
	 if($isi['f'] != '00') $kd=$f;	
	 if($isi['g'] != '00' )$kd=$f.'.'.$g;	 
	 if($isi['h'] != '00') $kd=$f.'.'.$g.'.'.$h;
	 if($isi['i'] != '000') $kd=$f.'.'.$g.'.'.$h.'.'.$i;
	 if($isi['j'] != '0000') $kd=$f.'.'.$g.'.'.$h.'.'.$i.'.'.$j;
		
		
	 		$Koloms = array();
	 		$Koloms[] = array('align="center"', $no.'.' );
	 	if ($Mode == 1) $Koloms[] = array(" align='center'  ", $TampilCheckBox);
	 		$Koloms[] = array('align="left" width="5"',$kd);
	 		$Koloms[] = array('align="left" width="3000px"',"<span $margin>".$isi['nama']."</span>");
	 		$Koloms[] = array('align="left" width="250px"',$isi['satuan_terkecil']);
	 		$Koloms[] = array('align="left" width="250px"',$isi['satuan_terbesar']);
	 		$Koloms[] = array('align="left" width="100px"',$isi['jml_barang_konversi']);
	 }
	 return $Koloms;
	}
	
	
	function genDaftarInitial($status_filter=''){
		$vOpsi = $this->genDaftarOpsi();
		$status_filter=$_REQUEST['status_filter'];
		return			
			"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 		
				"<input type='hidden' id='ref_jenis' name='ref_jenis' value='$ref_jenis'>".
				"<input type='hidden' id='status_filter' name='status_filter' value='$status_filter'>".
			"</div>".					
			"<div id=garis style='height:1;border-bottom:1px solid #E5E5E5;'></div>".
			"<div id=contain style='overflow:auto;height:$height;'>".
			//"<div id=contain style='overflow:auto;height:256;'>".
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".					
			"</div>
			</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";
	}
}
$pangkat = new pangkatObj();
?>