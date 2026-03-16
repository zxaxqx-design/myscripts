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
    'http://stock.kvt24.ru/stock_smr.csv';
      
    // Use basename() function to return the base name of file
    $date = date('d.m.y');
    $file_name = 'kvtpars.csv';
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents_curl($url)))
    {
        echo "Прайс-лист KVT успешно скачан.";
        print "<br>".$date;
    }
    else
    {
        echo "File downloading failed.";
    }

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$inputFileType = PhpOffice\PhpSpreadsheet\IOFactory::identify('kvtpars.csv');

$spreadsheetsta = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readersta = PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

$spreadsheetsta = $readersta->load('kvtpars.csv');


#записываем дату обновления в столбец

for ($tx = 1; $tx <=  $spreadsheetsta->getActiveSheet()->getHighestRow(); $tx++) {
    $spreadsheetsta->getActiveSheet()->setCellValue('K'.$tx, $date);
}

#записываем конечный файл
$stawriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetsta, "Xlsx");
$stawriter->save('kvtp.xlsx');

?>