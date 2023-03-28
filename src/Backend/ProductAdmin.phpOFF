<?php

namespace Schrattenholz\Order;

use App\Models\Player;
use App\Models\Team;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use Terraformers\RichFilterHeader\Form\GridField\RichFilterHeader;

use SilverStripe\ORM\DataList;

/**
 * Class PlayersAdmin
 *
 * @package App\Admin
 */
class ProductAdmin extends ModelAdmin
{
    /**
     * @var array
     */
    private static $managed_models = [
        Preis::class => ['title' => 'ProduktVarianten'],
    ];

    /**
     * @var string
     */
    private static $menu_title = 'Produkte';

    /**
     * @var string
     */
    private static $url_segment = 'products';

    /**
     * @param mixed|null $id
     * @param FieldList|null $fields
     * @return Form
     */
    public function getEditForm($id = null, $fields = null): Form
    {
        $form = parent::getEditForm($id, $fields);

        /** @var GridField $gridField */
        $gridField = $form->Fields()->fieldByName('Schrattenholz-Order-Preis');

        if ($gridField) {
            // Default sort order
            $config = $gridField->getConfig();

            // custom filters
            $config->removeComponentsByType(GridFieldFilterHeader::class);

            $filter = new RichFilterHeader();
            $filter
                ->setFilterConfig([
                    'Title',
                    'Product.Title' => [
                        'title' => 'ProductID',
                        'filter' => 'ExactMatchFilter',
                    ],
                ])
                ->setFilterFields([
					'Title'=>$label = TextField::create('', ''),
                    'ProductID' => $product = DropdownField::create(
                        '',
                        '',
                        Product::get()->sort('Title', 'ASC')->map('ID', 'Title')
                    ),
                ])->setFilterMethods([
					'Title' => function (DataList $list, $name, $value) {
						return $list->filterAny([
							'Title:PartialMatch' => $value
						]);
					},
				]);


            $product->setEmptyString('-- select --');
            $config->addComponent($filter, GridFieldPaginator::class);
        }

        return $form;
    }
}