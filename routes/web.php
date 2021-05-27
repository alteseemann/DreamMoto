<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/verify/{token}', 'Auth\VerificationController@verify')->name('register.verify');

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//Настроить временный редирект 302
//с главной на страницу мотоциклов
//для посетителей пришедших с посковиков
// см. requesr-> http refer
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//^^Переадресации
//классы
Route::get('/krossovye-mototsikly', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'cross'], 301);
});
Route::get('/compare', function () {
    return redirect()->route('catalog.index', 'motorcycles', 301);
});
Route::get('/krossovye-mototsikly/kupit-krossovyj-mototsikl', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'cross'], 301);
});
Route::get('/enduro-mototsikly', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'enduro'], 301);
});
Route::get('/sportivnye-mototsikly', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'sport'], 301);
});
Route::get('/dorozhnye-mototsikly', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'road'], 301);
});
Route::get('/klassicheskie-mototsikly', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'classic'], 301);
});
Route::get('/tyazhelye-mototsikly-kruizery', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'cruiser'], 301);
});
Route::get('/turisticheskie-mototsikly', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'touring'], 301);
});
Route::get('/enduro-mototsikly/turenduro', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'tour-enduro'], 301);
});
Route::get('/naked-moto', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'naked'], 301);
});
Route::get('/supermotard', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'supermoto'], 301);
});
Route::get('/kafe-rejsery', function () {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'city' => 'cafe-racer'], 301);
});
Route::get('/nedorogie-mototsikly', function () {
    return redirect()->route('catalog.index', 'motorcycles', 301);
});
Route::get('/zhenskij-mototsikl', function () {
    return redirect()->route('catalog.index', 'motorcycles', 301);
});
Route::get('/samye-dorogie-mototsikly', function () {
    return redirect()->route('catalog.index', 'motorcycles', 301);
});
//дилеры
Route::get('/dilers', function () {
    return redirect()->route('dealers.index', 'motorcycles', 301);
});
//дилеры салоны
Route::get('/dilers/{dealer_alias}', function ($dealer_alias) {
    return redirect()->route('dealers.dealer', $dealer_alias, 301);
});
//дилеры в городах, города дилеров
Route::get('/dilers/city/{city}', function ($city) {
    if (Request::has('make')) {
        return redirect()->route('city.dealers.brand', ['moto_alias' => 'motorcycles', 'city' => $city, 'brand_alias' => Request::input('make')], 301);
    } else {
        return redirect()->route('city.dealers.index', ['moto_alias' => 'motorcycles', 'city' => $city], 301);
    }
});
//public
Route::get('/public/{moto_alias}/catalog', function ($moto_alias) {
    return redirect()->route('catalog.index', $moto_alias, 301);
});
Route::get('/public/{moto_alias}/catalog/{brand_alias}', function ($moto_alias, $brand_alias) {
    return redirect()->route('catalog.brands', ['moto_alias' => $moto_alias, 'brand_alias' => $brand_alias], 301);
});
Route::get('/public/{moto_alias}/catalog/{brand_alias}/{product_alias}', function ($moto_alias, $brand_alias, $product_alias) {
    return redirect()->route('catalog.product', ['moto_alias' => $moto_alias, 'brand_alias' => $brand_alias, 'product_alias' => $product_alias], 301);
});
//дилеры брендов
Route::get('/dilers/brand/{brand}', function ($brand) {
    return redirect()->route('dealers.brand', ['moto_alias' => 'motorcycles', 'brand_alias' => $brand], 301);
});
Route::get('/public/{city}/{moto_alias}/dealers', function ($city_alias, $moto_alias) {
    return redirect()->route('city.dealers.index', ['city' => $city_alias, 'moto_alias' => $moto_alias], 301);
});
Route::get('/public/{city}/{moto_alias}/dealers/{brand_alias}', function ($city_alias, $moto_alias, $brand_alias) {
    return redirect()->route('city.dealers.brand', ['city' => $city_alias, 'moto_alias' => $moto_alias, 'brand_alias' => $brand_alias], 301);
});
//бренды
Route::get('/moto/{brand_alias}', function ($brand_alias) {
    return redirect()->route('catalog.brands', ['moto_alias' => 'motorcycles', 'brand_alias' => $brand_alias], 301);
});
//модели
Route::get('/listings/{product_alias}', function ($product_alias) {
    $product = \App\Models\Product::where('old_alias', $product_alias)
        ->with('brand')
        ->first();
    if ($product) {
        $brand_alias       = $product->brand->alias;
        $new_product_alias = $product->alias;

        return redirect()
            ->route('catalog.product', [
                'moto_alias'    => 'motorcycles',
                'brand_alias'   => $brand_alias,
                'product_alias' => $new_product_alias,
            ], 301);
    } else {
        abort('404');
    }
});
//--переадресации


//Route::post('/login', 'Auth\LoginController@login');

Route::get('/', 'WelcomeController@index')->middleware('check_city')->name('welcome');

//Обратная связь
Route::post('/callback', 'WelcomeController@callback');


//HOME
Route::get('/home/sales', 'HomeController@sales')
    ->middleware('IsDealer')//пользователь не сможет добавлять объявления, пока не заполнит информацию о себе как о дилере
    ->name('home.sales');
//Главная страница личного кабинета
Route::get('/home/personal','HomeController@personal')->name('home.personal');
//Ajax запрос на получение списка брендов при выборе конкретной техники
Route::post('/home/personal/get_brands','HomeController@get_brands')->name('home.get_brands');
//Ajax передача данных нового дилера
Route::post('/home/personal/new_dealer','HomeController@new_dealer')->name('home.new_dealer');
//Ajax передача отредактированных данных дилера
Route::post('/home/personal/edit_dealer','HomeController@edit_dealer')->name('home.edit_dealer');
//Добавить новое объявление
Route::get('/home/sales/add', 'HomeController@salesAdd')->name('home.sales.add');
//Страница просмотра объявлений

