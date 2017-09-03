@component('profiles.activities.activity')

    @slot('heading')
        <span class="flex">
            Published
                <a href="{{$record->subject->path()}}">
                {{$record->subject->title}}
                </a>
            </span>
        {{$record->subject->created_at->diffForHumans()}}
    @endslot

    @slot('body')

        {{$record->subject->body}}
    @endslot


@endcomponent
