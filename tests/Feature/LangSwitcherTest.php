<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LangSwitcherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_switch_en_lang()
    {
        $user = $this->loginAsUser();

        $this->visitRoute('home');
        $this->seeElement('button', ['id' => 'lang_en']);

        $this->press('lang_en');

        $this->seeInSession('lang', 'en');
    }
}
