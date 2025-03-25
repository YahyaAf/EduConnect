<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Exécuter la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->decimal('amount', 10, 2); 
            $table->string('payment_status'); 
            $table->string('payment_method')->default('paypal'); 
            $table->string('transaction_id')->nullable();
            $table->string('paypal_response')->nullable(); 
            $table->timestamps();

            // Définir les clés étrangères
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Annuler la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

