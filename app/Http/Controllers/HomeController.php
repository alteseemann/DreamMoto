<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSaleRequest;
use App\Models\Dealer;
use App\Models\Image;
use App\Models\Moto;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Sale;
use App\Models\City;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as ImageResize;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Отправление ответов и ошибок (Axios)

    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
    }

    //Обработка главной страницы ЛК

    public function personal(){
        return view('home.my_profile');
    }

    //Получаем список брендов для конкретного типа техники
    public function get_brands(Request $request, Moto $motos, City $city){
        $moto_request = $request->motos;
        foreach ($moto_request as $moto){
            $brand_list[$moto['id']]=$motos->find($moto['id'])->brands;
        }
        //получаем список городов
        $cities = $city::all();
        $data = [
            'brand_list'=>$brand_list,
            'cities'=>$cities
        ];
        return $this->sendResponse($data, "Передан список брендов");
    }

    //Создание нового дилера
    public function new_dealer(Request $request,City $city){
        $coords = $request -> coords;
        $req_city = $request->city;
        $organization = $request->organization;
        $alias = $request->alias;
        $address = $request->adress;
        $phone = $request->phone;
        $site = $request->site;
        $products = $request->products;
        $dealer_id = Auth::user()->dealer_id;
        if ($dealer_id != null){
            return $this->sendError('Ошибка', $errorMessages = ['Вы уже загрузили свои данные и можете отредактировать их в соответствующем разделе.'], $code = 404);
        }
        $city_id=$city->where('title',$req_city)->first()->id;
        $dealer = new Dealer([
            'city_id' => $city_id,
            'title' => $organization,
            'alias' => $alias,
            'address' => $address,
            'phone' => $phone,
            'site' => $site,
            'latitude' => $coords[0],
            'longitude' => $coords[1],
            'is_active' => 0,
            'sale_count' => 0,
            'view_count' => 0
        ]);
        $dealer->save();
        $user = Auth::user();
        $user->dealer_id = $dealer->id;
        $user->save();
        $data['new_user_dealer_id']=Auth::user()->dealer_id;
        $motos=[];
        foreach ($products as $log){
            $log_array = explode('_',$log);
            $moto_id = $log_array[0];
            $brand_id = $log_array[1];
            $brand = Brand::find($brand_id);
            $brand->dealers()->attach($dealer,['moto_id'=>$moto_id]);
            array_push($motos,$moto_id);
            $data[$log]="запись добавлена";
        }
        $motos=array_unique($motos);
        foreach ($motos as $moto_id){
            $moto = Moto::find($moto_id);
            $moto->dealers()->save($dealer);
        }
        return $this->sendResponse($data, "Данные успешно сохранены. Вы можете начать создавать объявления, но другие пользователи увидят их, когда ваши данные пройдут модерацию.");
    }

    //Редактирование данных профиля
    public function edit_dealer(Request $request){
        $changed_data=$request->changed_data;
        $dealer = Dealer::where('id', Auth::user()->dealer_id)
            ->with('brands')
            ->with('city')
            ->with('motos.brands')
            ->with('sales')
            ->first();
        foreach ($changed_data as $key=>$value){
            if ($key=='moto_brands'){
                $motos=[];
                DB::table('moto_dealer')->where('dealer_id',$dealer->id)->delete();
                DB::table('dealer_brand')->where('dealer_id',$dealer->id)->delete();
                foreach ($value as $log){
                    $log_array = explode('_',$log);
                    $moto_id = $log_array[0];
                    $brand_id = $log_array[1];
                    $brand = Brand::find($brand_id);
                    $brand->dealers()->attach($dealer,['moto_id'=>$moto_id]);
                    array_push($motos,$moto_id);
                    $data[$log]="запись добавлена";
                }
                $motos=array_unique($motos);
                foreach ($motos as $moto_id){
                    $moto = Moto::find($moto_id);
                    $moto->dealers()->save($dealer);
                }
            }elseif ($key == 'coords'){
                $latitude = $value[0];
                $longitude = $value[1];
                $dealer->update([
                    'latitude'=>$latitude,
                    'longitude'=>$longitude
                ]);
            }elseif ($key == 'city'){
                $dealer->update([
                    'city_id'=>City::where('title',$value)->first()->id,
                ]);
            }else{
                $dealer->update([
                    $key=>$value
                ]);
            }
        }
        return $this->sendResponse($request,"Данные успешно сохранены и отправлены на модерацию.");
    }

    //Страница добавления нового объявления
    public function salesAdd()
    {
        //Загрузка дилера со всеми связями
        $dealer = Dealer::where('id', Auth::user()->dealer_id)
            ->with('brands')
            ->with('city')
            ->with('motos.brands')
            ->with('sales')
            ->first();
        $motos = $dealer->motos;
        $brands = Brand::all();
        $moto_brands=[];
        foreach ($motos as $moto){
            $brand_collection = $dealer->filter_brands($moto->id);
            /*$brand_collection - массив вида [[$brand_1],[$brand_2,$brand_3],...[$brand_n]].
            Данный массив в качестве элементов содержит коллекции моделей брендов, выбранных пользователем
            для каждого типа техники. Количество вложенных массивов - количество типов, выбранных пользователем
            Количество элементов во вложенном массиве - количество брендов, выбранных для каждого типа*/
            foreach ($brand_collection as $brand){
                $title = $brands->find($brand->brand_id)->title;
                array_push($moto_brands,[
                    'title'=>$title,
                    'moto_id'=>$brand->moto_id,
                    'brand_id'=>$brand->brand_id
                    ]);
            }
        }
        $moto_brands=json_encode($moto_brands,JSON_UNESCAPED_UNICODE);
        //dd($moto_brands);
        return view('home.sales.add', [
            'dealer'      => $dealer,
            'sale_images' => collect([]),
            'moto_brands' => $moto_brands
        ]);
    }


    public function sales(Request $request)
    {
        //Загрузка дилера со всеми связями
        $dealer = Dealer::where('id', Auth::user()->dealer_id)
            ->with('brands')
            ->with('city')
            ->with('motos')
            ->first();
        //выбираем тип техники из бокового списка (переход по ссылке из меню)
        $current_moto = Moto::where('alias', $request->input('type'))
            ->first();
        //массивы техники и брендов для дилера
        $motos_arr  = $dealer->motos->pluck('id')->all();
        $brands_arr = $dealer->brands->pluck('id')->all();
        //Из таблицы объявлений выбираем все объявления конкретного дилера
        $sales = Sale::whereHas('dealer', function ($query) {
            $query->where('dealer_id', Auth::user()->dealer_id)->with('product');
        });
        //если пользователь выбрал конкретный тип техники в боковом меню, фильтруем объявления
        if ($current_moto) {
            $sales = $sales->whereHas('product', function ($query) use ($current_moto) {
                $query//объявления, к которым привязан продукт...
                    ->whereHas('moto', function ($query) use ($current_moto) {
                        $query->where('motos.id', $current_moto->id);//...у которого значение moto_id соответствует заданному
                    });
            })//Sale->Product->Moto->id
                ->with(['product' => function ($query) use ($current_moto) {
                    $query
                        ->with('moto')
                        ->with('brand')
                        ->with('images');
                }]);//для выбранных объявлений загружаются связанные модели
        } else {
            $sales = $sales->with(['product' => function ($query) {
                $query->with('brand')
                    ->with('moto')
                    ->with('images');
            }]);//если категория не выбрана, отображаются все объявления дилера (со связанными моделями)
        }
        //пагинация
        $sales = $sales->with('images')->paginate(15);
        //????
        $new_motos = Moto::whereNotIn('id', $motos_arr)->get();

        $motos_brands = Moto::whereHas('brands', function ($query) use ($brands_arr) {
            $query->whereIn('brands.id', $brands_arr);
        })
            ->with(['brands' => function ($query) use ($brands_arr) {
                $query->whereIn('brands.id', $brands_arr);
            }])
            ->whereIn('id', $motos_arr)
            ->get();

        $current_motos = Moto::wherein('id', $motos_arr)
            ->with(['brands' => function ($query) {
                $query->orderby('title');
            }])
            ->get();

//        dd($dealer);

        return view('home.sales.sales', [
            'current_motos' => $current_motos,
            'current_moto'  => $current_moto,
            'dealer'        => $dealer,
            'sales'         => $sales,
            'motos_brands'  => $motos_brands,
            'new_motos'     => $new_motos,
        ]);
    }

    public function settings()
    {
        $user = Auth::user();
        if ($user->dealer_id != null){
            $is_registred = 1;
            $dealer = Dealer::where('id', $user->dealer_id)
                ->with('brands')
                ->with('city')
                ->with('motos')
                ->first();
            $dealer_data = [
                'id'=>$dealer->id,
                'city'=>$dealer->city->title,
                'title'=>$dealer->title,
                'coords'=>['lat'=>$dealer->latitude,'long'=>$dealer->longitude],
                'address'=>$dealer->address,
                'phone'=>$dealer->phone,
                'site'=>$dealer->site,
            ];
        }else{
            $is_registred = 0;
            $dealer_data = 'not_dealer';
            $dealer = 'not_dealer';
        }
        return view('home.settings', [
            'is_registred'=>$is_registred,
            'dealer_data'=>$dealer
        ]);
    }

    public function getModels(Request $request)
    {
        $models = Product::where('moto_id', $request->moto_id)
            ->where('brand_id', $request->brand_id)
            ->orderBy('title')
            ->get();

        $response['models'] = $models;

        return $response;
    }

    public function saleSave(SaveSaleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $sale = new Sale([
                'product_id'  => $request->input('product_id'),
                'dealer_id'   => Auth::user()->dealer_id,
                'price'       => $request->input('price'),
                'year'        => $request->input('year'),
                'mileage'     => $request->input('mileage'),
                'description' => $request->input('description'),
            ]);

            $sale->save();

            $images = $request->images;

            if (isset($images)) {
                for ($i = 0; $i < count($images); $i++) {

                    if ($images[$i]['id'] == '0') {

                        //кладем в хранилище
                        $image_path = Storage::disk('public')
                            ->putFile('images/sales/' . $sale->id, $images[$i]['image']);
                        //ресайзим
                        $new_image = ImageResize::make(Storage::disk('public')->get($image_path))->fit(900, 600);
                        //если картинка png, делаем белую подложку
                        if ($new_image->mime() == 'image/png') {
                            $canvas    = ImageResize::canvas(900, 600, 'ffffff');
                            $new_image = $canvas->insert($new_image);
                        }
                        $new_image->encode('jpg', 95);


                        //пересохраняем
                        Storage::disk('public')->put($image_path, $new_image);

                        $path = new Image([
                            'path' => $image_path,
                            'sort' => $i,
                        ]);
                        $sale->images()->save($path);

                    } else {
                        $sale->images()->find($images[$i]['id'])->update([
                            'sort' => $i,
                        ]);
                    }

                }
            }

            $deleted = $request->deleted;

            if (isset($deleted)) {
                for ($i = 0; $i < count($deleted); $i++) {

                    $dir_name         = pathinfo($deleted[$i]['path'])['dirname'];
                    $find_file        = pathinfo($deleted[$i]['path'])['filename'];
                    $files            = Storage::disk('public')->files($dir_name);
                    $files_for_delete = [];

                    //формируем список файлов для удаления
                    foreach ($files as $file) {
                        $pos = strpos($file, $find_file);
                        if ($pos !== false) {
                            array_push($files_for_delete, $file);
                        }
                    }

                    //удаление файлов изображений
                    Storage::disk('public')->delete($files_for_delete);
                    //удаление записи об изображении
                    $image = Image::find($deleted[$i]['id']);
                    $image->delete();

                }
            }
        });

        $redirect_path = '/home/sales';

        $response['success']       = true;
        $response['redirect_path'] = $redirect_path;

        return $response;

    }
}
