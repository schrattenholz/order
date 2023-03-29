<?php


namespace Schrattenholz\Order;

use Silverstripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class Attribute extends DataObject{
	private static $table_name='Attribute';
	private static $db=[
		'Title'=>'Varchar(255)',
		'Shortcode'=>'Varchar(10)',
		'FrontendMessage'=>'Text'
	];
	private static $belongs_many_many=[
		'Preise'=>Preis::class,
		'ProductLists'=>ProductList::class
	];
	private static $singular_name="Produktattribute";
	private static $plural_name="Produktattribute";
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
}
?>