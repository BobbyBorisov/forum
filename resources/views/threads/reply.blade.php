<div class="panel panel-default">
    <div class="panel-heading level-end">
        <div>
            {{$reply->owner->name}} said
            {{$reply->created_at->diffForHumans()}}
        </div>
        <div class="gotoend">
        @auth
                @can('delete', $reply)
                    <div>
                        <form method="POST" action="{{ route('reply.delete', $reply) }}">
                            {{csrf_field()}}
                            {{method_field("DELETE")}}
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                @endcan

            @if($reply->isFavorited())
                <form method="POST" action="/replies/{{$reply->id}}/favorites">
                    {{csrf_field()}}
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-danger">
                        {{$reply->favorites_count}} {{str_plural('Favorite', $reply->favorites_count)}}
                    </button>
                </form>
            @else
                <form method="POST" action="/replies/{{$reply->id}}/favorites">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-sm btn-success">
                        {{$reply->favorites_count}} {{str_plural('Favorite', $reply->favorites_count)}}                    </button>
                </form>
            @endif
        </div>
        @endauth
    </div>

    <div class="panel-body">
        <article>
            <div class="body">{{$reply->body}}</div>
        </article>
    </div>
</div>