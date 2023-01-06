<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

	public $firstName;
	public $lastName;
	public $officeName;
	public $email;
	public $messenger;
	public $messengerName;
	public $location;
	public $accountType;
	public $agents;
	public $offerTypes;
	public $experience;
	public $sales;
	public $additionalInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($validatedData)
    {
	    $this->firstName = $validatedData['first_name'];
	    $this->lastName = $validatedData['last_name'];
	    $this->officeName = $validatedData['office_name'];
	    $this->email = $validatedData['email'];
	    $this->messenger = $validatedData['messenger'];
		$this->messengerName = $validatedData['messenger_name'];
		$this->location = $validatedData['location'];
		$this->accountType = $validatedData['account_type'];
		$this->agents = $validatedData['agents'];
		$this->offerTypes = $validatedData['offer_types'];
		$this->experience = $validatedData['experience'];
	    $this->sales = $validatedData['sales'];
	    $this->additionalInfo = $validatedData['additional_info'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    return $this->subject('Contact Form Entry')
	                ->replyTo($this->email)
	                ->markdown('emails.contact',[
		                'firstName'     => $this->firstName,
						'lastName'      => $this->lastName,
						'officeName'    => $this->officeName,
		                'email'         => $this->email,
		                'messenger'     => $this->messenger,
		                'messengerName' => $this->messengerName,
		                'location'      => $this->location,
		                'accountType'   => $this->accountType,
		                'agents'        => $this->agents,
		                'offerTypes'    => $this->offerTypes,
		                'experience'    => $this->experience,
						'sales'         => $this->sales,
	                    'additionalInfo' => $this->additionalInfo
	                ]);
    }
}
