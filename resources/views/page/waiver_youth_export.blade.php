<html>
<head>
    <link href="{{ elixir('css/cupa.min.css') }}" rel="stylesheet">
    <style>
        body {
            background: white;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-xs-12">
        <h3 class="text-center">CINCINNATI ULTIMATE PLAYERS ASSOCIATION YOUTH WAIVER, RELEASE OF AND MEDICAL AUTHORIZATION FORM</h3>
        <hr/>
    </div>
    <div class="col-xs-6 text-center">
        <h4>Signed on {{$release->updated_at->format('m/d/Y')}}</h4>
    </div>
    <div class="col-xs-6 text-center">
        <h4>Good until 01/01/{{$release->year + 1}}</h4>
    </div>
    <div class="col-xs-12">
        <hr/>
    </div>
</div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <p>
            In consideration of being allowed to participate in any way in the Cincinnati Players
            Association activities, the undersigned acknowledges, appreciates and willingly
            agrees that:
        </p>
        <p>
            <strong>1.</strong> I will comply with the stated and customary terms and conditions
            for participation. If, however, I observe any unusual significant hazard during my
            presence and participation, I will remove myself from participation and bring such
            to the attention of the nearest official immediately.
        </p>
        <p>
            <strong>2.</strong> I acknowledge and fully understand that each participant will be
            engaging in activities that involve risk or serious injury, including permanent
            disability and death, and severe social and economic losses which may result not only
            from their own actions, inaction or negligence but the action, inaction or negligence
            of others, the rules of play, or the condition of the premises of any equipment used.
            Further, I accept personal responsibility for the damages following such injury,
            permanent disability or death.
        </p>
        <p>
            <strong>3.</strong> I hereby authorize and give my full consent to the Cincinnati
            Ultimate Players Association to copyright and/or publish any and all photographs,
            videotapes and/or film in which I appear while attending any CUPA event. I further
            agree that the CUPA may transfer, use or cause to be used, these photographs,
            videotapes, or films for any exhibitions, public displays, publications, commercials,
            art and advertising purposes, and television programs without limitations or
            reservations.
        </p>
        <p>
            <strong>4.</strong> I knowingly and freely assume all such risk, both known and
            unknown, even those arising from the negligent acts or omissions of others, and
            assume full responsibility for my participation.
        </p>
        <p>
            <strong>5.</strong> I, for myself and on behalf of my heirs, assigns, personal
            representatives and next of kin, hereby release, and agree to hold harmless the
            Cincinnati Ultimate Players Association, its officials, affiliated clubs, their
            respective administrators, directors, agents, coaches, and other employees of the
            organization, other participants, sponsoring agencies, advertisers, and, if
            applicable owners and lessees of premises used to conduct the event, all of which
            are hereinafter referred to as “releases”, with respect to all and any injury,
            disability, death or loss or damage to person or property, whether arising from
            the negligence of the releases or otherwise, to the fullest extent permitted by
            law. I will indemnify, save and hold harmless above named releases of, from and
            against any loss, cost, expense, damage or liability that such releases may incur
            as a result of, arising from or in connection with such claims, including without
            limitation my attorney’s fees, other costs or expenses of litigation.
        </p>

        <p>
            I have read this release of liability and assumption of risk agreement, fully
            understand its terms, and understand that I have given up substantial righty
            by signing it and freely and voluntarily without any inducement.
        </p>
        <p><br/></p>
    </div>
</div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <legend>Participant Information</legend>

        <div class="row">
            <div class="col-sm-4">
                {!! Form::label('Name') !!}
                {!! Form::text('participant_name', $release->user->fullname(), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-5">
                {!! Form::label('Email Address') !!}
                {!! Form::email('participant_email', $release->user->parentObj->email, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::label('Phone') !!}
                {!! Form::text('participant_phone', $release->user->parentObj->profile->phone, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                <span class="help-block">Format: ###-###-####</span>
            </div>
        </div>
        <p><br/></p>
    </div>
</div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <p>
            This is to certify that I, as parent/guardian with legal responsibility
            for this participant, do consent and agree to indemnify and hold harmless
            the releases from any and all liabilities incident to my minor child’s
            involvement or participation in these programs above, even if arising from
            their negligence, to the fullest extent permitted by law. In the event of
            such an injury to my child and I cannot be contacted, I give permission to
            qualified and licensed EMTs, physicians, paramedics, and/or other medical
            or hospital personnel to render such treatment. I release the Cincinnati
            Ultimate Players Association, its employees, its agents, its volunteers
            and its assigns from any personal injuries caused by or having any
            relation to this activity. I understand that this release applies to any
            present or future injuries or illnesses and that it binds my heirs,
            executors and administrators. This release form is completed and signed
            of my own free will and with full knowledge of its significance. I have
            read and understand all of its terms. <strong>At least one parent/guardian
            must sign</strong>, please list contact information for all parents/guardians
            who wish to be contacted in an emergency.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <legend>Emergency contact information</legend>

        <h4>Parent/Guardian #1</h4>
        <div class="row">
            <div class="col-sm-4">
                {!! Form::label('Name') !!}
                {!! Form::text('ice1_name', $release->user->parentObj->fullname(), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-5">
                {!! Form::label('Email Address') !!}
                {!! Form::email('ice1_email', $release->user->parentObj->email, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::label('Phone') !!}
                {!! Form::text('ice1_phone', $release->user->parentObj->profile->phone, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                <span class="help-block">Format: ###-###-####</span>
            </div>
        </div>
        <br/>
        <h4>Parent/Guardian #2</h4>
        <div class="row">
            <div class="col-sm-4">
                {!! Form::label('Name') !!}
                {!! Form::text('ice2_name', $release->data->ice2_name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-5">
                {!! Form::label('Email Address') !!}
                {!! Form::email('ice2_email', $release->data->ice2_email, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::label('Phone') !!}
                {!! Form::text('ice2_phone', $release->data->ice2_phone, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                <span class="help-block">Format: ###-###-####</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h4>Emergency Contact (in event parent(s) / guardian(s) cannot be reached):</h4>
            </div>
            <div class="col-sm-8">
                {!! Form::label('Name') !!}
                {!! Form::text('ice3_name', $release->data->ice3_name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-4">
                {!! Form::label('Phone') !!}
                {!! Form::text('ice3_phone', $release->data->ice3_phone, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                <span class="help-block">Format: ###-###-####</span>
            </div>
        </div>
        <p><br/></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <legend>Medical Information</legend>
        <div class="row">
            <div class="col-sm-8">
                {!! Form::label('Family Physician') !!}
                {!! Form::text('physician_name', $release->data->physician_name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
            <div class="col-sm-4">
                {!! Form::label('Family Physician Phone') !!}
                {!! Form::text('physician_phone', $release->data->physician_phone, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                <span class="help-block">Format: ###-###-####</span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h4>
                    Specific facts concerning the child’s medical history including allergies,
                    medications being taken, chronic illness or other conditions which a physician
                    should be alerted and entered below.
                </h4>
                {!! Form::textarea('medical_history', $release->data->medical_history, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
            </div>
        </div>
        <p><br/></p>
        <hr/>
    </div>
</div>
</body>
</html>
