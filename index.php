<?php
session_start();
$sPageTitle = "Deep-Skies Home";
//unset($_SESSION['boolCustomSettings']);
//session_destroy();
$constellations = array(
    'AND', 'ANT', 'APS', 'AQL', 'AQR', 'ARA', 'ARI', 'AUR', 'BOO', 'CAE', 'CAM', 'CAP', 'CAR', 'CAS',
    'CEN', 'CEP', 'CET', 'CHA', 'CIR', 'CMA', 'CMI', 'CNC', 'COL', 'COM', 'CRA', 'CRB', 'CRT', 'CRU',
    'CRV', 'CVN', 'CYG', 'DEL', 'DOR', 'DRA', 'EQU', 'ERI', 'FOR', 'GEM', 'GRU', 'HER', 'HOR', 'HYA',
    'HYI', 'IND', 'LAC', 'LEO', 'LEP', 'LIB', 'LMI', 'LUP', 'LYN', 'LYR', 'MEN', 'MIC', 'MON', 'MUS',
    'NOR', 'OCT', 'OPH', 'ORI', 'PAV', 'PEG', 'PER', 'PHE', 'PIC', 'PSA', 'PSC', 'PUP', 'PYX', 'RET',
    'SCL', 'SCO', 'SCT', 'SER', 'SEX', 'SGE', 'SGR', 'TAU', 'TEL', 'TRA', 'TRI', 'TUC', 'UMA', 'UMI',
    'VEL', 'VIR', 'VOL', 'VUL'
);

$aSettings = array();
if (!isset($_SESSION['boolCustomSettings']) || $_SESSION['boolCustomSettings'] == false) {
    $sDate = date('Y-m-d');
    $currentSettings = 'Website default';
    $aSettings[0] = "<p>Location: Cluj Napoca, Romania, (23.45 E, 45.23 N)  </p>";
    $aSettings[1] = "<p>Date: $sDate  Time: 23:10:00</p>";
    $aSettings[2] = "<p>Timezone: GMT +2:00</p>";
} else {

    $currentSettings = 'Custom Settings';
    $location = $_SESSION['customSettings']['location'];
    $datetime1 = $_SESSION['customSettings']['datetime1'];
    $datetime2 = $_SESSION['customSettings']['datetime2'];
    $timezone = $_SESSION['customSettings']['timezone'];

    $aSettings[0] = "<p>Location: $location</p>";
    $aSettings[1] = "<p>Time interval: $datetime1  <> $datetime2</p>";
    $aSettings[2] = "<p><small>(*Note: Objects visible between this interval.) </small></p>";
    $aSettings[3] = "<p>Timezone: $timezone</p>";
}

if(isset($_POST['user_date'])){
    $sFormUserDate = $_POST['user_date'];
}
else{
    $sFormUserDate = date("Y-m-d");
}

require_once('php/views/header.php');
?>

<body>
<div id="wrap">

    <?php
    require_once('php/views/horizontalNavigation.php');
    require_once('php/views/index/jumbotron.php');
    ?>

    <div class="container">

        <?php
        require_once('php/views/index/showObserverSettings.php');
        require_once('php/views/index/observerSettingsForm.php');
        ?>

        <!-- <hr> -->
        <div class="row blue-bg" style="margin-top: 30px;">
            <div class="col-lg-12">
                <h2 class="text-center">Filter objects by</h2>
            </div>
        </div>

        <?php
        require_once('php/views/index/applyFilters.php');
        ?>

        <!-- <hr> -->
        <?php
        require_once('php/views/index/resultsVisibility.php');
        ?>
    </div><!-- /container -->

</div><!-- /wrap -->
<?php
require_once('php/views/index/modal.php');
require_once('php/views/index/hiddenLinks.php');
require_once('php/views/footer.php');
?>