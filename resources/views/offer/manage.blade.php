@extends('layouts.master')

@section('content')
    <!--right_panel-->
    <div class="right_panel">
        <div class="white_box_outer large_table">
            <div class="heading_holder">
                <span class="lft value_span9">Offers</span>
                @if (\LeadMax\TrackYourStats\System\Session::permissions()->can("create_offers"))
                    <a style='margin-left: 1%; margin-top:.3%;' href="/offer_add.php"
                       class='btn btn-default btn-sm value_span5-1 value_span6-5 value_span2'>Create New Offer</a>
                @endif
            </div>

            @if(\LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_AFFILIATE)
                @include('report.options.active')
            @endif

           {{-- @if(\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                <div class='form-group'>
                    <p class='form-control'>
                        Add up to 5 Sub variables as follows: http://domain.com/?repid=1&offerid=1&sub1=XXX&sub2=YYY&sub3=ZZZ&sub4=AAA&sub5=BBB
                    </p>
                    
                </div>
            @endif--}}


            <script type="text/javascript">
                function handleSelect(elm) {
                    window.location = '/{{request()->path()}}?url=' + elm.value <?= request('adminLogin',
                        null) ? " + '&adminLogin'" : ""?>;
                }
            </script>


            {{--<div style="margin:0 0 1px 0; padding:5px; width:250px;">

                @if(\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)

                    <label class="value_span9">Offer URLS: </label>
                    <select onchange='handleSelect(this);' class="form-control input-sm " id="offer_url"
                            name="offer_url">


                        @for ($i = 0; $i < count($urls); $i++)
                            @if (request('url',0) == $i) {
                            <option selected value='{{$i}}'> {{$urls[$i]}}</option>
                            @else
                                <option value='{{$i}}'> {{$urls[$i]}}</option>
                            @endif
                        @endfor

                    </select>

                @endif
            </div>--}}


            <div class="form-group searchDiv">
                <input id="searchBox" onkeyup="searchTable()" class="form-control" type="text"
                       placeholder="Search offers...">
            </div>

            <div class="clear"></div>
            <div class="white_box manage_aff white_box_x_scroll large_table value_span8">


                <table class="table table-condensed table-bordered table_01 adjust_width" id="mainTable">
                    <thead>

                    <tr>
                        <th class="value_span9">Offer ID</th>
                        <th class="value_span9">Offer Name</th>
                        @if($userType != 3)
                            <th class="value_span9">Offer Type</th>
                        @endif
                        @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                            <th class="value_span9">Offer URL</th>
                        @elseif(\LeadMax\TrackYourStats\System\Session::permissions()->can("create_offers"))
                            <th class="value_span9">Affiliate Access</th>
                        @endif


                        @if (\LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_MANAGER && $userType != 3)
                            <th class="value_span8">Payout</th>
                        @endif

                        @if ($userType != 3)
                            <th class="value_span9">Status</th>
                        @endif
                        {{--@if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                            <th class="value_span9">Postback Options</th>
                        @endif--}}

                        @if (\LeadMax\TrackYourStats\System\Session::userType() != \App\Privilege::ROLE_AFFILIATE)
                            <th class="value_span9">Offer Timestamp</th>
                        @endif

                        @if ($userType != 3)
                            <th class="value_span9">Actions</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>


                    @if(isset($requestableOffers))
                        @foreach ($requestableOffers as $offer)
                            <tr>
                                <td>{{$offer->idoffer}}</td>
                                <td>{{$offer->offer_name}}</td>
                                <td>Requires Offer</td>
                                <td>${{$offer->payout}}</td>
                                <td>{{$offer->status}}</td>
                                <td>Requires Offer</td>
                                <td>
                                    <button id='btn_{{$offer->idoffer}}' class='btn btn-sm btn-default'
                                            onclick='requestOffer({{$offer->idoffer}})'>Request Offer
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif


                    @foreach($offers as $offer)
                        <tr>
                            <td>{{$offer->idoffer}}</td>
                            <td>{{ucwords($offer->offer_name)}}</td>
                            @if($userType != 3)
                                <td>{{\LeadMax\TrackYourStats\Offer\Offer::offerTypeAsString($offer->offer_type)}}</td>
                            @endif
                            @if(\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)

                                <p style='display:none;' id="url_{{$offer->idoffer}}">http://{{$urls[request('url',0)]}}
                                    /?repid={{\LeadMax\TrackYourStats\System\Session::userID()}}
                                    &offerid={{$offer->idoffer}}&sub1=</p>
                                <td class="value_span10">
                                    <button data-toggle="tooltip" title="Copy My Link"
                                            onclick="copyToClipboard(getElementById('url_{{$offer->idoffer}}'));"
                                            class="btn btn-default">Copy My Link
                                    </button>
                                </td>
                            @endif
                            @if (\LeadMax\TrackYourStats\System\Session::permissions()->can("create_offers"))
                                <td class="value_span10">
                                    <a target='_blank' class='btn btn-sm btn-default value_span5-1'
                                       href='/offer_access.php?id={{$offer->idoffer}}'>Affiliate Access</a>
                                </td>
                            @endif

                           {{-- @if (\LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_MANAGER)
                                @if(\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                                    <td class="value_span10">${{$offer->pivot->payout}}</td>
                                @else
                                    <td class="value_span10">${{$offer->payout}}</td>
                                @endif
                            @endif--}}
                            @if (\LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_MANAGER && \LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_AFFILIATE)
                                <td class="value_span10">${{$offer->payout}}</td>
                            @endif

                            @if($userType != 3)
                                <td class="value_span10">
                                    @if($offer->status == 1)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                            @endif


                           {{-- @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                                <td class="value_span10"><a class='btn btn-default value_span6-1 value_span4' data-toggle="tooltip"
                                                            title="Offer PostBack Options"
                                                            href="/offer_edit_pb.php?offid={{$offer->idoffer}}">Edit
                                        Post Back</a></td>
                            @endif--}}


                            @if (\LeadMax\TrackYourStats\System\Session::userType() != \App\Privilege::ROLE_AFFILIATE)
                                <td class="value_span10">{{\Carbon\Carbon::parse($offer->offer_timestamp)->diffForHumans()}}</td>
                            @endif

                            @if (\LeadMax\TrackYourStats\System\Session::userType() != \App\Privilege::ROLE_AFFILIATE)
                                @if (\LeadMax\TrackYourStats\System\Session::permissions()->can("create_offers"))
                                    <td class="value_span10">
                                        <a class="btn btn-default btn-sm value_span6-1 value_span4" data-toggle="tooltip" title="Edit Offer"
                                           href="/offer_update.php?idoffer={{$offer->idoffer}}">Edit</a>
                                    </td>
                                @endif
                            @endif

                            @if (\LeadMax\TrackYourStats\System\Session::permissions()->can("edit_offer_rules"))
                                <td class="value_span10">
                                    <a class="btn btn-default btn-sm value_span6-1 value_span4" data-toggle="tooltip" title="Edit Offer Rules"
                                       href="/offer_edit_rules.php?offid={{$offer->idoffer}}"> Rules</a>
                                </td>

                            @endif

                            @if(\LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_AFFILIATE)
                                <td class="value_span10">
                                    {{--<a class="btn btn-default btn-sm value_span6-1 value_span4" data-toggle="tooltip" title="View Offer"
                                       href="/offer_details.php?idoffer={{$offer->idoffer}}"> View</a>--}}
                                    <a class="btn btn-default btn-sm value_span6-1 value_span4" data-toggle="tooltip" title="View Offer"
                                       target="_blank" href="https://bigbucksglobal.com/?repid=1005&offerid={{$offer->idoffer}}&sub1="> View</a>
                                </td>
                            @endif

                            @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_GOD)
                                <td class="value_span10">
                                    <a class="btn btn-default btn-sm value_span6-1 value_span4" data-toggle="tooltip" title="Duplicate Offer"
                                       href="/offer/{{$offer->idoffer}}/dupe"> Duplicate </a>
                                </td>

                                <td class="value_span10">
                                    <a class="btn btn-default btn-sm value_span11 value_span4" data-toggle="tooltip" title="Delete Offer"
                                       onclick="confirmSendTo('Are you sure you want to delete this offer?',
                                               '/offer/{{$offer->idoffer}}/delete')" href="javascript:void(0);">
                                        Delete </a>
                                </td>

                            @endif


                        </tr>
                    @endforeach


                    </tbody>
                </table>
                @include('report.options.pagination')

            </div>
        </div>
    </div>
    <!--right_panel-->

@endsection

@section('footer')
    <script type="text/javascript">
        function requestOffer(id) {


            $("#btn_" + id).attr('disabled', true);

            $.ajax({
                url: "/offer/" + id + '/request?' <?= (isset($_GET["adminLogin"])) ? " + '&adminLogin'" : ""?>,
                success: function (result) {

                    $.notify({

                            title: 'Successfully',
                            message: ' requested offer!'

                        }, {
                            placement: {
                                from: 'top',
                                align: 'center'
                            },
                            type: 'info',
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        }
                    );
                    console.log(result);
                },

                error: function (result) {
                    $("#btn_" + id).attr('disabled', false);

                    $.notify({

                            title: 'Failed to request offer!',
                            message: ' Please try again later or contact an admin.'

                        }, {
                            placement: {
                                from: 'top',
                                align: 'center'
                            },
                            type: 'danger',
                            animate: {
                                enter: 'animated fadeInDown',
                                exit: 'animated fadeOutUp'
                            },
                        }
                    );
                }
            });

        }
    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            $("#mainTable").tablesorter(
                {
                    sortList: [[1, 0]],
                    widgets: ['staticRow']
                });
        });
    </script>
@endsection

