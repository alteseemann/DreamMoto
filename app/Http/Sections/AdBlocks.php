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
 * @property \App\Models\AdBlock $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class AdBlocks extends Section
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
    protected $title = 'Рекламные блоки';

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
            ->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::link('title', 'Наименование'),
                AdminColumn::text('alias', 'Псевдоним'),
                AdminColumn::text('is_active', 'Активность'),
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
            AdminFormElement::text('alias', 'Псевдоним')->unique('Этот псевдоним уже используется')->required(),
            AdminFormElement::textarea('script', 'Скрипт рекламного блока')->required(),
            AdminFormElement::checkbox('is_active', 'Показывать на сайте')->setDefaultValue(1),
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
