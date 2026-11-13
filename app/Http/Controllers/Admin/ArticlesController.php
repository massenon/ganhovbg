<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket\Category;
use App\Models\Articles\Article;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Illuminate\Support\Facades\Validator;
use File;
use DataTables;
use Illuminate\Support\Str;
use DB;
use Auth;
use App\Models\Subcategorychild;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Comment;

class ArticlesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->authorize('Article Access');
        $article = Article::get();
        $data['article'] = $article;

        $basic = Apptitle::first();
        $data['basic'] = $basic;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $articles = Article::latest()->get();
        $data['articles'] = $articles;

        return view('admin.article.index')-> with($data)->with('i', (request()->input('page', 1) - 1) * 5);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('Article Create');
        $category = Category::whereIn('display',['knowledge', 'both'])->where('status', '1')
        ->get();
        $data['category'] = $category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.article.create')-> with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('Article Create');

        $this->validate($request, [
            'title' => 'required|string|max:120',
            'category' => 'required',
            'message' => 'required',
            'tags' => 'required',
            'status' => 'required|in:Published,UnPublished',
        ]);

        $article = new Article();

        $article->title = $request->input('title');
        $article->category_id = $request->input('category');
        $article->message = $request->input('message');
        $article->status = $request->input('status');
        $article->tags = $request->input('tags');
        $article->subcategory = $request->input('subscategory');
        $article->privatemode = $request->input('privatemode') ? 1 : 0;

        $file = $request->featureimage;
        $fileinput = public_path('uploads/featureimage/' . $file);
        $article->featureimage = $file;

        $article->save();


        $articlefind = Article::where('articleslug', Str::slug($request->input('title'), '-'))->first();

        if(!$articlefind){
            $articlefinds = Article::find($article->id);
            $articlefinds->articleslug = Str::slug($request->input('title'), '-');
            $articlefinds->update();
        }
        if($articlefind){
            $articlefinds = Article::find($article->id);
            if($articlefinds->articleslug == null){
                $articlefinds->articleslug = Str::slug($request->input('title'), '-') .'-'. $articlefind->id;
                $articlefinds->update();
            }
        }

        foreach ($request->input('article', []) as $file) {
            $article->addMedia(public_path('uploads/article/' . $file))->toMediaCollection('article');
        }

        return response()->json(['success' => lang('A new article was successfully created.', 'alerts')], 200);
    }

    public function featureimagestoreMedia(Request $request){

        if($request->file('file')){
            $path = public_path('uploads/featureimage');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $request->file('file');

            $name = uniqid() . '_' . trim($file->getClientOriginalName());

            $file->move($path, $name);

            return response()->json([
                'name'          => $name,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }
    }
    public function storeMedia(Request $request)
    {
        if($request->file('file')){
            $path = public_path('uploads/article');

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file = $request->file('file');

            $name = uniqid() . '_' . trim($file->getClientOriginalName());

            $file->move($path, $name);

            return response()->json([
                'name'          => $name,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('Article Edit');
        $article = Article::where('id', $id)->firstOrFail();
        $category = Category::whereIn('display',['knowledge', 'both'])->where('status', '1')
        ->get();
        $data['category'] = $category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;
        if(request()->ajax()){
            $subcategory = '';
            $category1 = Subcategorychild::where('category_id',$article->category_id)->get();
            $totalrow1 = $category1->count();
            $ticket1 = DB::table('articles')->where('id', $id)->first();
            if($totalrow1 > 0){
                foreach($category1 as $categories){
                    foreach ($categories->subcatlists()->get() as $subcategorylist) {
                       $subcategory .= '
                        <option  value="'.$subcategorylist->id.'"'.($subcategorylist->id == $article->subcategory ? 'selected': '').'>'.$subcategorylist->subcategoryname.'</option>
                        ';
                    }
                }
            }else{
                $subcategory .= '
                <option label="No Data Found"></option>
                ';
            }

            return response()->json($subcategory, 200);
        }

        return view('admin.article.edit', compact('article', 'category','title','footertext'))->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('Article Edit');
        $articles = Article::where('id', $id)->findOrFail();
        $data['articles'] = $articles;

        return view('admin.article.edit')-> with($data);
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
        $this->authorize('Article Edit');
        $request->validate([
            'title' => 'required|string|max:120',
            'category' => 'required',
            'message' => 'required',
            'status' => 'required',

        ]);

        $article = Article::findOrFail($id);

        $article->title = $request->input('title');
        $article->category_id = $request->input('category');
        $article->message = $request->input('message');
        $article->status = $request->input('status');
        $article->tags = $request->input('tags');
        $article->subcategory = $request->input('subscategory');
        $article->privatemode = $request->input('privatemode') ? 1 : 0;

        $articlefind = Article::where('articleslug', Str::slug($request->input('title'), '-'))->first();

        if(!$articlefind){
            if($article->articleslug == null)
            {
                $article->articleslug = Str::slug($request->input('title'), '-');
            }
        }
        if($articlefind){

            if($article->articleslug == null)
            {
                $article->articleslug = Str::slug($request->input('title'), '-').'-'. $article->id;
            }
        }

        if($request->featureimage){
            $file = $request->featureimage;
            $destinations = public_path() . "" . '/uploads/featureimage/'.$article->featureimage;
            if(File::exists($destinations)){
                File::delete($destinations);
            }
            $fileinput = public_path('uploads/featureimage/' . $file);
            $article->featureimage = $file;
        }

        $article->update();
        $media = $article->getMedia('article');

        if($request->input('article', [])){
            foreach ($request->input('article', []) as $file) {
                $article->addMedia(public_path('uploads/article/' . $file))->toMediaCollection('article');
            }
        }

        return redirect('/administration/article/')->with('success', lang('This article has been successfully updated.', 'alerts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Article Delete');
        $article = Article::findOrFail($id);
        $media = $article->getMedia('article');

        foreach ($media as $media) {

            $media->delete();

        }
        $article->delete();
        return response()->json(['success'=>lang('The article was successfully deleted.', 'alerts')]);
    }

    public function articlemassdestroy(Request $request){
        $student_id_array = $request->input('id');

        $articles = Article::whereIn('id', $student_id_array)->get();

        foreach($articles as $article){

            foreach ($article->getMedia('article') as $media) {

                    $media->delete();

            }
            $article->delete();
        }
        return response()->json(['success'=> lang('The article was successfully deleted.', 'alerts')]);

    }

    public function article(Request $request)
    {

        $request->validate([
            'articletitle'=> 'required',
        ]);
        $calID = ['id' => $request->id];
        $calldetails = [
            'articletitle' => $request->articletitle,
            'articlesub' => $request->articlesub,
            'articlecheck'  => $request->has('articlecheck') ? 'on' : 'off',

        ];

        $callaction = Apptitle::updateOrCreate(
        ['id' => $calID], $calldetails);


        return redirect()->back()->with('success', lang('Updated successfully', 'alerts'));
    }


    public function status(Request $request, $id)
    {
        $calID = Article::find($id);
        $calID->status = $request->status;
        $calID->save();

        return response()->json(['code'=>200, 'success'=>lang('Updated successfully', 'alerts')], 200);

    }

    public function privatestatus(Request $request, $id)
    {
        $calID = Article::find($id);
        $calID->privatemode = $request->privatemode;

        $calID->save();

        return response()->json(['code'=>200, 'success'=>lang('Updated successfully', 'alerts')], 200);

    }



    public function featureimage(Request $request, $id)
    {
        $calID = Article::find($id);
        $calID ->featureimage = null;
        $calID ->save();


        return response()->json(['code'=>200, 'success'=> lang('Updated successfully', 'alerts')], 200);

    }

    public function ticketarticle( $ticket, $comment)
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $category = Category::whereIn('display',['knowledge', 'both'])->where('status', '1')->get();
        $data['category'] = $category;

        $articleticket = Ticket::where('ticket_id', $ticket)->first();
        $data['articleticket'] = $articleticket;

        $finalcomment = [];
        $com = explode(',', $comment);
        foreach($articleticket->comments as $co){
            if(in_array($co->id , $com)){
                array_push($finalcomment, $co->comment);
            }
        }
        $data['finalcomment'] = $finalcomment;

        // $finalcomment = [];
        // $com = explode(',', $comment);
        // foreach($com as $coms){
        //     $comments = Comment::find($coms);
        //     array_push($finalcomment, $comments->comment);
        // }
        // $data['finalcomment'] = $finalcomment;

        return view('admin.article.ticketarticle')->with($data);
    }
}
