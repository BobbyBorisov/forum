@extends('layouts.app')

@section('header')
    <link href="/css/vendor/jquery.atwho.css" rel="stylesheet">
@endsection

@section('content')
    <thread-view :data='{{$thread}}' inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('threads._question')

                    <replies @add="repliesCount++" @remove="repliesCount--"></replies>

                    {{--{{$replies->links()}}--}}

                    @guest
                        <p class="text-center">Please <a href="{{route('login')}}">sign in</a> to participate in this discussion</p>
                    @endguest
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Info
                        </div>
                        <div class="panel-body">
                            <p>Created: {{$thread->created_at->diffForHumans()}}</p>
                            <p>Creator: <a href="#">{{$thread->creator->name}}</a></p>
                            <p>{{str_plural('Comment', $thread->replies_count)}}: <span v-text="repliesCount"></span></p>
                            <p>{{str_plural('Subscriber', $thread->subscriptions_count)}}: {{$thread->subscriptions_count}}</p>
                            <div class="level">
                                <subscription-button :active="{{json_encode($thread->isSubscribed)}}" class="mr-3"></subscription-button>
                                <button class="btn btn-primary" v-if="authorize('isAdmin')" v-text="this.locked ? 'Unlock' : 'Lock'" @click="toggleLock"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
