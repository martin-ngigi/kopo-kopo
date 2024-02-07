<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('transaction_id')->primary(); // Use transaction_id as primary key
            $table->uuid('account_id'); // Foreign key
            // $table->string('transaction_id')->primary(); // Use transaction_id as primary key
            // $table->string('account_id'); // Foreign key
            $table->integer('amount');
            $table->timestamp('created_at');

            $table->foreign('account_id')
            ->references('account_id')
            ->on('accounts')
            ->onDelete('cascade'); // This line sets up cascade deletion. The foreign association enables cascade deletion
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
