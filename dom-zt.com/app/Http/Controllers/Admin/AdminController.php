<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Note;
use App\Models\Obekts;
use App\Users;
use App\Models\User;
use App\Models\Rieltors;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class AdminController extends AC
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexAdmin()
    {
        $countLand = Obekts::where('category_id', 3)->count();
        $countHouse = Obekts::where('category_id', 2)->count();
        $countFlat = Obekts::where('category_id', 1)->count();
        $countCommerceEstate = Obekts::where('category_id', 4)->count();

        return view('adminHome', compact('countCommerceEstate', 'countFlat', 'countHouse', 'countLand'));
    }

    // START - RIELTORS //

    public function getRieltors()
    {
        $dataRieltors = User::where('is_admin', 0)->get();

        return view('admin.rieltor.index', compact("dataRieltors"));
    }

    public function insertRieltor(Request $request)
    {
        // new
    }

    public function deleteRieltor($id)
    {
        // remove
        $data = User::find($id);

        if($data->delete()){
            return redirect('/manage/admin/rieltors');
        }

    }

    public function editRieltor(Request $request, $id)
    {
        // edit
    }

    public function viewRieltor($id)
    {
        // show detail page about rieltor

    }

    // END - RIELTORS //

    // START - CLEINTS //

    public function getClients()
    {
        $clients = Owner::all();

        return view('admin.clients', compact('clients'));
    }

    public function insertClients(Request $request)
    {
        $newOwner = new Owner();
        $newOwner->name = $request->input('name');
        $newOwner->phone = $request->input('phone');
        $newOwner->address = $request->input('address');

        if($newOwner->save()){
            return redirect('/manage/admin/clients');
        }
    }

    public function deleteClients($id)
    {
        $owner = Owner::find($id);

        if($owner->delete()){
            return redirect('/manage/admin/clients');
        }
    }

    // END - CLEINTS //

    // START - OBEKT //

    public function viewObekt($category)
    {
        // 3 - land
        // 2 - house
        // 1 - flat
        // 4 - commerce estate

        switch ($category)
        {
            case 'land':
            {
                $category = Category::where('id', '=', 3)->first();
                $categoryName = $category->name;
                $categorySlug = $category->slug;
                $category = [$categorySlug, $categoryName];
                $obekts = Obekts::where('category_id', '=', 3)->get();
                return view('admin.obekt', compact('obekts', 'category'));
            }
            case 'house' :
            {
                $category = Category::where('id', '=', 2)->first();
                $categoryName = $category->name;
                $categorySlug = $category->slug;
                $category = [$categorySlug, $categoryName];
                $obekts = Obekts::where('category_id', '=', 2)->get();
                return view('admin.obekt', compact('obekts', 'category'));
            }
            case 'flat' :
            {
                $category = Category::where('id', '=', 1)->first();
                $categoryName = $category->name;
                $categorySlug = $category->slug;
                $category = [$categorySlug, $categoryName];
                $obekts = Obekts::where('category_id', '=', 1)->get();
                return view('admin.obekt', compact('obekts', 'category'));
            }
            case 'commercial-real-estate' :
            {
                $category = Category::where('id', '=', 4)->first();
                $categoryName = $category->name;
                $categorySlug = $category->slug;
                $category = [$categorySlug, $categoryName];
                $obekts = Obekts::where('category_id', '=', 4)->get();
                return view('admin.obekt', compact('obekts', 'category'));
            }
        }

    }

    public function newObekt($categorySlug, $categoryName)
    {
        $category = [$categorySlug, $categoryName];

        return view('admin.obekt-new', compact('category'));
    }

    public function insertObekt(Request $request, $category)
    {
        // must be return to category page obekts
        return back()->with("success", "успішно.");
    }

    // END - OBEKT //

    // START - NOTE //

    public function note()
    {
        $getUserID = Auth::user()->id;
        $notes = Note::where('user_id', '=', $getUserID)->orderBy('id', 'desc')->paginate(10);

        $countNotes = count($notes);

        $obekts = Obekts::where('rieltor_id', '=', $getUserID)->get();

        return view('admin.note', compact('notes', 'countNotes', 'obekts'));
    }

    // END - NOTE //

    // START - BLOG //

    public function getBlog()
    {
        $blog = Blog::orderBy('id', 'desc')->get();//paginate(2);
        $countBlogItem = Blog::count();

        return view('admin.blog', compact('blog', 'countBlogItem'));
    }

    public function deleteBlog($id)
    {
        $blog = Blog::find($id);

        $image = $blog->picture;
        $path = public_path('files/images/blog');

        if(file_exists($path.$image))
        {
            unlink($path.$image);

            if($blog->delete()){

//                return redirect('/manage/admin/blog');
                return back()->with("success", "Пост видалено успішно.");
            }
        }

    }

    public function newBlog()
    {
        return view('admin.blog-new');
    }

    public function insertBlog(Request $request)
    {
        $blog = new Blog();
        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
        $blog->text = $request->input('text');

        $blog->author_id = 1;// Адміністратор
        $blog->category_id = 2;//Статті

        // Image save on server
        if ($request->hasFile('imgInp')) {
            // check validate
            $request->validate([
                'imgInp' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            ]);
            // generate new file name
            $imageName = time().'.'.$request->imgInp->extension();
            // move to folder image
            $request->imgInp->move(public_path('files/images/blog'), $imageName);
            // save new name image to database
            $blog->picture = $imageName;
        }

        // save data
        if($blog->save()){
//            return redirect('/manage/admin/blog');
            return back()->with("success", "Blog insert successfully.");
        }
    }

    //
    // END - BLOG
    //

    //
    // START - SETTINGS
    //

    public function settings()
    {
        return view('admin.settings');
    }

    public function settingsSave(Request $request)
    {
        $about_text = $request->input('about_text');
//        config()->set('adminsettings.about_text', $about_text);

        if(Config::set('adminsettings.about_text', $about_text)){
            return back()->with("success", "Налаштування збережено");
        }

        return back()->with("failed", "Помилка!");

    }

    //
    // END - SETTINGS
    //
}

