<?php

use SleepingOwl\Admin\Navigation\Page;

// Default check access logic
// AdminNavigation::setAccessLogic(function(Page $page) {
// 	   return auth()->user()->isSuperAdmin();
// });
//
// AdminNavigation::addPage(\App\User::class)->setTitle('test')->setPages(function(Page $page) {
// 	  $page
//		  ->addPage()
//	  	  ->setTitle('Dashboard')
//		  ->setUrl(route('admin.dashboard'))
//		  ->setPriority(100);
//
//	  $page->addPage(\App\User::class);
// });
//
// // or
//

AdminSection::addMenuPage(\App\Models\Product::class)
    ->setIcon('fa fa-newspaper-o');

return [
    [
        'title' => 'Dashboard',
        'icon'  => 'fa fa-dashboard',
        'url'   => route('admin.dashboard'),
    ],

    [
        'title' => 'Information',
        'icon'  => 'fa fa-exclamation-circle',
        'url'   => route('admin.information'),
    ],

    [
        'title'    => 'Настройки',
        'icon'     => 'fa fa-magic',
        'priority' => '10000',
        'pages'    => [
            (new Page(\App\Models\Moto::class))
                ->setTitle('Типы техники')
                ->setPriority(0),
            (new Page(\App\Models\Brand::class))
                ->setTitle('Бренды')
                ->setPriority(2),
            (new Page(\App\Models\ParameterNameGroup::class))
                ->setTitle('Группы характеристик')
                ->setPriority(3),
            (new Page(\App\Models\ParameterName::class))
                ->setTitle('Характеристики')
                ->setPriority(4),
            (new Page(\App\Models\ParameterNameTerm::class))
                ->setTitle('Термины характеристик')
                ->setPriority(5)
        ]
    ],

    [
        'title'    => 'Каталог',
        'icon'     => 'fa fa-magic',
        'priority' => '2000',
        'pages'    => [
            (new Page(\App\Models\Product::class))
                ->setTitle('Модели')
                ->setPriority(0),
        ]
    ],

    [
        'title'    => 'Продажа',
        'icon'     => 'fa fa-magic',
        'priority' => '5000',
        'pages'    => [
            (new Page(\App\Models\Sale::class))
                ->setTitle('Объявления')
                ->setPriority(0),
        ]
    ],

    [
        'title'    => 'Реклама',
        'icon'     => 'fa fa-magic',
        'priority' => '7000',
        'pages'    => [
            (new Page(\App\Models\AdBlock::class))
                ->setTitle('Блоки')
                ->setPriority(0),
        ]
    ],

    [
        'title'    => 'Полномочия',
        'icon'     => 'fa fa-group',
        'priority' => '10000',
        'pages'    => [
            (new Page(\App\User::class))
                ->setTitle('Пользователи')
                ->setIcon('fa fa-user')
                ->setPriority(0)
        ]
    ]
    // Examples
    // [
    //    'title' => 'Content',
    //    'pages' => [
    //
    //        \App\User::class,
    //
    //        // or
    //
    //        (new Page(\App\User::class))
    //            ->setPriority(100)
    //            ->setIcon('fa fa-user')
    //            ->setUrl('users')
    //            ->setAccessLogic(function (Page $page) {
    //                return auth()->user()->isSuperAdmin();
    //            }),
    //
    //        // or
    //
    //        new Page([
    //            'title'    => 'News',
    //            'priority' => 200,
    //            'model'    => \App\News::class
    //        ]),
    //
    //        // or
    //        (new Page(/* ... */))->setPages(function (Page $page) {
    //            $page->addPage([
    //                'title'    => 'Blog',
    //                'priority' => 100,
    //                'model'    => \App\Blog::class
    //		      ));
    //
    //		      $page->addPage(\App\Blog::class);
    //	      }),
    //
    //        // or
    //
    //        [
    //            'title'       => 'News',
    //            'priority'    => 300,
    //            'accessLogic' => function ($page) {
    //                return $page->isActive();
    //		      },
    //            'pages'       => [
    //
    //                // ...
    //
    //            ]
    //        ]
    //    ]
    // ]
];
