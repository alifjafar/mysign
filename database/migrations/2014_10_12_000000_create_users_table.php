<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->tinyInteger('isAdmin')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->longText('address')->nullable();
            $table->string('phone', 12)->nullable();
            $table->string('sign')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        User::insert([
            'name' => 'Admin',
            'email' => 'admin@mysign.com',
            'username' => 'admin',
            'isAdmin' => 1,
            'password' => Hash::make('admin123'),
            'address' => 'Jl. Telekomunikasi Bandung',
            'phone' => '081081081081',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
