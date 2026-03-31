<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegalComplianceTest extends TestCase
{
    public function test_legal_mentions_page_is_accessible(): void
    {
        $this->get(route('legal.mentions'))
            ->assertOk()
            ->assertSee('Mentions légales');
    }

    public function test_privacy_policy_page_is_accessible(): void
    {
        $this->get(route('legal.privacy'))
            ->assertOk()
            ->assertSee('Politique de confidentialité')
            ->assertSee('données uniquement quand un client remplit le formulaire');
    }

    public function test_home_displays_privacy_popup_content(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Informations sur les données personnelles')
            ->assertSee('Suppression possible sur simple demande');
    }
}
