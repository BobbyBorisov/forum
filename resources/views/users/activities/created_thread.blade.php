<div class="panel panel-default">
    <div class="panel-heading">
        <a href="#">{{$model->subject->creator->name}}</a> posted: {{$model->subject->created_at->diffForHumans()}}
    </div>
    <div class="panel-body">
        <article>
            <h4>{{$model->subject->title}}</h4>
            <div class="body">{{$model->subject->body}}</div>
        </article>
    </div>
</div>