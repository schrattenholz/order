<?php

namespace Schrattenholz\Order;

use SilverStripe\ORM\DataObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\Security\Permission;
class OrderConfig extends DataObject
{
	private static $db = array (
		'Title'=>'Varchar(255)',
		'InfoEmail'=>'Varchar(255)',
		'OrderEmail'=>'Varchar(255)',
		'ConfirmationMailBeforeContent'=>'HTMLText',
		'ConfirmationMailAfterContent'=>'HTMLText',
		'EmailSignature'=>'HTMLText',
		'ProductFooter'=>'HTMLText',
		'OpeningDaysText'=>'HTMLText',
		'ShopIsActive'=>'Boolean(1)'
	);
	private static $table_name="shopconfig";
	private static $has_one=array(
		"Basket"=>Basket::class,
		"CheckoutAddress"=>CheckoutAddress::class,
		"CheckoutSummary"=>CheckoutSummary::class,
		"CheckoutFinal"=>CheckoutFinal::class,
		"ProductRoot"=>ProductList::class,
		"AGB"=>Infotext::class,
		"PrivacyPoliciy"=>Infotext::class,
		"Imprint"=>Infotext::class,
		"DeliveryInformation"=>Infotext::class,
		"Withdrawal"=>Infotext::class,
		"ProductImage"=>Image::class,
		"Logo"=>Image::class,
		"DirectDebitAuthForm"=>File::class
	);
	private static $has_many=[
		"OpeningDays"=>OpeningDay::class
	];

	private static $singular_name="Shop Konfiguration";
	private static $plural_name="Shop Konfiguration";
	public function getTitle(){
		return "ShopConfig";
		
	}
	private static $owns=[
		"ProductImage",
		"Logo",
		"DirectDebitAuthForm"
	];

}
	public function canView($member = null) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canEdit($member = null) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canDelete($member = null) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }

    public function canCreate($member = null, $context = []) 
    {
        return Permission::check('CMS_ACCESS_CMSMain', 'any', $member);
    }
?>