<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase as TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageCategoriesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_category_list_in_category_index_page()
    {
        $category = factory(Category::class)->create();

        $this->loginAsUser();
        $this->visit(route('categories.index'));
        $this->see($category->name);
    }

    /** @test */
    public function user_can_create_a_category()
    {
        $this->loginAsUser();
        $this->visit(route('categories.index'));

        $this->click(trans('category.create'));
        $this->seePageIs(route('categories.index', ['action' => 'create']));

        $this->submitForm(trans('category.create'), [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
        ]);
    }

    /** @test */
    public function user_can_edit_a_category_within_search_query()
    {
        $this->loginAsUser();
        $category = factory(Category::class)->create(['name' => 'Testing 123']);

        $this->visit(route('categories.index', ['q' => '123']));
        $this->click('edit-category-'.$category->id);
        $this->seePageIs(route('categories.index', ['action' => 'edit', 'id' => $category->id]));

        $this->submitForm(trans('category.update'), [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
        ]);
    }

    /** @test */
    public function user_can_delete_a_category()
    {
        $this->loginAsUser();
        $category = factory(Category::class)->create();

        $this->visit(route('categories.index', ['action' => 'edit', 'id' => $category->id]));
        $this->click('del-category-'.$category->id);
        $this->seePageIs(route('categories.index', ['action' => 'delete', 'id' => $category->id]));

        $this->seeInDatabase('categories', [
            'id' => $category->id,
        ]);

        $this->press(trans('app.delete_confirm_button'));

        $this->dontSeeInDatabase('categories', [
            'id' => $category->id,
        ]);
    }
}
