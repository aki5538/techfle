<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    public function up()
    {
        // postal_code を nullable で作り直す
        if (Schema::hasColumn('users', 'postal_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('postal_code');
            });
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('postal_code', 8)->nullable();
        });

        // address を nullable で作り直す
        if (Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
        Schema::table('users', function (Blueprint $table) {
            $table->string('address', 255)->nullable();
        });

        // email_verified_at がなければ追加
        if (!Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            });
        }

        // remember_token がなければ追加
        if (!Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->rememberToken();
            });
        }
    }

    public function down()
    {
        // postal_code を削除
        if (Schema::hasColumn('users', 'postal_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('postal_code');
            });
        }

        // address を削除
        if (Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }

        // email_verified_at を削除
        if (Schema::hasColumn('users', 'email_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('email_verified_at');
            });
        }

        // remember_token を削除
        if (Schema::hasColumn('users', 'remember_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }
    }
}