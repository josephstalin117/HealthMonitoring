<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Response;
use Auth;
use App\Http\Requests;

class ArticleController extends Controller {
    //
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $this->authorize('userManage', Auth::user());
        $keyword = $request->input('keyword');

        $articles = Article::where('title', 'LIKE', "%$keyword%")->get();
        return view('article.index', [
            'articles' => $articles,
            'keyword' => $keyword
        ]);
    }

    public function show($id) {

        $article = Article::findOrFail($id);
        return view('article.show', [
            'article' => $article,
        ]);
    }


    public function list_articles(Request $request) {
        $keyword = $request->input('keyword');

        $articles = Article::where('title', 'LIKE', "%$keyword%")->get();
        return view('article.list', [
            'articles' => $articles,
            'keyword' => $keyword
        ]);
    }

    public function destroy(Request $request, $id) {

        try {
            $pressure = Article::findOrFail($id);
            $pressure->delete();
            $response = [
                "status" => "success",
            ];

            $request->session()->flash('success', '删除成功');
            return Response::json($response, 200);
        } catch (\Exception $e) {
            return Response::json("{}", 404);
        }
    }

    public function create() {

        $this->authorize('userManage', Auth::user());

        return view('article.create');
    }

    public function update($id) {
        $this->authorize('userManage', Auth::user());
        $article = Article::findOrFail($id);

        return view('article.update', [
            'article' => $article
        ]);

    }

    public function store(Request $request) {

        $this->authorize('userManage', Auth::user());

        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($request->input('id')) {
            $article = Article::findOrFail($request->input('id'));
        } else {
            $article = new Article();
        }

        $article->title = $request->input('title');
        $article->content = $request->input('content');
        $article->save();


        if ($article) {
            $request->session()->flash('success', '更新一个文章');
        } else {
            $request->session()->flash('error', '失败');
        }

        return redirect('/articlemanage');
    }
}
