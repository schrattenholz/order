<?php


namespace Schrattenholz\Order;

use Silverstripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class Unit extends DataObject{
	private static $table_name='unit';
	private static $db=[
		'Title'=>'Varchar(255)',
		'Shortcode'=>'Varchar(10)',
		'Plural'=>'Varchar(255)',
		'Factor'=>'Float',
		'Type'=>'Enum("volume,weight,piece","weight")'
	];
	private static $singular_name="Größeneinheit";
	private static $plural_name="Größeneinheiten";
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