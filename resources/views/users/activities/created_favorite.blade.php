<div class="panel panel-default">
    <div class="panel-heading level-end">
        <div>
            <span class="glyphicon glyphicon-heart" aria-hidden="true"></span> <a href="#">{{auth()->user()->name}}</a> favorited reply related to <a href="">{{$model->subject->favorited->thread->title}}</a>
        </div>
        <div>
            {{$model->created_at->diffForHumans()}}
        </div>
    </div>
    <div class="panel-body">
        <article>
            <div class="body">{{$model->subject->favorited->body}}</div>
        </article>
    </div>
</div>