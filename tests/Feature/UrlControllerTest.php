<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        $result = ['name' => 'http://needtostore.com'];
        $response = $this->post(route('urls.store'), ['url' => $result]);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('urls', $result);
    }

    public function testCheck(): void
    {
        /** @var object|null $url */
        $url = DB::table('urls')->where(['name' => "http://example.com"])->first();
        static::assertNotNull($url);
        $urlId = $url->id;

        $filePath = __DIR__ . '/../fixtures/fake.html';
        $fileContent = file_get_contents($filePath);
        if ($fileContent === false) {
            throw new Exception('File not found');
        }

        Http::fake(fn() => Http::response($fileContent, 200));

        $response = $this->post(route('urls.check', ['url' => $urlId]));
        $response->assertSessionHasNoErrors();

        $result = [
            'url_id'      => $urlId,
            'status_code' => 200,
            'h1'          => 'First h1',
            'keywords'    => 'meta keywords content',
            'description' => 'meta description content'
        ];
        $this->assertDatabaseHas('url_checks', $result);
    }

    public function testShow(): void
    {
        /** @var object|null $url */
        $url = DB::table('urls')->where(['name' => "http://example.com"])->first();
        static::assertNotNull($url);
        $urlId = $url->id;

        $response = $this->get(route('urls.show', ['url' => $urlId]));
        $response->assertOk();
    }
}
