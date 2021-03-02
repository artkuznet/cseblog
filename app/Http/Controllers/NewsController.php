<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\{
    JsonResponse,
    Request
};
use App\News;
use Illuminate\Support\Facades\Validator;
use App\Image;

class NewsController extends Controller
{
    /**
     * @param string|null $slug
     * @return JsonResponse
     */
    public function get(string $slug = null): JsonResponse
    {
        $from = request()->exists('from') ? request()->input('from') : null;
        $to = request()->exists('to') ? request()->input('to') : null;
        $header = request()->exists('header') ? request()->input('header') : null;

        $news = News::get($slug, $from, $to, $header);
        if (null !== $slug && count($news) === 0) {
            abort(404); // если ничего не найдено, выдаем 404
        }

        return response()->json($news, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request): JsonResponse
    {
        $validator = Validator::make([
            'header' => $request->input('header'),
            'content' => $request->input('content'),
            'guid' => $request->input('guid'),
            'preview' => $request->input('preview'),
            'slug' => $request->input('slug'),
        ], [
            'header' => 'required',
            'content' => 'required',
            'guid' => 'required_without:preview|nullable|uuid',
            'preview' => 'required_without:guid|nullable|url',
            'slug' => 'alpha_dash|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $header = $request->input('header');
        $slug = $request->exists('slug')
            ? str_slug($request->input('slug'))
            : str_slug($header);
        $slug .= count(News::get($slug)) > 0 ? '-' . round(microtime(true) * 1000) : ''; // чтобы избежать повторяющихся слагов
        /** @var Image[] $images */
        $images = Image::where('guid', '=', $request->input('guid'))->get(); // если передали guid картинки, по ставим превью из галереи
        $news = new News([
            'slug' => $slug,
            'preview' => count($images) > 0
                ? $images[0]->img
                : $request->input('preview'), // иначе превью из параметров,
            'header' => $header,
            'content' => $request->input('content'),
        ]);
        $news->save();

        return response()->json($news, 200);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make([
            'guid' => $request->input('guid'),
            'preview' => $request->input('preview'),
            'slug' => $request->input('slug'),
        ], [
            'guid' => 'nullable|uuid',
            'preview' => 'nullable|url',
            'slug' => 'alpha_dash|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        /** @var News $news */
        $news = News::find($id);
        if (is_null($news)) {
            abort(404);
        }

        if ($request->exists('header')) {
            $news->header = $request->input('header');
        }
        if ($request->exists('content')) {
            $news->content = $request->input('content');
        }

        if ($request->exists('slug')) {
            $Slug = str_slug($request->input('slug'));
            $Slug .= count(News::get($Slug)) > 0 ? '-' . round(microtime(true) * 1000) : ''; // чтобы избежать повторяющихся слагов
            $news->slug = $Slug;
        }

        if ($request->exists('guid')) {
            /** @var Image[] $images */
            $images = Image::where('guid', '=', $request->input('guid'))->get();
            if (count($images) > 0) {
                $news->preview = $images[0]->img;
            }
        } else if ($request->exists('preview')) {
            $news->preview = $request->input('preview');
        }

        $news->save();

        return response()->json($news, 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        return response()->json(['success' => News::destroy($id)], 200);
    }
}
