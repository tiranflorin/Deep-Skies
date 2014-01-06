<?php
/**
* PHPExcel
*
* Copyright (C) 2006 - 2013 PHPExcel
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*
* @category   PHPExcel
* @package    PHPExcel
* @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
* @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
* @version    1.7.9, 2013-06-02
*/

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/Bucharest');

if (PHP_SAPI == 'cli')
die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once 'PhpExcel/PHPExcel.php';

$aResult = array(0 => array('name1' => 'ngc1', 'name2' =>  'M 27', 'mag' => '8'),
				 1 => array('name1' => 'ngc2', 'name2' =>  'M 28', 'mag' => '9'),
				 2 => array('name1' => 'ngc3', 'name2' =>  'M 29', 'mag' => '10'),	
				 3 => array('name1' => 'ngc4', 'name2' =>  'M 30', 'mag' => '7')	
);

/*
echo "<pre>";
print_r($aResult);
die;
*/

function exportToXls($aRes){
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("DSO Planner")
	->setLastModifiedBy("DSO Planner")
	->setTitle("DSO List")
	->setSubject("DSO XLS Custom List")
	->setDescription("DSO object list, generated using PHPExcel.")
	->setKeywords("DSO list xls php")
	->setCategory("xlsx export");	

	$aColumnNames = array_keys($aRes[0]);
	$let = 'A';	
	$row = 1;
	for($i=0; $i<count($aColumnNames);$i++){
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("$let$row", "$aColumnNames[$i]");
		$let++;
	}	

	$letter = 'A';
	$i=2;
	foreach ($aRes as $numRows) {
		

		foreach ($numRows as $key => $value) {
			//echo "$key - $value";
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$letter$i", "$value");
			//echo "$letter$i  - $value  <br/>";
			$letter++;
		}
		$letter = 'A';
		$i++;
	}

	

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Customized DSO Export');


	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$sharedStyle1 = new PHPExcel_Style();
	
	$sharedStyle1->applyFromArray(
		array(	'fill' 	=> array(
								'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
								'color'		=> array('rgb' => '059329')
							),
		  		'borders' => array(
								'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'top'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
								'left'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
							),
		  		'alignment' => array(
        						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    						),
		));

	$endLetter = chr(ord($let) - 1);

	// Forces the spreadsheet to take the size of the longest value             
	for ($col = 'A'; $col != "$endLetter"; $col++) { //Runs through all cells between A and E and sets to autosize
	    $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
	}

	$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "A1:$endLetter$row");
	
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="CustomDsoExport.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;			
}