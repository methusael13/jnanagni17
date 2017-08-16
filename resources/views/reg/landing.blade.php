@extends('reg.layout')

@section('title')
Event Registrations
@endsection

@section('content')
<div class="content">
    <div class="title">Jnanagni</div>
    <div class="form-section">
        {{ Form::open(['url' => '/reg-details', 'method' => 'POST', 'class' => 'form', 'id' => 'form-org']) }}
            <div class="inp-row">
                <input type="password" name="org-pass" placeholder="Enter Organiser Pass">
                <input type="submit" name="org-pass-btn" class="btn animatable-all" value="Submit">
            </div>
        {{ Form::close() }}
    </div>
    @if ($errors->any())
    <div class="err">{{ $errors->first() }}</div>
    @endif
</div>
@endsection
