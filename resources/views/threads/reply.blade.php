<reply :attributes="{{$reply}}" inline-template>
    <div class="panel panel-default">
        <div class="panel-heading level-end">
            <div>
                {{$reply->owner->name}} said
                {{$reply->created_at->diffForHumans()}}
            </div>
            <div class="gotoend">
            @auth
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
            <div v-if="editing">
                <div class="form-group">
                    <textarea v-model="body" class="form-control"></textarea>
                </div>
                <button class="btn btn-xs btn-success" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body">
            </div>
        </div>

        <div class="panel-footer">
            <button class="btn btn-xs" @click="editing = true">Edit</button>
            <button class="btn btn-xs btn-danger" @click="destroy">Delete</button>
        </div>
    </div>
</reply>