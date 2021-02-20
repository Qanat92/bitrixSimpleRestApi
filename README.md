# bitrixSimpleRestApi
Простой рест апи для битрикса, для запуска:
1. Разместите код в папку /api/
2. Укажите свой уникальный токен в константе core/CHelper.php ACCESS_TOKEN
3. Укажите список метод в массиве core/CHelper.php $arMethods
4. Создайте необходимые классы и методы в папке /core/classes, **все файлы классов подключаются автоматически**

## Важно знать:
- Все запросы должны прийти в формате json методом POST

## Пример запроса 
```
POST /api/ HTTP/1.1
Host: bitrix.loc
ApiToken: 6dQkt3gubyKrtjh665EhKp3qrYHycjgK
Content-Type: application/json
Cookie: PHPSESSID=WbdUO8oszQM25sKVBXmy0BfwJtsEs9UE

{
    "method" : "users.list",
    "data" : {
        "filter" : {
            "ACTIVE" : "Y"
        },
        "select" : [
            "ID",
            "NAME",
            "LOGIN"
        ]
    }
}
```

## Пример ответа
```
{
    "success": true,
    "data": [
        {
            "ID": "1",
            "NAME": "Qanat",
            "LOGIN": "admin"
        }
    ],
    "error": null
}
```

