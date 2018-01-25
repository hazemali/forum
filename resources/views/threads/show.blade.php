@extends('layouts.app')

@section('content')

    <thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <div class="level">
                            <span class="flex">
                             <img class="" src="{{$thread->creator->avatar_path}}"
                                         alt="{{$thread->creator->name}}" width="25" height="25"/>

                                <a href="{{$thread->creator->path()}}">
                                    {{$thread->creator->name}}
                                </a>
                                posted:
                                {{$thread->title}}
                            </span>

                                @can('update',$thread)
                                    <form id="deleteThreadForm" method="post" action="{{$thread->path()}}">
                                        {{csrf_field()}}
                                        {{ method_field('DELETE') }}

                                        <button class="btn btn-link" type="submit">Delete</button>
                                    </form>
                                @endcan

                            </div>

                        </div>

                        <div class="panel-body">

                            {{$thread->body}}

                        </div>
                    </div>

                    <replies @removed="repliesCount--" @added='repliesCount++'></replies>


                </div>

                <div class="col-md-4">


                    <div class="panel panel-default">

                        <div class="panel-body">

                            This thread was published {{ $thread->created_at->diffForHumans() }}
                            by <a href="{{ $thread->creator->path() }}">{{ $thread->creator->name }}</a> , and currently
                            has <span v-text="repliesCount"></span> {{ str_plural('comment',$thread->replies_count) }}.


                            <p>

                                <subscribe-button
                                        :active="{{ json_encode($thread->isSubscribed) }}">
                                </subscribe-button>
                            </p>

                        </div>


                    </div>


                </div>


            </div>
        </div>

    </thread-view>


@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.min.css') }}"/>
@endsection