@extends('app')
@section('content')
    <div class="jumbotron">
        <div class="container">
            <h2>欢迎来到韩海波的社区
                <a class="btn btn-primary btn-lg pull-right" href="/discussions/create" role="button">发布新的帖子 »</a>

            </h2>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-9" role="main">
                @foreach($discussions as $discussion)
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object img-circle" alt="64×64" src="{{$discussion->user->avatar}}"width="64" height="64" style="align-content: flex-start">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="/discussions/{{$discussion->id}}">{{$discussion->title}}</a></h4>
                            {{$discussion->user->name}}
                            <div class="media-conversation-meta">
<span class="media-conversation-replies">
<a href="/discussion/154#reply">{{ count($discussion->comments) }}</a>
回复
</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @stop