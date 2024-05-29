<?php

namespace App\CPU;

use App\Model\Category;
use App\Model\Product;

class CategoryManager
{
    public static function parents()
    {
        $x = Category::with(['childes.childes'])->where('position', 0)->priority()->get();
        return $x;
    }

    public static function child($parent_id)
    {
        $x = Category::where(['parent_id' => $parent_id])->get();
        return $x;
    }

    public static function products($category_id)
    {
        $id = '"'.$category_id.'"';
        return Product::with(['rating','tags','seller.shop'])
            ->active()
            ->where('category_ids', 'like', "%{$id}%")->get();
            /*->whereJsonContains('category_ids', ["id" => (string)$data['id']])*/
    }

    public static function get_category_name($id){
        $category = Category::find($id);

        if($category){
            return $category->name;
        }
        return '';
    }

    public static function get_categories_with_counting($shop=null)
    {
        $categories = [];

        $category_ids = Category::where('position',0)->pluck('id');

        foreach ($category_ids as $category_id) {
            $category = Category::withCount(['product'=>function($qc1){
                                $qc1->where(['status'=>'1']);
                            }])->with(['childes' => function ($qc2) {
                                $qc2->with(['childes' => function ($qc3) {
                                    $qc3->withCount(['sub_sub_category_product'])->where('position', 2);
                                }])->withCount(['sub_category_product'])->where('position', 1);
                            }, 'childes.childes'])
                            ->where('position', 0)
                            ->find($category_id);

            if ($category != null) {
                array_push($categories, $category);
            }
        }
        $categories = array_unique($categories);

        return $categories;
    }
}
