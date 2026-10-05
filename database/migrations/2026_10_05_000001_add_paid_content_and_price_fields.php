<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                if (!Schema::hasColumn('quizzes', 'is_paid')) {
                    $table->boolean('is_paid')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('quizzes', 'price')) {
                    $table->decimal('price', 8, 2)->default(0.00)->after('is_paid');
                }
            });
        }

        if (Schema::hasTable('study_materials')) {
            Schema::table('study_materials', function (Blueprint $table) {
                if (!Schema::hasColumn('study_materials', 'is_paid')) {
                    $table->boolean('is_paid')->default(false)->after('is_active');
                }
                if (!Schema::hasColumn('study_materials', 'price')) {
                    $table->decimal('price', 8, 2)->default(0.00)->after('is_paid');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('quizzes')) {
            Schema::table('quizzes', function (Blueprint $table) {
                if (Schema::hasColumn('quizzes', 'price')) {
                    $table->dropColumn('price');
                }
                if (Schema::hasColumn('quizzes', 'is_paid')) {
                    $table->dropColumn('is_paid');
                }
            });
        }

        if (Schema::hasTable('study_materials')) {
            Schema::table('study_materials', function (Blueprint $table) {
                if (Schema::hasColumn('study_materials', 'price')) {
                    $table->dropColumn('price');
                }
                if (Schema::hasColumn('study_materials', 'is_paid')) {
                    $table->dropColumn('is_paid');
                }
            });
        }
    }
};
