@component('mail::message')

# A new user has submitted a inquiry.

<br>
Name: {{$firstName}} {{$lastName}}
<br>
Office Name: {{$officeName}}
<br>
Email: {{$email}}
<br>
Messenger: {{$messenger}}: {{$messengerName}}
<br>
location: {{$location}}
<br>
Account Type: {{$accountType}}
<br>
Agents: {{$agents}}
<br>
Offer Type(s):
@foreach($offerTypes as $type)
{{$type}},
@endforeach
<br>
Experience: {{$experience}}
<br>
Additional Info: {{$additionalInfo}}
<br>
<br>
<p class="signature">CPA Admin</p>

@endcomponent
