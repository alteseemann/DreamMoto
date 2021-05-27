<?php

namespace App\Providers;

use SleepingOwl\Admin\Providers\AdminSectionsServiceProvider as ServiceProvider;

class AdminSectionsServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $sections = [
        \App\User::class                      => 'App\Http\Sections\Users',
        \App\Models\Moto::class               => 'App\Http\Sections\Motos',
        \App\Models\Brand::class              => 'App\Http\Sections\Brands',
        \App\Models\ParameterName::class      => 'App\Http\Sections\ParameterNames',
        \App\Models\ParameterNameGroup::class => 'App\Http\Sections\ParameterNameGroups',
        \App\Models\ParameterNameTerm::class  => 'App\Http\Sections\ParameterNameTerms',
        \App\Models\Product::class            => 'App\Http\Sections\Products',
        \App\Models\Sale::class               => 'App\Http\Sections\Sales',
        \App\Models\AdBlock::class            => 'App\Http\Sections\AdBlocks',
    ];

    /**
     * Register sections.
     *
     * @param \SleepingOwl\Admin\Admin $admin
     * @return void
     */
    public function boot(\SleepingOwl\Admin\Admin $admin)
    {
        //

        parent::boot($admin);
    }
}
