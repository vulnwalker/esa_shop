<?php
include("config.inc.php");
JConn();




@set_time_limit(600);
$crlf="\n";
if(empty($asfile))
{
    echo "<div align=left><pre>\n";

} 
else 
{
    header("Content-disposition: filename=$db.sql");
    header("Content-type: application/octetstream");
    header("Pragma: no-cache");
    header("Expires: 0");
   
    // doing some DOS-CRLF magic...
    $client = getenv("HTTP_USER_AGENT");
    if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) 
    {
        $os = $regs[1];
        // this looks better under WinX
        if (eregi("Win",$os)) 
            $crlf="\r\n";
    }
}

function get_table_def($db, $table, $crlf)
{
    global $drop;

    $schema_create = "";
    if(!empty($drop))
        $schema_create .= "DROP TABLE IF EXISTS $table;$crlf";

    $schema_create .= "CREATE TABLE $table ($crlf";

    $result = mysql_query("SHOW FIELDS FROM $table") or mysql_die();
    while($row = mysql_fetch_array($result))
    {
        $schema_create .= "   $row[Field] $row[Type]";

        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
            $schema_create .= " DEFAULT '$row[Default]'";
        if($row["Null"] != "YES")
            $schema_create .= " NOT NULL";
        if($row["Extra"] != "")
            $schema_create .= " $row[Extra]";
        $schema_create .= ",$crlf";
    }
    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
    $result = mysql_query("SHOW KEYS FROM $table") or mysql_die();
    while($row = mysql_fetch_array($result))
    {
        $kname=$row['Key_name'];
        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
            $kname="UNIQUE|$kname";
         if(!isset($index[$kname]))
             $index[$kname] = array();
         $index[$kname][] = $row['Column_name'];
    }

    while(list($x, $columns) = @each($index))
    {
         $schema_create .= ",$crlf";
         if($x == "PRIMARY")
             $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
         elseif (substr($x,0,6) == "UNIQUE")
            $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
         else
            $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
    }

    $schema_create .= "$crlf)";
    return (stripslashes($schema_create));
}

// Get the content of $table as a series of INSERT statements.
// After every row, a custom callback function $handler gets called.
// $handler must accept one parameter ($sql_insert);
function get_table_content($db, $table, $handler)
{
    $result = mysql_query("SELECT * FROM $table") or mysql_die();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        set_time_limit(60); // HaRa
        $table_list = "(";

        for($j=0; $j<mysql_num_fields($result);$j++)
            $table_list .= mysql_field_name($result,$j).", ";

        $table_list = substr($table_list,0,-2);
        $table_list .= ")";

        if(isset($GLOBALS["showcolumns"]))
            $schema_insert = "INSERT INTO $table $table_list VALUES (";
        else
            $schema_insert = "INSERT INTO $table VALUES (";

        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= " NULL,";
            elseif($row[$j] != "")
                $schema_insert .= " '".addslashes($row[$j])."',";
            else
                $schema_insert .= " '',";
        }
        $schema_insert = ereg_replace(",$", "", $schema_insert);
        $schema_insert .= ")";
        $handler(trim($schema_insert));
        $i++;
    }
    return (true);
}

function count_records ($db,$table)
{
    $result = mysql_query($db, "select count(*) as num from $table");
    $num = mysql_result($result,0,"num");
    echo $num;
}

// Get the content of $table as a CSV output.
// $sep contains the separation string.
// After every row, a custom callback function $handler gets called.
// $handler must accept one parameter ($sql_insert);
function get_table_csv($db, $table, $sep, $handler)
{
    $result = mysql_query($db, "SELECT * FROM $table") or mysql_die();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        set_time_limit(60); // HaRa
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $handler(trim($schema_insert));
        $i++;
    }
    return (true);
}


function my_handler($sql_insert)
{
    global $crlf, $asfile;
    
    if(empty($asfile)) 
    {
        echo htmlspecialchars("$sql_insert;$crlf");
    }
    else
    {
        echo "$sql_insert;$crlf";
    }
}

$tables = mysql_list_tables($db);

$num_tables = @mysql_numrows($tables);
if($num_tables == 0)
{
    echo $strNoTablesFound;
}
else
{
    $i = 0;
    print "# Inovasi MySQL-Dump$crlf";
    print "# ICT Subang$crlf";
    print "#$crlf";

    while($i < $num_tables)
    { 
        $table = mysql_tablename($tables, $i);
        print $crlf;
        print "# ========= #$crlf";
        print "#$crlf";
        print "#$crlf";
        print $crlf;
        echo get_table_def($db, $table, $crlf).";$crlf$crlf";
        
        if($what == "data")
        {
            print "#$crlf";
            print "#$crlf";
            print "# Dumping Data";
            print "#$crlf";
            get_table_content($db, $table, "my_handler");
        }
        $i++;
    }
}

if(empty($asfile))
{
    print "</pre></div>\n";
}
?>
