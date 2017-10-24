# Тестовое задание WOW

## Как запускать проект

1. Файл [scripts/installSettings.json.sample](scripts/installSettings.json.sample) скопировать в scripts/installSettings.json

2. В свойство githubToken файла `scripts/installSettings.json` добавить свой github токен. Это нужно для того, чтобы композер скачал yii2.

3. В hosts-файле прописать
``192.168.22.25 wowtest.dev``

4. Потом надо установить [VirtualBox](https://www.virtualbox.org/wiki/Downloads) последней версии

5. Установить [Vagrant](https://www.vagrantup.com/downloads.html)

6. Перейти в терминал/консоль

7. В терминале перейти в папку [vm/ubuntu1604](vm/ubuntu1604) `cd vm/ubuntu1604`

8. Ввести в терминале `vagrant plugin install vagrant-vbguest`

9. Ввести `vagrant up`

После этого установится и запустится виртуальная машина со всем окружением. Всё установится, настроится и запустится.

## Как управлять виртуалкой

Находясь в терминале в папке [vm/ubuntu1604](vm/ubuntu1604) используйте следующие команды:

- `vagrant up`  чтобы запустить виртуалку

- `vagrant halt` чтобы остановить машину (выключить)

- `vagrant destroy -f` чтобы удалить машину

## Как запускать/смотреть

- [wowtest.dev](http://wowtest.dev) - открыть сайт с заданием

- [192.168.22.25](http://192.168.22.25) - открыть сайт с заданием

- [wowtest.dev/image-list?id=59efb8ffb42e3](http://wowtest.dev/image-list?id=59efb8ffb42e3) - это пример урла доступа к REST API (насколько я понял, что это такое)

