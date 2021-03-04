<?php

// Öffnungszeiten in OrderConfig
//Möglicherweise Basisklasse für Delivery/DeliveryDay und Delivery/CollectionDay

namespace Schrattenholz\Order;

use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use Silverstripe\Forms\TextField;
use Silverstripe\Forms\NumericField;
use Silverstripe\Forms\CheckboxField;
use Silverstripe\Forms\DropdownField;
use Silverstripe\Forms\HiddenField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldButtonRow;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldEditButton;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use Symbiote\GridFieldExtensions\GridFieldEditableColumns;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Schrattenholz\OrderProfileFeature\OrderCustomerGroup;
use Silverstripe\Forms\TimeField;
//Debugging
use SilverStripe\Core\Injector\Injector;
use Psr\Log\LoggerInterface;
use SilverStripe\Security\Permission;
use Schrattenholz\Order\Preis;
class OpeningDay extends DataObject
{
	private static $default_sort=['SortOrder'];
	private static $db = array (
		'Title'=>'Varchar(255)',
		'Day'=>'Enum("monday, tuesday, wednesday, thursday, friday, saturday, sunday","monday")',
		'SortOrder'=>'Int',
		'TimeFrom'=>'Time',
		'TimeTo'=>'Time'
		
	);
	private static $has_one=[
		'OrderConfig'=>OrderConfig::class,
	];
	private static $summary_fields = [
			'Day' => 'Liefertag'
    ];
 	private static $singular_name="Ladentag";
	private static $plural_name="Ladentage";
	private static $table_name="Shopday";
	public function DayTranslated(){
		return _t("Day.".$this->Day,$this->Day);
	}
	public function getTitle(){
		return $this->DayTranslated();
	}
 	public function getCMSFields()
	{
		$fields=FieldList::create(TabSet::create('Root'));
		$fields->addFieldsToTab('Root.Main', [
			DropdownField::create('Day', 'Tag',singleton('Schrattenholz\\Delivery\\DeliveryDay')->dbObject('Day')->enumValues()),
			TimeField::create('TimeFrom','von'),
			TimeField::create('TimeTo','bis')
        ]);
		return $fields;
	}
}

