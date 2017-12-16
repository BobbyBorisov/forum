@foreach ($threads as $thread)
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                            <a href="{{$thread->path()}}"><strong>{{$thread->title}}</strong></a>
                        @else
                            <a href="{{$thread->path()}}">{{$thread->title}}</a>
                        @endif
                    </h4>
                    <h5>Posted by: <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a></h5>
                </div>
                <strong>
                    {{$thread->replies_count}} replies.
                </strong>
            </div>
        </div>
        <div class="panel-body">
            <div class="body">{{$thread->body}}</div>
        </div>
        <div class="panel-footer">
            {{$thread->visits()->count()}} visits
        </div>
    </div>
@endforeach