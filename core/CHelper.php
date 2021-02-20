<?php

namespace MyRestApi;

/**
 * Доп. функционал
 * Class CHelper
 * @package Webprofy
 */
class CHelper
{
    /** @var string Токен доступа */
    const ACCESS_TOKEN = "6dQkt3gubyKrtjh665EhKp3qrYHycjgK";
    /** @var string Имя хедер параметра дял получения токена */
    const API_KEY_PARAM_NAME = "ApiToken";

    /** @var array Описание методов */
    public static $arMethods = [
        // Список пользователей
        "users.list" => [ // Наименования метода
            "class" => 'MyRestApi\Users::getList',  // Метод для обработки
            "fields" => [   // Массив обязательных параметров
                "filter" => [
                    "required" => true,
                ],
                "select" => [
                    "required" => true,
                ]
            ]
        ],
        // Получения пользователя по id
        "users.getById" => [ // Наименования метода
            "class" => 'MyRestApi\Users::getById',  // Метод для обработки
            "fields" => [   // Массив обязательных параметров
                "id" => [
                    "required" => true,
                ]
            ]
        ],
    ];

    /**
     * Поиск и запуск метода
     * @return mixed
     * @throws ErrorInputException
     */
    public static function findMethod ()
    {
        $request = self::getJsonRequest();
        $method = $request['method'];
        $data = $request['data'];

        if (empty($method)) {
            throw new ErrorInputException('Не передан имя метода');
        }
        $arMethod = self::$arMethods[$method];
        if (empty($arMethod)) {
            throw new ErrorInputException('Отправлен несуществующий метод');
        }

        if (!empty($arMethod['fields'])) {
            foreach ($arMethod['fields'] as $fieldName => $field) {
                if ($field['required'] && empty($data[$fieldName])) {
                    throw new ErrorInputException('Поле ' . $fieldName . ' не передан.');
                }
            }
        }

        return call_user_func($arMethod['class'], $data);
    }

    /**
     * Получения json запроса
     * @return array|mixed
     * @throws ErrorInputException
     */
    public static function getJsonRequest ()
    {
        $data = [];
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if (empty($data)) {
            throw new ErrorInputException('Нужно сделать json запрос с методом POST');
        }
        return $data;
    }

    /**
     * Проверка токена
     * @return bool
     */
    public static function checkToken ()
    {
        $requestToken = self::getRequestToken();
        if ($requestToken == self::ACCESS_TOKEN) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получаем токен из запроса
     * @return bool|mixed
     */
    public static function getRequestToken ()
    {
        if (!empty($_SERVER['HTTP_' . self::API_KEY_PARAM_NAME])) {
            return $_SERVER['HTTP_' . self::API_KEY_PARAM_NAME];
        } else {
            return self::getHeaderParam(self::API_KEY_PARAM_NAME);
        }
    }

    /**
     * Получение хедер параметра из запроса
     * @param string $paramName
     * @return bool|mixed
     */
    public static function getHeaderParam ($paramName)
    {
        $headers = \apache_request_headers();
        if ($headers[$paramName]) {
            return $headers[$paramName];
        } else {
            return false;
        }
    }

    /**
     * Получение адреса сайта "https://mamadeti.ru"
     * @return string
     */
    public static function getSiteAddress ()
    {
        $address = $_SERVER['HTTP_HTTPS'] == "on" ? "https://" : "http://";
        $address .= $_SERVER['SERVER_NAME'];
        return $address;
    }
}

class ErrorInputException extends \Exception {}
class ErrorTokenException extends \Exception {}