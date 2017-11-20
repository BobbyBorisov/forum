<div class="panel panel-default">
    <div class="panel-heading level-end">
        <div flex>
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><a href="#">{{$model->subject->creator->name}}</a> created thread <a href="{{$model->subject->path()}}">{{$model->subject->title}}</a>
        </div>
        <div>
            {{$model->subject->created_at->diffForHumans()}}
        </div>
    </div>
    <div class="panel-body">
        <article>
            <div class="body">{{$model->subject->body}}</div>
        </article>
    </div>
</div>