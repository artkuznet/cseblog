<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Image;

class NewsController extends Controller
{
    public function Get($Slug = null)
    {
        $from=request()->exists('from') ? request()->input('from') : null;
        $to=request()->exists('to') ? request()->input('to') : null;
        $header=request()->exists('header') ? request()->input('header') : null;

        $News=News::Get($Slug,$from,$to,$header);
        if(count($News)===0 && !is_null($Slug)) abort(404);
        return response()->json($News,200) ;
    }

    public function Post(Request $request)
    {
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

        $Image=Image::where('guid','=',$request->input('guid'))->get();
        $Preview = count($Image)>0 ? $Image[0]->img : $request->input('preview');

        $News=new News([
            'slug'=>$Slug,
            'preview'=>$Preview,
            'header'=>$Header,
            'content'=>$Content
        ]);
        $News->save();
        return response()->json($News, 200);
    }

    public function Update(Request $request,$id)
    {
        $Validator = Validator::make(
            [
                'guid' => $request->input('guid'),
                'preview' => $request->input('preview'),
                'slug' => $request->input('slug')
            ],
            [
                'guid'=>'nullable|uuid',
                'preview' => 'nullable|url',
                'slug' => 'alpha_dash|nullable'
            ]
        );

        if ($Validator->fails()) return response()->json($Validator->errors(), 400);

        $News=News::find($id);
        if(is_null($News)) abort(404);

        if($request->exists('header')) $News->header=$request->input('header');
        if($request->exists('content')) $News->content=$request->input('content');

        if($request->exists('slug'))
        {
            $Slug = str_slug($request->input('slug'));
            $Slug.=count(News::Get($Slug)) > 0 ? '-'.round(microtime(true) * 1000) : ''; // чтобы избежать повторяющихся слагов
            $News->slug=$Slug;
        }

        if($request->exists('guid'))
        {
            $Image = Image::where('guid', '=', $request->input('guid'))->get();
            if(count($Image)>0) $News->preview = $Image[0]->img;
        }
        else if($request->exists('preview'))
        {
            $News->preview=$request->input('preview');
        }

        $News->save();
        return response()->json($News,200);
    }

    public function Delete($id)
    {
        return response()->json(['success' => News::destroy($id)],200);
    }
}
