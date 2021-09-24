<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UrlCheckController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function store(int $id): Response
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);

        try {
            $response = Http::get($url->name);

            $page = new Document($response->body());
            $h1 = optional($page->first('h1'))->text();
            $keywords = optional($page->first('meta[name=keywords]'))->getAttribute('content', null);
            $description = optional($page->first('meta[name=description]'))->getAttribute('content', null);

            DB::table('url_checks')->insert([
                'url_id' => $url->id,
                'status_code' => $response->status(),
                'h1' => $h1,
                'keywords' => $keywords,
                'description' => $description,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        } catch (HttpClientException $exception) {
            flash($exception->getMessage())->error();
            return Redirect::route('urls.show', ['url' => $url->id]);
        }

        flash("Created successfully")->success();
        return Redirect::route('urls.show', ['url' => $url->id]);
    }
}
