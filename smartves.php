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
    'https://docs.google.com/spreadsheets/d/1twbnldHHxapl5UIeGuBu0FxadKBVGbWGVMG_ZqRjNzM/export?format=xlsx';
      
    // Use basename() function to return the base name of file
    $date = date('d_m_Y H:i', time());
    $file_name = '/srv/projects/100kwatt.ru/public_html/myscripts/smartves.xlsx';
      
    // Use file_get_contents() function to get the file
    // from url and use file_put_contents() function to
    // save the file by using base name
    if (file_put_contents($file_name, file_get_contents_curl($url)))
    {
        echo "Прайс-лист SMARTVES успешно скачан.";
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
$readersta->setLoadSheetsOnly('ценыостатки');

$spreadsheetpl = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$readerpl = PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
$readerpl->setLoadSheetsOnly('платформенные');

$spreadsheetsta = $readersta->load('/srv/projects/100kwatt.ru/public_html/myscripts/smartves.xlsx');
$spreadsheetpl = $readerpl->load('/srv/projects/100kwatt.ru/public_html/myscripts/smartves.xlsx');

$stnsta = 100;
$stnpl = 2;

while ($stnpl < 20) {
    $art = $spreadsheetpl->getActiveSheet()->getCell('A'.$stnpl)->getValue();
    $kolvo = $spreadsheetpl->getActiveSheet()->getCell('F'.$stnpl)->getValue();
    $cen = $spreadsheetpl->getActiveSheet()->getCell('B'.$stnpl)->getValue();
    $spreadsheetsta->getActiveSheet()->setCellValue('A'.$stnsta, $art);
    $spreadsheetsta->getActiveSheet()->setCellValue('G'.$stnsta, $kolvo);
    $spreadsheetsta->getActiveSheet()->setCellValue('B'.$stnsta, $cen);
    ++$stnsta;
    ++$stnpl;
}

#записываем конечный файл
$stawriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheetsta, "Xlsx");
$stawriter->save('/srv/projects/100kwatt.ru/public_html/myscripts/smartves1.xlsx');

?>