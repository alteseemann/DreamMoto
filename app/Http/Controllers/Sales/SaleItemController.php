<?php

namespace App\Http\Controllers\Sales;

use App\Models\Brand;
use App\Models\Moto;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Dealer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SaleItemController extends Controller
{
    public function index(Request $request){
        $sale = Sale::where('id',$request->route('sale_id'))
            ->with('product')
            ->with('dealer')
            ->with('images')
            ->first();
        $dealer = $sale->dealer;
        $current_moto = Moto::where('id',$sale->product->moto_id)->first();
        $product = $sale->product;
        $brand = Brand::where('id',$product->brand_id)
            ->with('dealers')
            ->first();
        $coords = json_encode([
            'lat'=>$dealer->latitude,
            'long'=>$dealer->longitude
        ]);
        if ($current_moto && $brand && $product && $sale) {
            $brands = $current_moto->brands()->get();

            $products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $brand->id)
                ->with('brand')
                ->orderby('title')
                ->get();

            $all_products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $brand->id)
                ->orderby('title')
                ->get();
        }
        return view('sales.sale',[
            'sale'         => $sale,
            'current_moto' => $current_moto,
            'dealer'       => $dealer,
            'all_products' => $all_products,
            'product'      => $product,
            'products'     => $products,
            'brands'       => $brands,
            'brand'        => $brand,
            'coords'       => $coords
        ]);
    }
    /*public function index(Request $request)
    {
        $current_moto = Moto::where('alias', $request->route('moto_alias'))->first();
        $current_brand = Brand::where('alias', $request->route('brand_alias'))->first();
        $current_product = Product::where('alias', $request->route('product_alias'))->first();
        $current_sale = Sale::where('id', $request->route('sale_id'))->first();
        $dealer = $current_sale->dealer;
        $coords = json_encode([
            'lat'=>$dealer->latitude,
            'long'=>$dealer->longitude
        ]);

        if ($current_moto && $current_brand && $current_product && $current_sale) {

            $brands = $current_moto->brands()->get();

            $products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $current_brand->id)
                ->with('brand')
                ->orderby('title')
                ->get();

            $all_products = Product::where('moto_id', $current_moto->id)
                ->where('brand_id', $current_brand->id)
                ->orderby('title')
                ->get();

            return view('sales.sale', [
                'sale'            => $current_sale,
                'brands'          => $brands,
                'current_moto'    => $current_moto,
                'brand'           => $current_brand,
                'product'         => $current_product,
                'sales'           => NULL,
                'products'        => $products,
                'all_products'    => $all_products,
                'dealer'          => $dealer,
                'coords'          => $coords,
            ]);

        } else {

            abort('404');

        }
    }*/
}
