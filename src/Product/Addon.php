<?php


namespace Schrattenholz\Order;

use Silverstripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class Addon extends DataObject{
	private static $table_name='addon';
	private static $db=[
		'Title'=>'Varchar(255)',
		'Shortcode'=>'Varchar(10)',
	];
	private static $belongs_many=[
		'Products'=>Product::class
	];
	private static $singular_name="Produkteigenschaft";
	private static $plural_name="Produkteigenschaften";
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