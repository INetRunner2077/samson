<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true):
    die();
endif;
use Bitrix\Main\Localization\Loc;
$arComponentDescription = array(
    "NAME" => Loc::getMessage("SAMSON_COMPONENT"),
    "DESCRIPTION" => Loc::getMessage("SAMSON_COMPONENT_DESCRIPTION"),
    "PATH" => array(
        "ID" => "samson",
        "NAME" => Loc::getMessage("SAMSON")
    ),
);
