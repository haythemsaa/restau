<?php

namespace Tests\Feature\CRM;

use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerSegment;
use App\Models\CustomerVisit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_lists_customers_with_pagination()
    {
        Customer::factory()->count(20)->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'email',
                        'first_name',
                        'last_name',
                        'full_name',
                        'tier',
                        'lifetime_value',
                        'visit_count',
                    ],
                ],
                'current_page',
                'total',
            ])
            ->assertJsonCount(15, 'data'); // Default pagination is 15
    }

    /** @test */
    public function it_filters_customers_by_tier()
    {
        Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'tier' => 'vip',
        ]);

        Customer::factory()->count(5)->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers?tier=vip');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');
        $this->assertTrue(collect($data)->every(fn($c) => in_array($c['tier'], ['vip', 'super_vip'])));
    }

    /** @test */
    public function it_filters_customers_by_segment()
    {
        $segment = CustomerSegment::factory()->create([
            'business_id' => $this->business->id,
            'name' => 'VIP Customers',
        ]);

        $customersInSegment = Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
        ]);

        foreach ($customersInSegment as $customer) {
            $customer->segments()->attach($segment->id);
        }

        Customer::factory()->count(5)->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/customers?segment_id={$segment->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_filters_vip_only_customers()
    {
        Customer::factory()->count(3)->vip()->create([
            'business_id' => $this->business->id,
        ]);

        Customer::factory()->count(5)->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers?vip_only=1');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_filters_at_risk_customers()
    {
        Customer::factory()->count(2)->atRisk()->create([
            'business_id' => $this->business->id,
        ]);

        Customer::factory()->count(5)->create([
            'business_id' => $this->business->id,
            'last_visit_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers?at_risk_only=1');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_searches_customers_by_name_or_email()
    {
        Customer::factory()->create([
            'business_id' => $this->business->id,
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean.dupont@example.com',
        ]);

        Customer::factory()->create([
            'business_id' => $this->business->id,
            'first_name' => 'Marie',
            'last_name' => 'Martin',
            'email' => 'marie.martin@example.com',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers?search=Dupont');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.last_name', 'Dupont');
    }

    /** @test */
    public function it_shows_customer_with_details()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
        ]);

        CustomerVisit::factory()->count(5)->create([
            'customer_id' => $customer->id,
            'business_id' => $this->business->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/customers/{$customer->id}?include_rfm=1");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'full_name',
                    'tier',
                    'lifetime_value',
                    'latest_visits',
                ],
                'stats' => [
                    'rfm_score',
                    'average_spend',
                    'is_vip',
                    'at_risk',
                    'is_birthday',
                ],
            ]);
    }

    /** @test */
    public function it_creates_new_customer()
    {
        $customerData = [
            'email' => 'nouveau.client@example.com',
            'phone' => '+33612345678',
            'first_name' => 'Nouveau',
            'last_name' => 'Client',
            'birth_date' => '1990-05-15',
            'preferences' => [
                'dietary' => ['vegetarian'],
                'allergies' => [],
            ],
            'tags' => ['new_customer'],
            'language' => 'fr',
            'notes' => 'Client potentiel VIP',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/customers', $customerData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'email', 'full_name'],
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'nouveau.client@example.com',
            'first_name' => 'Nouveau',
            'last_name' => 'Client',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_for_customer_creation()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/customers', [
                // Missing required fields
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'first_name', 'last_name']);
    }

    /** @test */
    public function it_validates_unique_email()
    {
        Customer::factory()->create([
            'business_id' => $this->business->id,
            'email' => 'existing@example.com',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/customers', [
                'email' => 'existing@example.com',
                'first_name' => 'Test',
                'last_name' => 'User',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_updates_customer()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'first_name' => 'Original',
            'tier' => 'regular',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->putJson("/api/v1/customers/{$customer->id}", [
                'first_name' => 'Updated',
                'tier' => 'vip',
                'notes' => 'Promoted to VIP',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.first_name', 'Updated')
            ->assertJsonPath('data.tier', 'vip');

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => 'Updated',
            'tier' => 'vip',
        ]);
    }

    /** @test */
    public function it_deletes_customer()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->deleteJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
    }

    /** @test */
    public function it_gets_customer_segments()
    {
        CustomerSegment::factory()->count(5)->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers-segments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'criteria', 'customers_count'],
                ],
            ])
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_gets_at_risk_customers_list()
    {
        Customer::factory()->count(3)->atRisk()->create([
            'business_id' => $this->business->id,
        ]);

        Customer::factory()->count(5)->create([
            'business_id' => $this->business->id,
            'last_visit_at' => now()->subDays(10),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers-at-risk');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');
        foreach ($data as $customer) {
            $this->assertGreaterThanOrEqual(60, $customer['days_since_last_visit']);
        }
    }

    /** @test */
    public function it_gets_vip_customers_list()
    {
        Customer::factory()->count(4)->vip()->create([
            'business_id' => $this->business->id,
        ]);

        Customer::factory()->count(6)->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers-vips');

        $response->assertStatus(200)
            ->assertJsonCount(4, 'data');

        $data = $response->json('data');
        $this->assertTrue(collect($data)->every(fn($c) => in_array($c['tier'], ['vip', 'super_vip'])));
    }

    /** @test */
    public function it_gets_customers_with_birthdays_this_month()
    {
        Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'birth_date' => now()->day(15),
        ]);

        Customer::factory()->count(4)->create([
            'business_id' => $this->business->id,
            'birth_date' => now()->addMonths(2)->day(15),
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers-birthdays');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');
        foreach ($data as $customer) {
            $birthDate = new \DateTime($customer['birth_date']);
            $this->assertEquals(now()->month, $birthDate->format('m'));
        }
    }

    /** @test */
    public function it_requires_authentication()
    {
        $response = $this->getJson('/api/v1/customers');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_only_shows_customers_from_user_business()
    {
        $otherUser = User::factory()->create();
        $otherBusiness = Business::factory()->create(['user_id' => $otherUser->id]);

        Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
        ]);

        Customer::factory()->count(5)->create([
            'business_id' => $otherBusiness->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_prevents_accessing_other_users_customers()
    {
        $otherUser = User::factory()->create();
        $otherBusiness = Business::factory()->create(['user_id' => $otherUser->id]);

        $customer = Customer::factory()->create([
            'business_id' => $otherBusiness->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_supports_custom_pagination()
    {
        Customer::factory()->count(50)->create([
            'business_id' => $this->business->id,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson('/api/v1/customers?per_page=25');

        $response->assertStatus(200)
            ->assertJsonCount(25, 'data')
            ->assertJsonPath('total', 50);
    }
}
