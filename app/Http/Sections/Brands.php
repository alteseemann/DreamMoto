<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Moto;
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
class Brands extends Section
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
    protected $title = 'Бренды';

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
            ->with('motos')
            ->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::link('title', 'Наименование')->setWidth('150px'),
                AdminColumn::lists('motos.title', 'Типы техники'),
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
            AdminFormElement::text('title', 'Наименование')->required(),
            AdminFormElement::text('alias', 'Псевдоним(только латиница, цифры, -, _)')
                ->unique('Этот псевдоним уже используется')
                ->required(),
            AdminFormElement::multiselect('motos', 'Типы техники', Moto::class)->setDisplay('title'),
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
