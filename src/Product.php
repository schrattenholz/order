<?php

namespace Schrattenholz\Order;
 
use Page;
use PageController;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\NumericField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\DropdownField;
use Silverstripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\ListboxField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\LiteralField;

use Bummzack\SortableFile\Forms\SortableUploadField;

use SilverStripe\ORM\Queries\SQLUpdate;
class Product extends Page
{
	private static $table_name="Product";
 	private static $singular_name="Produkt";
	private static $plural_name="Produkte";
	private static $db=array(
		'AdditionalTitle'=>'Text',
		'SortOrder'=>'Int',
		'Inventory'=>'Decimal',
		'InfiniteInventory'=>'Boolean',
		'Price'=>'Decimal(6,2)',
		'CaPrice'=>'Boolean',
		'Vacuum'=>'Boolean',
		'ShowPricingTable'=>'Boolean(0)',
		'GlobalProductSort'=>'Int',
		'Quantity'=>'Int',
		'ShowBasePrice'=>'Boolean(1)',
		'OutOfStock'=>'Boolean',
		'ShowQualityLabel'=>'Boolean(1)'
	);

	private static $has_many = [
		//Produktvarianten
		'Preise'=>Preis::class
	];
	private static $owns = [
		'ProductImages'
	];
	private static $many_many=[
		'Ingredients'=>Ingredient::class,
		'Addons'=>Addon::class,
		'ProductImages'=>ProductImage::class
	];
    private static $many_many_extraFields = [
        'ProductImages' => ['SortOrder' => 'Int']
    ];

