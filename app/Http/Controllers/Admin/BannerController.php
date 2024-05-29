<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banners = Banner::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('banner_type', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $banners = Banner::orderBy('id', 'desc');
        }
        $banners = $banners->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.banner.view', compact('banners', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'image' => 'required',
        ], [
            'url.required' => 'url is required!',
            'image.required' => 'Image is required!',

        ]);

        $banner = new Banner;
        $banner->banner_type = $request->banner_type;
        $banner->resource_type = $request->resource_type;
        $banner->resource_id = $request[$request->resource_type . '_id'];
        $banner->title = $request->title;
        $banner->sub_title = $request->sub_title;
        $banner->button_text = $request->button_text;
        $banner->background_color = $request->background_color;
        $banner->url = $request->url;
        $banner->photo = ImageManager::upload('banner/', 'png', $request->file('image'));
        $banner->save();
        Toastr::success('Banner added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $banner = Banner::find($request->id);
            $banner->published = $request->status;
            $banner->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        $banner = Banner::where('id', $id)->first();
        return view('admin-views.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'url' => 'required',
        ], [
            'url.required' => 'url is required!',
        ]);

        $banner = Banner::find($id);
        $banner->banner_type = $request->banner_type;
        $banner->resource_type = $request->resource_type;
        $banner->resource_id = $request[$request->resource_type . '_id'];
        $banner->title = $request->title;
        $banner->sub_title = $request->sub_title;
        $banner->button_text = $request->button_text;
        $banner->background_color = $request->background_color;
        $banner->url = $request->url;
        if ($request->file('image')) {
            $banner->photo = ImageManager::update('banner/', $banner['photo'], 'png', $request->file('image'));
        }
        $banner->save();

        Toastr::success('Banner updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Banner::find($request->id);
        ImageManager::delete('/banner/' . $br['photo']);
        Banner::where('id', $request->id)->delete();
        return response()->json();
    }
}
