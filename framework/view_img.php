<?php
//include ("common/vars");
//include ("common/fnfile.php"); 
//include ("common/fnimg.php");
//error_reporting(0);
//include ("common/fnport.php");
//include ("common/vars_def.php");

//view_img.php?fname=2801_20141205_1_1603808635.jpg&sw=500&sh=500

//param 

$fname = $_GET['fname'];// "1002464988.JPG"; 
$sw = $_GET['sw'];//400;
$sh = $_GET['sh'];//400;
$path = empty($_GET['path']) ? 'gambar2011/' : $_GET['path'].'2011/';
$photoprofil = $_GET['photoprofil'];



//if(CekLogin())
if($photoprofil == '')
{
	


//const
//$path = $Main->GambarPath.'/';
$path = 'gambar2011/';






$errmsg = '';
$ext= strtolower(getExtension($fname));	
if ( !(strcmp("jpg",$ext)|| strcmp("jpeg",$ext) || strcmp("png",$ext)) ){
	$errmsg = 'Hanya format jpg/jpeg dan png';
}

if ($errmsg == ''){
	
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext)){
		header('Content-Type: image/jpeg');
	}else if(!strcmp("png",$ext)){
		header('Content-Type: image/png');
	}
	//echo "tes";

	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))	$src_img=imagecreatefromjpeg($path.$fname);
	if(!strcmp("png",$ext))	$src_img=imagecreatefrompng($path.$fname);
	//$src_img= imagecreatefrompng($path.$fname);

	// resize  -------------------------------------------------------------------------------
	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);

	// next we will calculate the new dimmensions for the thumbnail image
	// the next steps will be taken:
	// 1. calculate the ratio by dividing the old dimmensions with the new ones
	// 2. if the ratio for the width is higher, the width will remain the one define in WIDTH variable
	// and the height will be calculated so the image ratio will not change
	// 3. otherwise we will use the height ratio for the image
	// as a result, only one of the dimmensions will be from the fixed ones
	$ratio1=$old_x/$sw;
	$ratio2=$old_y/$sh;
	if($ratio1>$ratio2) {
		if($sw<$old_x){//teu stretch up
			$thumb_w=$sw;
			$thumb_h=$old_y/$ratio1;	
		}else{
			$thumb_w = $old_x;
			$thumb_h = $old_y;
		}
		
	}else {
		if($sh<$old_y){
			$thumb_h=$sh;
			$thumb_w=$old_x/$ratio2;
		}else{
			$thumb_w = $old_x;
			$thumb_h = $old_y;
		}
	}
	
	// we create a new image with the new dimmensions
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);

	// resize the big image to the new created one
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

	//img_resize(@$src_img, @$dst_img, $sw, $sh);
	
	
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext)){		
		imagejpeg( $dst_img );
		//imagejpeg( $src_img );		
	}else if(!strcmp("png",$ext)){
		imagepng( $dst_img );
		//imagepng( $src_img );		
		
	}

	//imagedestroy($dst_img);
	imagedestroy($src_img);

}else{
	//echo "";
}

}else{

	
	$errmsg = '';
$ext= strtolower(getExtension($fname));	
if ( !(strcmp("jpg",$ext)|| strcmp("jpeg",$ext) || strcmp("png",$ext)) ){
	$errmsg = 'Hanya format jpg/jpeg dan png';
}
	
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext)){
		header('Content-Type: image/jpeg');
	}else if(!strcmp("png",$ext)){
		header('Content-Type: image/png');
	}
	
	
	
	 $path = "ajax-upload/uploads/";
	//$path = $Main->ProfilePath.'/';
	
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))	$src_img=imagecreatefromjpeg($path.$fname);
	if(!strcmp("png",$ext))	$src_img=imagecreatefrompng($path.$fname);
	
	//echo $path.$fname;
	
	if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext)){		
		imagejpeg( $src_img );
				
	}else if(!strcmp("png",$ext)){
		imagepng( $src_img );
	}
}

function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l); 
	return $ext;
}
?>