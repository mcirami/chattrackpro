<?php

namespace App\Http\Controllers\Report;

use App\Offer;
use App\Services\Repositories\Offer\OfferAffiliateClicksRepository;
use App\Services\Repositories\Offer\OfferClicksRepository;
use App\User;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use LeadMax\TrackYourStats\Report\ID\Clicks;
use LeadMax\TrackYourStats\System\Session;
use LeadMax\TrackYourStats\Table\Paginate;
use LeadMax\TrackYourStats\User\Permissions;

class ClickReportController extends ReportController
{

    /**
     * Shows an offers clicks, and affiliates with those clicks.
     * Shows only affiliates assigned to the current logged in user
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function offerClicks($id)
    {
        $offer = Offer::findOrFail($id);
        $dates = self::getDates();
        $repo = new OfferClicksRepository($id, Session::user(), Session::permissions()->can(Permissions::VIEW_FRAUD_DATA));
        $affiliateRepo = new OfferAffiliateClicksRepository($id, Session::user());

        $start = Carbon::parse($dates['start'], 'America/Los_Angeles');
        $end = Carbon::parse($dates['end'], 'America/Los_Angeles');

        $clickReport = $repo->between($start, $end);
        $page = request()->query('page', 1);
        $rpp = request()->query('rpp', 10);
        $clickReport = new LengthAwarePaginator($clickReport->forPage($page, $rpp), $clickReport->count(), $rpp, $page,
            ['path' => request()->fullUrlWithQuery(request()->except('page'))]);
        $affiliateReport = $affiliateRepo->between($start, $end);

        return view('report.clicks.offer', compact('offer', 'affiliateReport', 'clickReport'));
    }

    public function showOfferClicks($id)
    {
        $dates = self::getDates();
        $offer = Offer::findOrFail($id);

        // TODO: This should REALLY be refactored
        $myAssignments = array(
            'd_from' => Carbon::today()->format('Y-m-d'),
            'd_to' => Carbon::today()->format('Y-m-d'),
            'dateSelect' => 0,

            'rpp' => 10,


            'idoffer' => $id,
        );


        $assign = new \LeadMax\TrackYourStats\Table\Assignments($myAssignments);

        $assign->getAssignments();
        $report = new \LeadMax\TrackYourStats\Report\ID\Offer($assign);

        $report->fetchReport($dates['startDate'], $dates['endDate']);

        $paginate = new Paginate(request()->query('rpp', 10),
            $report->getCount($dates['startDate'], $dates['endDate']));


        return view('report.clicks.offer', compact('paginate', 'report', 'offer', 'dates'));
    }

    public function showUsersClicks($userId)
    {
        $dates = self::getDates();

        $user = User::myUsers()->findOrFail($userId);
        $report = new Clicks($user->getRole(), request());

        $paginate = new Paginate(request()->query('rpp', 10),
            $report->getCount($dates['startDate'], $dates['endDate'], $userId));

        $report->fetchReport($dates['startDate'], $dates['endDate'], $userId, $paginate->items_per_page,
            $paginate->offset());


        return view('report.clicks.affiliate', compact('report', 'paginate', 'user'));
    }

}
