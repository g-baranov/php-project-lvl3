<?php

namespace Tests\Feature;

class MainPageTest extends AbstractFeatureTestCase
{
    public function testMain(): void
    {
        $response = $this->get(route('main'));
        self::assertEquals(200, $response->getStatusCode());
    }
}
