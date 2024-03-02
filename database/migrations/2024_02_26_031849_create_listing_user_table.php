<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('listing_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('shortlister')->default(false);
            $table->timestamps();

            //when the listing has delete from listing table the records related to that listing id should delete in lising_user table
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
            //when the user has delete from user table the records related to that user id should delete in lising_user table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_user');
    }
};
