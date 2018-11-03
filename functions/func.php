<?php
// Для проверки валидности даты рождения
function timecheck($array)
{
    return (bool)strtotime($array);
}

// Для удаления лишних символов из строки
function trimmer($array) {
   // Исключаем из фильтра рузге алфавит, англицке навсякий ну и "-", ".", " "
   return preg_replace('/[^A-Za-z0-9А-Яа-я\-\s\.]/', '', $array); 
}

?>
