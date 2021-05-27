<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Models\Moto;
use App\Models\ParameterName;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Section;

/**
 * Class Moto
 *
 * @property \App\Models\ParameterNameTerm $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class ParameterNameTerms extends Section
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
    protected $title = 'Термины характеристик';

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
            ->with('parameter_name')
            ->setHtmlAttribute('class', 'table-primary')
            ->setColumns([
                AdminColumn::link('title', 'Термин')->setWidth('150px'),
                AdminColumn::text('parameter_name.title', 'Характеристика')->setWidth('150px'),
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
            AdminFormElement::text('title', 'Термин')->required(),
            AdminFormElement::select('parameter_name_id', 'Характеристика', ParameterName::class)->setDisplay('title')->required(),
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
