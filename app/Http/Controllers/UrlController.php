<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
