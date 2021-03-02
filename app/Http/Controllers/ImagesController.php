<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request
};
use App\Tag;
use App\Image;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class ImagesController extends Controller
{
    /**
     * @var string
     */
    private const FOLDER_NAME = 'gallery'; // имя директории с файлами картинок в public

    /**
     * @param string|null $guid
     * @return JsonResponse
     */
    public function get(string $guid = null): JsonResponse
    {
        if (null !== $guid && !Uuid::isValid($guid)) {
            abort(400); // если неверный формат uuid, то bad request
        }
        $tags = request()->exists('tags')
            ? request()->input('tags')
            : null; // проверяем, есть ли теги запросе
        $Image = Image::get($guid, $tags);
        if (null !== $guid && count($Image) === 0) {
            abort(404); // если ничего не найдено, то 404
        }

        return response()->json($Image, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function post(Request $request): JsonResponse
    {
        $validator = Validator::make([
            'image' => $request->file('image'),
        ], [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]); // валидация файла изображения

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $imageFile = $request->file('image');
        $guid = Uuid::uuid1(); // генерируем guid
        $imageName = md5($guid) . '.' . $imageFile->getClientOriginalExtension(); // генерируем имя файла

        $image = new Image([
            'guid' => $guid,
            'img' => '/' . self::FOLDER_NAME . '/' . $imageName,
            'description' => $request->exists('description')
                ? $request->input('description')
                : null,
        ]);
        $image->save();

        if ($request->exists('tags')) {
            foreach ($request->input('tags') as $tagName) {
                /** @var Tag $tag */
                $tag = Tag::firstOrNew(['name' => $tagName]);
                $tag->save();
                $image->tags()->attach($tag->id);
            }
        }

        $imageFile->move(public_path('/' . self::FOLDER_NAME), $imageName);

        return response()->json($image, 200);
    }

    /**
     * @param Request $request
     * @param string $guid
     * @return JsonResponse
     */
    public function update(Request $request, string $guid): JsonResponse
    {
        $validator = Validator::make([
            'guid' => $guid,
            'image' => $request->file('image'),
        ], [
            'guid' => 'required|uuid',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        /** @var Image $image */
        $image = Image::find($guid);
        if (is_null($image)) {
            abort(404);
        }

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = md5($image->guid) . '.' . $imageFile->getClientOriginalExtension();
            unlink(public_path($image->img));   //удаляем старый файл изображения
            $imageFile->move(public_path('/' . self::FOLDER_NAME), $imageName);
            $image->img = '/' . self::FOLDER_NAME . '/' . $imageName;
        }

        if ($request->exists('description')) {
            $image->description = $request->input('description');
        }

        $image->tags()->detach(); // обновляем связь many to many
        if ($request->exists('tags')) {
            foreach ($request->input('tags') as $tagName) {
                /** @var Tag $tag */
                $tag = Tag::firstOrNew(['name' => $tagName]);
                $tag->save();
                $image->tags()->attach($tag->id);
            }
        }
        $image->save();

        return response()->json($image, 200);
    }

    /**
     * @param string $guid
     * @return JsonResponse
     */
    public function delete(string $guid): JsonResponse
    {
        /** @var Image $image */
        $image = Image::find($guid);
        if (null !== $image) {
            $image->tags()->detach(); // не забываем открепить теги
        }

        return response()->json(['success' => Image::destroy($guid)], 200);
    }
}