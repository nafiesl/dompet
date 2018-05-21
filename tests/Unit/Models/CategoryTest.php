<?php

namespace Tests\Unit\Models;

use App\User;
use App\Category;
use Tests\TestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_category_has_belongs_to_creator_relation()
    {
        $category = factory(Category::class)->make();

        $this->assertInstanceOf(User::class, $category->creator);
        $this->assertEquals($category->creator_id, $category->creator->id);
    }
}
