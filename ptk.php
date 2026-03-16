<?php
require '/srv/projects/100kwatt.ru/public_html/myscripts/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load('/srv/projects/100kwatt.ru/public_html/myscripts/ptk_prices.xlsx');
 
    $sheet = $spreadsheet->getActiveSheet();
    $last_row = (int) $sheet->getHighestRow();
    $i = 5;
    $sheet->setCellValue('E2', 'Общее наличие МСК+СПБ');
    
    while ($i <= $last_row) {
        $sheet->setCellValue('E'.$i, '=G'.$i.'+I'.$i++);
    }
 
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save('/srv/projects/100kwatt.ru/public_html/myscripts/ptk_prices.xlsx');
?>