<?php
//header("Content-Type: text/javascript; charset=utf-8");
ob_start("ob_gzhandler");
/* ganti selector di index */
// include("common/vars.php");
include("config.php");

$Pg = isset($HTTP_GET_VARS["Pg"]) ? $HTTP_GET_VARS["Pg"] : "";

//if (CekLogin ()) {
  //  setLastAktif();

    switch ($Pg) {
        case "brg" : {
			if (CekLogin ()) {  setLastAktif();
               	include("pages/brg/selector.php");
			} else {  
				header("Location:index.php?");//header("Location: http://$Main->SITE/");
			}
        	break;                
        }
        case "map" : {
			//if (CekLogin () && $Main->MODUL_PETA) {  setLastAktif();
            if (CekLogin ()) {  setLastAktif();
               	include("pages/map/selector.php");
			} else {  
				header("Location:index.php?");//header("Location: http://$Main->SITE/");
			}
        	break;                
        }
		case 'renja':{
			if (CekLogin()  ) {  setLastAktif();
				
				include('pages/perencanaan/daftarobj.php');

					include("pages/perencanaan/renja/renja.php"); //break;
					$renja->selector();
			}else{
				header("Location:index.php?");//header("Location: http://$Main->SITE/");
			}			
			break;
		}
		

		
	 }
    
	ob_flush();
	flush();

//} else {  header("Location: http://atisisbada.net/");}
?>