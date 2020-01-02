<?php

namespace Tests\Unit;

use Tests\TestCase;

class ZResetDBTest extends TestCase
{
    public function testResetDB()
    {
        $this->assertTrue(true);
        $this->artisan('migrate:fresh');
    }
}