

# terbase
Парсит список террористов, получаемый от "www.fedsfm.ru"

# Установка
Для установки используется либо HHVM, либо PHP7+, можно так же использовать вместе с PHPSH.

## Способ с использованием PHP:
```sh
$ apt-get update
$ apt-get install php php-mysql

$ git clone https://github.com/hugmouse/RTBPA.git
$ cd RTBPA/
$ php terror_parser.php
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

$ git clone https://github.com/hugmouse/RTBPA.git
$ cd RTBPA/
$ hhvm terror_parser.php
```

### Ubuntu
```sh
$ apt-get update
$ apt-get install software-properties-common apt-transport-https
$ apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xB4112585D386EB94

$ add-apt-repository https://dl.hhvm.com/ubuntu
$ apt-get update
$ apt-get install hhvm

$ git clone https://github.com/hugmouse/RTBPA.git
$ cd RTBPA/
$ hhvm terror_parser.php
```
##### Дополнительную информацию вы можете найти [на официальном сайте HHVM](https://docs.hhvm.com/hhvm/installation/linux)
