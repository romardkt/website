@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Sign player Waiver for {{{ $year }}}</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            <hr/>
            <p>In consideration of my participation in any way in the Cincinnati Ultimate
            Players Association’s leagues, related events and activities, I,
            <strong>{{{ $user->fullname() }}}</strong>,</p>

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
            <hr/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}

        <legend>Sign Waiver for {{ $year }}</legend>

        <div class="form-group">
            {{ Form::label('Enter your name exactly as it appears in the waiver text') }}
            {{ Form::text('fullname', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('read', 1, false) }} I have read and agree to the above waiver
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-xs-12 text-center">
                <button class="btn btn-primary" type="submit">Sign Waiver</button>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
