<?php

namespace Schrattenholz\Order;

use Silverstripe\Assets\Image;
use Silverstripe\ORM\DataObject;

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