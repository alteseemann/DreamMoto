<?php


namespace App\Helpers;


use File;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Request;
use Intervention\Image\ImageManagerStatic as ImageResize;

class Helpers extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'helper';
    }

    //Рекламный блок
    public static function get_ad_block($blockId, $renderTo, $pageNumber)
    {
        if ($pageNumber) {

            $script = '(function(w, d, n, s, t) {
                w[n] = w[n] || [];
                w[n].push(function() {
                    Ya.Context.AdvManager.render({
                blockId: "' . $blockId . '",
                renderTo: "' . $renderTo . '",
                pageNumber: ' . $pageNumber . ',
                async: true,
                onRender: function (data) {
                    $(document.body).trigger("sticky_kit:recalc");
                    console.log(data.product);
}
            });
        });
                t = d.getElementsByTagName("script")[0];
                s = d.createElement("script");
                s.type = "text/javascript";
                s.src = "//an.yandex.ru/system/context.js";
                s.async = true;
                t.parentNode.insertBefore(s, t);
            })(this, this.document, "yandexContextAsyncCallbacks");';

        } else {

            $script = '(function(w, d, n, s, t) {
                w[n] = w[n] || [];
                w[n].push(function() {
                    Ya.Context.AdvManager.render({
                blockId: "' . $blockId . '",
                renderTo: "' . $renderTo . '",
                async: true,
                onRender: function (data) {
                    $(document.body).trigger("sticky_kit:recalc");
                    console.log(data.product);
                }
            });
        });
                t = d.getElementsByTagName("script")[0];
                s = d.createElement("script");
                s.type = "text/javascript";
                s.src = "//an.yandex.ru/system/context.js";
                s.async = true;
                t.parentNode.insertBefore(s, t);
            })(this, this.document, "yandexContextAsyncCallbacks");';
        }

        return $script;

    }

    //Активный пункт меню
    public static function set_route_city($route)
    {
        $current_city_alias = Cookie::get('current_city_alias');

        if ($current_city_alias) {
            if ($route == Request::root()) {
                $route = '/' . $current_city_alias;
            } else {
                $route = '/' . $current_city_alias . $route;
            }
        }

        return $route;
    }

    //Год для СЕО
    public static function get_current_year()
    {
        if (date('m') == '12') {
            $current_year = (string)(date('Y') + 1);
        } else {
            $current_year = date('Y');
        }

        return $current_year;
    }

    //Ресайз и сохранение изображений
    public static function get_image($image_path, $width = 0, $height = 0, $default_image = 0)
    {
        $result_path = $default_image ? $default_image : 'default.png';

        if ($image_path && File::exists(public_path($image_path))) {

            if (!$width && !$height) {

                $result_path = $image_path;

            } else {

                $height = $height ? $height : $width;

                $path_parts = pathinfo($image_path);

                $filename  = $path_parts['filename'];
                $dirname   = $path_parts['dirname'];
                $extension = $path_parts['extension'];

                $resize_image_name = $filename . '(' . $width . '_' . $height . ').' . $extension;
                $resize_image_path = $dirname . '/' . $resize_image_name;

                if (File::exists(public_path($resize_image_path))) {

                    $result_path = $resize_image_path;

                } else {

                    $image = ImageResize::make(public_path($image_path));

                    $image->fit($width, $height);

                    if ($image->save(public_path($resize_image_path))) {

                        $result_path = $resize_image_path;
                    }
                }
            }
        } else {

            if ($width) {

                $height = $height ? $height : $width;
                $image  = ImageResize::make('default.png');

                if (!is_dir($image->dirname . '/default_images/')) {
                    mkdir($image->dirname . '/default_images/');
                }

                $resize_image_name = $image->filename . '(' . $width . '_' . $height . ').' . $image->extension;
                $resize_image_path = $image->dirname . '/default_images/' . $resize_image_name;

                if (File::exists(public_path($resize_image_path))) {

                    $result_path = $resize_image_path;

                } else {

                    $image->fit($width, $height);

                    if ($image->save($resize_image_path)) {

                        $result_path = $resize_image_path;

                    }
                }
            }
        }

        return $result_path;
    }

}
