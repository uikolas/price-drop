<?php

namespace Tests;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function otherUser(): User
    {
        return User::factory()->make();
    }

    protected function createProduct(): Product
    {
        return Product::factory()->create(['name' => 'Some product name']);
    }
}
