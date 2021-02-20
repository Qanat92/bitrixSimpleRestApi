<?php
use MyRestApi\CHelper;
use MyRestApi\ErrorInputException;
use MyRestApi\ErrorTokenException;
use Bitrix\Main\Loader;

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('BX_NO_ACCELERATOR_RESET', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

require_once 'core/autoload.php';

$data = [
    "success" => false,
    "data" => null,
    "error" => null
];

try {
    // Подключаем инфоблоки
    if (!Loader::includeModule('iblock')) {
        throw new Exception('Модуль инфоблок не подключен!');
    }
    // Подключаем highload блоки
    if (!Loader::includeModule('highloadblock')) {
        throw new Exception('Модуль highload блоков не подключен!');
    }
    // Проверка токена
    if (!CHelper::checkToken()) {
        throw new ErrorTokenException("Ошибка вы не передали токен в хедере \"" . CHelper::API_KEY_PARAM_NAME . "\" или токен не правильный!");
    }

    // Поиск и запуск метода
    $result = CHelper::findMethod();

    // Успешный ответ
    $data["success"] = true;
    $data['data'] = $result;

} catch (ErrorTokenException $e) { // Ошибки связанной с токеном
    header("HTTP/1.1 401 Unauthorized");
    $data = [
        "data" => null,
        "success" => false,
        "error" => $e->getMessage(),
    ];
} catch (ErrorInputException $e) { // Ошибки связанной с параметрами
    header("HTTP/1.1 400 Bad Request");
    $data = [
        "data" => null,
        "success" => false,
        "error" => $e->getMessage(),
    ];
} catch (Exception $e) { // Ошибки связанной с кодом
    header("HTTP/1.1 500 Internal Server Error");
    $data = [
        "data" => null,
        "success" => false,
        "error" => $e->getMessage(),
    ];
}
header('Content-Type: application/json');
die(json_encode($data));