<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Meta;
use App\Models\Brand;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Moto
 *
 * @property \App\Models\Moto $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Motos extends Section
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Типы техники';

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::table()
            ->with('brands')
            ->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::link('title', 'Наименование')->setWidth('150px'),
                AdminColumn::lists('brands.title', 'Бренды'),
            ])->paginate(20);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {

        Meta::addJs('components_js', ('packages/sleepingowl/default/js/admin/components/common.js'), null, true);

        return AdminForm::panel()->addBody([
            AdminFormElement::text('title', 'Наименование')->required(),
            AdminFormElement::text('title_chego', 'Склонение "ЧЕГО"')->required(),
            AdminFormElement::text('alias', 'Псевдоним(только латиница, цифры, -, _)')
                ->unique('Этот псевдоним уже используется')
                ->required(),
//            AdminFormElement::custom(function () {
//            })
//                ->setDisplay(function () {
//                    $button = view('admin::components.button.translit', ['id' => 23]);
//
//                    return $button;
//                }),
            AdminFormElement::multiselect('brands', 'Бренды', Brand::class)->setDisplay('title'),
        ])->setHtmlAttribute('enctype', 'multipart/form-data');
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        // remove if unused
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}
