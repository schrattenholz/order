<?php

namespace Schrattenholz\Order;

use SilverStripe\ORM\DataObject;
use Silverstripe\Forms\TextField;
use Silverstripe\Forms\LiteralField;
use Silverstripe\Forms\NumericField;
use Silverstripe\Forms\CheckboxField;
use Silverstripe\Forms\DropdownField;
use Silverstripe\Forms\HiddenField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\ListboxField;
use Silverstripe\ORM\ArrayList;
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use SilverStripe\Security\Permission;

class Preis extends DataObject
{
	private static $default_sort=['SortOrder'];
	private static $table_name="Preis";
 	private static $singular_name="Produktvariante";
	private static $plural_name="Produktvarianten";
	private static $db = array (
		'Price'=>'Decimal(6,2)',
		'CaPrice'=>'Boolean',
		'Content'=>'Varchar(255)',
		'Currency'=>"Enum('EUR', 'EUR')",
		'Amount'=>'Int',
		'SortOrder'=>'Int',
		'Vacuum'=>'Boolean',
		'ShowAmount'=>'Boolean',
		'ShowContent'=>'Boolean',
		'Unit'=>'Enum("weight,piece","weight")',
		'NotInPresale'=>'Boolean'
	);
	private static $has_one = [
		'Product'=>Product::Class
	];
	private static $many_many=[
		'Attributes'=>Attribute::class,
		'AttributesIntern'=>Attribute::class
	];
	private static $summary_fields = [
	'Content'=>'Content',
			'Product.Title'=>'Produkt',
			'Title'=>'Title',
			'DisplayAmount' => 'Menge',
			'Price'=>'Preis',
			'AttributeString'=>'Attribute'
    ];
	public function getAttributeString(){
		$attributes=array();
		foreach($this->Attributes() as $a){
			array_push($attributes,$a->Title);
		}
		return implode(", ",$attributes);
	}
	public function getProductTitle(){
		return $this->Product()->Title;
	}
	public function getFullTitle($showBasePrice=true){
		$data=new ArrayList(["Title"=>"","ShowBasePrice"=>$showBasePrice]);
		$title=$data->Title;
		if($this->Content){
			$title.=$this->Content;
		}

		foreach($this->Attributes() as $a){
		if($title!=""){
					$title.=", ";
				}
			$title.=$a->Title;
		}
		if(!$this->Content && $this->ShowAmount || $this->Product()->ShowBasePrice){
				if($title!=""){
					$title.=", ";
				}
			if($this->CaPrice){

				$title.="ca. ";
			}
			$title.=$this->getDisplayAmount();
		}
		if($this->Product()->ShowBasePrice && $showBasePrice){
			if($title!=""){
				$title.=", ";
			}
			$title.=$this->formattedNumber($this->Product()->KiloPrice()->Price) ."€/".$this->Product()->Unit->Shortcode;
		}
		$this->extend('updateFullTitle', $data);

		return $data->Title;
	}
	public function getTitle(){
		if($this->Content){
			return $this->getField('Content');
		}else{
			return $this->getField('Amount');
		}
	}
	public function getSummaryTitle(){
		if($this->Content){
			return $this->getField('Content');
		}else{
			return $this->getField('Amount');
		}
		
	}
	public function formattedNumber($val){
			return number_format(floatval($val), 2, ',', '.');
	}
	public function formattedWeight($val){
		if($val>=1000){
			return str_replace(".",",",round($val/1000,2)."kg");
		}else{
			return str_replace(".",",",$val."g");
		}
	}
 	public function getCMSFields()
	{
		$fields=FieldList::create(TabSet::create('Root'));
		$num=new NumericField("Price","Preis");
		$num->setLocale("DE_De");
		$num->setScale(2);
		$vac=new CheckboxField("Vacuum","Vakuumierbar");
		$caprice=new CheckboxField("CaPrice","Preise sind ein Mittelwert (Ca. wird vor dem Preis angezeigt)");
		$unit=new DropdownField("Unit","Einheit",singleton('Schrattenholz\Order\Preis')->dbObject('Unit')->enumValues());
		$amount=TextField::create('Amount','Menge (Gewicht in Gramm)');
		$attributes=new ListboxField("Attributes", "Produktattribute",Attribute::get()->map("ID", "Title", "Bitte auswählen"));
		$attributesIntern=new ListboxField("AttributesIntern", "Produktattribute (intern)",Attribute::get()->map("ID", "Title", "Bitte auswählen"));
		$fields->addFieldsToTab('Root.Main', [
			LiteralField::create("ProduktTitel","<h2>".$this->getProductTitle()."</h2>"),
			TextField::create('Content','Freitext (z.B: 1/4 Rad)'),
			$attributes,
			$attributesIntern,
            $num,
			$caprice,
			HiddenField::create('Curreny','EUR'),
			CheckboxField::create('ShowAmount','Menge anzeigen'),
			$unit,
			$amount,
			//DropdownField::create("UnitID","Einheit, falls abweichend eintragen.",Unit::get()->map("ID", "Title ", "Bitte auswählen"))
        ]);
		$this->extend('updateCMSFields', $fields);
		return $fields;
	}	
	/*
	Gibt die angegeben Menge inkl. der Einheit wieder. Gewicht wird in Gramm eingegeben und ab 1000gr in Kilo umgerechnet
	*/
	public function getDisplayAmount(){
		if($this->Unit=="weight"){
			if($this->Amount/1000>1){
				// Anzeige in Kilo
				return round($this->Amount/1000,2)." kg";
			}else{
				// Anzeige in Gramm
				return $this->Amount." gr";
			}
		}else{
			// Anzeige als Stueck
			if($this->Amount > 1 ){
				return $this->Amount. " Stk.";
			}else{
				return false;
			}
		}
	}
	public function getAmountUnit(){
		if($this->UnitID!=0){
			return $this->Unit()->Shortcode;
		}else{
			return $this->Product()->Unit()->Shortcode;
		}
	}
	public function getIncludedVAT(){
		$netto=$this->getNetto();
		return ($this->Price-$netto);
	}
	public function getNetto($vat){
		return round($this->Price/100*$vat,2);
	}

}
?>
