<div class="panel panel-default">
    <div class="panel-heading level-end">
        <div>
            <a href="#">{{$model->subject->owner->name}}</a> replied to <a href="{{$model->subject->thread->path()}}">{{$model->subject->thread->title}}</a>
        </div>
        <div>
            {{$model->created_at->diffForHumans()}}
        </div>
    </div>
    <div class="panel-body">
        <article>
            <div class="body">{{$model->subject->body}}</div>
        </article>
    </div>
</div>