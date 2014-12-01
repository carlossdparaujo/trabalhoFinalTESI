<?php
/**
 * Created by JetBrains PhpStorm.
 * User: windows
 * Date: 27/11/14
 * Time: 00:35
 * To change this template use File | Settings | File Templates.
 */

require_once('140dev_config.php');
require_once('db_lib.php');
$oDB = new db;
$filename = 'entities.json';
// This should run continuously as a background process

sleep(30);

while (true) {
    $entities = file_get_contents ( $filename , FILE_TEXT);
    $success = true;

    if($entities)
    {
        $entities = trim($entities);

        $bom = pack('H*','EFBBBF');
        $entities = preg_replace("/^$bom/", '', $entities);

        //$entities = substr($entities,0,strlen($entities)-1);
        //$entities = '{"tweets":[' . $entities . ']}';

        $entitiesObj = json_decode($entities);
        $entitiesArray = $entitiesObj->tweets;

        mysqli_autocommit($oDB->dbh, FALSE);
        mysqli_begin_transaction($oDB->dbh);

        foreach ($entitiesArray as $ent)
        {
            $query = 'SELECT entitie_id ' .
            'FROM entities '.
            'WHERE entitie_dbpedia_uri = "'. $ent->dbpedia . '"' ;
            $result = $oDB->select($query);

            if($result && mysqli_num_rows($result) > 0)
            {
                $entitie_id = $result->fetch_assoc()["entitie_id"];
                $query = 'SELECT entitie_name_id' .
                    ' FROM entities_names'.
                    ' WHERE entitie_label = "'. $ent->label . '"' .
                    ' AND entitie_id =' . $entitie_id;
                $result = $oDB->select($query);

                if($result &&mysqli_num_rows($result) > 0)
                {
                    $entitie_name_id = $result->fetch_assoc()["entitie_name_id"];
                    $query = 'UPDATE entities_names' .
                        ' SET use_count = use_count + 1'.
                        ' WHERE entitie_name_id = "'. $entitie_name_id . '"' ;
                    $result = $oDB->select($query);
                    $success = $result;
                }else
                {
                    $query = 'INSERT INTO' .
                        ' entities_names (entitie_id, entitie_label, use_count)'.
                        ' VALUES'.
                        ' (' . $entitie_id . ', "' . $ent->label . '", 1)';
                    $result = $oDB->select($query);
                    $success = $result;
                }
            }else{
                $query = 'INSERT INTO entities(entitie_dbpedia_uri)' .
                    ' VALUES ("' . $ent->dbpedia . '")';
                $result = $oDB->select($query);

                $entitie_id = $oDB->dbh->insert_id;

                if($entitie_id > 0)
                {
                    $query = 'INSERT INTO' .
                        ' entities_names (entitie_id, entitie_label, use_count)' .
                        ' VALUES '.
                        ' (' . $entitie_id . ', "' . $ent->label . '", 1)';
                    echo $query;
                    $result = $oDB->select($query);
                    $success = $result;
                }else{
                    $success = false;
                }
            }
            if(!$success)
            {
                break;
            }
        }
        if($success)
        {
            echo "caminho s\r\n";
            mysqli_commit($oDB->dbh);
        }else{
            echo "caminho f\r\n";
           mysqli_rollback($oDB->dbh);
        }
        mysqli_autocommit($oDB->dbh, TRUE);
    }
    if($success)
    {
       file_put_contents( $filename, "", LOCK_EX);
    }
    sleep(20);
}