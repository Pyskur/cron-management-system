<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Validator;


class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = Video::all();
        return response()->json($videos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
 
        $validator = Validator::make($input, [
            'title' => 'required|string',
            'filename' => 'required|string',
            'status' => 'required|numeric',
            'file_size' => 'required|string|max:45',
            'geo_restrict' => 'required|numeric',
            'thumbnail' => 'required|string|max:45',
            'parent_name' => 'required|string|max:45',
            'url' => 'required|string',
            'drm_enabled' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);
        
        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $video = Video::create($input);
            return response()->json($video);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Video store error";
            }
            return response()->json([
                "error" => "Error",
                "code"=> 0,
                "message"=> $message
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        return response()->json($video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'nullable|string',
            'filename' => 'nullable|string',
            'status' => 'nullable|numeric',
            'file_size' => 'nullable|string|max:45',
            'geo_restrict' => 'nullable|numeric',
            'thumbnail' => 'nullable|string|max:45',
            'parent_name' => 'nullable|string|max:45',
            'url' => 'nullable|string',
            'drm_enabled' => 'nullable|numeric',
            'user_id' => 'nullable|numeric'
        ]);

        if($validator->fails()){
            return response()->json([
                "error" => "Validation Error",
                "code"=> 0,
                "message"=> $validator->errors()
            ]);
        }

        try {
            $video->update($input);
            return response()->json($video);
        } catch (\Exception $e) {
            if (App::environment('local')) {
                $message = $e->getMessage();
            }
            else{
                $message = "Video update error";
            }
            return response()->json([
                "error" => "Error",
                "code"=> 0,
                "message"=> $message
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $video->delete();
        return response()->json();
    }
}
