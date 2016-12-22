<html>
<head>
    <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background: white;
        }
    </style>
</head>
<body>
<div class="container-fluid">
<div class="row">
    <div class="col-xs-12">
        <h3 class="text-center">CINCINNATI ULTIMATE PLAYERS ASSOCIATION WAIVER</h3>
        <hr/>
    </div>
    <div class="col-xs-6 text-center">
        <h4>
            Signed on {{$waiver->updated_at->format('m/d/Y')}} by
            <?php if($waiver->updated_by): ?>
                {{ $waiver->updatedBy->fullname() }}
            <?php else: ?>
                {{ $waiver->user->fullname() }}
            <?php endif; ?>
        </h4>
    </div>
    <div class="col-xs-6 text-center">
        <h4>Good until 01/01/{{$waiver->year + 1}}</h4>
    </div>
    <div class="col-xs-12">
        <hr/>
    </div>
</div>
@if($age < 18)
    <div>
        <h4 class="text-center">Contact the CUPA Treasurer for a paper copy</h4>
    </div>
@endif
<div>
    <hr/>
    <p>In consideration of my participation in any way in the Cincinnati Ultimate
    Players Association’s leagues, related events and activities, I,
    <strong>{{ $waiver->user->fullname() }}</strong>,</p>

    <p>the undersigned acknowledge, appreciate, and agree that:</p>

    <ol>
        <li>
            The risk of injury from the activities involved in this program is
            significant, and while particular rules and personal discipline reduce
            this risk, the risk of serious injury does exist: and,
        </li>
        <li>
            I KNOWINGLY AND FREELY ASSUME ALL SUCH RISKS, both known and unknown,
            Even if arising from the negligence of the releasees or others, and
            assume full responsibility for my participation: and,
        </li>
        <li>
            I willingly agree to comply with the state and customary terms and
            conditions for participation. If however, I observe any unusual
            significant hazard during my presence or participation, I will remove
            myself from participation and bring such to the attention of the
            nearest official immediately: and,
        </li>
        <li>
            I, for myself and on behalf of my heirs, assigns, personal representatives
            and next of kin, hereby release and hold harmless the Cincinnati
            Ultimate Players Association, their officers, officials, sponsors,
            advertisers, and if applicable, owners and lessors of premises used
            to conduct the event (“Releasees”). With respect to any and all injury,
            disability, death or loss or damage to person or property, whether
            arising from the negligence of the releasees or otherwise.
        </li>
    </ol>

    <p>
        I HAVE READ THIS RELEASE OF LIABILITY AND ASSUMPTION OF RISK AGREEMENT,
        FULLY UNDERSTAND ITS TERMS, AND SIGN IT FREELY AND VOLUNTARILY WITHOUT
        ANY INDUCEMENT.
    </p>

    <p>
        THIS IS TO CERTIFY I DO CONSENT AND AGREE TO HIS/HER RELEASE AS PROVIDED
        ABOVE OF ALL RELEASEES, AND FOR MYSELF, MY HEIRS, ASSIGNS, AND NEXT OF
        KIN, I RELEASE AND AGREE TO INDEMNIFY THE RELEASEES FROM ANY AND ALL
        LIABILITIES INCIDENT TO MY INVOLVEMENT OR PARTICIPATION IN THESE PROGRAMS
        AS PROVIDED ABOVE, EVEN IF ARISING FROM THE NEGLIGENCE OF THE RELEASEES,
        TO THE FULLEST EXTENT PERMITTED BY LAW.
    </p>
</div>
</div>
</body>
</html>
