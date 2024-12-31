<?php



namespace App\Controllers;

use App\Controllers\Admin\Shop as AdminShop;

class Home extends BaseController
{
    public function index(): string
    {
        return view('login', ['showNavbar' => false]);
    }

    function validation()
    {
        $shop = new Shop();
        echo $shop->product('laptop', 'lenovo');

        $newShop = new AdminShop;
        echo $newShop->product('laptop', 'HP');
    }
}
