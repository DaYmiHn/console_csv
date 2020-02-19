# Задание: Написать консольную утилиту, которая может загрузить CSV файл в БД


![](https://i.imgur.com/P5qnSqE.png)

### Требования
* в CSV файле для каждой секунды(timestamp) есть только одна запись
* при повторной загрузке строк из CSV файла в БД не должно появиться дубликатов
* скрипт должен потреблять не более 32MB оперативной памяти
* время выполнения скрипта не более ~ 90 секунд
* в пользовательских классах использовать директиву declare(strict_types=1)
* в файле README.md написать команды, чтобы поднять проект и загрузить данные в БД
* выложить код на github



### 1) Установка

Скачиваем, переходим и устанавливаем зависимости 

```sh
git clone https://github.com/DaYmiHn/console_csv
cd console_csv
composer install
```
### 2) Настройка
- В .env надо отредактировать конфигурацию для СУБД MySQL
``` 
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
```
- Создать БД
``` 
php bin/console doctrine:database:create
 ```
- И мигрировать сущность
```
php bin/console doctrine:migrations:migrate
 ```




### 3) Запуск
```sh
php bin/console app:import -f dataset.csv
```
![](https://i.imgur.com/4rFx5Bn.png)
