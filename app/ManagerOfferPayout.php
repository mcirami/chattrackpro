<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManagerOfferPayout extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'offer_id',
		'payout',
	];

	public function User() {
		return $this->belongsTo(User::class);
	}
}
