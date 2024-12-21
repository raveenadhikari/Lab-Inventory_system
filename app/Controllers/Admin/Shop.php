<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class Shop extends BaseController
{
    public function index(): string
    {
        return view('shop');
    }

    public function product($type)
    {
        echo '<h2>this is an Admin prduct:' . $type . '</h2>';
        //return view('product');
    }
}
