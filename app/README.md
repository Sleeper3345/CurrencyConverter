<h1>Конвертер валют</h1>

<b>Запуск проекта локально:</b>
1. Делаем build: docker-compose build
2. Поднимаем контейнеры: docker-compose up -d
3. Устанавливаем зависимости: docker-compose exec app composer install
4. Настраиваем common/config/main-local.php. Примеры берем из environments/dev/common/config/main-local.php
5. Настраиваем common/config/serv.env. Примеры берем из environments/dev/common/config/serv.env
6. Выполняем миграции: docker-compose exec app php yii migrate

<b>Импорт курсов валют локально:</b>
1. Если нужно, редактируем список валют, закрепленный в common\enums\CurrencyEnum. По умолчанию там хранятся все актуальные валюты сервиса https://app.freecurrencyapi.com/. ВНИМАНИЕ!!! Если добавить в список хотя бы одну валюту, не поддержимваемую сервисом, то импорт не произойдет. К сожалению, сервис возвращает 422 ответ в такой ситуации, игнорируя допустимые валюты, которые были переданы ему.
2. Запускаем создание джоб для импорта курсов валют: docker-compose exec app php yii currency/upload-rates
3. Запускаем выполнение джоб в очереди. Последовательно: docker-compose exec app php yii queue/run. Для параллельной обработки джоб можно запустить несколько процессов docker-compose exec app php yii queue/listen

<b>Конвертация валюты локально:</b>
<br />
Запускам docker-compose exec app php yii currency-rate/convert $1 $2 $3, где $1, $2, $3 - это конвертируемая валюта, получаемая валюта и количество конвертируемой валюты соответственно.
<br />
Пример. Конвертируем 100 рублей в доллары: docker-compose exec app php yii currency-rate/convert RUB USD 100
<br />
Ответ: По состоянию на 2025-08-24 06:37:04 100 RUB = 1.2398 USD