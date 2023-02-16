<?php

namespace LeadMax\TrackYourStats\Report\Repositories\Offer;


use App\User;
use LeadMax\TrackYourStats\Report\Repositories\Repository;
use LeadMax\TrackYourStats\System\Session;

class AdminOfferRepository extends Repository
{

    public function query($dateFrom, $dateTo): \PDOStatement
    {
        //todo lol

    }


    public function between($dateFrom, $dateTo): array
    {
        $clicks = $this->getClicks($dateFrom, $dateTo);

        $conversions = $this->getConversions($dateFrom, $dateTo);

        $report = $this->mergeReport($clicks, $conversions);

        $report = $this->setRequiredKeysIfNotSet($report, [
            'idoffer' => '',
            'offer_name' => '',
            'Clicks' => 0,
            'UniqueClicks' => 0,
            'FreeSignUps' => 0,
            'PendingConversions' => 0,
            'Conversions' => 0,
            'Revenue' => 0,
            'Deductions' => 0,
            'EPC' => 0,
        ]);


        return $report;
    }


    private function getClicks($dateFrom, $dateTo)
    {
        $db = $this->getDB();

	    $userRole = Session::user()->getRole();

	    if($userRole == 1) {
		    $managers = User::where('referrer_repid',  Session::userID())->get()->pluck('idrep')->toArray();
		    $managerIDs = implode(',', array_map('intval', $managers));

		    $sql = "
					SELECT
						offer.idoffer,
						offer.offer_name,
						count(rawClicks.idclicks) Clicks,
						SUM(CASE WHEN rawClicks.click_type = 0 THEN 1 ELSE 0 END) UniqueClicks,
	                    count(pc.id) as PendingConversions
					FROM
						offer
						
					LEFT JOIN clicks rawClicks ON rawClicks.offer_idoffer = offer.idoffer
					
	                LEFT JOIN pending_conversions pc
	                    ON pc.click_id = rawClicks.idclicks  
						AND pc.converted = 0
					
					LEFT JOIN rep ON rep.idrep = rawClicks.rep_idrep
					
	                WHERE
						rawClicks.first_timestamp BETWEEN :dateFrom AND :dateTo  and rawClicks.click_type !=2 and rep.referrer_repid IN (" . $managerIDs . ")
						
				 GROUP BY offer.idoffer, rawClicks.offer_idoffer
					
			";

	    } else {
			    $sql = "
					SELECT
						offer.idoffer,
						offer.offer_name,
						count(rawClicks.idclicks) Clicks,
						SUM(CASE WHEN rawClicks.click_type = 0 THEN 1 ELSE 0 END) UniqueClicks,
	                    count(pc.id) as PendingConversions
					FROM
						offer
						
					LEFT JOIN clicks rawClicks ON rawClicks.offer_idoffer = offer.idoffer
					
	                LEFT JOIN pending_conversions pc
	                    ON pc.click_id = rawClicks.idclicks  
						AND pc.converted = 0
	                
	                WHERE
						rawClicks.first_timestamp BETWEEN :dateFrom AND :dateTo  and rawClicks.click_type !=2
				 GROUP BY offer.idoffer, rawClicks.offer_idoffer
					
			";
	    }

        $stmt = $db->prepare($sql);


        $stmt->bindParam(":dateFrom", $dateFrom);
        $stmt->bindParam(":dateTo", $dateTo);

        $stmt->execute();


        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);


        return $result;
    }

    private function getConversions($dateFrom, $dateTo)
    {
        $db = $this->getDB();

	    $userRole = Session::user()->getRole();

	    if($userRole == 1) {
		    $managers   = User::where( 'referrer_repid', Session::userID() )->get()->pluck( 'idrep' )->toArray();
		    $managerIDs = implode( ',', array_map( 'intval', $managers ) );

		    $sql = "
					SELECT
						offer.idoffer,
						offer.offer_name,
						count(f.id) FreeSignUps,
						count(conversions.id) Conversions,
						sum(conversions.paid) Revenue,
						sum(deducted.paid) Deductions
					FROM
						offer
						
					LEFT JOIN clicks rawClicks ON rawClicks.offer_idoffer = offer.idoffer
					
					LEFT JOIN conversions ON conversions.click_id = rawClicks.idclicks
					
					LEFT JOIN free_sign_ups f ON f.click_id = rawClicks.idclicks
					
					
					LEFT JOIN deductions ON deductions.conversion_id = conversions.id
					
					LEFT JOIN conversions deducted ON deducted.id = deductions.conversion_id
					
					LEFT JOIN rep ON rep.idrep = conversions.user_id
					
					WHERE
						conversions.timestamp BETWEEN :dateFrom AND :dateTo and rep.referrer_repid IN (" . $managerIDs . ")
					 
				 GROUP BY offer.idoffer, rawClicks.offer_idoffer
					
			";

	    } else {
			    $sql = "
					SELECT
						offer.idoffer,
						offer.offer_name,
						count(f.id) FreeSignUps,
						count(conversions.id) Conversions,
						sum(conversions.paid) Revenue,
						sum(deducted.paid) Deductions
					FROM
						offer
						
					LEFT JOIN clicks rawClicks ON rawClicks.offer_idoffer = offer.idoffer
					
					LEFT JOIN conversions ON conversions.click_id = rawClicks.idclicks
					
					LEFT JOIN free_sign_ups f ON f.click_id = rawClicks.idclicks
					
					
					LEFT JOIN deductions ON deductions.conversion_id = conversions.id
					
					LEFT JOIN conversions deducted ON deducted.id = deductions.conversion_id
					
					WHERE
						conversions.timestamp BETWEEN :dateFrom AND :dateTo 
					 
				 GROUP BY offer.idoffer, rawClicks.offer_idoffer
					
			";
	    }

        $stmt = $db->prepare($sql);


        $stmt->bindParam(":dateFrom", $dateFrom);
        $stmt->bindParam(":dateTo", $dateTo);

        $stmt->execute();


        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }


}