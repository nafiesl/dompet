<?php

namespace Tests\Feature;

use App\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ManageCategoriesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_see_category_list_in_category_index_page()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

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
            'color'       => '#00aabb',
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
            'color'       => '#00aabb',
        ]);
    }

    /** @test */
    public function user_can_edit_a_category_within_search_query()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

        $this->visit(route('categories.index'));
        $this->click('edit-category-'.$category->id);
        $this->seePageIs(route('categories.index', ['action' => 'edit', 'id' => $category->id]));

        $this->submitForm(trans('category.update'), [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
            'color'       => '#00aabb',
        ]);

        $this->seePageIs(route('categories.index'));

        $this->seeInDatabase('categories', [
            'name'        => 'Category 1 name',
            'description' => 'Category 1 description',
            'color'       => '#00aabb',
        ]);
    }

    /** @test */
    public function user_can_delete_a_category()
    {
        $user = $this->loginAsUser();
        $category = factory(Category::class)->create(['creator_id' => $user->id]);

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
