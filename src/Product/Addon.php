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

}
?>