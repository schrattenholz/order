<?php


namespace Schrattenholz\Order;

use PageController;
use SilverStripe\View\Requirements;

class ProductController extends PageController{
	
    protected function init()
    {
        parent::init();
		//Requirements::javascript('public/resources/vendor/schrattenholz/order/template/javascript/order.js');
	}

}