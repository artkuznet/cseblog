<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ImagesController extends Controller
{
    private $FolderName = 'gallery'; // имя директории с файлами картинок в public

    public function Get($guid = null)
    {
        if(!is_null($guid) && !Uuid::isValid($guid)) abort(400); // если неверный формат uuid, то bad request
        $tags=request()->exists('tags') ? request()->input('tags') : null; // проверяем, есть ли теги запросе
        $Image=Image::Get($guid,$tags);
        if(count($Image)===0 && !is_null($guid)) abort(404); // если ничего не найдено, то 404
        return response()->json($Image,200);
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
        ); // валидация файла изображения

        if ($Validator->fails()) return response()->json($Validator->errors(), 400);

        $ImageFile = $request->file('image');
        $Guid = Uuid::uuid1(); // генерируем guid
        $ImageName = md5($Guid) . '.' . $ImageFile->getClientOriginalExtension(); // генерируем имя файла
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

    public function Update(Request $request,$guid)
    {
        $Validator = Validator::make(
            [
                'guid' => $guid,
                'image' => $request->file('image')
            ],
            [
                'guid'=>'required|uuid',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
            ]
        );

        if ($Validator->fails()) return response()->json($Validator->errors(), 400);

        $Image=Image::find($guid);
        if(is_null($Image)) abort(404);

        if($request->hasFile('image'))
        {
            $ImageFile = $request->file('image');
            $ImageName = md5($Image->guid) . '.' . $ImageFile->getClientOriginalExtension();
            $ImgSrc = '/' . $this->FolderName . '/' . $ImageName;
            unlink(public_path($Image->img));   //удаляем старый файл изображения
            $ImageFile->move(public_path('/' . $this->FolderName), $ImageName);
            $Image->img=$ImgSrc;
        }

        if($request->exists('description')) $Image->description=$request->input('description');

        $Image->tags()->detach(); // обновляем связь many to many
        if ($request->exists('tags')) {
            foreach ($request->input('tags') as $TagName) {
                $Tag = Tag::firstOrNew(['name' => $TagName]);
                $Tag->save();
                $Image->tags()->attach($Tag->id);
            }
        }

        $Image->save();
        return response()->json($Image,200);
    }

    public function Delete($guid)
    {
        $Image=Image::find($guid);
        if(!is_null($Image)) $Image->tags()->detach(); // не забываем открепить теги
        return response()->json(['success' => Image::destroy($guid)],200);
    }
}