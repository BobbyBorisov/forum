@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading level-end">
                        <div>
                            <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a> posted:
                        </div>
                        @can('delete', $thread)
                            <div>
                                <form method="POST" action="{{ route('thread.delete', $thread) }}">
                                    {{csrf_field()}}
                                    {{method_field("DELETE")}}
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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

                @foreach ($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{$replies->links()}}

                <form method="POST" action="{{$thread->path().'/replies'}}">
                    {{csrf_field()}}
                    <div class="form-group">
                        <textarea name="body" id="body" class="form-control" placeholder="Have something to say?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default">Post</button>
                </form>

                @guest
                    <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion</p>
                @endguest
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Stats
                    </div>
                    <div class="panel-body">
                        <p>Created: {{$thread->created_at->diffForHumans()}}</p>
                        <p>Creator: <a href="#">{{$thread->creator->name}}</a></p>
                        <p>{{str_plural('Reply', $thread->replies_count)}}: {{$thread->replies_count}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
