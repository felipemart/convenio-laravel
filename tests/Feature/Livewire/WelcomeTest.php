<?php

declare(strict_types = 1);

use App\Livewire\Welcome;

it('renders the welcome component', function (): void {
    Livewire::test(Welcome::class)
        ->assertStatus(200)
        ->assertViewIs('livewire.welcome');
});
