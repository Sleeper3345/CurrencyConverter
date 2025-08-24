<h1>Конвертер валют</h1>

<b>Запуск проекта локально:</b>
1. Делаем build: docker-compose build
2. Поднимаем контейнеры: docker-compose up -d
3. Устанавливаем зависимости: docker-compose exec app composer install
4. Копируем все local файлы из папки environments/dev/common/config в common/config (main-local.php, test-local.php и т.д.)
5. Копируем все файлы из папки environments/dev/backend/web в backend/web, а также local файлы из папки environments/dev/backend/config в backend/config
6. Настраиваем common/config/main-local.php
7. Настраиваем common/config/serv.env. Примеры берем из environments/dev/common/config/serv.env
8. Выполняем миграции: docker-compose exec app php yii migrate

<b>Импорт курсов валют локально:</b>
1. Если нужно, редактируем список валют, закрепленный в common\enums\CurrencyEnum. По умолчанию там хранятся все актуальные валюты сервиса https://app.freecurrencyapi.com/. ВНИМАНИЕ!!! Если добавить в список хотя бы одну валюту, не поддержимваемую сервисом, то импорт не произойдет. К сожалению, сервис возвращает 422 ответ в такой ситуации, игнорируя допустимые валюты, которые были переданы ему.
2. Запускаем создание джоб для импорта курсов валют: docker-compose exec app php yii currency/upload-rates
3. Запускаем выполнение джоб в очереди. Для параллельной обработки джоб можно запустить несколько процессов docker-compose exec app php yii queue/listen

<b>Конвертация валюты локально:</b>
<br />
Запускаем docker-compose exec app php yii currency-rate/convert $1 $2 $3, где $1, $2, $3 - это исходная валюта, целевая валюта и количество конвертируемой валюты соответственно.
<br />
Пример. Конвертируем 100 рублей в доллары: docker-compose exec app php yii currency-rate/convert RUB USD 100
<br />
Ответ: По состоянию на 2025-08-24 06:37:04 100 RUB = 1.2398 USD

<b>Страница с курсами валют:</b>
<br />
Страница доступна авторизованным пользователям по адресу /currency-rate/index. Например: http://localhost:8080/currency-rate/index