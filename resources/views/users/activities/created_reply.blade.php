<div class="panel panel-default">
    <div class="panel-heading">
    <a href="#">{{$model->subject->owner->name}}</a> posted: {{$model->created_at->diffForHumans()}}
    </div>
    <div class="panel-body">
    <article>
    <h4>The user published reply to <a href="{{$model->subject->thread->path()}}">{{$model->subject->thread->title}}</a></h4>
    <div class="body">{{$model->subject->body}}</div>
    </article>
    </div>
</div>