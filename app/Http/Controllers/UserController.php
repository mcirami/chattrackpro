<?php

namespace App\Http\Controllers;

use App\Privilege;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LeadMax\TrackYourStats\Table\Paginate;

class UserController extends Controller
{

    public function viewManagersAffiliates($id)
    {
        $manager = User::myUsers()->withRole(Privilege::ROLE_MANAGER)->findOrFail($id);


        $affiliates = $manager->users()->withRole(Privilege::ROLE_AFFILIATE)->with('referrer');

        $paginate = new Paginate(request('rpp',10), $affiliates->count());

        $affiliates = $affiliates->paginate(request('rpp', 10));

        return view('user.managers-affiliates', compact('manager', 'affiliates','paginate'));
    }

    public function viewManageUsers()
    {
        $this->validate(request(), [
            'showInactive' => 'numeric|min:0|max:1'
        ]);

        $users = User::myUsers()->withRole(request('role', Privilege::ROLE_AFFILIATE))->with('referrer');


        if (request('showInactive', 0) == 1) {
            $users->where('status', 0);
        } else {
            $users->where('status', 1);
        }

        $users = $users->get();

        return view('user.manage', compact('users'));
    }

	public function getUserSubIds() {
		$affId = $_GET["idrep"] ?? null;

		$subIds = DB::table('click_vars')
		            ->where('sub1', '!=', "")->distinct()
		            ->join('clicks', function($join) use($affId) {
						$join->on('idclicks', '=', 'click_vars.click_id')->where('clicks.rep_idrep', '=', $affId);
		            })->select('click_vars.sub1')->pluck('sub1')->toArray();

		$blocked = DB::table('blocked_sub_ids')->where('rep_idrep', '=', $affId)->distinct()->pluck('sub_id')->toArray();

		$data = [];

		foreach($subIds as $subId) {
			if (in_array($subId, $blocked)) {
				$object = [
					'subId'     => $subId,
					'blocked'   => true
				];
			} else {
				$object = [
					'subId'     => $subId,
					'blocked'    => false
				];
			}

			array_push($data, $object);
		}

		return $data;

	}

	public function blockUserSubId(Request $request) {

		$userID = $request->user_id;
		$subID = $request->sub_id;

		DB::table('blocked_sub_ids')->insert([
			'rep_idrep' => $userID,
			'sub_id'    => $subID,
		]);

		return response()->json(['success' => true]);
	}

	public function unblockUserSubId(Request $request) {

		$userID = $request->user_id;
		$subID = $request->sub_id;

		DB::table('blocked_sub_ids')->where('rep_idrep', '=', $userID)->where('sub_id', '=', $subID)->delete();

		return response()->json(['success' => true]);
	}

	public function changeAffPayout(Request $request) {
		$message = null;

		$userID = $request->rep;
		$offer = $request->offer_id;
		$payout = $request->payout;

		// TODO: check if already has access or not.

		if(\LeadMax\TrackYourStats\System\Session::userType() != Privilege::ROLE_AFFILIATE) {

			$offerAccess = DB::table('rep_has_offer')
			                 ->where('rep_idrep', '=', $userID)
			                 ->where('offer_idoffer', '=', $offer)->get();
			if (count($offerAccess) > 0) {
				DB::table('rep_has_offer')
				  ->where('rep_idrep', '=', $userID)
				  ->where('offer_idoffer', '=', $offer)
				  ->update([
					  'payout' => $payout
				  ]);
				$success = true;
			} else {
				$success = false;
				$message = "User does not have access to offer yet!";
			}

		} else {
			$success = false;
			$message = "You don't have permissions to do this!";
		}
		return response()->json(['success' => $success, 'message' => $message]);
	}

	public function updateAffOfferAccess(Request $request) {
		$userID = $request->rep;
		$offer = $request->offer_id;
		$access = $request->access;
		$message = "";

		if(\LeadMax\TrackYourStats\System\Session::userType() != Privilege::ROLE_AFFILIATE) {

			if ($access) {
				DB::table('rep_has_offer')->insert([
					'rep_idrep'     => $userID,
					'offer_idoffer' => $offer,
					'payout'        => $request->payout
				]);
			} else {
				DB::table('rep_has_offer')
				  ->where('rep_idrep', '=', $userID)
				  ->where('offer_idoffer', '=', $offer)->delete();
			}

			$success = true;
		} else {
			$success = false;
			$message = "You don't have permissions to do this";
		}

		return response()->json(['success' => $success, 'message' => $message]);
	}

	public function editUserOffers(User $user) {
		$userID = $user->idrep;
		$userFName = $user->first_name;

		$offers = DB::table('offer')->where('status', '=', 1)->select('idoffer', 'offer_name', 'payout')->get()->toArray();

		foreach($offers as $index => $offer ) {
			$affHasOffer = DB::table('rep_has_offer')->where('rep_idrep', '=', $userID)->where('offer_idoffer', '=', $offer->idoffer)->get()->toArray();

			if (count($affHasOffer) > 0) {
				$offers[$index]->has_offer = true;
				$offers[$index]->reppayout = $affHasOffer[0]->payout;
			} else {
				$offers[$index]->has_offer = false;
				$offers[$index]->reppayout = 1.00;
			}
			$offers[$index]->idrep = $userID;
		}


		return view('user.offers')->with(['offers' => $offers, 'name' => $userFName]);
	}
}
