<?php
namespace MyRestApi;

use Bitrix\Main\UserTable;

class Users {
    /**
     * Список пользователей
     * @param $arRequestFields
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getList ($arRequestFields)
    {
        // TODO если нужно выводить какую ту ошибку
        /*if (/* что-то *) {
            throw new ErrorInputException('Какая та ошикба');
        }*/

        $arResult = [];
        $rsUser = UserTable::getList([
            "filter" => $arRequestFields['filter'],
            "select" => $arRequestFields['select']
        ]);
        while ($arUser = $rsUser->fetch()) {
            $arResult[] = $arUser;
        }
        return $arResult;
    }

    /**
     * Пользователь по ид
     * @param $arRequestFields
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getById ($arRequestFields)
    {
        $arResult = [];
        $rsUser = UserTable::getById($arRequestFields['id']);
        if ($arUser = $rsUser->fetch()) {
            $arResult = $arUser;
        }
        return $arResult;
    }
}
?>