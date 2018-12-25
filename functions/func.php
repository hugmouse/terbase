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

// thx msxcms@bmforum.com (http://php.net/manual/function.json-last-error.php)
function safe_json_encode($value, $options = 0, $depth = 512) 
{
    $encoded = json_encode($value, $options, $depth);
    
    if ($encoded === false && $value && json_last_error() == JSON_ERROR_UTF8) 
    {
        $encoded = json_encode(utf8ize($value), $options, $depth);
    }

    return $encoded;
}

function utf8ize($mixed) 
{
    if (is_array($mixed)) 
    {
        foreach ($mixed as $key => $value) 
        {
            $mixed[$key] = utf8ize($value);
        }
    } 
    elseif (is_string($mixed)) 
    {
        return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
    }

    return $mixed;
}    
?>