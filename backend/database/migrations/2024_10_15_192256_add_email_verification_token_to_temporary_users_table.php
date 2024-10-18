<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailVerificationColumnsToTemporaryUsersTable extends Migration
{
    public function up()
    {
        Schema::table('temporary_users', function (Blueprint $table) {
            $table->string('email_verification_token')->nullable(); // Add this line
            $table->timestamp('email_verified_at')->nullable(); // Add this line
        });
    }

    public function down()
    {
        Schema::table('temporary_users', function (Blueprint $table) {
            $table->dropColumn('email_verification_token'); // Drop this column
            $table->dropColumn('email_verified_at'); // Drop this column
        });
    }
}
