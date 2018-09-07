<?php

namespace App\Http\Controllers;

use App\ImageTag;
use App\Tag;
use Illuminate\Http\Request;

use Faker\Provider\Uuid;

use App\Image;
use Illuminate\Http\Response;


class ImagesController extends Controller
{

    public function Get()
    {
        return response()->json(Image::GetAll(),200);
    }

    public function Post(Request $request)
    {
/*
        $this->validate($request, [
            'input_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
*/
        if ($request->hasFile('image'))
        {
            $image = $request->file('image');

            $name = Uuid::uuid().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/gallery');
            $image->move($destinationPath, $name);

            return 'success Image Upload successfully';
        }


        return 'no file';

    }
}
