<?php

namespace Tests\Feature;

use App\Models\Membership;
use App\Models\MembershipOrder;
use App\Models\Quiz;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MembershipSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_membership_order()
    {
        $response = $this->postJson(route('membership.order'));
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_create_membership_order()
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->postJson(route('membership.order'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'amount' => 4900,
                'currency' => 'INR',
            ]);

        $this->assertDatabaseHas('membership_orders', [
            'user_id' => $user->id,
            'amount' => 49.00,
            'status' => 'created',
        ]);
    }

    public function test_payment_verification_activates_30_days_membership()
    {
        config(['services.razorpay.secret' => 'test_razorpay_secret']);

        $user = User::factory()->create(['role' => 'student']);
        $order = MembershipOrder::create([
            'order_number' => 'ORD-TEST-123',
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_test_123',
            'amount' => 49.00,
            'currency' => 'INR',
            'status' => 'created',
        ]);

        $secret = config('services.razorpay.secret');
        $signature = hash_hmac('sha256', 'order_test_123|pay_test_456', $secret);

        $response = $this->actingAs($user)->postJson(route('membership.verify'), [
            'membership_order_id' => $order->id,
            'razorpay_payment_id' => 'pay_test_456',
            'razorpay_order_id' => 'order_test_123',
            'razorpay_signature' => $signature,
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('membership_orders', [
            'id' => $order->id,
            'status' => 'paid',
            'razorpay_payment_id' => 'pay_test_456',
        ]);

        $this->assertTrue($user->fresh()->hasActiveMembership());

        $membership = Membership::where('user_id', $user->id)->first();
        $this->assertNotNull($membership);
        $this->assertEquals(30, (int)round($membership->starts_at->diffInDays($membership->expires_at)));
    }

    public function test_membership_renewal_extends_expiration_date()
    {
        config(['services.razorpay.secret' => 'test_razorpay_secret']);

        $user = User::factory()->create(['role' => 'student']);
        
        // Existing active membership valid for 10 more days
        $existing = Membership::create([
            'user_id' => $user->id,
            'starts_at' => now(),
            'expires_at' => now()->addDays(10),
            'status' => 'active',
            'amount_paid' => 49.00,
        ]);

        $order = MembershipOrder::create([
            'order_number' => 'ORD-TEST-999',
            'user_id' => $user->id,
            'razorpay_order_id' => 'order_test_999',
            'amount' => 49.00,
            'currency' => 'INR',
            'status' => 'created',
        ]);

        $secret = config('services.razorpay.secret');
        $signature = hash_hmac('sha256', 'order_test_999|pay_test_888', $secret);

        $this->actingAs($user)->postJson(route('membership.verify'), [
            'membership_order_id' => $order->id,
            'razorpay_payment_id' => 'pay_test_888',
            'razorpay_order_id' => 'order_test_999',
            'razorpay_signature' => $signature,
        ]);

        $updatedMembership = Membership::where('user_id', $user->id)->latest('expires_at')->first();
        // Should now be valid for ~40 days from now (10 existing + 30 new)
        $this->assertGreaterThan(38, (int)round(now()->diffInDays($updatedMembership->expires_at)));
    }

    public function test_non_member_cannot_access_membership_required_quiz()
    {
        $subject = Subject::create(['name' => 'Library Science', 'slug' => 'library-science']);
        $quiz = Quiz::create([
            'subject_id' => $subject->id,
            'title' => 'Exclusive Member Mock Test',
            'slug' => 'exclusive-mock-test',
            'access_type' => 'membership',
            'is_active' => true,
            'type' => 'mock',
            'duration_minutes' => 30,
        ]);

        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->get(route('tests.show', $quiz->id));
        $response->assertRedirect(route('membership.index'));
    }

    public function test_active_member_can_access_membership_required_quiz()
    {
        $subject = Subject::create(['name' => 'Library Science', 'slug' => 'library-science']);
        $quiz = Quiz::create([
            'subject_id' => $subject->id,
            'title' => 'Exclusive Member Mock Test 2',
            'slug' => 'exclusive-mock-test-2',
            'access_type' => 'membership',
            'is_active' => true,
            'type' => 'mock',
            'duration_minutes' => 30,
        ]);

        $q = $quiz->questions()->create(['question_text' => 'Sample Q1?', 'order' => 1]);
        $q->options()->createMany([
            ['option_text' => 'A', 'is_correct' => true, 'order' => 1],
            ['option_text' => 'B', 'is_correct' => false, 'order' => 2],
        ]);

        $user = User::factory()->create(['role' => 'student']);
        Membership::create([
            'user_id' => $user->id,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'status' => 'active',
            'price' => 49.00,
        ]);

        $response = $this->actingAs($user)->get(route('tests.show', $quiz->id));
        $response->assertStatus(200);
    }

    public function test_guest_can_access_free_quiz()
    {
        $subject = Subject::create(['name' => 'Library Science', 'slug' => 'library-science']);
        $quiz = Quiz::create([
            'subject_id' => $subject->id,
            'title' => 'Free Starter Quiz',
            'slug' => 'free-starter-quiz',
            'access_type' => 'free',
            'is_active' => true,
            'type' => 'mock',
            'duration_minutes' => 15,
        ]);

        $q = $quiz->questions()->create(['question_text' => 'Sample Q1?', 'order' => 1]);
        $q->options()->createMany([
            ['option_text' => 'A', 'is_correct' => true, 'order' => 1],
            ['option_text' => 'B', 'is_correct' => false, 'order' => 2],
        ]);

        $response = $this->get(route('tests.show', $quiz->id));
        $response->assertStatus(200);
    }

    public function test_admin_can_grant_and_revoke_free_membership()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        // Grant 1 Month Free
        $response = $this->actingAs($admin)->post(route('admin.students.grant_membership', $student->id));
        $response->assertRedirect();

        $this->assertTrue($student->fresh()->hasActiveMembership());

        // Revoke Membership
        $response = $this->actingAs($admin)->post(route('admin.students.revoke_membership', $student->id));
        $response->assertRedirect();

        $this->assertFalse($student->fresh()->hasActiveMembership());
    }
}
