<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subjects') && !Schema::hasColumn('subjects', 'sort_order')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }

        if (Schema::hasTable('topics') && !Schema::hasColumn('topics', 'sort_order')) {
            Schema::table('topics', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }

        if (Schema::hasTable('study_materials') && !Schema::hasColumn('study_materials', 'sort_order')) {
            Schema::table('study_materials', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }

        if (Schema::hasTable('videos') && !Schema::hasColumn('videos', 'sort_order')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }

        if (Schema::hasTable('quizzes') && !Schema::hasColumn('quizzes', 'sort_order')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
        Schema::table('study_materials', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
