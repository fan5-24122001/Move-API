<?php
use Illuminate\Support\Facades\File;

function imageDelete($urlImage)
{
  File::delete(public_path(str_replace(url('') . '/', '', $urlImage)));
}

function imgCreate( $request,$thumbnail)
{
  $extension2 = $request->thumbnail->extension();
  $urlHost = url('');
  $img2Name = $urlHost . '/Videovimeo/' . time() . '.' . $extension2;
  $thumbnail->move(public_path('Videovimeo'), $img2Name);
  return $img2Name;
}
