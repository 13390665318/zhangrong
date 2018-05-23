<?php
ini_set("display_errors", "On"); 

/**
 * test short summary.
 *
 * test description.
 *
 * @version 1.0
 * @author admin
 */
echo phpinfo();
exit(0);

echo DbGetAccountIds();

function DbGetAccountIds()
{
    $arr[] = null;
    try
    {
        $query = "SELECT NAME from stage_pve limit 10;";
        $con = mysql_connect("10.11.110.100","bfadmin",'qoxmf321#@!', 1, 131072); 
        mysql_select_db("51_newstatisticsdb", $con);
        mysql_query("set names utf8",$con);
        $rs = mysql_query($query, $con);
        if($rs)
        {
            while($row = mysql_fetch_row($rs))
            {
                $arr_row = array('NAME' => $row[0]);
                $arr[] = $arr_row;
            }
        }
        else
        {
            $err = $query .":". mysql_error();
        }
        mysql_free_result($rs);
        mysql_close($con);
    }
    catch(Exception $ex)
    {
        echo "err" . $ex;
    }
    if(count($arr) > 0)
    {
        return json_encode($arr);
    }
    else
    {
        return "";
    }
}