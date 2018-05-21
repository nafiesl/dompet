<?php

namespace Tests\Unit\Policies;

use App\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase as TestCase;

class CategoryPolicyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_create_category()
    {
        $user = $this->createUser();
        $this->assertTrue($user->can('create', new Category));
    }

    /** @test */
    public function user_can_view_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create();
        $this->assertTrue($user->can('view', $category));
    }

    /** @test */
    public function user_can_update_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create();
        $this->assertTrue($user->can('update', $category));
    }

    /** @test */
    public function user_can_delete_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create();
        $this->assertTrue($user->can('delete', $category));
    }
}
