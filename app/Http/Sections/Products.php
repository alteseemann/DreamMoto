<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Brand;
use App\Models\Moto;
use App\Models\MotoClass;
use App\Models\ParameterName;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Moto
 *
 * @property \App\Models\Product $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Products extends Section
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
    protected $title = 'Каталог';

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
            ->with('brand')
            ->with('moto')
            ->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::text('moto.title', 'Тип')->setWidth('150px'),
                AdminColumn::link('title', 'Модель'),
                AdminColumn::text('brand.title', 'Бренд'),
            ])->paginate(20);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody([
            AdminFormElement::select('moto_id', 'Тип техники', Moto::class)->required()->setDisplay('title'),
            AdminFormElement::select('brand_id', 'Бренд', Brand::class)->required()->setDisplay('title')->setFetchColumns(['title', 'id']),
            AdminFormElement::text('title', 'Название модели')->required(),
            AdminFormElement::text('alias', 'Псевдоним(только латиница, цифры, -, _)')
                ->unique('Этот псевдоним уже используется')
                ->required(),
            AdminFormElement::select('class_id', 'Тип (Класс)', MotoClass::class)->required()->setDisplay('title')->setFetchColumns(['title', 'id']),
            AdminFormElement::text('price_catalog', 'Цена, рекомендуемая производителем'),
            AdminFormElement::textarea('description', 'Описание'),

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
