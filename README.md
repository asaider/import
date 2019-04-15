# Import CSV

***Развертывание проекта***
  +  Выполнить git clone https://github.com/asaider/itransition.git
  +  Выполнить ```composer install```
  +  Для проекта необходима пустая база "ImportTest",подключение к базе данных вы должны указать в файле .env
  +  Накатить миграции:
       ```php bin/console doctrine:migrations:migrate```
  +  Для запуска скрипта импорта данных из csv файла,необходимо выполнить команду:
       ```php bin/console Import```
  +  Чтобы запустить срипт в тестовом режиме:без записи в бд,необходимо выполнить команду:
       ```php bin/console Import test```
  +  Для проверки тестов выполните:   
       ```php bin/phpunit```
  Сsv файл,который импортируется должен быть расположен в /src/Data/stock.csv
