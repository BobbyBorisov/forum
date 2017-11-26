@extends('layouts.app')

@section('content')
    <thread-view :data='{{$thread}}' inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('threads._question')

                    <replies :data="{{$replies}}" @add="repliesCount++" @remove="repliesCount--"></replies>

                    {{--{{$replies->links()}}--}}

                    @guest
                        <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion</p>
                    @endguest
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Statsxdas
                        </div>
                        <div class="panel-body">
                            <p>Created: {{$thread->created_at->diffForHumans()}}</p>
                            <p>Creator: <a href="#">{{$thread->creator->name}}</a></p>
                            <p>{{str_plural('Comment', $thread->replies_count)}}: <span v-text="repliesCount"></span></p>
                            <p>{{str_plural('Subscriber', $thread->subscriptions_count)}}: {{$thread->subscriptions_count}}</p>
                            <subscription-button :active="{{json_encode($thread->isSubscribed)}}"></subscription-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
