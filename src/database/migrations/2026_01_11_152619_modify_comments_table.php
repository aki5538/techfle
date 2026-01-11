<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            // 新しいカラムを追加（DBAL不要）
            $table->string('comment')->nullable();
        });
        // 旧カラムの内容を新カラムへコピー
        DB::table('comments')->update([
            'comment' => DB::raw('content')
        ]);

        Schema::table('comments', function (Blueprint $table) {
            // 旧カラムを削除（DBAL不要）
            $table->dropColumn('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            // 元に戻すためのカラム追加
            $table->string('content')->nullable();
        });

        // データを戻す
        DB::table('comments')->update([
            'content' => DB::raw('comment')
        ]);

        Schema::table('comments', function (Blueprint $table) {
            // 新カラムを削除
            $table->dropColumn('comment');
        });
    }
}