Route::get('/{moto_type}/{moto_alias}/{sale_id}','Sales\SaleItemController@index')->name('sales.show');


//^^^^^^^Объявление
Route::get('/{moto_alias}/{brand_alias}/{product_alias}/{sale_id}', 'Sales\SaleItemController@index')->where('sale_id', '\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b')->name('sale.item');
//-------Объявление

Route::get('/home/settings', 'HomeController@settings')->name('home.settings');
Route::post('/sale-save', 'HomeController@saleSave');
Route::post('/get-models', 'HomeController@getModels');





//Route::get('/load-cities', 'WelcomeController@load_cities');
Route::get('/set-cities', 'WelcomeController@set_cities');

//список продуктов для сравнения
Route::post('/get-compare-list', 'Compare\CompareController@get_compare_list');

//Парсеры
//Route::group(['prefix' => 'parsers'], function () {
//    Route::get('/', 'ParserController@index')->name('parsers');
//    Route::get('/cities', 'ParserController@cities')->name('parsers.cities');
//    Route::get('/posts', 'ParserController@posts')->name('parsers.posts');
//    Route::get('/brands', 'ParserController@brands')->name('parsers.brands');
//    Route::get('/brands_product', 'ParserController@brands_product')->name('parsers.brands_product');
//    Route::get('/options', 'ParserController@options')->name('parsers.options');
//    Route::get('/images', 'ParserController@images')->name('parsers.images');
//    Route::get('/dealers', 'ParserController@dealers')->name('parsers.dealers');
//    Route::get('/onmap', 'ParserController@on_map')->name('parsers.onmap');
//    Route::get('/parameters', 'ParserController@parameters')->name('parsers.parameters');
//    Route::get('/views', 'ParserController@views')->name('parsers.views');
//    Route::get('/body', 'ParserController@body')->name('parsers.body');
//    Route::get('/dubl_params', 'ParserController@dubl_params')->name('parsers.dubl_params');
//});

//Каталог
Route::get('/{moto_alias}/catalog', 'Catalog\CatalogController@index')->name('catalog.index');
//Сравнение моделей
Route::get('/{moto_alias}/catalog/compare', 'Compare\CompareController@compare')->name('catalog.compare');
//Добавление элемента каталога
Route::get('/{moto_alias}/catalog/add', 'Catalog\CatalogController@product_add')->name('catalog.product.add');
Route::get('/{moto_alias}/catalog/classes/{brand_alias}', 'Catalog\CatalogController@brands')->name('catalog.brands');
Route::get('/{moto_alias}/catalog/{brand_alias}/{product_alias}', 'Catalog\CatalogController@product')->name('catalog.product');
//Редактирование элемента каталога
Route::get('/{moto_alias}/catalog/{brand_alias}/{product_alias}/edit', 'Catalog\CatalogController@product_edit')->name('catalog.product.edit');

//Ajax запросы
Route::post('/catalog/parameters_change', 'Catalog\CatalogController@parameters_change')->name('catalog.parameters_change');
Route::post('/catalog/images_save', 'Catalog\CatalogController@images_save')->name('catalog.images_save');
Route::post('/catalog/product_save', 'Catalog\CatalogController@product_save');
Route::post('/catalog/price_save', 'Catalog\CatalogController@price_save');
Route::post('/catalog/class_save', 'Catalog\CatalogController@class_save');
Route::post('/dealers/get_brands', 'Dealers\DealersController@get_brands');
Route::post('/dealers/dealer_save', 'Dealers\DealersController@dealer_save');
Route::post('/dealers/dealer_save_coords', 'Dealers\DealersController@dealer_save_coords');
Route::post('/dealers/dealer_check', 'Dealers\DealersController@dealer_check');

//^^^^^^^^Дилеры
//админка дилеров
Route::get('/dealers/add', 'Dealers\DealersController@dealer_add')->name('dealer.add');
//админка - редактирование дилера
Route::get('/dealers/edit/{dealer_alias}', 'Dealers\DealersController@dealer_edit')->name('dealer.edit');
//страница дилера
Route::get('/dealers/{dealer_alias}', 'Dealers\DealersController@dealer')->name('dealers.dealer');
//список дилеров
Route::get('/{moto_alias}/dealers', 'Dealers\DealersController@index')->name('dealers.index');
Route::get('/{moto_alias}/dealers/{brand_alias}', 'Dealers\DealersController@brand')->name('dealers.brand');
//с городом
Route::get('/{city}/{moto_alias}/dealers', 'Dealers\DealersController@city_index')->name('city.dealers.index');
Route::get('/{city}/{moto_alias}/dealers/{brand_alias}', 'Dealers\DealersController@city_brand')->name('city.dealers.brand');
//---------Дилеры



//^^^^^^^Продажа
if (!strpos(Request::url(), "admin") && !strpos(Request::url(), "nova")) {
    Route::get('/{city}', 'Sales\SaleController@parameter1')
        ->middleware('check_city')
        ->name('parameter1');
    Route::get('/{city}/{moto_alias}', 'Sales\SaleController@parameter2')
        ->middleware('check_city')
        ->name('parameter2');
    Route::get('/{city}/{moto_alias}/{brand_alias}', 'Sales\SaleController@parameter3')
        ->middleware('check_city')
        ->name('parameter3');
    Route::get('/{city}/{moto_alias}/{brand_alias}/{product_alias}', 'Sales\SaleController@parameter4')
        ->middleware('check_city')
        ->name('parameter4');
}
//-------Продажа
