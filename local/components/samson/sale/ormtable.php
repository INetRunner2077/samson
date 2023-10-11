<?php
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\ORM\Fields;

class SamsonFormTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName()
    {
        return 'samson_table';
    }

    /**
     * @return string
     */
    public static function getUfId()
    {
        return 'SAMSON_TABLE';
    }

    /**
     * @return array
     */
    public static function getMap()
    {
        return [
            /** ID значения */
            new Fields\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            /** Логин */
            new Fields\StringField('LOGIN', [
                'required' => true,
            ]),
            /** ID пользователя */
            new Fields\IntegerField('USER_ID', [
                'required' => true,
            ]),
            /** Значение скидки */
            new Fields\StringField('SALE', [
                'required' => true,
            ]),
            /** Купон */
            new Fields\StringField('COUPON', [
                'required' => true,
            ]),
            /** Дата и время получения скидки */
            new Fields\DatetimeField('DATE_GIVE', [
                'required' => true,
            ]),
        ];
    }

}
?>