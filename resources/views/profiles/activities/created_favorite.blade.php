@component('profiles.activities.activity')

    @slot('heading')
        <span class="flex">

               Favorited  <a href="{{$record->subject->favorited->path()}}">
                {{$record->subject->favorited->owner->name}}'s reply.
                </a>


            </span>
        {{$record->subject->created_at->diffForHumans()}}
    @endslot


    @slot('body')
        {{$record->subject->favorited->body}}

    @endslot

@endcomponent
