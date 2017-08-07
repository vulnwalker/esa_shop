<?php
/**05 maret 2013**/



function head(){
	return
	"<script src=\"js/jquery.min.js\" type=\"text/javascript\"></script>
	<script type=\"text/javascript\" src=\"js/jquery.form.js\"></script>
	 <script> 
        $(document).ready(function() { 
			//elements
			var progressbox 	= $('#progressbox');
			var progressbar 	= $('#progressbar');
			var statustxt 		= $('#statustxt');
			var submitbutton 	= $(\"#SubmitButton\");
			var myform 			= $(\"#UploadForm\");
			var output 			= $(\"#output\");
			var completed 		= '0%';
						
			$(myform).ajaxForm({
				beforeSend: function() { //brfore sending form
					submitbutton.attr('disabled', ''); // disable upload button
					statustxt.empty();
					progressbox.show(); //show progressbar
					progressbar.width(completed); //initial value 0% of progressbar
					statustxt.html(completed); //set status text
					statustxt.css('color','#000'); //initial color of status text
				},
				uploadProgress: function(event, position, total, percentComplete) { //on progress
					progressbar.width(percentComplete + '%') //update progressbar percent complete
					statustxt.html(percentComplete + '%'); //update status text
					if(percentComplete>50) {
						statustxt.css('color','#fff'); //change status text to white after 50%
					}
				},
				complete: function(response) { // on complete
					//output.html(response.responseText); //update element with received data
					
					myform.resetForm();  // reset form
					submitbutton.removeAttr('disabled'); //enable submit button
					progressbox.hide(); // hide progressbar
					var resp = eval('(' + response.responseText + ')');						
					UserProfil.batalEdit(3);
					
					//update data 
					UserProfil.updatePhoto(resp.content.fname 
						,function(){
							//refresh photo
							document.getElementById('imgprofil').setAttribute('src','view_img.php?photoprofil=1&fname='+ resp.content.fname);
							alert('Upload Sukses');
						}
					);
					
					
					
					
					
				}
			});
		
        }); 

    </script> 
 	<link href=\"css/upload_style.css\" rel=\"stylesheet\" type=\"text/css\" />";
 
}

function body(){
	return 
	"<div id='divupload'>".
	"<a href=\"javascript:document.getElementById('divupload_edit').style.display='block';document.getElementById('divupload').style.display='none';\" style='float:left; padding: 0 0 0 0'>upload foto</a>
	</div>".
				
	"<div id='divupload_edit'  style='display:none;background-color: #F2F2F2;padding: 8 5 10 8;'>".	
	"
	
	<!--<form action=\"processupload.php\" method=\"post\" enctype=\"multipart/form-data\" id=\"UploadForm\">-->
	<form action=\"pages.php?Pg=processupload\" method=\"post\" enctype=\"multipart/form-data\" id=\"UploadForm\">
	
	<div style='width:100;float:left;'><strong>Upload Foto</strong></div>
	<div style='text-align:center;height:100'>
	<table cellpadding='0' cellspacing='0' border='0' width='100%'>					
		<tr style='height:30'>
			<!--<td style='width:150;text-align:right;padding:0 4 0 0;color:gray'></td>-->
			<td style='text-align:center'><input name=\"ImageFile\" type=\"file\" />
		</tr>
		<tr style='height:30'><td colspan=2  style='text-align:center'>
			<!--<a class='buttonui' href='javascript:uploadImage()' style=''>Upload</a> -->
			<input type='submit'  id='SubmitButton' value='Upload'    
				style= 'background-color: #eee;border: 1px solid #999;border-bottom-color: #888;
				box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);padding: 4 8 4 8;text-decoration: none;
				margin: 2;color: #333;font-weight: bold;'
			/>
			<a class='buttonui' href=\"javascript:document.getElementById('divupload').style.display='block';document.getElementById('divupload_edit').style.display='none';\" >Batal</a>
			
		</td></tr>
	</table>
	</div>	
</form>

<div id=\"progressbox\"><div id=\"progressbar\"></div ><div id=\"statustxt\">0%</div ></div>
<div id=\"output\"></div>".
"</div>";
	
}


class UserProfilObj  extends DaftarObj2{	
	var $Prefix = 'UserProfil';
	var $SHOW_CEK = TRUE;
	var $withform = FALSE;
	var $elCurrPage="HalDefault";
	var $TblName = 'v1_admin_aktivitas';// 'v1_admin_aktivitas'; //daftar
	var $TblName_Hapus = 'ref_pegawai';
	var $MaxFlush = 10;
	var $TblStyle = array( 'koptable', 'cetak','cetak'); //berdasar mode
	var $ColStyle = array( 'GarisDaftar', 'GarisCetak','GarisCetak');
	var $KeyFields = array('uid');//('p','q'); //daftar/hapus
	var $FieldSum = array();//array('jml_harga');
	var $SumValue = array();
	var $FieldSum_Cp1 = array( 14, 13, 13);//berdasar mode
	var $FieldSum_Cp2 = array( 1, 1, 1);	
	var $checkbox_rowspan = 1;
	var $PageTitle = 'Profil Pengguna';
	var $PageIcon = 'images/masterdata_ico.gif';
	//var $cetak_xls=TRUE ;
	var $fileNameExcel='pegawai.xls';
	var $Cetak_Judul = 'AKTIFITAS PENGGUNA';	
	var $Cetak_Mode=2;
	var $Cetak_WIDTH = '30cm';
	var $Cetak_OtherHTMLHead;
	
	function genDaftarInitial(){
		global $Main;
		global $HTTP_COOKIE_VARS;
		
		$kdSubUnit0 = genNumber(0, $Main->SUBUNIT_DIGIT );

	
		$uid = $HTTP_COOKIE_VARS['coID'];// $_REQUEST['id'];
		$vOpsi = $this->genDaftarOpsi();
		
		//--- get data user
		$aqry = "select * from admin where uid ='$uid' ";
		$qry = mysql_query($aqry);
		while($isi=mysql_fetch_array($qry)){
			$nama = $isi['nama'];
			$level = $isi['level'];
			$group = $isi['group'];
			
			//--- get data gambar
			$fname = $isi['photo'];//'20121102_339737_1934758231.jpg';
		}
		
		//--- get data bidang
		if ($group!='00.00.00.'.$kdSubUnit0){
			$arrgrp = explode('.',$group);
			$c= $arrgrp[0];
			$d= $arrgrp[1];
			$e= $arrgrp[2];			
			$e1= $arrgrp[3];			
			
			$get = mysql_fetch_array(mysql_query( "select * from v_bidang where c='".$c."' " ));		
			if($get['nmbidang']<>'') $fmSKPD = $get['nmbidang'];
			$get = mysql_fetch_array(mysql_query( "select * from v_opd where c='".$c."' and d='".$d."' " ));		
			if($get['nmopd']<>'') $fmUNIT = $get['nmopd'];
			$get = mysql_fetch_array(mysql_query( "select * from v_unit where c='".$c."' and d='".$d."' and e='".$e."'"	));		
			if($get['nmunit']<>'') $fmSUBUNIT = $get['nmunit'];			
			$get = mysql_fetch_array(mysql_query( "select * from ref_skpd where c='".$c."' and d='".$d."' and e='".$e."' and e1='".$e1."' " 	));		
			if($get['nm_skpd']<>'') $fmSEKSI = $get['nm_skpd'];				
			
			
		
		}
		//--- tampil
		
		//-- form edit  nama
		$vlabel_nama = "<div style='width:100;float:left;'><strong>Nama Lengkap</strong></div>" ;
		$fnama_view=
			"<div id='div_nama' style='display:block;height:18;padding: 8 5 10 8;'>".				
				"<div style='width:100;float:left;'><strong>Nama Lengkap</strong></div>".
				"<span id='nama_lengkap' style='width:200;float:left;color:gray'>$nama</span>
				<span style='width:60;float:right;text-align:right'>
					<a href=\"javascript:UserProfil.suntingNama()\">Sunting</a>
				</span>				
			</div>";
		$fnama_edit=
			"<div id='div_nama_edit' style='display:none;background-color: #F2F2F2;padding: 8 5 10 8;'>".
				"<div style='width:100;float:left;'><strong>Nama Lengkap</strong></div>".
				"<div style='text-align:center;height:100'>
					<table cellpadding='0' cellspacing='0' border='0'>					
					<tr style='height:30'><td style='width:150;text-align:right;padding:0 4 0 0;color:gray'><b>Nama Lengkap Baru: </b></td>
						<td>
						<input type='text' id='nama' name='nama' value='$nama' style='width:200' onKeyup=\"UserProfil.cekStatusEdit('$nama',this,1);\">
						<input type='hidden' id='nama_old' name='nama_old' value='$nama'  >
						</td>
					</tr>
					<tr style='height:30'><td style='width:150;text-align:right;padding:0 4 0 0;color:gray'><b>Kata Sandi: </b></td>
					<td>
						<input type='password' id='nama_pass' name='nama_pass' value=''>
					</td></tr>
					<tr style='height:30'><td colspan=2  style='text-align:center'>
						<input type='button' value='Simpan Perubahan' id='btsimpanNama' onClick='javascript:UserProfil.update_nama()' disabled='true' >
						<input type='button' value='Batal' id='btBatal' onClick='javascript:UserProfil.batalEdit(1)' >
						
					</td></tr>
					</table>
				</div>".
			"</div>";
		$fupload_view = 
			"<div id='div_upload'>".
				//"<a href=\"javascript:document.getElementById('divupload_edit').style.display='block';document.getElementById('divupload').style.display='none';\" style='float:left; padding: 0 0 0 0'>upload foto</a>
				"<a href=\"javascript:UserProfil.suntingUpload()\" style='float:left; padding: 0 0 0 0'>upload foto</a>
			</div>";
		$fupload_edit=
			"<div id='div_upload_edit'  style='display:none;background-color: #F2F2F2;padding: 8 5 10 8;'>".	
			"<form action=\"pages.php?Pg=processupload\" method=\"post\" enctype=\"multipart/form-data\" id=\"UploadForm\">
			<table cellpadding='0' cellspacing='0' border='0' width='100%'>					
				<tr style='height:30'>
					<td style='width100'><strong>Upload Foto</strong></td>		
					<td style='text-align:center'>
						<input name=\"ImageFile\"  id=\"ImageFile\"  type=\"file\"  onchange=\"UserProfil.cekStatusEdit('',this,3);\" />
					</td>
				</tr>
				<tr style='height:30'>
					<td ></td>
					<td style='text-align:center'>
						<input type='submit'  id='btSimpanUpload' value='Upload'  class='' disabled='true'  />
						<input type='button'  id='btbatal' value='Batal'  onclick='UserProfil.batalEdit(3)'  />								
					</td>
				</tr>
			</table>
			</div>	
			</form>
			
			<div id=\"progressbox\"><div id=\"progressbar\"></div ><div id=\"statustxt\">0%</div ></div>
			<div id=\"output\"></div>".
			"</div>";
			
		$fpass_view = "<div id='div_pass'>
				 <div style='padding:8 5 10 8;height:18'>
				  <span style='width:100;float:left;'><strong>Kata Sandi</strong></span>
				  <span style='width:200;float:left;color:gray'>*******</span>
				  <span style='width:60;float:right;text-align:right'>
			
				<a href=\"javascript:UserProfil.suntingPass()\">Sunting</a>
				   </span>
				 </div>
				</div>";
		$fpass_edit = 
			"<div id='div_pass_edit' style='display:none;background-color: #F2F2F2;padding: 8 5 10 8; height:130'>
				<span style='width:100;float:left;'><strong>Kata Sandi</strong></span>
				  <div style='text-align:center;height:120'>
					<table cellpadding='0' cellspacing='0' border='0'>
					<tr style='height:30'><td style='width:150;text-align:right;padding:0 4 0 0;color:gray'><b>Berlaku: </b></td>
						<td><input type='password' id='passLama' name='passLama' value=''  style='width:200'></td></tr>
					<tr style='height:30'><td style='width:150;text-align:right;padding:0 4 0 0;color:gray'><b>Baru: </b></td>
						<td><input type='password' id='passBaru' name='passBaru' value='' onKeyup=\"UserProfil.cekStatusEdit('',this,2);\"></td></tr>
		         <tr style='height:30'><td style='width:150;text-align:right;padding:0 4 0 0;color:gray'><b>Ketik Ulang yang Baru: </b></td>
						<td><input type='password' id='passBaru1' name='passBaru1' value=''></td></tr>
						<tr style='height:30'><td colspan=2  style='text-align:center'>
						<input type='button' value='Simpan Perubahan' id='btsimpanPass' onClick='javascript:UserProfil.update_pass()' disabled='true'>						
						<input type='button' value='Batal' id='btBatal' onClick='javascript:UserProfil.batalEdit(2)' >						
						
					</td></tr>
				   </table>
				   </div>
				</div>";
		if($fname != ''){
			$srcimg =  "view_img.php?fname=$fname&photoprofil=1";
		}else{
			$srcimg =  "images/administrator/images/smile.png";
		}
		
		return	
		//view_img.php?fname=thumb_407296_708049009-645648845.jpg&photoprofil=1
			"<table width='100%'><tr>
			<td width='10'>
				
			</td>
			<td width='500'>".
				/*"<ul>
				<li></li>
				</ul>".*/
				
				"<div style= 'padding:10 0 0 0'>".
					//<img id='imgprofil' src='view_img.php?fname=$fname&amp;sw=200&amp;sh=150'>
					"<img id='imgprofil' src='$srcimg'>".
				"</div>".
				
				
				//	body().
				//"</div>".
				$fupload_view.
				$fupload_edit.
				
				
				"<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:1; display:block'></div>". 
				"<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18; display:block'>
				<span style='width:100;float:left;'><strong>ID Pengguna</strong></span>
				<span style='width:200;float:left;color:gray'><b>$uid</b></span>
				<!--<span style='width:60;float:right;text-align:right'><a href='#'>Sunting</a></span>-->
				<div style='height:300;display:block' id'=content1'></div>
				</div>".
				
				
				"<div  style='border-bottom:solid 1px #e9e9e9;'>
				$fnama_view
				$fnama_edit
				</div>".
				"<div  style='border-bottom:solid 1px #e9e9e9;'>
				$fpass_view
				$fpass_edit
				</div>".
				
				
				
				"<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18'>
				<span style='width:100;float:left;'><strong>Level</strong></span>
				<span style='width:200;float:left;color:gray'>".$Main->UserLevel[ $level]."</span>
				<span style='width:60;float:right;text-align:right'></span>
				</div>
				<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18'>
				<span style='width:100;float:left;'><strong>Group</strong></span>
				<span style='width:200;float:left;color:gray'>$group</span>
				<span style='width:60;float:right;text-align:right'></span>
				</div>
				<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18'>
				<span style='width:100;float:left;'><strong>BIDANG</strong></span>
				<span style='width:200;float:left;color:gray'><b>$fmSKPD</b></span>
				<span style='width:60;float:right;text-align:right'></span>
				</div>
				<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18'>
				<span style='width:100;float:left;'><strong>SKPD</strong></span>
				<span style='width:200;float:left;color:gray'>$fmUNIT</span>
				<span style='width:60;float:right;text-align:right'></span>
				</div>
				<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18'>
				<span style='width:100;float:left;'><strong>UNIT</strong></span>
				<span style='width:200;float:left;color:gray'>$fmSUBUNIT</span>
				<span style='width:60;float:right;text-align:right'></span>
				</div>
				<div style='padding:8 5 10 8;border-bottom:solid 1px #e9e9e9;height:18'>
				<span style='width:100;float:left;'><strong>SUB UNIT</strong></span>
				<span style='width:200;float:left;color:gray'>$fmSEKSI</span>
				<span style='width:60;float:right;text-align:right'></span>
				</div>
				".
			"</td>
			<td width='*'>&nbsp</td>
			</tr></table>";
			//$NavAtas.	
			/*"<div id='{$this->Prefix}_cont_title' style='position:relative'></div>". 
			"<div id='{$this->Prefix}_cont_opsi' style='position:relative'>". 
				//$vOpsi['TampilOpt'].
			"</div>".					
			"<div id='{$this->Prefix}_cont_daftar' style='position:relative' >".	
				//$this->genDaftar($Opsi['Kondisi'],$Opsi['Order'], $Opsi['Limit'], $Opsi['NoAwal']).									
			"</div>".
			"<div id='{$this->Prefix}_cont_hal' style='position:relative'>".				
				"<input type='hidden' id='".$this->Prefix."_hal' name='".$this->Prefix."_hal' value='1'>".
			"</div>";*/
	}
	
	function setPage_OtherScript(){
		$scriptload = '';
					/*"<script>
						$(document).ready(function(){ 
							".$this->Prefix.".loading();
						});
						
					</script>";*/					
		return
		 
			head().
			"<script type='text/javascript' src='js/barcode.js' language='JavaScript' ></script>".			
			"<script type='text/javascript' src='js/skpd.js' language='JavaScript' ></script>".		
			"<script type='text/javascript' src='js/".strtolower($this->Prefix).".js' language='JavaScript' ></script>".
			$scriptload;
	}
     //05Mar2013
	function update_nama()
{
	global $HTTP_COOKIE_VARS;
	$cek = ''; $err=''; $content=''; $json=FALSE;
	$nama = $_POST['nama'];$cek .= $nama;
	$nama_pass= $_POST['nama_pass'];$cek .= $nama_pass;
	
	$cek .=' '. md5($nama_pass);
	//cekpassword
	  $qy = "SELECT password 
			  from admin
			  WHERE uid = '".$HTTP_COOKIE_VARS['coID']."'
				";
	 $rs = mysql_query($qy);
	 while($row = mysql_fetch_array($rs))
	 {
	 	$pass = $row['password'];
	 }
	  if(md5($nama_pass) == $pass)
	  {}else{ $err = 'Password salah!! ';
	  	
	  }
	if($err =="")
	{
		$query = "UPDATE admin
			  set nama = '".$nama."'
			  WHERE uid = '".$HTTP_COOKIE_VARS['coID']."'
			  "; $cek .= $query;
	$result = mysql_query($query);
	//$cek .=$query;
	$content= $nama; 
	}
	
	
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
}
   
	
   
   //06mar2013
function update_pass()
{
	global $HTTP_COOKIE_VARS;
	$cek = ''; $err=''; $content=''; $json=FALSE;
	$passLama= $_POST['passLama'];$cek .= $passLama;
	$passBaru = $_POST['passBaru'];$cek .= $passBaru;
	$passBaru1 = $_POST['passBaru1'];$cek .= $passBaru1;
	
	
	$cek .=' '. md5($passLama);
	//cekpassword
	  $qry = "SELECT password 
			  from admin
			  WHERE uid = '".$HTTP_COOKIE_VARS['coID']."'
				";
	 $rst = mysql_query($qry);
	 while($row = mysql_fetch_array($rst)){
	 	$pass = $row['password'];
	 }
	 //cek password Lama dengan Pass di DB
	 if( $err=='' && md5($passLama) != $pass)  $err = 'Password salah!! ';
	  	
	  
	 //pengecekan passbaru=passbaru1
	 if($err =="" && $passBaru1 != $passBaru) $err = 'Ulang Password Tidak Sama';
	   
	 if($err =="")	{
		$query = "UPDATE admin
			  set password = md5('".$passBaru."')
			  WHERE uid = '".$HTTP_COOKIE_VARS['coID']."'
			  ";
	$result = mysql_query($query);
	$cek .=$query;
	$content= $passBaru; 
	}
	
	return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
}

	function update_photo(){   		
		global $HTTP_COOKIE_VARS;
		$cek = ''; $err=''; $content=''; $json=FALSE;
		
		$fname = $_REQUEST['fname'];
		
		if($err ==""){
			$query = "UPDATE admin
				set photo = '".$fname."'
			  		WHERE uid = '".$HTTP_COOKIE_VARS['coID']."'
			  	";
			$result = mysql_query($query);
			$cek .=$query;
			$content= $passBaru; 
		}
		
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
   	}

	function set_selector_other($tipe){
		$cek = ''; $err=''; $content=''; $json=FALSE;
		switch($tipe){
			case 'simpan_upload': 
				/**  
				$upload = $_FILES['files'];
				//$f =$_FILES['files']['name'];
				//$err = "tes"; 
				 $files = array();
				 foreach ($upload['tmp_name'] as $index => $value) {
			 		$files[] =$upload['name'][$index];
			 		}
					$err = $files[0];
					$json = TRUE;
					 //fungsi php
					 //simpan_upload();**/
			break;
			case 'update_nama':
				$get = $this->update_nama();
				$cek = $get['cek']; $err=$get['err']; $content=$get['content'];	
				$json=TRUE;	
			break;	
			case 'update_pass':
			 	$get = $this->update_pass();
				$cek = $get['cek']; $err=$get['err']; $content=$get['content'];	
				$json=TRUE;	
			break;
			case 'update_photo':
			 	$get = $this->update_photo();
				$cek = $get['cek']; $err=$get['err']; $content=$get['content'];	
				$json=TRUE;	
			break;
			default:{
				$err = 'tipe tidak ada!';
				break;
			}
		}	
		return	array ('cek'=>$cek, 'err'=>$err, 'content'=>$content, 'json'=>$json);
	}
}
$UserProfil = new UserProfilObj();


?>