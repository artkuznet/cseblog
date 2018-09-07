<?php

namespace App\Http\Controllers;

use App\News;

use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function Get($slug = null)
    {
        return response()->json(News::Get($slug),200) ;
    }

    public function Post(Request $request)
    {

        $Preview=$request->input('preview');
        $Header=$request->input('header');
        $Content=$request->input('content');

        $Slug=str_slug($Header);

        return response()->json([$Slug,$Preview,$Header,$Content],200);
    }
}
