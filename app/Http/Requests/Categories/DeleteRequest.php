<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', $this->route('category'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'delete_transactions' => 'required',
        ];
    }

    /**
     * Delete category from database.
     *
     * @return bool
     */
    public function delete()
    {
        $category = $this->route('category');

        if ($this->get('delete_transactions') == 1) {
            $category->transactions()->delete();
        } else {
            $category->transactions()->update(['category_id' => null]);
        }

        if ($this->get('category_id') == $category->id) {
            return $category->delete();
        }

        return false;
    }
}
