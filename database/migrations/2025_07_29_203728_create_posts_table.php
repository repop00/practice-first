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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email');
            $table->text('postcontent');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->nullable();
            // $table->softDeletes(); // This will add a deleted_at column for soft deletes
            // $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Assuming you have a users table and want to link posts to users
            // // If you don't have a users table, you can remove this line or adjust it accordingly.
            // // $table->foreign
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
