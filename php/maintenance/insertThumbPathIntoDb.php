<?php
    set_time_limit(0);
    //include fisierele necesare:
    require_once('../DbPdo.php');
      
    /*    
    $sSqlCreateTable = "
        CREATE TABLE IF NOT EXISTS `image_paths` (  
            `id` int(10) NOT NULL AUTO_INCREMENT, 
            `object_id` int(10) NOT NULL,  
            `thumb` varchar(255) NOT NULL,  
            `full_size` varchar(255) NOT NULL,  
            PRIMARY KEY (`id`),  
            KEY `object_id` (`object_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ";
    $iAffectedRows = $dbh->exec($sSqlCreateTable);
    echo "Affected rows: $iAffectedRows <hr/>";
    */

    /*
    //Step1: insert thumbnails (TRUNCATE TABLE recomended before)
    $handle = @fopen("files/thumbs.txt", "r");
    if ($handle) {
        while (($thumbName = fgets($handle)) !== false) {
            //echo $thumbName;
            $thumbName = trim($thumbName);
            $tmp1 = str_replace('.gif', '', $thumbName);
            $tmp2 = str_replace('tn_', '', $tmp1);
            $searchedName = str_replace('_', '', $tmp2);
            //echo $searchedName;

            $sSqlInsert = "
                INSERT INTO  `image_paths`(
                    `object_id`,
                    `thumb`,
                    `full_size`
                )
                SELECT 
                    `id`,
                    '{$thumbName}',
                    ''
                FROM `object`
                WHERE  name = '{$searchedName}'
                ";
               
            $iAffectedRows = $dbh->exec($sSqlInsert);
            echo "Affected rows: $iAffectedRows <hr/>";
        }

        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }
    */

   	//Step2: update full_size column
    $handle = @fopen("files/large.txt", "r");
    if ($handle) {
        while (($thumbName = fgets($handle)) !== false) {
            $thumbName = trim($thumbName);
            $searchedName = str_replace('fs_', 'tn_', $thumbName);
            
            $sSqlInsert = "
                UPDATE `image_paths`
                SET `full_size` = '{$thumbName}'
                WHERE `thumb` = '{$searchedName}'  
                ";
            
            /*   
            echo "<pre>";
            echo $sSqlInsert;
            die;
            */ 
              

            $iAffectedRows = $dbh->exec($sSqlInsert);
            echo "Affected rows: $iAffectedRows <hr/>";
        }

        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }
?>