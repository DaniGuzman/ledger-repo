<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ledger_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_id');
            $table->foreignId('currency_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledger_currencies');
    }
};
