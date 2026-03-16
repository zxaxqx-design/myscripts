<?php
     // функция для устранения проблем с ssl и file_get_contents на сервере 100kwatt
     function file_get_contents_curl( $url ) {

        $ch = curl_init();
      
        curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
      
        $data = curl_exec( $ch );
        curl_close( $ch );
      
        return $data;
      
      } 
    // Initialize a file URL to the variable
    $url = 
    'https://foxweld.ru/upload/Foxweld_price.xlsx?nocache';
    $urlcsv = 
    'https://foxweld.ru/upload/csv_stock.csv';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = 'foxweld_prices.xlsx';
    $file_name2 = 'foxweld_csv.csv';
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents_curl($url)) && file_put_contents($file_name2, file_get_contents_curl($urlcsv)))
    {
        echo "Files downloaded successfully";
        print "<br>".$date;
    }
    else
    {
        echo "File downloading failed.";
    }

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load('foxweld_prices.xlsx');
 
    $sheet = $spreadsheet->getActiveSheet();
    $last_row = (int) $sheet->getHighestRow();
    $i = 8;
    $xn = 7;
    $pricerow = 1;
    
    #цикл для исключения строчек с пустыми значениями (строки без артикулов). На самом деле просто записываем данные из информативных ячеек в новое место таблицы.
    while ($i <= $last_row) {
        $pricerow = (int) $sheet->getCell('C'.$i)->getValue();
        if ($pricerow > 999) {
            ++$xn;
            $sheet->setCellValue('K'.$xn, '=A'.$i);
            $sheet->setCellValue('L'.$xn, '=B'.$i);
            $sheet->setCellValue('M'.$xn, '=C'.$i);
            $sheet->setCellValue('N'.$xn, '=E'.$i);
            ++$i;
        }
        else {
            ++$i;
        }
    }
 
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save('foxweld_prices.xlsx');


$reader2 = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet2 = $reader2->load('foxweld_prices.xlsx');
    $sheet2 = $spreadsheet2->getActiveSheet();

#записываем нужные данные из таблицы в массив, пересчитав все формулы в этих ячейках:
$foxarray = $spreadsheet2->getActiveSheet()
    ->rangeToArray(
        'K8:N1000',     // The worksheet range that we want to retrieve
        NULL,        // Value that should be returned for empty cells
        TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
        FALSE         // Should the array be indexed by cell row and cell column
    );

$foxedited = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$foxedited->getActiveSheet()
    ->fromArray(
        $foxarray,  // The data to set
        NULL,        // Array values with this value will not be set
        'A2'         // Top left coordinate of the worksheet range where
                     //    we want to set these values (default is A1)
    );

$foxedited->getActiveSheet()->setCellValue('A1', 'Артикул от Foxweld');
$foxedited->getActiveSheet()->setCellValue('B1', 'Наименование');
$foxedited->getActiveSheet()->setCellValue('C1', 'РРЦ');
$foxedited->getActiveSheet()->setCellValue('D1', 'Остатки МСК');

#ширина для столбцов:
for ($z = 'A'; $z <=  $foxedited->getActiveSheet()->getHighestColumn(); $z++) {
    $foxedited->getActiveSheet()->getColumnDimension($z)->setWidth(40);
}

#записываем конечный файл:
$foxeditedwriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($foxedited, "Xlsx");
$foxeditedwriter->save('foxweld_prices.xlsx');

#converting and editing csv to xlsx
$spreadsheetcsv = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readercsv = new \PhpOffice\PhpSpreadsheet\Reader\Csv();

$readercsv->setDelimiter(';');
$readercsv->setEnclosure('"');
$readercsv->setInputEncoding('windows-1251');

$spreadsheetcsv = $readercsv->load('foxweld_csv.csv');

#цикл для исключения строчек с пустыми значениями (строки без артикулов). На самом деле просто записываем данные из информативных ячеек в новое место таблицы.
$sheetcsv = $spreadsheetcsv->getActiveSheet();
    $last_rowcsv = (int) $sheetcsv->getHighestRow();
    $ci = 2;
    $cxn = 1;
    $pricerowcsv = 1;
    
    while ($ci <= $last_rowcsv) {
        $pricerowcsv = (int) $sheetcsv->getCell('C'.$ci)->getValue();
        if ($pricerowcsv > 999) {
            ++$cxn;
            $sheetcsv->setCellValue('K'.$cxn, '=A'.$ci);
            $sheetcsv->setCellValue('L'.$cxn, '=B'.$ci);
            $sheetcsv->setCellValue('M'.$cxn, '=C'.$ci);
            $sheetcsv->setCellValue('N'.$cxn, '=D'.$ci);
            ++$ci;
        }
        else {
            ++$ci;
        }
    }


#записываем нужные данные из таблицы в массив, пересчитав все формулы в этих ячейках:
$foxarraycsv = $spreadsheetcsv->getActiveSheet()
    ->rangeToArray(
        'K2:N1000');

$foxeditedcsv = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$foxeditedcsv->getActiveSheet()
    ->fromArray($foxarraycsv, '', 'A2');

$foxeditedcsv->getActiveSheet()->setCellValue('A1', 'Артикул от Foxweld');
$foxeditedcsv->getActiveSheet()->setCellValue('B1', 'Наименование');
$foxeditedcsv->getActiveSheet()->setCellValue('C1', 'РРЦ');
$foxeditedcsv->getActiveSheet()->setCellValue('D1', 'Остатки МСК');



#ширина столбцов
for ($tz = 'A'; $tz <=  $foxeditedcsv->getActiveSheet()->getHighestColumn(); $tz++) {
    $foxeditedcsv->getActiveSheet()->getColumnDimension($tz)->setWidth(40);
}

#записываем конечный файл (из csv получается xlsx)
$foxcsvwriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($foxeditedcsv, "Xlsx");
$foxcsvwriter->save('foxweld_csv_to_xlsx.xlsx');


?>