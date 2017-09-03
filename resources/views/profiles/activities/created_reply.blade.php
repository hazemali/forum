@component('profiles.activities.activity')

    @slot('heading')
            <span class="flex">

             Replied to
                <a href="{{$record->subject->thread->path()}}">
                {{$record->subject->thread->title}}
                </a>


            </span>
            {{$record->subject->created_at->diffForHumans()}}
    @endslot


    @slot('body')
        {{$record->subject->body}}

    @endslot

@endcomponent
