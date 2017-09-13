@extends('app')
@section('content')
    <div class="jumbotron">
        <div class="container">
            <div class="media">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object img-circle" alt="64×64" src="{{$discussions->user->avatar}}"width="64" height="64" style="align-content: flex-start">
                    </a>
                </div>
                <div class="media-body">
                    @if(Auth::check()&&Auth::user()->id==$discussions->user_id)
                    <h4 class="media-heading">{{$discussions->title}}  <a class="btn btn-primary btn-lg pull-right" href="/discussions/{{$discussions->id}}/edit" role="button">修改帖子 »</a></h4>
                    @endif
                        {{$discussions->user->name}}
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-9" role="main">
{!! $html !!}
            </div>
        </div>
        {{ Form::open(array('method' => 'DELETE', 'route' => array('discussions.destroy',$discussions->id))) }}
        {{ Form::submit('删除帖子') }}
        {{ Form::close() }}
<hr>
               @foreach($discussions->comments as $comment)
                    <div class="media">
                       <div class="media-left">
                           <a href="#">
                           <img class="media-object img-circle"  src="{{$comment->user->avatar}}" style="width: 64px; height: 64px" alt="64×64">
                           </a>
                       </div>
                      <div class="media-body">
                        <h4 class="media-heading">{{$comment->user->name}}</h4>
                          {{$comment->body}}
                      </div>
                     </div>
                   <div class="pull-right">
                       {{ Form::open(array('method' => 'DELETE', 'route' => array('comments.destroy',$comment->id))) }}
                       {{ Form::submit('删除回复') }}
                       {{ Form::close() }}
                   </div>
                    @endforeach

        <hr>
        @if(Auth::check())
        {!! Form::open(['url'=>'/comments']) !!}
        {!! Form::hidden('discussion_id',$discussions->id) !!}
        <div class="form-group">
            {!! Form::label('body', 'Body:') !!}
            {!! Form::textarea('body', null, ['class' => 'form-control']) !!}
        </div>
<div>
    {!! Form::submit('发表评论',['class'=>'btn btn-success pull-right']) !!}
</div>
{!! Form::close() !!}
            @else
            <a href="/user/login" class="btn btn-block btn-success">登陆参与评论</a>
            @endif
</div>

@stop