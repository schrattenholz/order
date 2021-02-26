<?php


namespace Schrattenholz\Order;

use Silverstripe\ORM\DataObject;
use SilverStripe\Security\Permission;

class Ingredient extends DataObject{
	private static $table_name='ingredient';
	private static $db=[
		'Title'=>'Varchar(255)',
		'Shortcode'=>'Varchar(10)',
	];
	private static $belongs_many=[
		'Products'=>Product::class
	];
	private static $singular_name="Zutat";
	private static $plural_name="Zutaten";

}
?>