<?php

namespace Tests\Unit\Models;

use App\User;
use App\Category;
use App\Transaction;
use Tests\TestCase as TestCase;
use Illuminate\Support\Collection;
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

    /** @test */
    public function a_category_has_for_user_global_scope()
    {
        $categoryOwner = $this->loginAsUser();
        $category = factory(Category::class)->create([
            'creator_id' => $categoryOwner->id,
        ]);
        $othersCategory = factory(Category::class)->create();

        $this->assertCount(1, Category::get());
    }

    /** @test */
    public function a_category_has_many_transactions_relation()
    {
        $categoryOwner = $this->loginAsUser();
        $category = factory(Category::class)->create([
            'creator_id' => $categoryOwner->id,
        ]);
        $transaction = factory(Transaction::class)->create([
            'category_id' => $category->id,
            'creator_id' => $categoryOwner->id,
        ]);

        $this->assertInstanceOf(Collection::class, $category->transactions);
        $this->assertInstanceOf(Transaction::class, $category->transactions->first());
    }

    /** @test */
    public function a_category_has_name_label_attribute()
    {
        $category = factory(Category::class)->make();

        $nameLabel = '<span class="badge" style="background-color: '.$category->color.'">'.$category->name.'</span>';
        $this->assertEquals($nameLabel, $category->name_label);
    }
}
