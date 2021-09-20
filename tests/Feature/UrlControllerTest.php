<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;

class UrlControllerTest extends AbstractFeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::table('urls')->insert(['name' => 'http://example.com']);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        self::assertEquals(200, $response->getStatusCode());
    }

    public function testStore(): void
    {
        $result = ['name' => 'http://needtostore.com'];
        $response = $this->post(route('urls.store'), ['url' => $result]);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('urls', $result);
    }

    public function testCheck(): void
    {
        $url = DB::table('urls')->where(['name' => "http://example.com"])->first();
        $response = $this->post(route('urls.check', ['url' => $url->id]));
        $response->assertSessionHasNoErrors();

        $result = ['url_id' => $url->id];
        $this->assertDatabaseHas('url_checks', $result);
    }

    public function testShow(): void
    {
        $url = DB::table('urls')->where(['name' => "http://example.com"])->first();
        $response = $this->get(route('urls.show', ['url' => $url->id]));
        self::assertEquals(200, $response->getStatusCode());
    }
}
