<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Markdown\Markdown;
use Illuminate\Http\Request;

use App\Http\Requests;
use YuanChao\Editor\EndaEditor;
class PostsController extends Controller
{
    protected $markdown;
    public function __construct(Markdown $markdown)
    {
        $this->middleware('auth',['only'=>['create','store','edit','updata']]);
        $this->markdown=$markdown;
    }
    public function index()
    {
        $discussions=Discussion::latest()->get();
      return view('forum.index',compact('discussions'));
   }

    public function show($id)
    {
       $discussions=Discussion::findOrFail($id);
       $html=$this->markdown->markdown($discussions->body);
       return view('forum.show',compact('discussions','html'));
   }

    public function create()
    {
        return view('forum.create');
   }

    public function store(Requests\StoreBlogPostRequset $request)
    {
        $data=[
          'user_id'=>\Auth::user()->id,
            'last_user_id'=>\Auth::user()->id,
        ];
        $discussion=Discussion::create(array_merge($request->all(),$data));
        return redirect()->action('PostsController@show',['id'=>$discussion->id]);
    }

    public function edit($id)
    {
        $discussion=Discussion::findOrFail($id);
        if (\Auth::user()->id!==$discussion->user_id){
            return redirect('/');
        }
        return view('forum.edit',compact('discussion'));
    }

    public function update(Requests\StoreBlogPostRequset $request,$id)
    {
        $discussion=Discussion::findOrFail($id);
        $discussion->update($request->all());
        return redirect()->action('PostsController@show',['id'=>$discussion->id]);
    }

    public function destroy($id)
    {
        Discussion::destroy($id);
        return redirect('/');
    }

    public function upload()
    {
        $data = EndaEditor::uploadImgFile('uploads');

        return json_encode($data);
    }
}
