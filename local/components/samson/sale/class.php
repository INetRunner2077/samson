<?php

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Security\Random;
use Bitrix\Main\Type;
use Bitrix\Sale\Internals\DiscountCouponTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arResult */
class SamsonForm extends CBitrixComponent implements Controllerable

{

    /**
     * Возвращает фильтр для Action
     *
     * @return ActionFilter\Authentication
     */
    public function configureActions(): array
    {
        return [
            'send' => [
                'prefilters' => [
                    new ActionFilter\Authentication(),
                    // проверяет авторизован ли пользователь
                ],
            ],
        ];
    }

    /**
     * Action получения купона
     *
     * @return array
     */
    public static function getSalesAction()
    {
        define('TIME_NEW_SALE', 1);
        require('ormtable.php');
        Loader::includeModule("sale");
        $arData = Context::getCurrent()->getRequest()->getPostList();
        $userList = self::GetUserSale($arData['USER_ID']);
        if ($userList) {
            $userGiveTime = $userList['DATE_GIVE']->toString();
            $currentTime = date('d.m.Y H:i:s');
            $hours = self::dateCheck($currentTime, $userGiveTime);
            if (!(int)$hours >= TIME_NEW_SALE) {
                return [
                    'RESULT' => [
                        'COUPON' => $userList['COUPON'],
                        'SALE'   => $userList['SALE'],
                    ],
                ];
            } else {
                self::deleteUserSale($userList['ID']);

                return self::setUserSale($arData);
            }
        } else {
            return self::setUserSale($arData);
        }
    }

    /**
     * Action проверки купона
     *
     * @return array
     */
    public static function checkSaleAction()
    {
        define('TIME_ACTIVE_SALE', 3);
        require('ormtable.php');
        $arData = Context::getCurrent()->getRequest()->getPostList();
        $userList = self::GetUserSale($arData['USER_ID']);
        if ($userList) {
            $userGiveTime = $userList['DATE_GIVE']->toString();
            $currentTime = date('d.m.Y H:i:s');
            $hours = self::dateCheck($currentTime, $userGiveTime);
            if ((!(int)$hours >= TIME_ACTIVE_SALE)
                and ((string)$arData['COUPON'] == (string)$userList['COUPON'])
            ) {
                return [
                    'RESULT' => [
                        'COUPON' => $userList['COUPON'],
                        'SALE'   => $userList['SALE'],
                    ],
                ];
            }
        }

        return ['RESULT' => ["ERROR" => Loc::GetMessage('ERROR')]];
    }

    /**
     * Удаляет запись в ORM таблице по ключу
     *
     * @param $primeKe
     */
    public static function deleteUserSale($primeKey)
    {
        SamsonFormTable::delete($primeKey);
    }

    /**
     * Возвращает ранее полученные скидки пользователя
     *
     * @param $userId
     * @return array|bool
     *
     */
    public static function GetUserSale($userId)
    {
        $user = SamsonFormTable::getList(
            [
                'filter' => array('=USER_ID' => $userId),
            ]
        );

        if ($userList = $user->fetch()) {
            return $userList;
        } else {
            return false;
        }
    }

    /**
     * Записывает новую скидку для пользователя в ORM таблицу
     *
     * @param $arData
     * @return array
     */
    public static function setUserSale($arData)
    {
        $currentTime = date('d.m.Y H:i:s');
        $SALE = Random::getInt(1, 50);
        $COUPON = DiscountCouponTable::generateCoupon(true);

        SamsonFormTable::add(
            [
                'LOGIN'     => $arData['LOGIN'],
                'USER_ID'   => $arData['USER_ID'],
                'SALE'      => $SALE,
                'DATE_GIVE' => new Type\DateTime($currentTime, 'd.m.Y H:i:s'),
                'COUPON'    => $COUPON,
            ]
        );

        return [
            'RESULT' => [
                'SALE'   => $SALE,
                'COUPON' => $COUPON,
            ],
        ];
    }

    /**
     * Метод возвращает время между двумя датами в часах
     *
     * @param $date1
     * @param $date2
     * @return float
     */
    public static function dateCheck($date1, $date2)
    {
        $d1_ts = strtotime($date1);
        $d2_ts = strtotime($date2);

        $seconds = abs($d1_ts - $d2_ts);

        return floor($seconds / 3600);
    }

    /**
     * Метод создаёт ORM таблицу, которая необходима для работы компонента.
     *
     * Данный метод был сделан лишь для удобства. Подключение класса
     * при каждом вызове компонента не является оптимальным решением !!!
     *
     */
    public static function CreateOrm() {

        require('ormtable.php');
        if (!Application::getConnection()->isTableExists(
            Base::getInstance('SamsonFormTable')->getDBTableName()
        )) {
            Base::getInstance('SamsonFormTable')->createDBTable();
        }

    }

    public function executeComponent()
    {
        self::CreateOrm();
        $this->includeComponentTemplate();
    }
}