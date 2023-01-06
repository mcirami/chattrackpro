<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show() {

		return view('contact');
    }

	public function contactSendMail(ContactRequest $request) {

		$email = 'admin@rockphase.com';
		//$email = 'matteo@moneylovers.com';

		Mail::to($email)->send(new ContactMail($request));

		return back()->with('success', true);
	}
}
