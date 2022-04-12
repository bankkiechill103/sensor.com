<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Image;
class ImageController extends Controller
{
  public function uploadFile($images)
  {
    $img = Image::make($images);
    $sizew = number_format(floor($img->width()), 0);
    $sizeh = number_format(floor($img->height()), 0);
    $img->resize($sizew, $sizeh);
    $date = date("YmdHis");
    $name = $date."-".$images->getClientOriginalName();
    $img->save('images/'.$name);
    return $name;
  }
  public function uploadGuild($images, $guild_id)
  {
    $img = Image::make($images);
    $sizew = $img->width();
    $sizeh = $img->height();
    $img->resize(64, 64);
    if($img->mime() == "image/jpeg"){
      $type = $guild_id.".jpeg";
    }elseif($img->mime() == "image/jpg"){
      $type = $guild_id.".jpg";
    }else{
      $type = $guild_id.".png";
    }
    $img->save('images/guilds/'.$type);
    return $type;
  }
  public function uploadArticleWarterMark($images)
  {
    $img = Image::make($images);
    $name = time()."-".$images->getClientOriginalName();
    // $img->insert('images/png-website/asset-01.png', 'bottom-right', 10, 10);
    $img->save('images/articles/'.$name);
    return $name;
  }
  public function imagesWarterMark(Request $request)
  {
    $images = $request->image;
    // $img = Image::cache(function($image) use ($request, $images) {
    //    $image->make('images/'.$images)->insert('images/example.png', $request->position);
    // }, 31104000, true);
    $img = Image::make('images/'.$images)->insert('images/png-website/asset-01.png', $request->position);
    $part_img_resize = 'images/resizes/wartermark_'.$request->image;
    $img->save($part_img_resize);
  }
  public function resizeImageWidth(Request $request)
  {
    $part_img = asset('images/resizes/'.$request->image);
    // $img = Image::cache(function($image) use ($request, $part_img) {
    //    $image->make($part_img)->resize($request->size, null, function ($constraint) {
    //        $constraint->aspectRatio();
    //    });
    // }, 31104000, true);
    $part_img_resize = 'images/resizes/'.$request->size."_".$request->image;
    $img = Image::make($part_img)->resize(null, $request->size, function ($constraint) {
      $constraint->aspectRatio();
    });
    $img->resize(null, $request->size);
    $img->save($part_img_resize);
  }
  public function resizeImageHight(Request $request)
  {
    $part_img = asset('images/'.$request->image);
    // $img = Image::cache(function($image) use ($request, $part_img) {
    //    $image->make($part_img)->resize(null, $request->size, function ($constraint) {
    //        $constraint->aspectRatio();
    //    });
    // }, 31104000, true);
    $part_img_resize = 'images/resizes/'.$request->size."_".$request->image;
    $img = Image::make($part_img)->resize(null, $request->size, function ($constraint) {
      $constraint->aspectRatio();
    });
    $img->resize($request->size, null);
    $img->save($part_img_resize);
  }
  public function imageCache(Request $request)
  {
    $part_img = asset('images/'.$request->image);
    $img = Image::cache(function($image) use ($part_img) {
       $image->make($part_img);
    }, 31104000, true);
    return $img->response();
  }
  public function resizetmagewidthHight(Request $request)
  {
    $part_img = asset('images/'.$request->image);
    // $img = Image::cache(function($image) use ($request, $part_img) {
    //    $image->make($part_img)->resize($request->sizew, $request->sizeh, function ($constraint) {
    //        $constraint->aspectRatio();
    //    });
    // }, 31104000, true);
    $part_img_resize = 'images/resizes/'.$request->sizew."x".$request->sizeh."_".$request->image;
    $img = Image::make($part_img)->resize($request->sizew, $request->sizeh, function ($constraint) {
      $constraint->aspectRatio();
    });
    $img->save($part_img_resize);
  }
}
