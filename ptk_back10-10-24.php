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
    'https://ptk-svarka.ru/personal/export/prices.xlsx';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = 'ptk_'.basename($url);
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents_curl($url)))
    {
        echo "File downloaded successfully";
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
    $spreadsheet = $reader->load('ptk_prices.xlsx');
 
    $sheet = $spreadsheet->getActiveSheet();
    $last_row = (int) $sheet->getHighestRow();
    $i = 5;
    $sheet->setCellValue('E2', 'Общее наличие МСК+СПБ');
    
    while ($i <= $last_row) {
        $sheet->setCellValue('E'.$i, '=F'.$i.'+H'.$i++);
    }
 
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save('ptk_prices.xlsx');
?>