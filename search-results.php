<?php
require_once('php/DbPdo.php');
session_start();
$sPageTitle = "Results Quick Search | Deep-Skies";

if(isset($_POST['quickSearch']) && ($_POST['quickSearch'] != '')){
    $keywords = $_POST['keywords'];
    $sCond1 = str_replace(" ", "", $keywords);

    $sCond2  = "%";
    //$sCond1 .= str_replace(" ", "%", $keywords);
    $sCond2 .= str_replace(" ", "", $keywords);
    $sCond2 .= "%";
    $sSql = "
                    SELECT
                    *
                    FROM dso.object
                    WHERE 1
                    AND (name LIKE '{$sCond1}' OR other_name like '$sCond1' OR notes LIKE '$sCond2')
                    ORDER BY mag ASC
                    LIMIT 100

                ";
    /*
    echo "<pre>";
    echo $sSql;
    die;
    */
    $stmt = $dbh->prepare($sSql);
    $stmt->execute();
    $res = array();
    //echo '<pre>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $res[]=$row;
    }
    $iResultsFound = count($res);
    //print_r($res);
}


require_once('php/views/header.php');
?>

<body>
<div id="wrap">

    <?php
    require_once('php/views/horizontalNavigation.php');
    require_once('php/views/search-results/jumbotron.php');
    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php
                    if(isset($_POST['quickSearch'])){
                        echo "<p>Your search for <span class='highlight'>$keywords</span> returned $iResultsFound results.</p>";
                    }
                ?>
            </div>
        </div>
        <div class="row">

            <div class='col-lg-12'>
            <?php
                if(isset($_POST['quickSearch'])){
                foreach ($res as $obj){
                    echo "<div class='panel panel-info'>";
                    echo "<div class='panel-heading'>
                                <h3 class='panel-title'>".$obj['name']."</h3>
                            </div>";
                    echo "<div class='panel-body'>";
                    echo "<div class='row'>
                            <div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
                    echo "<ul class='list-group' style='margin-bottom: 0px;'>";
                    echo    "<li class='list-group-item'>Other name: ". $obj['other_name']."</li>";
                    echo    "<li class='list-group-item'>Constellation: ". $obj['constellation']."</li>";
                    echo    "<li class='list-group-item'>Type: ". $obj['type']."</li>";
                    echo    "<li class='list-group-item'>Magnitude: ". $obj['mag']."</li>";
                    echo    "<li class='list-group-item'>Right Ascension: ". $obj['ra']."</li>";
                    echo    "<li class='list-group-item'>Declination: ". $obj['dec']."</li>";
                    echo "</ul>";
                    echo "</div>";
                    echo "<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>";
                    echo "<ul class='list-group' style='margin-bottom: 0px;'>";
                    echo "   <li class='list-group-item'>Minimal size: ". $obj['size_min']."</li>";
                    echo "   <li class='list-group-item'>Maximum size: ". $obj['size_max']."</li>";
                    echo "   <li class='list-group-item'>Class: ". $obj['class']."</li>";
                    echo "   <li class='list-group-item'>Nsts: ". $obj['nsts']."</li>";
                    echo "   <li class='list-group-item'>NGC Description: ". $obj['ngc_description']."</li>";
                    echo "   <li class='list-group-item'>Other notes: ". $obj['notes']."</li>";
                    echo "</ul>";

                    echo "</div></div>";
                    /*
                    foreach($obj as $column_name => $value){
                        if($column_name == 'name'){
                            echo "<div class='panel-heading'>
                                <h3 class='panel-title'>$value</h3>
                            </div>
                            <div class='panel-body'>";
                        }elseif(($column_name != 'id') && ($value != '')){
                            echo "<p>$column_name: $value";
                        }
                    }
                    */
                    echo "</div></div>";
                }
            }
            ?>
            </div>
            <!--<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel title</h3>
                </div>
                <div class="panel-body">
                    Panel content
                </div>
            </div> -->

        </div>
    </div>
    <!-- /container -->

</div>
<!-- /wrap -->
<?php
require_once('php/views/footer.php');
?>