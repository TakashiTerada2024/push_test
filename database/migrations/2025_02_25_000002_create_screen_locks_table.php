<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('screen_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apply_id')
                ->constrained('applies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('screen_code', 50);
            $table->boolean('is_locked')->default(false);
            $table->foreignId('last_updated_by')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();

            $table->unique(['apply_id', 'screen_code']);
        });

        DB::statement("COMMENT ON TABLE screen_locks IS '画面ロック管理テーブル'");
        DB::statement("COMMENT ON COLUMN screen_locks.screen_code IS 'どの画面かを示すコード。URLと対応する文字列を保持。'");
        DB::statement("COMMENT ON COLUMN screen_locks.is_locked IS 'ロック状態'");
        DB::statement("COMMENT ON COLUMN screen_locks.last_updated_by IS '最終更新者。操作を行ったユーザのID。'");
    }

    public function down()
    {
        Schema::dropIfExists('screen_locks');
    }
}; 