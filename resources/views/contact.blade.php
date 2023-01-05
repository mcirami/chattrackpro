<?php
//ini_set('display_errors', 1);
$webroot = getWebRoot();

?>
<!DOCTYPE html>
<html>
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/ico"
          href="<?PHP echo $webroot . "/" . \LeadMax\TrackYourStats\System\Company::loadFromSession()->getImgDir() . "/favicon.ico"; ?>"/>
    <link rel="shortcut icon" type="image/ico"
          href="<?PHP echo $webroot . "/" . \LeadMax\TrackYourStats\System\Company::loadFromSession()->getImgDir() . "/favicon.ico"; ?>"/>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="{{$webroot}}css/bootstrap.min.css" rel="stylesheet">-->
    <!--    <link href="css/bootstrap-theme.min.css" rel="stylesheet">-->
    <!-- <link href="{{$webroot}}css/animate.css" rel="stylesheet">-->

    <link rel="stylesheet" type="text/css" href="<?php echo $webroot; ?>css/default.css?v=1.1"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $webroot; ?>css/compiled/app.min.css"/>
    <link rel="stylesheet" media="screen" type="text/css"
          href="<?php echo $webroot; ?>css/company.php"/>

    <link rel="stylesheet" type="text/css" href="<?php echo $webroot; ?>css/font-awesome/css/all.css">

    <!--<script type="text/javascript" src="<?php echo $webroot; ?>js/jquery_2.1.3_jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $webroot; ?>js/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
    <link rel="stylesheet" href="{{$webroot}}css/jquery-ui.min.css"/>
    <script type="text/javascript" src="<?php echo $webroot; ?>js/iscroll.min.js"></script>

    @if(!env('APP_DEBUG') && env('APP_ENV') == 'production')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-127417577-1"></script>
        <script>window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}

			gtag('js', new Date());
			gtag('config', 'UA-127417577-1');</script>
    @endif


    <title><?php echo \LeadMax\TrackYourStats\System\Company::loadFromSession()->getShortHand(); ?></title>
</head>

<body>

<header class="top_sec value_span1">
    <div class="container">
        <div class="row_wrap">
            <div class="nav_wrap">
                <nav class="navbar navbar-expand-lg bg-body-tertiary">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="/">
                            <a href="{{$webroot}}">
                                <img src="{{ $webroot.\LeadMax\TrackYourStats\System\Company::loadFromSession()->getImgDir() .  "/logo.png"}}"
                                     alt="<?php echo \LeadMax\TrackYourStats\System\Company::loadFromSession()->getShortHand(); ?>"
                                     title="<?php echo \LeadMax\TrackYourStats\System\Company::loadFromSession()->getShortHand(); ?>"/>
                            </a>
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{$webroot}}#home">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{$webroot}}#our_benefits">Our Benefits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{$webroot}}#faq">FAQ</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{$webroot}}#contact" class="nav-link">Contact</a>
                                </li>
                            </ul>
                            <div class="buttons_wrap">
                                <a class="button blue" href="{{$webroot}}login.php">Sign In</a>
                                <a class="button transparent" href="{{$webroot}}contact-us">Contact us</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

<div class = "white_box_outer contact_us">
    <div class="container">
        <div class ="white_box value_span8">
            <div class = "com_acc">
                @if(session()->has('success'))
                    <div class="heading_holder success">
                        <h3>Thanks for contacting us!</h3>
                        <p>We will get back to you soon!</p>
                    </div>
                @else
                    <form method="POST" action="{{route('contact.send')}}" id="contact_us_form">
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        <div class = "heading_holder">
                            <h3>Contact Us</h3>
                            <p>Submit the form below to let us know about yourself and the account and offers you are looking for.</p>
                        </div>
                        <div class="errors mb-3">
                            @if($errors->any())
                                @foreach ($errors as $error)
                                    <p>{{$error}}</p>
                                @endforeach
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="row p-0">
                                <div class="col-6">
                                    <input id="first_name" class="form-control" type="text" name="first_name" placeholder="First Name" >
                                </div>
                                <div class="col-6">
                                    <input class="form-control" id="last_name" type="text" name ="last_name" placeholder="Last Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row p-0">
                                <div class="col-6">
                                    <input class="form-control" id="office_name" type="text" name ="office_name" placeholder="Group/Office Name" required>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" id="email" type="text" name="email" placeholder="E-mail" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row p-0">
                                <div class="col-6">
                                    <select name="messenger" id="messenger" class="form-control form-select" required>
                                        <option value="">Select Instant Messenger</option>
                                        <option value="skype">Skype</option>
                                        <option value="telegram">Telegram</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" id="messenger_name" type="text" name="messenger_name" placeholder="Messenger Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" id="location" type="text" name="location" placeholder="Location" required>
                        </div>
                        <div class="mb-3">
                            <select class="form-control form-select" name="account_type" id="account_type" required>
                                <option value="">Which Best Describes you?</option>
                                <option value="Network Owner">Network Owner</option>
                                <option value="Office Owner">Office Owner</option>
                                <option value="Office Manager">Office Manager</option>
                                <option value="Office Admin">Office Admin</option>
                                <option value="Recruiter">Recruiter</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select class="form-control form-select" name="agents" id="agents" required>
                                <option value="">Number of Agents?</option>
                                <option value="1-5">1-5</option>
                                <option value="6-10">6-10</option>
                                <option value="11-20">11-20</option>
                                <option value="21-30">21-30</option>
                                <option value="31-50">31-50</option>
                                <option value="50-100">50-100</option>
                                <option value="101+">101+</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <fieldset>
                                <legend>Seeking offer types</legend>
                                <p>(choose all that apply)</p>
                               <div class="checks_wrap">
                                   <div class="form-check">
                                       <input name="offer_types[]" class="form-check-input" type="checkbox" value="Dating" id="dating">
                                       <label class="form-check-label" for="dating">
                                           Dating
                                       </label>
                                   </div>
                                   <div class="form-check">
                                       <input name="offer_types[]" class="form-check-input" type="checkbox" value="Cams" id="cams">
                                       <label class="form-check-label" for="cams">
                                           Cams
                                       </label>
                                   </div>
                                   <div class="form-check">
                                       <input name="offer_types[]"  class="form-check-input" type="checkbox" value="Nutra" id="nutra">
                                       <label class="form-check-label" for="nutra">
                                           Nutra
                                       </label>
                                   </div>
                                   <div class="form-check">
                                       <input name="offer_types[]" class="form-check-input" type="checkbox" value="Mens Health" id="mens_health">
                                       <label class="form-check-label" for="mens_health">
                                           Mens Health
                                       </label>
                                   </div>
                               </div>
                            </fieldset>
                        </div>
                        <div class="mb-3">
                            <select class="form-control form-select" name="experience" id="experience" required>
                                <option value="">Years Experience</option>
                                <option value="0-9">0-9</option>
                                <option value="10-24">10-24</option>
                                <option value="25-49">25-49</option>
                                <option value="50-99">50-99</option>
                                <option value="100-149">100-149</option>
                                <option value="150-249">150-249</option>
                                <option value="250+">250+</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" name="additional_info" id="additional_info" rows="10" placeholder="Additional Info" required></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="submit"
                                   name="button"
                                   class="button blue"
                                   value="Submit"
                            />
                        </div>
                    </form>
                @endif
            </div>
        </div><!-- white_box -->
    </div>
</div><!-- white_box_outer -->
@include('layouts.contact-footer')

@yield('footer')

</body>
</html>

