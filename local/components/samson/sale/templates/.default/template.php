<?php

use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
CJSCore::Init(array("jquery"));

/** @var CBitrixComponent $this */
/** @var array $arResult */
$currentId = CurrentUser::get()->getId();
if ($currentId): ?>

    <form class="give__sales">
        <div class="give__button">
            <input type="hidden" name="USER_ID"
                   value="<?= CurrentUser::get()->getId() ?>">
            <input type="hidden" name="LOGIN"
                   value="<?= CurrentUser::get()->getLogin() ?>">
            <button class="button"> <?=Loc::GetMessage('GIVE_SALE') ?></button>
        </div>
    </form>


    <form class="check__sales">
        <div class="check__coupon">
            <input type="text" name="COUPON" placeholder="<?=Loc::GetMessage('ENTER_COUPON') ?>" required>
            <button class="button"> <?=Loc::GetMessage('CHECK_SALE') ?></button>
        </div>
    </form>
<?php
else: ?>
    <label> <?=Loc::GetMessage('LOGIN') ?> </label>
<?php
endif; ?>