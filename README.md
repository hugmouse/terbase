

# terbase
Парсит список террористов, получаемый от "www.fedsfm.ru"

# Установка необходимого ПО
Для установки используется либо HHVM, либо PHP7+, можно так же использовать вместе с PHPSH.

## Способ с использованием PHP:
```sh
$ apt-get update
$ apt-get install php php-mysql php-mbstring
```

## Для использования вместе с HHVM:

### Debian 8 Jessie, Debian 9 Stretch
```sh
$ apt-get update
$ apt-get install -y apt-transport-https software-properties-common
$ apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xB4112585D386EB94

$ add-apt-repository https://dl.hhvm.com/debian
$ apt-get update
$ apt-get install hhvm
```

### Ubuntu
```sh
$ apt-get update
$ apt-get install software-properties-common apt-transport-https
$ apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xB4112585D386EB94

$ add-apt-repository https://dl.hhvm.com/ubuntu
$ apt-get update
$ apt-get install hhvm
```
##### Дополнительную информацию вы можете найти [на официальном сайте HHVM](https://docs.hhvm.com/hhvm/installation/linux)

# Использование
### Перед использованием алгоритма стоит создать базу со следующими значениями:
```markdown
| name | surname | patronymic | tronim | date_of_birth | place_of_birth | name_second | surname_second | patronymic_second | ID      |
|------|---------|------------|--------|---------------|----------------|-------------|----------------|-------------------|---------|
| TEXT | TEXT    | TEXT       | TEXT   | DATE          | TEXT           | TEXT        | TEXT           | TEXT              | INT(16) |
```
##### Пример создания таблицы `'TABLENAME'` в базе с именем `'DB'` (MySQL):
```sql
CREATE TABLE `DB`.`TABLENAME`
 ( 
 `name` TEXT NOT NULL ,
 `surname` TEXT NOT NULL ,
 `patronymic` TEXT NOT NULL ,
 `tronim` TEXT NOT NULL ,
 `date_of_birth` DATE NOT NULL ,
 `place_of_birth` TEXT NOT NULL ,
 `name_second` TEXT NOT NULL ,
 `subname_second` TEXT NOT NULL ,
 `patronymic_second` TEXT NOT NULL ,
 `ID` INT NOT NULL
 ) 
ENGINE = InnoDB;
```
---
##### Запуск скрипта:
```sh
$ git clone https://github.com/hugmouse/terbase.git
$ cd terbase
$ php terror_parser.php 
# или 'hhvm terror_parser.php', если вы ставили 'hhvm' изначально
```

После установки и вызова `terror_base.php` создается файл `terrorist-base.json` , содержащий все те же данные, что передаются в базу, однако в `.json`.

