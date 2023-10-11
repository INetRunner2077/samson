<?
use \Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Type;
use Bitrix\Main\Context;
use \Bitrix\Main\Security\Random;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница скидок");
CJSCore::Init(array("jquery"));?>

<?$APPLICATION->IncludeComponent(
	"samson:sale",
	"",
Array()
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>