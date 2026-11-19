<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_inventory_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/inventory');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Stok & Sparepart');
    }

    public function test_admin_can_create_new_spare_part()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $partData = [
            'part_code' => 'PRT-TEST-001',
            'name' => 'Busi Iridium Special Test',
            'category' => 'Kelistrikan & Busi',
            'stock' => 15,
            'min_stock' => 3,
            'purchase_price' => 50000,
            'selling_price' => 75000,
            'unit' => 'Pcs',
            'supplier' => 'PT Test Supplier',
            'description' => 'Busi pengujian fitur',
        ];

        $response = $this->actingAs($admin)->post('/admin/inventory', $partData);

        $response->assertRedirect();
        $this->assertDatabaseHas('inventories', [
            'part_code' => 'PRT-TEST-001',
            'name' => 'Busi Iridium Special Test',
        ]);
    }

    public function test_customer_can_view_and_search_inventory()
    {
        $user = User::factory()->create(['role' => 'pelanggan']);
        Inventory::create([
            'part_code' => 'PRT-OLI-888',
            'name' => 'Oli Enduro 4T Sport',
            'category' => 'Oli & Cairan',
            'stock' => 10,
            'min_stock' => 2,
            'purchase_price' => 40000,
            'selling_price' => 55000,
            'unit' => 'Botol',
        ]);

        $response = $this->actingAs($user)->get('/inventaris?search=Enduro');

        $response->assertStatus(200);
        $response->assertSee('Oli Enduro 4T Sport');
    }
}

