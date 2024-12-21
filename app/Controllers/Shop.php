<?php

namespace App\Controllers;

class Shop extends BaseController
{
    public function index(): string
    {
        return view('shop');
    }

    public function product($type)
    {
        echo '<h2>this is a prduct:' . $type . '</h2>';
        //return view('product');
    }
}
