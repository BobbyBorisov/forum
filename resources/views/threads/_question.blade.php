<div class="panel panel-default" v-if="editing">
    <div class="panel-heading level-end">
        <div>
            <img src="{{asset($thread->creator->avatar_path)}}" style="margin-right:2px" width="25" height="25">
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
            <input type="text" class="form-control" v-model="form.title">
            <textarea name="body" class="form-control" id="body" cols="10" rows="10" v-model="form.body"></textarea>
        </article>
    </div>
    <div class="panel-footer">
        <button class="btn btn-xs btn-success" @click="update">Update</button>
        <button class="btn btn-xs btn-warning" @click="resetForm">Cancel</button>
    </div>
</div>

<div class="panel panel-default" v-else>
    <div class="panel-heading level-end">
        <div>
            <img src="{{asset($thread->creator->avatar_path)}}" style="margin-right:2px" width="25" height="25">
            <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> posted:
        </div>
    </div>
    <div class="panel-body">
        <article>
            <h4 v-text="title"></h4>
            <div v-text="body"></div>
        </article>
    </div>
    <div class="panel-footer" v-if="authorize('owns', this.data)">
        <button class="btn btn-xs btn-warning" @click="editing = true">Edit</button>
    </div>
</div>