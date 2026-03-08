<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['address_id']);
            });
        }

        Schema::dropIfExists('addresses');
    }
};
