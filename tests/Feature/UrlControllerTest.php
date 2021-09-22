<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
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
        $response->assertOk();
    }

    public function testStore(): void
    {
        $urlData = ['name' => 'http://needtostore.com'];
        $response = $this->post(route('urls.store'), ['url' => $urlData]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        /** @var Response $response - linter fix */
        $this->followRedirects($response)->assertSeeText($urlData['name']);

        $this->assertDatabaseHas('urls', $urlData);
    }

    public function testShow(): void
    {
        /** @var object|null $url */
        $url = DB::table('urls')->where(['name' => "http://example.com"])->first();
        static::assertNotNull($url);

        $response = $this->get(route('urls.show', ['url' => $url->id]));
        $response->assertOk();
        $response->assertSeeText($url->name);
    }
}
