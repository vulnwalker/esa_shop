<? 
// $nm=$_GET['nm'];
$dr='tmp2011';
$dir=$dr."/";
// error_reporting(0);
if (isset($_REQUEST["file"])) {
 
$type = !empty($_GET['type'])?$_GET['type']:"";
switch ($type){
 	case '1':{
		$dr='tmp';
		$dir=$dr."/";

		// xls
		break;
	}
 	case '2':{
		// doc
		break;
	}
 	case '3':{
		$dr='tmp2011';
		$dir=$dr."/";
 		header('Content-Type: image/PNG');

		// doc
		break;
	}
	default: exit;
 }

 
    $file=$dir.$_REQUEST["file"];
 
 /*   header("Content-type: application/force-download");
    header("Content-Transfer-Encoding: Binary");
    header("Content-length: ".filesize($file));
	
    //header("Content-disposition: attachment; filename=".basename($file)."");
	header("Content-disposition: attachment; filename=$nm");
*/
if (!file_exists($file)) {
  echo "Could not find image";
  exit;	
  }
  header("Content-length: ".filesize($file));	
  readfile("$file");
 // unlink($file);
} else {
    echo "No file selected";
	
}
 ?>