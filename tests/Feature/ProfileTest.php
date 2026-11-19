<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk()
            ->assertSee('Profil akun')
            ->assertSee('Informasi pribadi')
            ->assertSee('Nomor telepon / WhatsApp')
            ->assertSee('Alamat lengkap')
            ->assertSee('Provinsi')
            ->assertSee('Kota/kabupaten')
            ->assertSee('DKI Jakarta')
            ->assertSee('Keamanan akun')
            ->assertSee('Hapus akun')
            ->assertSee('Simpan perubahan');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone' => '081388796229',
                'address' => 'Jl. Merdeka No. 10, RT 01/RW 02',
                'province' => 'Jawa Barat',
                'city' => 'Kota Bandung',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('081388796229', $user->phone);
        $this->assertSame('Jl. Merdeka No. 10, RT 01/RW 02', $user->address);
        $this->assertSame('Kota Bandung', $user->city);
        $this->assertSame('Jawa Barat', $user->province);
        $this->assertNull($user->email_verified_at);
    }

    public function test_profile_rejects_a_city_that_does_not_belong_to_the_selected_province(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from('/profile')
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'province' => 'Jawa Barat',
                'city' => 'Kota Surabaya',
            ])
            ->assertSessionHasErrors('city')
            ->assertRedirect('/profile');
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
