<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Moto;
use App\Models\ParameterNameGroup;
use App\Models\ParameterNameTerm;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Moto
 *
 * @property \App\Models\ParameterName $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class ParameterNames extends Section
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
    protected $title = 'Характеристики';

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
                AdminColumn::link('title', 'Характеристика')->setWidth('150px'),
                AdminColumn::link('unit', 'Ед.изм.')->setWidth('70px'),
                AdminColumn::text('moto.title', 'Тип техники')->setWidth('150px'),
                AdminColumn::lists('parameter_name_terms.title', 'Термины'),
                AdminColumn::text('parameter_name_group.title', 'Группа'),
                AdminColumn::text('sort', 'Сортировка'),
            ])
            ->setApply(function ($query) {
                $query->orderBy('moto_id', 'asc');
                $query->orderBy('parameter_name_group_id', 'asc');
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
            AdminFormElement::text('title', 'Характеристика')->required(),
            AdminFormElement::text('alias', 'Алиас')->required(),
            AdminFormElement::text('unit', 'Ед.изм.'),
            AdminFormElement::select('moto_id', 'Тип техники', Moto::class)->setDisplay('title')->required(),
            AdminFormElement::multiselect('parameter_name_terms', 'Термины', ParameterNameTerm::class)->setDisplay('title'),
            AdminFormElement::select('parameter_name_group_id', 'Группировка', ParameterNameGroup::class)->setDisplay('title')->required(),
            AdminFormElement::text('sort', 'Сортировка'),
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
