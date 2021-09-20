<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class AbstractFeatureTestCase extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;
    use CreatesApplication;
}
