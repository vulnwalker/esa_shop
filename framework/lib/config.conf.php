<?
ini_set("session.gc_maxlifetime",432000);

# Main Configuration for including files

$DIR_HOME		=$_SERVER['DOCUMENT_ROOT'];
$DIR_INC		=$DIR_HOME."/lib";
$DIR_MOD		=$DIR_HOME."/modules";
$DIR_SESS		=$DIR_HOME."/session";
$DIR_CSS		=$DIR_HOME."/styles";
$DIR_JS			=$DIR_HOME."/javascripts";
$DIR_ADODB		=$DIR_HOME."/adodb";
$DIR_IMAGES		=$DIR_HOME."/images";

# Special Configuration for ADODB Cache
$ADODB_CACHE_DIR	=$DIR_HOME."/adodb_cache";
# Main Configuration for address referring

$HREF_HOME		="http://".$_SERVER['HTTP_HOST'];
$HREF_INC		=$HREF_HOME."/lib";
$HREF_MOD		=$HREF_HOME."/modules";
$HREF_SESS		=$HREF_HOME."/session";
$HREF_CSS		=$HREF_HOME."/styles";
$HREF_JS		=$HREF_HOME."/javascripts";
$HREF_ADODB		=$HREF_HOME."/adodb";
$HREF_IMAGES	=$HREF_HOME."/images";
$HREF_IMAGES2	=$HREF_HOME."/admin/images";
$HREF_JS2		=$HREF_HOME."/admin/javascripts";

# Main Configuration for redirecting

/* ---- deprecated, use $DOC_SELF_NAME above
$DOC_SELF		=$_SERVER['REQUEST_URI'];
if (substr($DOC_SELF, -4)!='.php'){
$DOC_SELF_NAME	=$_SERVER['REQUEST_URI']."index.php";
}else{
$DOC_SELF_NAME	=substr($_SERVER['PHP_SELF'],9);
}
 ---------------------------------------------- */

$DOC_SELF_NAME	=$_SERVER['PHP_SELF'];
$DOC_PARSE_URL = parse_url($DOC_SELF_NAME);
$DOC_SELF_ONLY = basename($DOC_PARSE_URL[path]); //return 'index.php'
$DOC_SELF_NAME_ONLY	=substr(basename($DOC_SELF_NAME), 0,-4); // return 'index'
$DOC_SELF_PATH = substr($DOC_SELF_NAME, 0,-strlen(basename($DOC_SELF_NAME))); // return '/path/to/directoty/'

/*-- Start of Paging--------------*/
$arrayName = array("1", "5", "10", "20");
$nLimit = "20";
/*-- End of Paging--------------*/
?>
