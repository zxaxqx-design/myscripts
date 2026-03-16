<?php
$file = __DIR__ . '/epnew.xlsx';
if (!file_exists($file)) {
    http_response_code(404);
    exit('file not found');
}

// Сброс любых проверок
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="epnew.xlsx"');
header('Content-Length: ' . filesize($file));

// Отключаем авторизацию/проверку
// Эта строка нужна, если на сервере включена какая-то базовая защита от скриптов
ignore_user_abort(true);
readfile($file);
exit;