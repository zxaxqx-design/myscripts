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
    'http://stalex.ru/upload/CSProductExport.xlsx';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = '/srv/projects/100kwatt.ru/public_html/myscripts/stalex.xlsx';
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents_curl($url)))
    {
        echo "Прайс-лист STALEX успешно скачан.";
        print "<br>".$date;
    }
    else
    {
        echo "File downloading failed.";
    }

require '/srv/projects/100kwatt.ru/public_html/myscripts/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheetsta = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readersta = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

$spreadsheetsta = $readersta->load('stalex.xlsx');

#ширина столбцов
for ($tz = 'A'; $tz <=  $spreadsheetsta->getActiveSheet()->getHighestColumn(); $tz++) {
    $spreadsheetsta->getActiveSheet()->getColumnDimension($tz)->setWidth(40);
}

#записываем конечный файл
$stawriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetsta, "Xlsx");
$stawriter->save('stalex.xlsx');

?>