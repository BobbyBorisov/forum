@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="page-header col-md-8 col-md-offset-2">
                <avatar-form :user="{{$user}}"></avatar-form>
                <h3>{{$activities->flatten()->count()}} activities so far.</h3>
            </div>
            <div class="col-md-8 col-md-offset-2">
                @foreach($activities as $date => $activity)
                    <div class="page-header"><h2>{{$date}}</h2></div>
                    @foreach($activity as $record)
                            @include('users.activities.'.$record->type, ['model' => $record])
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection
