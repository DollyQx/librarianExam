<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add access_type column to content tables
        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                if (!Schema::hasColumn('quizzes', 'access_type')) {
                    $table->string('access_type')->default('free')->after('is_active');
                }
            });
            // Migrate existing is_paid data
            DB::table('quizzes')->where('is_paid', true)->update(['access_type' => 'membership']);
        }

        if (Schema::hasTable('study_materials')) {
            Schema::table('study_materials', function (Blueprint $table) {
                if (!Schema::hasColumn('study_materials', 'access_type')) {
                    $table->string('access_type')->default('free')->after('is_active');
                }
            });
            // Migrate existing is_paid data
            DB::table('study_materials')->where('is_paid', true)->update(['access_type' => 'membership']);
        }

        if (Schema::hasTable('videos')) {
            Schema::table('videos', function (Blueprint $table) {
                if (!Schema::hasColumn('videos', 'access_type')) {
                    $table->string('access_type')->default('free')->after('is_active');
                }
            });
        }

        // 2. Create memberships table
        if (!Schema::hasTable('memberships')) {
            Schema::create('memberships', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('plan_name')->default('Studyly Membership');
                $table->decimal('price', 8, 2)->default(49.00);
                $table->string('status')->default('active'); // active, expired, revoked
                $table->string('payment_method')->default('razorpay'); // razorpay, admin_granted
                $table->string('razorpay_order_id')->nullable();
                $table->string('razorpay_payment_id')->nullable();
                $table->timestamp('starts_at');
                $table->timestamp('expires_at');
                $table->timestamps();
            });
        }

        // 3. Create membership_orders table
        if (!Schema::hasTable('membership_orders')) {
            Schema::create('membership_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 8, 2)->default(49.00);
                $table->string('currency', 10)->default('INR');
                $table->string('razorpay_order_id')->nullable();
                $table->string('razorpay_payment_id')->nullable();
                $table->string('razorpay_signature')->nullable();
                $table->string('status')->default('created'); // created, paid, failed
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_orders');
        Schema::dropIfExists('memberships');

        if (Schema::hasTable('quizzes') && Schema::hasColumn('quizzes', 'access_type')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('access_type');
            });
        }

        if (Schema::hasTable('study_materials') && Schema::hasColumn('study_materials', 'access_type')) {
            Schema::table('study_materials', function (Blueprint $table) {
                $table->dropColumn('access_type');
            });
        }

        if (Schema::hasTable('videos') && Schema::hasColumn('videos', 'access_type')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropColumn('access_type');
            });
        }
    }
};
