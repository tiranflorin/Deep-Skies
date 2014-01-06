<?php
session_start();

require_once('lib/tcpdf/tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF
{
    //private $_dbHandle;
    //private $_sDbName;
    private $_tempTable;
    private $_sourceTable;
    public $_sObserverLocation;
    public $_sObserverDateTime;
    public $_sObserverTimezone;

    public function dbConnect()
    {
        //$this->_dbHandle = new PDO('mysql:host=localhost;dbname=dso', 'root', '', array(PDO::ATTR_PERSISTENT => true));
        //$this->_sDbName = 'dso';
        if (isset($_SESSION['tempVisibleObjectsTable'])) {
            $this->_tempTable = $_SESSION['tempVisibleObjectsTable'];
        } else {
            $sDate = date('Ymd');
            $this->_tempTable = "temp__default_visibleobjectsfor_{$sDate}4724";
        }
        $this->_sourceTable = "object";
    }

    public function getDefaultHeaderData(){
        //set PDF header details:
        if (!isset($_SESSION['boolCustomSettings']) || $_SESSION['boolCustomSettings'] == false) {
            $sDate = date('Y-m-d');
            $this->_sObserverLocation = "Location: Cluj Napoca, Romania, (23.45 E, 45.23 N)";
            $this->_sObserverDateTime = "Date time: $sDate - 23:10:00";
            $this->_sObserverTimezone = "Timezone: GMT +2:00";
        } else {
            $this->_sObserverLocation = $_SESSION['customSettings']['location'];
            $this->_sObserverDateTime = $_SESSION['customSettings']['datetime'];
            $this->_sObserverTimezone = $_SESSION['customSettings']['timezone'];
        }
    }

    public function myLoadData()
    {
        $sWhereCondition = " AND altaz_coord.object_id IN('";
        $sWhereCondition .= implode(',', $_SESSION['object_id']);
        $sWhereCondition = str_replace(',', "','", $sWhereCondition);
        $sWhereCondition .= "') ";
        //echo $sWhereCondition;

        $sSql = "
		SELECT
            altaz_coord.object_id as `Object_id`,    
            source.name as `Name1`,
            source.other_name as `Name2`,
            source.type as `ObjType`,
            source.constellation as `Constellation`,
            source.mag as `ObjMagnitude`,
            source.size_min as `ObjMinSize`,
            source.size_max as `ObjMaxSize`, 
            source.ngc_description as `Ngc_desc`,
            source.notes as `Other_notes`,
            altaz_coord.altitude as `obj_altitude`,
            altaz_coord.azimuth as `obj_azimuth`
        FROM `{$this->_sDbName}`.`{$this->_sourceTable}`  as source
        LEFT JOIN `{$this->_sDbName}`.`{$this->_tempTable}` as altaz_coord
            ON altaz_coord.object_id = source.id
        WHERE 1
            AND `altitude` > 10
            {$sWhereCondition}
        ORDER BY 
            `ObjMagnitude`
        LIMIT 20";

        /*
        echo "<pre>";
        echo $sSql;
        die();
        */

        $stmt = $this->_dbHandle->prepare($sSql);
        $stmt->execute();
        $res = array();
        //echo '<pre>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $res[] = $row;
        }

        /*
        echo "<pre>";
        var_dump($res);
        die();
        */
        return $res;
    }

    public function myColoredTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(30, 40, 20, 20, 20, 50);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['Name1'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['Name2'], 'LR', 0, 'R', $fill);
            $this->Cell($w[2], 6, $row['ObjType'], 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, $row['Constellation'], 'LR', 0, 'R', $fill);
            $this->Cell($w[4], 6, $row['ObjMagnitude'], 'LR', 0, 'R', $fill);
            //$this->Cell($w[5], 12, $row['Ngc_desc'], 'LR', 0, 'R', $fill);
            //$this->Cell($w[6], 12, $row['Other_notes'], 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, '', 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('meAgain');
$pdf->SetAuthor('http://deep-skies.com');
$pdf->SetTitle('Deep Sky Objects observing list');
$pdf->getDefaultHeaderData();
$pdf->SetSubject("Session info: $pdf->_sObserverLocation; ");
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$sArg = "Session info: $pdf->_sObserverLocation;  \n $pdf->_sObserverDateTime; $pdf->_sObserverTimezone. ";
$pdf->SetHeaderData('crescent_neb_logo.jpg', PDF_HEADER_LOGO_WIDTH, 'Deep Sky Objects observing list', $sArg);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

// column titles
$header = array('Name1', 'Name2', 'Type', 'Const', 'Mag', 'Your notes');

// data loading
//$data = $pdf->LoadData('lib/tcpdf/examples/data/table_data_demo.txt');
$pdf->dbConnect();
$data = $pdf->myLoadData();

// print colored table
$pdf->myColoredTable($header, $data);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('CustomDsoExport.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
