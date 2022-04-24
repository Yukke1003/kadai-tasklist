<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task; //追加

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Auth::check()){
            //タスク一覧の取得
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('id','desc')->paginate(25);
            
            //メッセージ一覧のビューでそれを表示
            return view('tasks.index',[
                'tasks' => $tasks,
                ]);
        }else{
            return view('tasks.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        //メッセージ作成ビューの表示
        return view('tasks.create',[
            'task' => $task,
            ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        // 認証済みユーザを取得
            $user = \Auth::user();
        
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status
        ]);
        // タスクを作成
        // $task = new Task;
        // $task->content = $request->content;
        // $task->status = 
        // $task->save();
        
        
        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            return view('tasks.show', [
            'task' => $task,
            ]);
        }else{
            return back();
        }
        // メッセージ詳細ビューでそれを表示
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
        // メッセージ編集ビューでそれを表示
        if (\Auth::id() === $task->user_id) {
            return view('tasks.edit', [
            'task' => $task,
            ]);
        }else{
            return back();
        }
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
