<?php
header('Content-Type: text/html; charset=utf8');

// Чтобы хост не подумол, что мы роботы
ini_set("user_agent","Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0");

// Для парсинга HTML
require 'libs/simple_html_dom.php';
include 'functions/func.php';
include 'mysql/database.php';

//  "#russianFL" для загрузки листов
$html = file_get_html('http://www.fedsfm.ru/documents/terrorists-catalog-portal-act#russianFL');

// Для замера времени исполнения алгоритма
$time_start = microtime(true); 

// Находит все вхождения, то-есть список террористов
// Так же страница подгружает и другие вкладки с одинаковыми классами, 
// то начинаем поиск с 3 вхождения
$html_ul = $html->find('ol[class=terrorist-list]', 3);


foreach($html_ul->find('li') as $li) {
    // /[^\,]+/ тоже возможно, но я захотел через пробелы :)
    preg_match_all('/[^\s]+/', $li->plaintext, $word); 
    $amount = count($word[0]);

    $item['ID']         = $word[0][0];
    $item['name']       = $word[0][1];
    $item['surname']    = $word[0][2];
    $item['patronymic'] = $word[0][3];

    // Поиск исключений ввиде ФИО (ФИО). Бред.
    if (preg_match_all("/[\(\)]/", $word[0][4], $word_clear, PREG_OFFSET_CAPTURE)) 
    {
        // ФИО(ФИО)
        //    ^  ^ убираем эти тупые скобки
        $clear_name_patronymic = str_replace(array( '(', ')'), '', array( $word[0][4], $word[0][6] ));
        $item['name_second'] = $clear_name_patronymic[0];
        $item['surname_second'] = $word[0][5];
        $item['patronymic_second'] = $clear_name_patronymic[1];
    }
    else
    {
        if (preg_match_all("/(ОГЛЫ)|(УГЛИ)|(КЫЗЫ)|(УУЛУ)|(КИЗИ)/", $word[0][4], $word_clear, PREG_OFFSET_CAPTURE))
        {
            $item['date_of_birth'] = $word[0][5];
            // i=8 ибо с 8 позиции в массиве начинается место проживания, это из-за указателей пола на Тюрских языках
            $i=8;
            while ($i < $amount) 
            {
                $item['place_of_birth'] .= $word[0][$i]." ";
                $i++;
            }
        }
        else
        {
            unset($item['name_second'], $item['surname_second'], $item['patronymic_second'], $item['date_of_birth'], $item['place_of_birth']);
            $item['date_of_birth'] = $word[0][4];
            // i=7 ибо с 7 позиции в массиве начинается место проживания, это в обычном случае ФИО.
            $i=7;
            while ($i < $amount) 
            {
                @$item['place_of_birth'] .= $word[0][$i]." ";
                $i++;
            }
        }

    }

    if (timecheck($item['date_of_birth']) == false) 
    {
        // В случае, если у человека указано лишь фамилия и имя, а потом идет дата рождения
        if (timecheck($word[0][3]) == false)
        {
            unset($item['date_of_birth'], $item['place_of_birth']);
        }
        else
        {
            unset($item['patronymic']);
            $item['date_of_birth'] = $word[0][3];
        }
    }


    // В базе террористов порой встречается формат "ФИО, ,ГОРОД"
    // Для таких случаев следующая проверочка
    if (strlen(@$item['date_of_birth']) < 2)
    {
        unset($item['date_of_birth']);
    }

    // Для удобства
    $list = 
    [
        [
        'name'               => trimmer(@$item['name']), 
        'surname'            => trimmer(@$item['surname']),
        'patronymic'         => trimmer(@$item['patronymic']),
        'date_of_birth'      => trimmer(@$item['date_of_birth']),
        'place_of_birth'     => trimmer(@$item['place_of_birth']),
        'name_second'        => trimmer(@$item['name_second']),
        'surname_second'     => trimmer(@$item['surname_second']),
        'patronymic_second'  => trimmer(@$item['patronymic_second']),
        'ID'                 => preg_replace('/(\.)/', '', @$item['id'])
        ],
    ];

    $all[] = $list[0];

    // Формируем огромный запрос!
    foreach ($list as $key => $data) 
    {
        $sql_out .= 
        "(
            '{$data['name']}',
            '{$data['surname']}',
            '{$data['patronymic']}',
            '{$data['date_of_birth']}',
            '{$data['place_of_birth']}',
            '{$data['name_second']}',
            '{$data['surname_second']}',
            '{$data['patronymic_second']}',
            '{$data['ID']}'
        ),";
    }

}
$sql_in = "INSERT INTO ".$ini["SERVER"]["TABLE"]."
        (`name`, `surname`, `patronymic`, `date_of_birth`, `place_of_birth`, `name_second`, `subname_second`, `patronymic_second`, `ID`)
    VALUES ";

// Заменяем последнюю в массиве "," на ";" и делаем запрос
$sql_out = substr_replace($sql_out, ';', -1);
$link->query($sql_in.$sql_out);

// Для json вывода
$json = safe_json_encode($all, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT); 
file_put_contents('terrorist-base.json', $json); 

// Закрываем соединение
mysqli_close($link);

printf('Обработано %d строк за %f секунд. Все удачно! %c', $data['ID'], (microtime(true) - $time_start), 10);
?>
