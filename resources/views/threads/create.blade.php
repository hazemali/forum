@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-8 col-md-offset-2">

                <div class="panel panel-default">
                    <div class="panel-heading">Create a new thread</div>

                    <div class="panel panel-body">

                        <div class="form-group">


                            <form method="post" action="{{route('threads.store')}}">

                                {{csrf_field()}}

                                <select name="channel_id" class="form-control" required>
                                    <option value="">Choose a channel...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id  }}"
                                                {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <input class="form-control" value="{{old('title')}}" name="title" type="text"
                                       placeholder="title" required/>

                                <textarea class="form-control" name="body" placeholder="What to discuss?"
                                          rows="8" required>{{old('body')}}</textarea>

                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">Start Discussion</button>
                                </div>
                                @if(count($errors))

                                    <ul class="alert alert-danger">

                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>

                                @endif

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection