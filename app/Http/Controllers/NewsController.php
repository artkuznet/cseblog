<?php

namespace App\Http\Controllers;

use App\News;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use App\Image;

class NewsController extends Controller
{

    public function Get($slug = null)
    {
        $News=News::Get($slug);
        if(count($News)===0 && !is_null($slug)) abort(404);
        return response()->json($News,200) ;
    }

    public function Post(Request $request)
    {
        Validator::extend('uuid', function ($attribute, $value, $parameters, $validator) {
            return Uuid::isValid($value);
        });

        $Validator = Validator::make(
            [
                'header' => $request->input('header'),
                'content' => $request->input('content'),
                'guid' => $request->input('guid'),
                'preview' => $request->input('preview'),
                'slug' => $request->input('slug')
            ],
            [
                'header' => 'required',
                'content' => 'required',
                'guid'=>'required_without:preview|nullable|uuid',
                'preview' => 'required_without:guid|nullable|url',
                'slug' => 'alpha_dash|nullable'
            ]
        );

        if ($Validator->fails()) return response()->json($Validator->errors(), 400);

        $Header = $request->input('header');
        $Content = $request->input('content');

        $Slug = $request->exists('slug') ? str_slug($request->input('slug')) : str_slug($Header);

        $Slug.=count(News::Get($Slug)) > 0 ? '-'.round(microtime(true) * 1000) : ''; // чтобы избежать повторяющихся слагов


         $Img=Image::where('guid','=',$request->input('guid'))->get();

        $Preview= $request->exists('guid')&&count($Img)>0 ? $Img[0]->img : $request->input('preview');

        $News=new News([
            'slug'=>$Slug,
            'preview'=>$Preview,
            'header'=>$Header,
            'content'=>$Content
        ]);
        $News->save();

        return response()->json($News, 200);

    }


    public function Delete($id)
    {
        return response()->json(['success' => News::destroy($id)],200);
    }
}
