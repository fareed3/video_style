<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Video;
class Categories extends Controller
{

public function index()
  {
    $categories = Category::all();
    return view('back-end.Admin.allcategory',compact('categories'));
  }

  public function add(Request $request)
  {

    	$category= new Category;
    	$category->name=$request->input('name');
    	$category->des=$request->input('des');
        $category->icon=$this->uploade_icons($request);
    	$category->save();
    	return back();
  }


 public function delete($id)
 {
  Category::where('id',$id)->delete();
  return redirect('/allcategory');
}


public function edit($id)
{
	$category=Category::find($id);
	if (empty($category)){
		return redirect('/allcategory');
	}
	return view('back-end.Admin.updatecategory' , compact('category'));
}


public function update( Request $request , $id){
	$category=Category::find($id);
	$category->update([
		'name'=>$request->get('name'),

		'des'=>$request->get('des'),

                'icon'=> $this->uploade_icons($request)
	]);
			return  redirect('allcategory');
}



  protected  function uploade_icons($request)
    {
        $file=$request->file("icon");

        $filename= time().str_random(10).".".$file->getClientOriginalExtension();

        $file->move(public_path('icons'),$filename);

        return $filename;
    }
    //Mina
    public function allcategory()
    {
      $all = Category::all();
      $catvideos = Video::where('Category_id','=','1')->get();
      $category = Category::where('id','=','1')->first();
      $type = $category->name;
      return view('back-end.Admin.viewcat',compact('all','catvideos','type'));
    }

    public function myvideos($id)
    {
      // Get Videos For Secific Category
      $catvideos = Video::where('Category_id','=',$id)->get();

      // Get Category Name
      $cateName = Category::where('id','=',$id)->first();
      $type = $cateName->name;

      // To Show All Category
      $all = Category::all();

      return view('back-end.Admin.viewcat',compact('all','catvideos','type'));
    }
    public function showvideo($id)
    {
      $pc = false;
      $pVideo = Video::where('id',$id)->first();
      $url = $pVideo->Link;
      if(is_null($url))
      {
        $pc = true;
        $url = $pVideo->src;
      }
      return view('back-end.Admin.showvideo',compact('url','pc'));
    }
}
