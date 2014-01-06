<?php
    //include fisierele necesare:
    require_once('../DbPdo.php');
    $sSql = "
        SELECT
            `id`,
            `ra`,
            `dec`
        FROM `object`
        WHERE 1
    ";

    foreach ($dbh->query($sSql) as $row) {
        /*
        echo $row['id'];echo '<br/>';
        echo $row['ra'];echo '<br/>';
        echo $row['dec'];echo '<br/>';
        echo '<hr/>';
        */

        $iId = $row['id'];
        $tmpRa = $row['ra'];
        $tmpDec = $row['dec'];
        //$tmpRa = '16 41.7';
        //$tmpDec = '+36 28';

        //parse and extract RA info:
        $iRaPart1 = (int)substr($tmpRa,0,2);
        $iRaPart2 = (float)substr($tmpRa,-4);
        //echo "<strong>$iRaPart1 $iRaPart2 </strong>";

        //parse and extract DEC info:
        $sSign = substr($tmpDec,0,1);
        $iDecPart1 = (int)substr($tmpDec,1,2);
        $iDecPart2 = (float)substr($tmpDec,-2);
        //echo "<strong>$sSign $iDecPart1 $iDecPart2 </strong>";

        $dRa = $iRaPart1 + ($iRaPart2/60);//here RA is in hours
        $dRa = $dRa * 15; // we need RA in degrees
        $dDec = $iDecPart1 + ($iDecPart2/60);

        //format numbers:
        $dRa = number_format($dRa,5,'.',',');
        $dDec = number_format($dDec,5,'.',',');
        if($sSign == '-'){
            $dDec = -$dDec;
        }

        echo $row['ra']." - normal || $dRa - float RIGHT ASCESION <br/>";
        echo $row['dec']." - normal || $dDec - float DECLINATION <br/>";



        $sSqlUpdate = "
            UPDATE `object`
            SET
                `ra_float` = '{$dRa}',
                `dec_float` = '{$dDec}'
            WHERE 1
                AND `id` = '{$iId}'
        ";
        $iAffectedRows = $dbh->exec($sSqlUpdate);
        echo "Affected rows: $iAffectedRows <hr/>";
    }
?>