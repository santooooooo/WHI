<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'profile', function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(User::class)->unique();
                $table->string('icon')->nullable();
                $table->text('career')->nullable();
                $table->string('title')->nullable();
                $table->text('text')->nullable();
                $table->string('mail')->nullable();
                $table->string('twitter')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile');
    }
}