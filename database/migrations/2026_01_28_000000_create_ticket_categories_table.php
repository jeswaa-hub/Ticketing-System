<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->timestamps();
        });

        $now = now();
        DB::table('ticket_categories')->insert([
            ['key' => 'access', 'label' => 'Access Request', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'incident', 'label' => 'Incident', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'service', 'label' => 'Service Request', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'maintenance', 'label' => 'Maintenance', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'other', 'label' => 'Other', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
