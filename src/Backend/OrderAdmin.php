<?php

namespace Schrattenholz\Order;
use Schrattenholz\OrderProfileFeature\BackEnd\GridField_ExportOrderButton;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use Silverstripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;

use SilverStripe\Forms\Form;
use Terraformers\RichFilterHeader\Form\GridField\RichFilterHeader;

class OrderAdmin extends ModelAdmin
{

    private static $menu_title = 'Shop';

    private static $url_segment = 'orders';

    private static $managed_models = [
		OrderConfig::class,
		Unit::class,
		Ingredient::class,
		Addon::class,
		Attribute::class
    ];
	 private static $field_labels = [
      'OrderConfig' => 'Shopkonfiguration',
	  'Unit'=>'Größeneinheiten',
	  'Ingredient'=>'Zutatenliste',
	  'Addon'=>'Produkteigenschaften',
	  'ProductOption'=>'Produktoptionen',
	  'Attribute'=>'Produktattribute'
   ];
    public function getExportFields() {
		 $model = singleton($this->modelClass);
        if ($model->hasMethod('getExportFields')) {
            return $model->getExportFields();
        }
        return singleton($this->modelClass)->summaryFields();
    }
      public function getEditForm($id = null, $fields = null): Form
    {
		
		$form = parent::getEditForm($id, $fields);
		 $gridField = $form->Fields()->fieldByName('Schrattenholz-OrderProfileFeature-OrderProfileFeature_ClientOrder');
		
			
			if($gridField) {
				$config = $gridField->getConfig();
				$config->addComponent(new GridFieldPaginator(10));
				$config->removeComponentsByType('SilverStripe\Forms\GridField\GridFieldExportButton');
				$config->removeComponentsByType('SilverStripe\Forms\GridField\GridFieldImportButton');
				$config->removeComponentsByType('SilverStripe\Forms\GridField\GridFieldPrintButton');
				$config->addComponent(new GridField_ExportOrderButton('buttons-before-left'));
			}
			return $form;
    }
}