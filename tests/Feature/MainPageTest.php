<?php

namespace Tests\Feature;

class MainPageTest extends AbstractFeatureTestCase
{
    public function testMain(): void
    {
        $response = $this->get(route('main'));
        $response->assertOk();
    }
}
