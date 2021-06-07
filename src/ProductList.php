<?php


namespace Schrattenholz\Order;

use Page;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Assets\Image;
use SilverStripe\AssetAdmin\Forms\UploadField;
use Schrattenholz\Blog\BlogList;
class ProductList extends Page{
	private static $db=
	[
		'GlobalProductSort'=>'Int'
	];
	private static $many_many=[
		'Attributes'=>Attribute::class
	];
	public function getCMSFields(){
		$fields=parent::getCMSFields();
		$fields->addFieldToTab("Root.Bilder",new UploadField("TeaserImage","Bild für die Anzeige in der übergeordneten Liste"));
		
		$fields->addFieldToTab("Root.Main",new TextField("GlobalProductSort","Sortierreihenfolge(Nur Ganzzahlen)"),'Content');
				//HOOK-Punkt
		$this->extend('addExtension', $fields);
		return $fields;
	}
	private static $has_one=['TeaserImage'=>ProductImage::class];
	private static $table_name="ProductList";
 	private static $singular_name="Produktliste";
	private static $plural_name="Produktlisten";
    private static $allowed_children = [
        Product::class,
        ProductList::class,
		CollectedProduct::class,
		BlogList::class
    ];
	private static $owns = [
		'TeaserImage'
	];
}