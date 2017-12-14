<div class="panel panel-default">
    <div class="panel-heading level-end">
        <div>
            <img src="{{asset($thread->creator->avatar())}}" style="margin-right:2px" width="25" height="25">
            <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> posted:
        </div>
        @can('delete', $thread)
            <div>
                <form method="POST" action="{{ route('thread.delete', $thread) }}">
                    {{csrf_field()}}
                    {{method_field("DELETE")}}
                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="confirmation">Delete</button>
                </form>
            </div>
        @endcan
    </div>
    <div class="panel-body">
        <article>
            <h4>{{$thread->title}}</h4>
            <div class="body">{{$thread->body}}</div>
        </article>
    </div>
</div>