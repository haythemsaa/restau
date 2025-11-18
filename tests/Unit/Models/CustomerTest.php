<?php

namespace Tests\Unit\Models;

use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\CustomerSegment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();
        $this->business = Business::factory()->create();
    }

    /** @test */
    public function it_calculates_rfm_score_correctly()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'visit_count' => 15,
            'lifetime_value' => 750,
            'last_visit_at' => now()->subDays(5),
        ]);

        $rfm = $customer->getRFMScore();

        $this->assertIsArray($rfm);
        $this->assertArrayHasKey('recency', $rfm);
        $this->assertArrayHasKey('frequency', $rfm);
        $this->assertArrayHasKey('monetary', $rfm);
        $this->assertArrayHasKey('total_score', $rfm);
        $this->assertArrayHasKey('segment', $rfm);

        // Scores should be between 1 and 5
        $this->assertGreaterThanOrEqual(1, $rfm['recency']);
        $this->assertLessThanOrEqual(5, $rfm['recency']);
        $this->assertGreaterThanOrEqual(1, $rfm['frequency']);
        $this->assertLessThanOrEqual(5, $rfm['frequency']);
        $this->assertGreaterThanOrEqual(1, $rfm['monetary']);
        $this->assertLessThanOrEqual(5, $rfm['monetary']);
    }

    /** @test */
    public function it_identifies_vip_customers()
    {
        $vipCustomer = Customer::factory()->vip()->create([
            'business_id' => $this->business->id,
        ]);

        $regularCustomer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
        ]);

        $this->assertTrue($vipCustomer->is_vip);
        $this->assertFalse($regularCustomer->is_vip);
    }

    /** @test */
    public function it_identifies_super_vip_customers()
    {
        $superVipCustomer = Customer::factory()->superVip()->create([
            'business_id' => $this->business->id,
        ]);

        $this->assertTrue($superVipCustomer->is_super_vip);
        $this->assertTrue($superVipCustomer->is_vip); // Super VIP are also VIP
    }

    /** @test */
    public function it_detects_at_risk_customers()
    {
        // Customer who hasn't visited in 60+ days
        $atRiskCustomer = Customer::factory()->atRisk()->create([
            'business_id' => $this->business->id,
        ]);

        // Recent customer
        $activeCustomer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'last_visit_at' => now()->subDays(10),
        ]);

        $this->assertTrue($atRiskCustomer->isAtRiskOfChurn());
        $this->assertFalse($activeCustomer->isAtRiskOfChurn());
    }

    /** @test */
    public function it_promotes_customer_to_vip()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
            'lifetime_value' => 600,
            'visit_count' => 12,
            'last_visit_at' => now()->subDays(3),
        ]);

        $promoted = $customer->promoteToVIP();

        $this->assertTrue($promoted);
        $this->assertEquals('vip', $customer->fresh()->tier);
    }

    /** @test */
    public function it_promotes_customer_to_super_vip()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'tier' => 'vip',
            'lifetime_value' => 1500,
            'visit_count' => 25,
            'last_visit_at' => now()->subDays(2),
        ]);

        $promoted = $customer->promoteToVIP();

        $this->assertTrue($promoted);
        $this->assertEquals('super_vip', $customer->fresh()->tier);
    }

    /** @test */
    public function it_does_not_promote_customer_with_low_metrics()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
            'lifetime_value' => 100,
            'visit_count' => 3,
        ]);

        $promoted = $customer->promoteToVIP();

        $this->assertFalse($promoted);
        $this->assertEquals('regular', $customer->fresh()->tier);
    }

    /** @test */
    public function it_calculates_average_spend_correctly()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'lifetime_value' => 500,
            'visit_count' => 10,
        ]);

        $this->assertEquals(50.00, $customer->average_spend);
    }

    /** @test */
    public function it_returns_zero_average_spend_for_no_visits()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'lifetime_value' => 0,
            'visit_count' => 0,
        ]);

        $this->assertEquals(0, $customer->average_spend);
    }

    /** @test */
    public function it_calculates_days_since_last_visit()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'last_visit_at' => now()->subDays(15),
        ]);

        $this->assertEquals(15, $customer->days_since_last_visit);
    }

    /** @test */
    public function it_returns_null_days_for_never_visited()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'last_visit_at' => null,
        ]);

        $this->assertNull($customer->days_since_last_visit);
    }

    /** @test */
    public function it_formats_full_name_correctly()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
        ]);

        $this->assertEquals('Jean Dupont', $customer->full_name);
    }

    /** @test */
    public function it_detects_birthday_this_month()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'birth_date' => now()->day(15),
        ]);

        $this->assertTrue($customer->isBirthday());
    }

    /** @test */
    public function it_does_not_detect_birthday_in_other_month()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'birth_date' => now()->addMonths(2)->day(15),
        ]);

        $this->assertFalse($customer->isBirthday());
    }

    /** @test */
    public function it_has_visits_relationship()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
        ]);

        CustomerVisit::factory()->count(5)->create([
            'customer_id' => $customer->id,
            'business_id' => $this->business->id,
        ]);

        $this->assertCount(5, $customer->visits);
    }

    /** @test */
    public function it_has_segments_relationship()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $segment = CustomerSegment::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $customer->segments()->attach($segment->id);

        $this->assertCount(1, $customer->segments);
        $this->assertEquals($segment->id, $customer->segments->first()->id);
    }

    /** @test */
    public function it_scopes_vip_customers()
    {
        Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'tier' => 'vip',
        ]);

        Customer::factory()->count(2)->create([
            'business_id' => $this->business->id,
            'tier' => 'regular',
        ]);

        $vipCustomers = Customer::vip()->get();

        $this->assertCount(3, $vipCustomers);
        $this->assertTrue($vipCustomers->every(fn($c) => $c->is_vip));
    }

    /** @test */
    public function it_scopes_at_risk_customers()
    {
        Customer::factory()->count(2)->atRisk()->create([
            'business_id' => $this->business->id,
        ]);

        Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'last_visit_at' => now()->subDays(10),
        ]);

        $atRiskCustomers = Customer::atRisk()->get();

        $this->assertCount(2, $atRiskCustomers);
    }

    /** @test */
    public function it_scopes_birthday_this_month()
    {
        Customer::factory()->count(2)->create([
            'business_id' => $this->business->id,
            'birth_date' => now()->day(15),
        ]);

        Customer::factory()->count(3)->create([
            'business_id' => $this->business->id,
            'birth_date' => now()->addMonths(2)->day(15),
        ]);

        $birthdayCustomers = Customer::birthdayThisMonth()->get();

        $this->assertCount(2, $birthdayCustomers);
    }

    /** @test */
    public function it_uses_uuid_as_primary_key()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $this->assertIsString($customer->id);
        $this->assertEquals(36, strlen($customer->id)); // UUID length
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $customer->id
        );
    }

    /** @test */
    public function it_soft_deletes_customers()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
        ]);

        $customer->delete();

        $this->assertSoftDeleted($customer);
        $this->assertCount(0, Customer::all());
        $this->assertCount(1, Customer::withTrashed()->get());
    }

    /** @test */
    public function it_casts_preferences_to_array()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'preferences' => [
                'dietary' => ['vegetarian'],
                'allergies' => ['nuts'],
                'favorite_dishes' => ['Pizza Margherita'],
            ],
        ]);

        $this->assertIsArray($customer->preferences);
        $this->assertEquals(['vegetarian'], $customer->preferences['dietary']);
    }

    /** @test */
    public function it_casts_tags_to_array()
    {
        $customer = Customer::factory()->create([
            'business_id' => $this->business->id,
            'tags' => ['regular', 'food_blogger'],
        ]);

        $this->assertIsArray($customer->tags);
        $this->assertContains('regular', $customer->tags);
        $this->assertContains('food_blogger', $customer->tags);
    }
}
