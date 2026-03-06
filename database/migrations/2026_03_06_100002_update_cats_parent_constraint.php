<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE cats ALTER COLUMN cat_up_id TYPE BIGINT USING cat_up_id::bigint');
        }

        Schema::table('cats', function (Blueprint $table) {
            $table->index('cat_up_id', 'cats_cat_up_id_index');
            $table->foreign('cat_up_id', 'cats_cat_up_id_foreign')
                ->references('id')
                ->on('cats')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cats', function (Blueprint $table) {
            $table->dropForeign('cats_cat_up_id_foreign');
            $table->dropIndex('cats_cat_up_id_index');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE cats ALTER COLUMN cat_up_id TYPE INTEGER USING cat_up_id::integer');
        }
    }
};
