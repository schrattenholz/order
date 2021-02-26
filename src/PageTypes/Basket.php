<?php
namespace Schrattenholz\Order;

use Page;
use PageController;
class Basket extends Page
{
}
class BasketController extends PageController
{
	protected function init()
    {
        parent::init();
		//Requirements::javascript('public/resources/vendor/schrattenholz/order/template/javascript/order.js');
	}
}