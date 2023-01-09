<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyColumnToMaangerOfferPayouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manager_offer_payouts', function (Blueprint $table) {
	        $table->string('currency')->default('USD')->after('payout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manager_offer_payouts', function (Blueprint $table) {
	        $table->dropColumn('currency');
        });
    }
}
