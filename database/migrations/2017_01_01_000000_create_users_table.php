<?php

use App\User;
use Illuminate\Support\Facades\DB;
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
            $table->string('username')->unique();
            $table->string('name');
            $table->string('password');
            $table->tinyInteger('is_active');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
        $admin = new User();
        $admin->username = 'admin';
        $admin->name = 'admin';
        $admin->password = bcrypt('12345678');
        $admin->save();
        $now = now();
        DB::unprepared("INSERT INTO `oauth_clients`
            (
                `name`,
                `secret`,
                `redirect`,
                `personal_access_client`,
                `password_client`,
                `revoked`,
                `created_at`,
                `updated_at`
            )
            VALUES(
                'vue client',
                'voip.vue.secret',
                'http://localhost',
                '0',
                '1',
                '0',
                '$now',
                '$now'
            )
        ");
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