	private static $has_one=[
		'Unit'=>Unit::class
	];
	public function getNetto($vat){
		$netto=$this->getNetto($vat);
		return ($this->Price-$netto);
	}
	public function getIncludedVAT($vat){
		return round($this->Price/100*$vat,2);
	}
	public function getSummaryTitle(){
		$sumTitle=$this->Title;
		if($this->Addons()->Count()>0){
			$sumTitle.=", ".$this->Addons()->First()->Title;
		}
		if($this->Ingredients()->Count()>0){
			$sumTitle.=", ".$this->Ingredients()->First()->Title;
		}
		return $sumTitle;//." mit ".$this->Wheels." Rädern";
	}
	public function onBeforeWrite(){
		//Sortierunreiheienfolge wird gesetzt, wenn die übergeordnete Sortierung verändert wurde
		$newGlobalProductSort=($this->Parent()->GlobalProductSort*10000)+$this->Sort;
		if($this->GlobalProductSort!=$newGlobalProductSort){
			$this->GlobalProductSort=$newGlobalProductSort;
			$p_live= SQLUpdate::create('"Product_Live"')->addWhere(['ID' => $this->ID]);
			$p_live->assign('"GlobalProductSort"', $newGlobalProductSort);
			$p_live->execute();
			//$this->publish('Stage', 'Live');
		}
		//Der MenuTitle wird mit Zusatzeigenschaften angereichert um unterscheidbar zu bleiben
		$newMenuTitle=$this->getTitle();
		if($this->Addons()->count()>0){
			$newMenuTitle.=", ".$this->Addons()->First()->Title;
		}if($this->Ingredients()->count()>0){
			$newMenuTitle.=", ".$this->Ingredients()->First()->Title;
		}
		$this->setField('MenuTitle',$newMenuTitle);
		//Falls Hauptbild nicht gesetzt ist, wird das erste Produktbild als Hauptbild gesetzt
		/*if($this->ImageID==0){
			$this->MainImageID=$this->ProductImages()->First()->ID;
		}*/
		parent::onBeforeWrite();
	}
	/*public function getTitle(){
		//$tonnenart=	DataObject::get_one("Tonnenart","ID=".intval($this->TonnenartID));
		$tonnenart = Tonnenart::get()->filter(['ID' => $this->TonnenartID]);
		return $tonnenart->First()->Title;
	}*/
 	public function getCMSFields()
	{
		$fields=parent::getCMSFields();

		
		
		$bild=SortableUploadField::create(
                'ProductImages', $this->fieldLabel('Bilder')
            );
		$bild->setFolderName('Uploads/order/'.$this->URLSegment);
		
		$fields->addFieldToTab("Root.Main",new ListboxField("Addons", "Art der Bearbeitung",Addon::get()->map("ID", "Title", "Bitte auswählen")),"Content");
		
		$amount=new NumericField("Amount","Menge");
		$amount->setLocale("DE_De");
		$amount->setScale(2);
		
		$infiniteInventory=new CheckboxField("InfiniteInventory","Das Produkt hat einen unendlichen Bestand.");
		$showQualitiyLabel=new CheckboxField("ShowQualityLabel",utf8_encode("Qualitäts-Label anzeigen"));
		$outOfStock=new CheckboxField("OutOfStock","Das Produkt als ausverkauft anzeigen.");
		$inventory=new NumericField("Inventory","Vorhandene Anzahl");
		$inventory->setLocale("DE_De");
		$inventory->setScale(2);
		
		$price=new NumericField("Price","Kilopreis");
		$basePrice=new CheckboxField("ShowBasePrice","Grundpreis anzeigen");
		$caprice=new CheckboxField("CaPrice","Preise sind ein Mittelwert (Ca. wird vor dem Preis angezeigt)");
		$unit=new DropdownField("UnitID","Einheit",Unit::get()->map("ID", "Title", "Bitte auswählen"));

		$price->setLocale("DE_De");
		$price->setScale(2);
		
		$fields->addFieldToTab('Root.Main',new TextField("AdditionalTitle","Titel-Zusatz(z.B.: aus der Unterschale)"),"URLSegment");
		
		$fields->addFieldToTab("Root.Main",new ListboxField("Ingredients", "Zutaten",Ingredient::get()->map("ID", "Title", "Bitte auswählen")),"Content");
		//$fields->addFieldToTab("Root.Shop", $vac,"Content");
		$fields->addFieldToTab("Root.Main", new TextField('GlobalProductSort'),"Content");
		$fields->addFieldToTab("Root.Main", $showQualitiyLabel,"HeaderImage");
		$fields->addFieldToTab("Root.Shop", $caprice,"Content");
		$fields->addFieldToTab("Root.Shop",$unit,"Content");
		$fields->addFieldToTab("Root.Shop", $price,"Content");
		$fields->addFieldToTab("Root.Shop", $amount,"Content");
		$fields->addFieldToTab("Root.Shop", $basePrice,"Content");
		$fields->addFieldToTab("Root.Shop", $outOfStock,"Content");
		$fields->addFieldToTab("Root.Shop", $infiniteInventory,"Content");
		$fields->addFieldToTab("Root.Shop", $inventory,"Content");
		
		$fields->addFieldToTab("Root.Staffelpreise",new CheckboxField("ShowPricingTable","Staffeltabelle anzeigen"),"Content");
		$fields->addFieldToTab('Root.Staffelpreise', GridField::create(
            'Preise',
            'Staffelelemente',
            $this->Preise()->sort('SortOrder'),
            $gridfield=GridFieldConfig_RecordEditor::create()
        ),"Content");
		$gridfield->addComponent(new GridFieldOrderableRows('SortOrder'));
		$fields->removeFieldFromTab("Root.Main","HasRevoSlider");
		$fields->removeFieldFromTab("Root.Main","HasQuadCarousel");
		$fields->removeByName("Weitere Inhalte");
		$fields->addFieldToTab("Root.Bilder",$bild,"Metadata");

		//HOOK-Punkt
		$this->extend('addExtension', $fields);
		return $fields;
	}	
	public function VacReadable($data){
		if($data=='off'){
			return "nein";
		}else{
			return "ja";
		}
	}
}
