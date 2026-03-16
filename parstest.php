<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheetcsv = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$spreadsheetcsv2 = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readercsv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

$readercsv->setDelimiter(',');
$readercsv->setEnclosure('"');
$readercsv->setInputEncoding('utf-8');

$spreadsheetcsv = $readercsv->load('pars.csv');
$spreadsheetcsv2 = $readercsv->load('ids.csv');
$stn2 = 2;
$prd = 'product';
$sheetcsv = $spreadsheetcsv->getActiveSheet();
$sheetcsv2 = $spreadsheetcsv2->getActiveSheet();
$last_rowcsv = (int) $sheetcsv->getHighestRow();
$last_rowcsv2 = (int) $sheetcsv2->getHighestRow();

/*while ($stn <= $last_rowcsv) {
    while (($spreadsheetcsv->getActiveSheet()->getCell('F'.$stn)->getValue()) != 1) {
        if (($spreadsheetcsv->getActiveSheet()->getCell('A'.$stn)->getValue()) == ($spreadsheetcsv2->getActiveSheet()->getCell('A'.$stn2)->getValue())) {
            $prd = $spreadsheetcsv2->getActiveSheet()->getCell('B'.$stn2)->getValue();
            $spreadsheetcsv->getActiveSheet()->setCellValue('D'.$stn, $prd);
            $spreadsheetcsv->getActiveSheet()->setCellValue('F'.$stn, 1);
            ++$stn;
            $stn2 = 1;
        }
        else {
            ++$stn2;
        }
    }
}*/

for ($stn = 2; $stn < ($last_rowcsv + 1); $stn++) {
    $stn2 = 2;
    while ($stn2 < ($last_rowcsv2 + 1)) {
        if (($spreadsheetcsv->getActiveSheet()->getCell('A'.$stn)->getValue()) == ($spreadsheetcsv2->getActiveSheet()->getCell('A'.$stn2)->getValue())) {
        $prd = $spreadsheetcsv2->getActiveSheet()->getCell('B'.$stn2)->getValue();
        $spreadsheetcsv->getActiveSheet()->setCellValue('D'.$stn, $prd);
        $stn2 = ($last_rowcsv2 + 1);
    }
        else {
        ++$stn2;
    }
}

}

$rpres = $spreadsheetcsv->getActiveSheet()->getCell('B296')->getValue();
$pid = $spreadsheetcsv->getActiveSheet()->getCell('A296')->getValue();

$pattern1 = "/PR\";a:6:{s:5:\"value(.*)status/"; 
preg_match($pattern1 , $rpres, $matches1);
$tq1 = str_replace("\";d:", "", $matches1[1]);
$tq2 = str_replace("\";s:3:\"", "", $tq1);
$tq3 = str_replace("\";s:1:\"", "", $tq2);
$quantity = intval($tq3);
$prdid = $spreadsheetcsv->getActiveSheet()->getCell('D296')->getValue();
print "Количество у p_id ".$pid." равно ".$quantity.". Product ID - ".$prdid;
?>