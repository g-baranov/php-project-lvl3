<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DiDom\Document;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        $urls = DB::table('urls')->paginate(50);
        $lastUrlChecks = DB::table('url_checks')
            ->distinct('url_id')
            ->orderBy('url_id')
            ->latest()
            ->get()
            ->keyBy('url_id')
        ; //prevent n+1

        return response()->view('url.index', ['urls' => $urls, 'lastUrlChecks' => $lastUrlChecks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $urlData = $request->get('url');
        $validation = Validator::make($urlData, [
            'name' => 'required|max:255|url',
        ]);
        if ($validation->fails()) {
            flash("Invalid url")->error();
            return Redirect::route('main')
                ->withErrors($validation)
                ->withInput();
        }

        $urlRaw = $urlData['name'];
        $urlParts = parse_url($urlRaw);
        $normalizedUrl = mb_strtolower("{$urlParts['scheme']}://{$urlParts['host']}");

        $url = DB::table('urls')->where('name', $normalizedUrl)->first();

        if (!is_null($url)) {
            flash("The url already exists id#{$url->id}")->info();
            return Redirect::route('urls.show', ['url' => $url->id]);
        }

        $createdId = DB::table('urls')->insertGetId([
            'name' => $normalizedUrl,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        flash("Created successfully")->success();
        return Redirect::route('urls.show', ['url' => $createdId]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $url = DB::table('urls')->find($id);
        abort_unless($url, 404);

        $urlChecks = DB::table('url_checks')
            ->where('url_id', $url->id)
            ->orderByDesc('id')
            ->paginate(50);

        return response()->view('url.show', ['url' => $url, 'urlChecks' => $urlChecks]);
    }
}
