<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $urls = DB::table('urls')->paginate(50);
        $lastUrlChecks = DB::table('url_checks')
            ->distinct('url_id', 'id')
            ->get()
            ->keyBy('url_id')
        ; //prevent n+1

        return view('url.index', ['urls' => $urls, 'lastUrlChecks' => $lastUrlChecks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $validation = Validator::make($request->all(), [
            'url.name' => 'required|max:255|url',
        ]);
        if ($validation->fails()) {
            flash("Invalid url")->error();
            return Redirect::route('main')
                ->withErrors($validation)
                ->withInput();
        }

        $urlRaw = $request->get('url')['name'];
        $urlParts = parse_url($urlRaw);
        $host = mb_strtolower("{$urlParts['scheme']}://{$urlParts['host']}");

        $url = DB::table('urls')->where('name', $host)->first();

        if ($url) {
            flash("The url already exists id#{$url->id}")->error();
            return Redirect::route('main')->withInput();
        }

        DB::table('urls')->insert([
            'name' => $host,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        flash("Created successfully")->success();
        return Redirect::route('main')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|Response
     */
    public function show(int $id)
    {
        $url = DB::table('urls')->where('id', $id)->first();
        if (!$url) {
            flash("Url id#{$id} was not found")->error();
            return Redirect::route('main')->withInput();
        }

        $urlChecks = DB::table('url_checks')
            ->where('url_id', $url->id)
            ->orderByDesc('id')
            ->paginate(50);
        return view('url.show', ['url' => $url, 'urlChecks' => $urlChecks]);
    }


    /**
     * @param int $id
     * @return Response
     */
    public function check(int $id): Response
    {
        $url = DB::table('urls')->find($id);

        if (!$url) {
            flash("Url id#{$id} was not found")->error();
            return Redirect::route('main')->withInput();
        }
        DB::table('url_checks')->insert( [
            'url_id' => $url->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        flash("Created successfully")->success();
        return Redirect::route('urls.show', ['url' => $url->id])->withInput();
    }
}
