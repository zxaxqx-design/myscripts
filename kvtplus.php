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
    'http://stock.kvt24.ru/balances/sam/285315d3547d826393b5884cfa9fe4c3.xlsx';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = '/srv/projects/100kwatt.ru/public_html/myscripts/kvtplus.xlsx';
      
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

require '/srv/projects/100kwatt.ru/public_html/myscripts/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
    $spreadsheet = $reader->load('/srv/projects/100kwatt.ru/public_html/myscripts/kvtplus.xlsx');
 
    $sheet = $spreadsheet->getActiveSheet();
    $last_row = (int) $sheet->getHighestRow();
    $i = 2;
    $sheet->setCellValue('L1', 'Наличие КЛГ+СМР');
    
    while ($i <= $last_row) {
        $sheet->setCellValue('L'.$i, '=E'.$i.'+F'.$i++);
    }
 
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
    $writer->save('/srv/projects/100kwatt.ru/public_html/myscripts/kvtplus_final.xlsx');
?>