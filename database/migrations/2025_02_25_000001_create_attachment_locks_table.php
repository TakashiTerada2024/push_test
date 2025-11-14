<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attachment_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apply_id')
                ->constrained('applies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->smallInteger('attachment_type_id');
            $table->boolean('is_locked')->default(false);
            $table->foreignId('last_updated_by')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->timestamps();

            $table->unique(['apply_id', 'attachment_type_id']);
        });

        DB::statement("COMMENT ON TABLE attachment_locks IS '添付ファイルロック管理テーブル'");
        DB::statement("COMMENT ON COLUMN attachment_locks.attachment_type_id IS '添付ファイル種別ID'");
        DB::statement("COMMENT ON COLUMN attachment_locks.is_locked IS 'ロック状態'");
        DB::statement("COMMENT ON COLUMN attachment_locks.last_updated_by IS '最終更新者。操作を行ったユーザのID。'");
    }

    public function down()
    {
        Schema::dropIfExists('attachment_locks');
    }
}; 