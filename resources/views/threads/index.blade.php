@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
                <div style="display:flex;justify-content:center">
                    {{$threads->links()}}
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Trending threads</div>
                    <div class="panel-body">
                        @if(count($trending)==0)
                            No trending threads
                        @endif
                        <ul class="list-group">
                        @foreach($trending as $thread)
                            <li class="list-group-item">
                                <a href="{{url($thread->path)}}">
                                   {{$thread->title}}
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
