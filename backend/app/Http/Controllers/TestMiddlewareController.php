<?php

namespace App\Http\Controllers;

use DesignPattern\Example\Index;

class TestMiddlewareController extends Controller
{

    public function index()
    {
        dd(Index::test());
    }
}
