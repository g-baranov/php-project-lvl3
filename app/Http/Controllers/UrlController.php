<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $urls = DB::table('urls')->paginate(50);
        return view('url.index', ['urls' => $urls]);
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
     * @return View
     */
    public function show(int $id): View
    {
        $url = DB::table('urls')->where('id', $id)->first();
        return view('url.show', ['url' => $url]);
    }
}
