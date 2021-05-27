<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\City;
use App\Models\Dealer;
use App\Models\Image;
use App\Models\Moto;
use App\Models\ParameterName;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as ImageResize;

class ParserController extends Controller
{
    public function index()
    {
        $motos = Moto::get();

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function dealers()
    {
        $motos = Moto::get();

        $current_moto = Moto::find(1);

        $dealers = DB::table('wp_term_taxonomy')
            ->join('wp_term_relationships', 'wp_term_taxonomy.term_id', '=', 'wp_term_relationships.term_taxonomy_id')
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->where('wp_term_taxonomy.taxonomy', 'brand')
            ->get();

//        dd($dealers);

        foreach ($dealers as $dealer) {

            $new_dealer = Dealer::find($dealer->object_id);
            $brand = Brand::where('alias', $dealer->slug)->first();

//            $newauth = $new_dealer->update([
//                    'city_id' => $dealer->term_id,
//                ]
//            );


//            $newauth = Dealer::create([
//                    'id'          => $dealer->ID,
//                    'title'       => $dealer->post_title,
//                    'alias'       => $dealer->post_name,
//                ]
//            );
//
            $new_dealer->brands()->attach($brand->id);
        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function cities()
    {
        $motos = Moto::get();

//        $cities = DB::table('wp_term_taxonomy')
//            ->where('taxonomy', 'city')
//            ->get();
//
//        foreach ($cities as $city) {
//
//            $newauth = City::create([
//                    'id'   => $city->term_id,
//                ]
//            );
//        }

        $cities = DB::table('cities')
            ->get();

        foreach ($cities as $city) {

            $current_city = City::find($city->id);

            $old_city = DB::table('wp_termmeta')
                ->where('term_id', $city->id)
                ->where('meta_key', 'where')
                ->first();

            $newauth = $current_city->update([
                    'where' => $old_city->meta_value,
                ]
            );
        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function posts()
    {
        $motos = Moto::get();

        $posts = DB::table('wp_posts')
            ->where('post_status', 'publish')
            ->where('post_type', 'listings')
            ->get();

//        dd($posts);

        foreach ($posts as $post) {

            $newauth = Product::create([
                    'id' => $post->ID,
                    'moto_id' => 1,
                    'title' => $post->post_title,
                    'alias' => $post->post_name,
                    'old_alias' => $post->post_name,
                    'description' => $post->post_content,
                ]
            );
        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function brands()
    {
        $motos = Moto::get();

        $posts = DB::table('brands')
            ->get();

        foreach ($posts as $post) {

            $current_brand = Brand::find($post->id);

            $old_brand = DB::table('wp_terms')
                ->where('term_id', $post->id)
                ->first();

            $newauth = $current_brand->update([
                    'title' => $old_brand->name,
                    'alias' => $old_brand->slug,
                ]
            );
        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function brands_product()
    {
        $motos = Moto::get();

        $posts = DB::table('products')
            ->get();

        foreach ($posts as $post) {

            $brand = Brand::find($post->brand_id);

            $current_product = Product::find($post->id);
            $ba = trim($brand->title) . ' ';

            echo $new_title = ltrim($current_product->title, $ba);

            echo "<br/>";

            $newauth = $current_product->update([
                    'title' => $new_title,
                ]
            );
        }
//
//        $posts = DB::table('products')
//            ->get();
//
//        $ar_brands = [];
//
//        $brands = DB::table('brands')
//            ->get();
//        foreach ($brands as $brand) {
//
//            array_push($ar_brands , $brand->id);
//
//        }
//
//        print_r($ar_brands);
//
//
//
//        foreach ($posts as $post) {
//
//            $current_brand = Product::find($post->id);
//
//            $old_brand = DB::table('wp_term_relationships')
//                ->where('object_id', $post->id)
//                ->whereIn('term_taxonomy_id', $ar_brands)
//                ->first();
//
////            dd($old_brand);
////            if($old_brand->count()>0)
//            echo $old_brand->term_taxonomy_id.'<br/>';
//
//            $newauth = $current_brand->update([
//                    'brand_id' => $old_brand->term_taxonomy_id,
//                ]
//            );
//        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function options()
    {
        $motos = Moto::get();

        $wp_options = DB::table('wp_options')
            ->where('option_name', 'stm_vehicle_listing_options')
            ->first();

        $options = json_decode('{' . $wp_options->option_value . '}');

        dd($options);

//        foreach ($posts as $post) {
//
//            $current_brand = Brand::find($post->id);
//
//            $old_brand = DB::table('wp_terms')
//                ->where('term_id', $post->id)
//                ->first();
//
//            $newauth = $current_brand->update([
//                    'title' => $old_brand->name,
//                    'alias' => $old_brand->slug,
//                ]
//            );
//        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function images()
    {
        set_time_limit(1800);

        $motos = Moto::get();

        $products = Product::get();

        foreach ($products as $product) {

            $old_post_meta_start = DB::table('wp_postmeta')
                ->where('post_id', $product->id)
                ->where('meta_key', '_thumbnail_id')
                ->first();

            $old_post = DB::table('wp_posts')
                ->where('id', $old_post_meta_start->meta_value)
                ->first();

//            foreach ($old_posts as $key => $old_post) {
            if ($old_post) {
                if ($old_post->guid) {
                    $new_image = ImageResize::make($old_post->guid);
                    $new_image->fit(825, 550);
                    if ($new_image->mime() == 'image/png') {
                        $canvas = ImageResize::canvas(825, 550, 'ffffff');
                        $new_image = $canvas->insert($new_image);
                    }

                    $new_image->encode('jpg', 95);

                    //кладем в хранилище
                    $uuid = Str::uuid()->toString();

                    $file_name = 'images/catalog/' . $product->id . '/' . $uuid . '.jpg';

                    $image_path = Storage::disk('public')
                        ->put($file_name, $new_image);

                    $path = new Image([
                        'sort' => 1,
                        'path' => $file_name,
                        'old_wp_post_id' => $old_post->ID
                    ]);
                    $product->images()->save($path);
                }

            }

            $old_postmeta = DB::table('wp_postmeta')
                ->where('post_id', $product->id)
                ->where('meta_key', 'gallery')
                ->where('meta_value', '!=', '')
                ->first();

            if ($old_postmeta) {

                $gallery = unserialize($old_postmeta->meta_value);

//                dd($gallery);

                foreach ($gallery as $key => $pic) {

                    $old_post = DB::table('wp_posts')
                        ->where('id', $pic)
                        ->first();

                    if ($old_post->guid) {
                        $new_image = ImageResize::make($old_post->guid)->fit(900, 600)->encode('jpg', 95);

                        //кладем в хранилище
                        $uuid = Str::uuid()->toString();

                        $file_name = 'images/catalog/' . $product->id . '/' . $uuid . '.jpg';

                        $image_path = Storage::disk('public')
                            ->put($file_name, $new_image);

                        $path = new Image([
                            'sort' => $key + 2,
                            'path' => $file_name,
                            'old_wp_post_id' => $old_post->ID
                        ]);
                        $product->images()->save($path);
                    }


                }

            }
        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function on_map()
    {
        $motos = Moto::get();

        $on_maps = DB::table('wp_postmeta')
            ->where('meta_key', 'on_map')
            ->get();

        $dealers = Dealer::get();


        foreach ($dealers as $dealer) {

            $on_map = DB::table('wp_postmeta')
                ->where('meta_key', 'diler_phone')
                ->where('post_id', $dealer->id)
                ->first();

//            dd(($on_map->meta_value));
            $dealer->update([
                    'phone' => $on_map->meta_value,
                ]
            );
//
//            $old_posts = DB::table('wp_posts')
//                ->where('post_parent', $product->id)
//                ->where('post_type', 'attachment')
//                ->orderby('ID')
//                ->get();
//            if ($old_post) {
//                if ($old_post->meta_value) {
//
//                    echo $product->id . $old_post->meta_value . '<br/>';
//                    $images = unserialize($old_post->meta_value);
//
//                } else {
//                    echo $product->id . "-" . "<br/>";
//                }
//            } else {
//                echo $product->id . "-" . "<br/>";
//            }
//            dd(unserialize($on_map->meta_value)['lat']);

        }

        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

//    public function parameters()
//    {
//        $motos = Moto::get();
//
//        $products = DB::table('products')
//            ->get();
//
//        foreach ($products as $product) {
//
//            echo "<h2>" . $product->title . "</h2>";
//
//            $parameters = DB::table('parameter_names')->get();
//
//            foreach ($parameters as $parameter) {
//
//                $val = null;
//
//                $value = DB::table('wp_postmeta')
//                    ->where('post_id', $product->id)
//                    ->where('meta_key', $parameter->alias)
//                    ->first();
//
//                if ($value) {
//
//                    $val = $value->meta_value;
//
//                    $type = DB::table('wp_terms')
//                        ->where('slug', $value->meta_value)
//                        ->first();
//
//                    if ($type) {
//                        $val = $type->name;
//                    }
//                }
//
//                if($val) {
//
//                    echo  $value->meta_key . ' - '. $val . '<br>';
//
//                    DB::table('parameters')->insert(
//                        array(
//                            'product_id'        => $product->id,
//                            'parameter_name_id' => $parameter->id,
//                            'value'             => $val
//                        )
//                    );
//
//                }
//
//
//
//            }
//
//            echo '<br>';
//
//        }
//
//
//
//        $current_city_name = Cookie::get('current_city_name');
//
//        return view('services.parsers', [
//            'motos'             => $motos,
//            'current_moto'      => NULL,
//            'current_city_name' => $current_city_name,
//        ]);
//    }

    public function parameters()
    {
        $motos = Moto::get();

        $products = Product::get();

        foreach ($products as $product) {

            $price = DB::table('wp_postmeta')
                ->where('post_id', $product->id)
                ->where('meta_key', 'price')
                ->first();
//dd($price);
            if ($price) {
                echo "<h2>" . $product->title . ' - ' . $price->meta_value . "</h2>";

                $product->update([
                        'price_from' => $price->meta_value,
                    ]
                );
            }
            echo '<br>';

        }
        die();


        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function views()
    {
        $motos = Moto::get();

        $products = Product::get();

        foreach ($products as $product) {

            $views = DB::table('wp_postmeta')
                ->where('post_id', $product->id)
                ->where('meta_key', 'stm_car_views')
                ->first();
//dd($price);
            if ($views) {
                echo "<h2>" . $product->title . ' - ' . $views->meta_value . "</h2>";

                $product->update([
                        'view_count' => $views->meta_value,
                    ]
                );
            }
            echo '<br>';

        }
        die();


        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function body()
    {
        $motos = Moto::get();

        $products = Product::get();

        foreach ($products as $product) {

            $body = DB::table('wp_postmeta')
                ->where('post_id', $product->id)
                ->where('meta_key', 'body')
                ->first();

            $class_name = explode(',', $body->meta_value);

            $class = DB::table('moto_classes')
                ->where('alias', $class_name[0])
                ->first();
//dd($price);


            if ($class) {
                $product->update([
                        'class_id' => $class->id,
                    ]
                );
            }

        }
        die();


        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

    public function dubl_params()
    {
        $motos = Moto::get();

        $scoots = Product::where('moto_id',2)
        ->get();

        foreach ($scoots as $scoot) {

            $params = DB::table('parameters')
                ->where('product_id', $scoot->id)
                ->get();

            foreach ($params as $param) {

                $param_name_id = ParameterName::where('old', $param->parameter_name_id)->first();

                DB::table('parameters')
                    ->where('id', $param->id)
                    ->update(['parameter_name_id' => $param_name_id->id]);

            }

        }


        die();


        $current_city_name = Cookie::get('current_city_name');

        return view('services.parsers', [
            'motos' => $motos,
            'current_moto' => NULL,
            'current_city_name' => $current_city_name,
        ]);
    }

}
