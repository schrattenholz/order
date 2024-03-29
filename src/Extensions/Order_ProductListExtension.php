<?php

namespace Schrattenholz\Order;

use Silverstripe\ORM\DataExtension;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridField_ActionMenu;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SwiftDevLabs\DuplicateDataObject\Forms\GridField\GridFieldDuplicateAction;
use Silverstripe\ORM\ArrayList;
use SilverStripe\Forms\FieldList;
use SilverStripe\Security\Security;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use Silverstripe\Security\Group;
use SilverStripe\ORM\ValidationException;
use SilverStripe\ORM\Queries\SQLUpdate;
use SilverStripe\Forms\ListboxField;
use UncleCheese\DisplayLogic;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use Schrattenholz\Delivery\DeliverySetup;
use SilverStripe\Forms\DropdownField;
class Order_ProductListExtension extends DataExtension{
	private static $db=[
		'ResetPreSale' => 'Boolean',
		'InPreSale' => 'Boolean',
		'PreSaleInventory'=>'Int',
		'PreSaleStart'=>'Date',
		'PreSaleEnd'=>'Date',
		'PreSaleEndPercentage'=>'Enum("25,50,75,100","100")'
	];
	private static $many_many=[
		'Attributes'=>Attribute::class
	];
	private static $allowed_actions = array (
	);

	

	
	// Extension for Product::getCMSFields
	public function addExtension(FieldList $fields){
		if($this->owner->ID==OrderConfig::get()->First()->ProductRootID or $this->owner->Design=="Abverkaufliste"){
			$fields->addFieldToTab('Root.Produkte',new CheckboxField('InPreSale','Vorverkauf'));
			$fields->addFieldToTab('Root.Produkte',new CheckboxField('ResetPreSale','Vorverkauf beenden'));
			$fields->addFieldToTab('Root.Produkte',new DateField('PreSaleStart','Start des Vorverkauf'));
			$preSaleEnd=DateField::create('PreSaleEnd','Ende des Vorverkauf');
			$fields->addFieldToTab('Root.Produkte',$preSaleEnd);
			
			$attributesMap=Attribute::get()->map("ID", "Title", "Bitte ausw�hlen");
			$attributes=ListboxField::create('Attributes','Attribute',$attributesMap);
			$fields->addFieldToTab('Root.Produkte', $attributes,'Content');
					//Kilopreise pro Kundengruppe
			$gridFieldConfig=GridFieldConfig::create()
				->addComponent(new GridFieldButtonRow('before'))
				->addComponent($dataColumns=new GridFieldDataColumns())
				->addComponent($editableColumns=new GridFieldEditableColumns())
				->addComponent(new GridFieldSortableHeader())
				->addComponent(new GridFieldFilterHeader())

				->addComponent(new GridFieldOrderableRows('ProductID'))
				->addComponent(new GridFieldDuplicateAction())
				->addComponent(new GridFieldEditButton())
				->addComponent(new GridFieldDeleteAction())
				->addComponent(new GridFieldDetailForm())
				->addComponent(new GridField_ActionMenu())

				
			;

			//$fields->addFieldToTab('Root.Produkte',LiteralField::create("test","".);

			$attributesMap=Attribute::get()->map("ID", "Title", "Bitte ausw�hlen");
			$editableColumns->setDisplayFields(array(
				'AttributesIntern'  =>array(
						'title'=>'Interne Attribute',
						'callback'=>function($record, $column, $grid) use($attributesMap){
							return  ListboxField::create($column,'Attribute',$attributesMap);
					}),
				'Amount'  =>array(
						'title'=>'Menge',
						'callback'=>function($record, $column, $grid) {
							return NumericField::create($column)->setScale(2);
					}),
				'Inventory'  =>array(
						'title'=>utf8_encode('St�ckzahl'),
						'callback'=>function($record, $column, $grid) {
							return NumericField::create($column)->setScale(0);
					}),
				'SoldRatioInventory'  =>array(
						'title' => 'Verkauft',
						'field'=>ReadonlyField::class
					),
					'NotInPresale'  =>array(
						'title'=>utf8_encode('Vom Vorverkauf ausschlie�en'),
						'callback'=>function($record, $column, $grid) {
							$record->NotInPresale=0;
							return CheckboxField::create($column);
					})
			));
			//$fields->addFieldToTab("Root.Produkte",CheckboxField::create("ShowProducts","Produktliste anzeigen"));
			
			if($this->owner->Attributes()->Count()>0){
				//Injector::inst()->get(LoggerInterface::class)->error('gefilterte Produkte anzeigen');
				$attributes=[];
				foreach($this->owner->Attributes() as $attr){
					array_push($attributes,$attr->ID);
				}

				$data=Preis::get()->filter('AttributesIntern.ID',$attributes)->sort('Product.ID');
				//filter(['Attributes'=>$attributes])->sort('ProductID');
			}else{
				$data=Preis::get()->sort('Product.ID');
			}
			if($this->owner->ID!=OrderConfig::get()->First()->ProductRootID){
				
				$data=$data->filter("Product.ParentID",$this->owner->ID);
			}
			$products=GridField::create('Preise','Produktvarianten',$data,$gridFieldConfig);
			$fields->addFieldToTab('Root.Produkte',$products);

			//Produkte, erweitert um Quantity


			$dataColumns = $gridFieldConfig->getComponentByType(GridFieldDataColumns::class);
			
			$PreSaleEndPercentage=DropdownField::create( 'PreSaleEndPercentage', 'Verkaufende nach Verkauf von (in Prozent)', singleton('Schrattenholz\Order\ProductList')->dbObject('PreSaleEndPercentage')->enumValues() ); 
			$fields->addFieldToTab('Root.Produkte',$PreSaleEndPercentage,"PreSaleEnd");
			//Wenn PreSales werden die Verkaufszahlen angezeigt
			if($this->getOwner()->InPreSale){
				$dataColumns->setDisplayFields([
					'Product.Title' => 'Produktname',
					'Title' => 'Variante',

					'SoldQuantity'=>'Verkauft'
				]);
			}else{
				$dataColumns->setDisplayFields([
					'Product.Title' => 'Produktname',
					'Title' => 'Variante',

				]);
			}
		}
	}
	public function onBeforeWrite(){
		if($this->owner->getField("ResetPreSale")==true){
			$this->owner->setField("InPreSale",false);
			$this->owner->setField("PreSaleStart",null);
			$this->owner->setField("PreSaleEnd",null);
			//Injector::inst()->get(LoggerInterface::class)->error('productlist preale auf null setzten=');
		}
		parent::onBeforeWrite();
	}
	public function onAfterWrite(){
		
		if($this->owner->ID==OrderConfig::get()->First()->ProductRootID  or $this->owner->Design=="Abverkaufliste"){
		//Ausgew�hlte Produktvarianten holen
		//Injector::inst()->get(LoggerInterface::class)->error('onAfterWrite ProductList id');
		if($this->owner->Attributes()->Count()>0){
			$attributes=[];
			foreach($this->owner->Attributes() as $attr){
				array_push($attributes,$attr->ID);
			}
			
			$data=Preis::get()->filter('AttributesIntern.ID',$attributes)->sort('Product.ID');
		}else{
			$data=Preis::get();
		}
		//Varianten in Vorverkauf setzen
		if($this->owner->ID!=OrderConfig::get()->First()->ProductRootID){
				
				$data=$data->filter("Product.ParentID",$this->owner->ID);
			}
		if($this->owner->InPreSale){
			foreach($data as $product){
				Injector::inst()->get(LoggerInterface::class)->error('in PreSale setzen id'.$product->ID);
				
					$product->InPreSale=true;
					$product->PreSaleStart=$this->owner->PreSaleStart;
					$product->PreSaleEnd=$this->owner->PreSaleEnd;
					if($product->Inventory==0 && $product->NotInPresale==false){
						//Voreingestellten Bestand �bernehmen
						$product->Inventory=$product->PreSaleInventory;
						$product->PreSaleStartInventory=$product->PreSaleInventory;
					}else if($product->NotInPresale==true){
						$product->Inventory=0;
					}else if($product->Inventory!=0 && $product->NotInPresale==false){
						$product->PreSaleStartInventory=$product->Inventory;
					}
					$this->owner->extend('HOOK_Order_ProductListExtension_AfterWrite_Product', $product);
					$product->write(); // saves the record
				
			}
		}else if($this->owner->ResetPreSale){
			foreach($data as $product){
				$product->InPreSale=false;
				$product->PreSaleStart=null;
				$product->PreSaleEnd=null;
				//$product->Inventory=0;
				$this->owner->extend('HOOK_Order_ProductListExtension_AfterWrite_Product', $product);
				$product->write(); // saves the record
			}
		}else{
			foreach($data as $product){
						$product->PreSaleStartInventory=$product->Inventory;
					
					$this->owner->extend('HOOK_Order_ProductListExtension_AfterWrite_Product', $product);
					$product->write(); // saves the record
		}	
		}
		$this->owner->extend('HOOK_Order_ProductListExtension_AfterWrite', $this->owner);
		parent::onAfterWrite();
		$update = SQLUpdate::create('ProductList')->addWhere(['ID' => $this->owner->ID]);
		$update->addAssignments(['InPreSale'=> false,'ResetPreSale'=> false]);
		$update->execute();
		
	}
	
	
}

}
