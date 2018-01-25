@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <avatar-form :user="{{$profileUser}}"></avatar-form>
                @foreach ($activities as $date => $activity)

                    <h3 clas="page-header">
                        {{$date}}
                    </h3>


                    @foreach($activity as $record)
                        @if( view()->exists('profiles.activities.'.$record->type))
                            @include('profiles.activities.'.$record->type)
                        @endif
                    @endforeach
                @endforeach

            </div>
        </div>
    </div>

@endsection