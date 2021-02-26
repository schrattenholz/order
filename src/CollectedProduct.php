<?php

namespace Schrattenholz\Order;
 
 
use Page;

class CollectedProduct extends Page
{
	private static $table_name="CollectedProductProduct";
 	private static $singular_name="Sammelprodukt";
	private static $plural_name="Sammelprodukte";
	private static $allowed_children=[Product::class];
	public function getCMSFields(){
		$fields=parent::getCMSFields();
		$fields->removeByName('RedirectionType');
		$fields->removeByName('ExternalURL');
		return $fields;
	}
}