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
 * @property \App\Models\ParameterNameGroup $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class ParameterNameGroups extends Section
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
    protected $title = 'Группы характеристик';

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
                AdminColumn::link('title', 'Наименование')->setWidth('150px'),
                AdminColumn::lists('parameter_names.title', 'Характеристики'),
                AdminColumn::order()
                    ->setLabel('Сортировка')
                    ->setHtmlAttribute('class', 'text-center')
                    ->setWidth('100px'),
            ])
            ->setApply(function ($query) {
                $query->orderBy('sort', 'asc');
            })
            ->paginate(20);
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
