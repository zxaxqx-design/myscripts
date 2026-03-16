<?php
$path = '/srv/projects/100kwatt.ru/public_html/myscripts/epnew.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: '.filesize($path));

readfile($path);