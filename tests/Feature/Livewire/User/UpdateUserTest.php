<?php

declare(strict_types = 1);

use App\Livewire\User\Update;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

test('should access the user profile', function (): void {
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('admin')->create();

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->created_at->format('d/m/Y'))
        ->assertOk();
});

test('should access the deleted user profile and show the deletion date', function (): void {
    $admin = User::factory()->withRoles('admin')->create();

    $userEdit = User::factory()->withRoles('admin')->create(
        [
            'restored_at' => now(),
        ]
    );

    actingAs($admin);
    Livewire::test(Update::class, ['id' => $userEdit->id])
        ->assertSee($userEdit->name)
        ->assertSee($userEdit->email)
        ->assertSee($userEdit->role->name)
        ->assertSee($userEdit->created_at->format('d/m/Y'))
        ->assertSee($userEdit->restored_at->format('d/m/Y'))
        ->assertOk();
});
