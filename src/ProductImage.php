<?php

namespace Schrattenholz\Order;

use SilverStripe\Assets\Image;
use SilverStripe\ORM\DataObject;

class ProductImage extends Image{
	private static $table_name='ProductImage';
	private static $db=[
		'Title'=>'Varchar(255)',
		'Description'=>'Text'
	];
	private static $belongs_many=[
		'Products'=>Product::class
	];
}