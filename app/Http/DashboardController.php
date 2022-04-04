<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\EOSCollection;
use App\Models\Items;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DashboardController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('layouts.dashboard');
    }

    public function showUpdateCard()
    {
        //TODO: вывод верстки
        return view('components.update_card_button', ['page' => 'Update Cards']);
    }

    public function updateCard(): JsonResponse
    {
        $atomicCollection = EOSCollection::getTemplates();
        foreach ($atomicCollection as $card) {
            if (empty(Cards::query()->where('template_id', $card['template_id'])->first()) && isset($card['name'])) {
                if (isset($card['immutable_data']['img'])) {
                    $imgCode = $card['immutable_data']['img'];
                } else {
                    $imgCode = $card['immutable_data']['backimg'];
                }
                $imgUrl = 'https://gateway.pinata.cloud/ipfs/'.$imgCode;
                $responseImg = Http::get($imgUrl);

                $extension = explode('/', $responseImg->header('Content-Type'))[1];
                $fileName = $imgCode.'.'.$extension;
                $filePreviewName = $imgCode.'_preview.'.$extension;
                Storage::disk('public')->put('cards/'.$fileName, $responseImg);
                $path = Storage::disk('public')->path('cards/'.$fileName);

                $img = Image::make($path);
                $img->resize(round($img->getWidth() / 4), round($img->getHeight() / 4));
                $img->save($img->dirname.'/'.$filePreviewName);

                $card = new Cards(
                    [
                        'name' => $card['name'],
                        'schema' => $card['schema']['schema_name'],
                        'template_id' => $card['template_id'],
                        'description' => $card['immutable_data']['description'] ?? null,
                        'image' => $fileName,
                        'image_preview' => $filePreviewName,
                        'active' => 0,
                    ]
                );
                $card->save();

                /*TODO: добавить условие для добавление в таблицу. Например смотреть по схеме*/
                (new Items(
                    [
                        'card_id' => $card['id']
                    ]
                ))->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}
