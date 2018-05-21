<?php

namespace Tests\Unit\Policies;

use App\Category;
use Tests\TestCase;
use App\Transaction;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
    public function user_can_only_update_their_own_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($user->can('update', $category));
        $this->assertFalse($user->can('update', $othersCategory));
    }

    /** @test */
    public function user_can_only_delete_their_own_category()
    {
        $user = $this->createUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);
        $othersCategory = factory(Category::class)->create();

        $this->assertTrue($user->can('delete', $category));
        $this->assertFalse($user->can('delete', $othersCategory));
    }

    /** @test */
    public function user_cannot_delete_used_category()
    {
        $user = $this->createUser();
        $usedCategory = factory(Category::class)->create(['creator_id' => $user->id]);
        $transaction = factory(Transaction::class)->create([
            'creator_id'  => $user->id,
            'category_id' => $usedCategory->id,
        ]);

        $unusedCategory = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->assertFalse($user->can('delete', $usedCategory));
        $this->assertTrue($user->can('delete', $unusedCategory));
    }
}
