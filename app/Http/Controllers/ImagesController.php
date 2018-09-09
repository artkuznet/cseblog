<?php

namespace App\Http\Controllers;

use App\ImageTag;
use App\Tag;
use Illuminate\Http\Request;

use App\Image;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;


class ImagesController extends Controller
{

    private $FolderName = 'gallery';


    public function Get($guid = null)
    {
        if(!is_null($guid) && !Uuid::isValid($guid)) abort(404);
        $Image=Image::Get($guid);
        if(count($Image)===0 && !is_null($guid)) abort(404);
        return response()->json(Image::Get($guid),200);
    }

    public function Post(Request $request)
    {

        $Validator = Validator::make(
            [
                'image' => $request->file('image'),
            ],
            [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif'
            ]
        );

        if ($Validator->fails()) return response()->json($Validator->errors(), 400);

        $ImageFile = $request->file('image');
        $Guid = Uuid::uuid1();
        $ImageName = md5($Guid) . '.' . $ImageFile->getClientOriginalExtension();
        $ImgSrc = '/' . $this->FolderName . '/' . $ImageName;
        $Description = $request->exists('description') ? $request->input('description') : null;

        $Image = new Image([
            'guid'=>$Guid,
            'img'=>$ImgSrc,
            'description'=>$Description
        ]);
        $Image->save();

        if ($request->exists('tags')) {
            foreach ($request->input('tags') as $TagName) {
                $Tag = Tag::firstOrNew(['name' => $TagName]);
                $Tag->save();
                $Image->tags()->attach($Tag->id);
            }
        }

        $ImageFile->move(public_path('/' . $this->FolderName), $ImageName);

        return response()->json($Image, 200);
    }

    public function Delete($guid)
    {
        $Image=Image::find($guid);
        if(!is_null($Image)) $Image->tags()->detach();
        return response()->json(['success' => Image::destroy($guid)],200);
    }

}
