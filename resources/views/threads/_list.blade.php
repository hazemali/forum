@forelse ($threads as $thread)

    <div class="panel panel-default">
        <div class="panel-heading">

            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">

                            @if (auth()->check() && $thread->hasUpdatesFor())

                                <strong>
                                    {{$thread->title}}
                                </strong>

                            @else
                                {{$thread->title}}
                            @endif

                        </a>
                    </h4>
                    <h5>Posted By: <a
                                href="{{route('profiles.show',$thread->creator)}}">{{ $thread->creator->name }}</a>
                    </h5>
                </div>
                <strong>
                    <a href="{{ $thread->path() }}">
                        {{$thread->replies_count}} {{ str_plural('reply',$thread->replies_count) }}
                    </a>
                </strong>

            </div>

        </div>

        <div class="panel-body">
            {{$thread->body}}
        </div>

        <div class="panel-footer">
                      {{ $thread->visits }} Visits
        </div>

    </div>
@empty
    <p> There are no relevant results at this time.</p>
@endforelse
