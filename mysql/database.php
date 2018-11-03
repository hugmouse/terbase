<?php
// Паарсинг файла конфигурации
$ini = parse_ini_file("configs/config.ini", true);


// Инициализация соединения с бд
@$link = new mysqli
(
    $ini["SERVER"]["IP"],
    $ini["SERVER"]["LOGIN"],
    $ini["SERVER"]["PASSWORD"], 
    $ini["SERVER"]["DATABASE"]
);

// При ошибке получаем номер ошибки, причину и распарсенные данные из файла конфигурации
if (mysqli_connect_errno()) 
{
    printf("Подключение не удалось: %s\n", mysqli_connect_error());

    echo 
    "\r\n Parsed INI info:".
    "\r\n IP = "                     .$ini["SERVER"]["IP"].
    "\r\n LOGIN = "                  .$ini["SERVER"]["LOGIN"].
    "\r\n PASSWORD = "               .$ini["SERVER"]["PASSWORD"].
    "\r\n DATABASE = "               .$ini["SERVER"]["DATABASE"].
    "\r\n TABLE = "                  .$ini["SERVER"]["TABLE"].
    "\r\n \r\n";

    exit();
}
else
{
    printf("Подключение к серверу '%s' прошло успешно :) \n ", $ini["SERVER"]["IP"]);
}

// Для русских символов в базе
$link->query("SET NAMES `utf8`");

// Удаляю всю базу перед очередным обновлением, потому что мне лень все делать через апдейты :)
$link->query("DELETE FROM ".$ini["SERVER"]["TABLE"]." WHERE 1");
?>