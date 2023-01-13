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

            @if(\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                <div class='form-group'>
                    <p class='form-control'>
                        Add up to 5 Sub variables as follows: http://domain.com/?repid=1&offerid=1&sub1=XXX&sub2=YYY&sub3=ZZZ&sub4=AAA&sub5=BBB
                    </p>
                    
                </div>
            @endif


            <script type="text/javascript">
                function handleSelect(elm) {
                    window.location = '/{{request()->path()}}?url=' + elm.value <?= request('adminLogin',
                        null) ? " + '&adminLogin'" : ""?>;
                }
            </script>


            <div style="margin:0 0 1px 0; padding:5px; width:250px;">

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
            </div>


            <div class="form-group searchDiv">
                <input id="searchBox" onkeyup="searchTable()" class="form-control" type="text"
                       placeholder="Search offers...">
            </div>

            <div class="clear"></div>
            <div class="white_box manage_aff white_box_x_scroll large_table value_span8">


                <table class="table table-condensed table-bordered table_01" id="mainTable">
                    <thead>

                    <tr>
                        <th class="value_span9">Offer ID</th>
                        <th class="value_span9">Offer Name</th>
                        <th class="value_span9">Offer Type</th>

                        @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                            <th class="value_span9">Offer URL</th>
                        @elseif (\LeadMax\TrackYourStats\System\Session::permissions()->can("create_offers"))
                            <th class="value_span9">Affiliate Access</th>
                        @endif


                        @if ( \LeadMax\TrackYourStats\System\Session::permissions()->can("edit_aff_payout") || \LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE))
                            <th class="value_span8">Payout</th>
                        @endif

                        <th class="value_span9">Status</th>
                        @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                            <th class="value_span9">Postback Options</th>
                        @endif

                        @if (\LeadMax\TrackYourStats\System\Session::userType() != \App\Privilege::ROLE_AFFILIATE)
                            <th class="value_span9">Offer Timestamp</th>
                        @endif

                        <th class="value_span9">Actions</th>
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
                            <td>{{\LeadMax\TrackYourStats\Offer\Offer::offerTypeAsString($offer->offer_type)}}</td>
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

                            @if (\LeadMax\TrackYourStats\System\Session::userType() !== \App\Privilege::ROLE_MANAGER)
                                @if(\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                                    <td class="value_span10">
                                        @if($offer->pivot->currency == "USD" || !$offer->pivot->currency)
                                            ${{$offer->pivot->payout}}
                                        @elseif($offer->pivot->currency == "PHP")
                                            {{$offer->pivot->payout}} PHP
                                        @endif
                                    </td>
                                @else
                                    <td class="value_span10">${{$offer->payout}}</td>
                                @endif
                            @endif

                            @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_MANAGER && \LeadMax\TrackYourStats\System\Session::permissions()->can("edit_aff_payout"))
                                <td class="value_span10 edit_payout">
                                    <span id="offer_{{$offer->idoffer}}">
                                         @if($offer->currency == "USD" || !$offer->currency)
                                            ${{$offer->payout}}
                                        @elseif($offer->currency == "PHP")
                                            {{$offer->payout}} PHP
                                        @endif

                                    </span>
                                    <a class='value_span6 value_span5 offer_{{$offer->idoffer}}'
                                       data-price='{{$offer->payout}}'
                                       data-currency='{{$offer->currency}}'
                                       data-manid='{{$offer->referrer_repid}}'
                                       title="Offer Payout"
                                       href="javascript:void(0);"
                                       onclick="updatePayout({{$offer->idoffer}})"
                                    >
                                        Edit
                                    </a>
                                </td>
                            @endif

                            <td class="value_span10">
                                @if($offer->status == 1)
                                    Active
                                @else
                                    Inactive
                                @endif
                            </td>

                            @if (\LeadMax\TrackYourStats\System\Session::userType() == \App\Privilege::ROLE_AFFILIATE)
                                <td class="value_span10"><a class='btn btn-default value_span6-1 value_span4' data-toggle="tooltip"
                                                            title="Offer PostBack Options"
                                                            href="/offer_edit_pb.php?offid={{$offer->idoffer}}">Edit
                                        Post Back</a></td>
                            @endif


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
                                    <a class="btn btn-default btn-sm value_span6-1 value_span4" data-toggle="tooltip" title="View Offer"
                                       href="/offer_details.php?idoffer={{$offer->idoffer}}"> View</a>
                                </td>
                            @else
                                <td></td>
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

		function updatePayout(offerID) {
			let offer = document.querySelector('.offer_' + offerID);
			let offerPayout = offer.dataset.price;
			let currency = offer.dataset.currency;
			let manid = offer.dataset.manid;

			let replaceDiv = document.getElementById('offer_' + offerID);
			let editLink = document.querySelector('a.offer_' + offerID);
			let form = document.createElement("form");
			form.setAttribute("method", "post");
			form.setAttribute("class", "payout_update");
			//text input element
			let element1 = document.createElement("input");
			element1.setAttribute("type", "text");
			element1.setAttribute("name", "payout");
			element1.setAttribute("value", offerPayout);
			element1.setAttribute("id", "offer_payout_" + offerID);
			element1.setAttribute("class", "form-control");
			element1.setAttribute("placeholder", "Offer Payout");

			//select element
			let elementSelect = document.createElement("select");
			elementSelect.setAttribute("id", offerID + "_currency");
			elementSelect.setAttribute("class", "form-control")
			let elementOption1 = document.createElement("option");
			elementOption1.setAttribute('value', 'USD');
			elementOption1.text = 'USD'
            if (currency === "USD") {
	            elementOption1.setAttribute("selected", true);
            }
			let elementOption2 = document.createElement("option");
			elementOption2.setAttribute('value', 'PHP');
			elementOption2.text = "PHP";
			if (currency === "PHP") {
				elementOption2.setAttribute("selected", true);
			}
			elementSelect.add(elementOption1);
			elementSelect.add(elementOption2);

			//submit button
			let submit = document.createElement("input");
			submit.setAttribute("type", "submit");
			submit.setAttribute("value", "Submit");
			submit.setAttribute("class", "btn btn-default btn-sm value_span6-1 value_span4")
			form.appendChild(elementSelect);
			form.appendChild(element1);
			form.appendChild(submit);

			replaceDiv.innerText = "";
			editLink.style.display = "none";
			replaceDiv.appendChild(form);

			form.addEventListener('submit', function(e) {

				e.preventDefault();

				const newPayout = document.getElementById("offer_payout_" + offerID).value;
				const currency = document.getElementById(offerID + "_currency").value;
				const packets = {
					offerPayout: newPayout,
                    offerID: offerID,
                    currency: currency,
                    managerID: manid
                }

				return axios.post('/offer/update-payout', packets).then(
					(response) => {
						console.log(response);
						form.remove();
						replaceDiv.innerText = currency === "USD" ? "$" + newPayout : newPayout + " PHP";
						editLink.style.display = "inline-block";

						offer.dataset.price = newPayout;
						offer.dataset.currency = currency;

						$.notify({

								title: 'Successfully',
								message: ' Updated Payout!'

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
                    }
                ).catch(error => {
					$.notify({

							title: 'Failed to update offer payout!',
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
					console.log(error)
                })
            })

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

