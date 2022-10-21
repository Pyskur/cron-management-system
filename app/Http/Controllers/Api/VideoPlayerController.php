<?php

namespace App\Http\Controllers\Api;

use App\Models\VideoPlayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Auth;

class VideoPlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->isAdmin()){
            $videoPlayers = VideoPlayer::all();
            return response()->json($videoPlayers);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'Not Authorized.'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user();
        $videoPlayer = new VideoPlayer();
        $videoPlayer->user_id = $user->id;
        $videoPlayer->config = [
            "play_icon" => [
                "enable" => "true",
                "color" => "#AAAAAA",
                "position" => "center"
            ],
            "pause_icon" => [
                "enable" => "true",
                "color" => "#AAAAAA",
                "position" => "center"
            ],
            "progress_bar" => [
                "enable" => "true",
                "progress_thumb_color" => "#AAAAAA",
                "progress_played_color" => "#AAAAAA",
                "position" => "bottom"
            ] 
        ];

        $videoPlayer->save();
        return response()->json($videoPlayer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VideoPlayer  $videoPlayer
     * @return \Illuminate\Http\Response
     */
    public function show(VideoPlayer $videoPlayer)
    {
        $data = $videoPlayer->config;
        $data['player_id'] = $videoPlayer->id;
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VideoPlayer  $videoPlayer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VideoPlayer $videoPlayer)
    {
        //
        if(Auth::user()->isAdmin() || Auth::user()->id == $videoPlayer->user_id)
        {
            $updateList = [];
            $input = $request->all();
            if(array_key_exists('play_icon', $input)){
                if(array_key_exists('enable', $input['play_icon'])){
                    $updateList['config->play_icon->enable'] = $input['play_icon']['enable'];
                }
                if(array_key_exists('color', $input['play_icon'])){
                    $updateList['config->play_icon->color'] = $input['play_icon']['color'];
                }
                if(array_key_exists('position', $input['play_icon'])){
                    $updateList['config->play_icon->position'] = $input['play_icon']['position'];
                }
            }
            if(array_key_exists('pause_icon', $input)){
                if(array_key_exists('enable', $input['pause_icon'])){
                    $updateList['config->pause_icon->enable'] = $input['pause_icon']['enable'];
                }
                if(array_key_exists('color', $input['pause_icon'])){
                    $updateList['config->pause_icon->color'] = $input['pause_icon']['color'];
                }
                if(array_key_exists('position', $input['pause_icon'])){
                    $updateList['config->pause_icon->position'] = $input['pause_icon']['position'];
                }
            }
            if(array_key_exists('progress_bar', $input)){
                if(array_key_exists('enable', $input['progress_bar'])){
                    $updateList['config->progress_bar->enable'] = $input['progress_bar']['enable'];
                }
                if(array_key_exists('progress_thumb_color', $input['progress_bar'])){
                    $updateList['config->progress_bar->progress_thumb_color'] = $input['progress_bar']['progress_thumb_color'];
                }
                if(array_key_exists('progress_played_color', $input['progress_bar'])){
                    $updateList['config->progress_bar->progress_played_color'] = $input['progress_bar']['progress_played_color'];
                }
                if(array_key_exists('position', $input['progress_bar'])){
                    $updateList['config->progress_bar->position'] = $input['progress_bar']['position'];
                }
            }

            $videoPlayer->forceFill($updateList)->save();
            return response()->json($videoPlayer);
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this videoPlayer'
            ]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VideoPlayer  $videoPlayer
     * @return \Illuminate\Http\Response
     */
    public function destroy(VideoPlayer $videoPlayer)
    {
        if(Auth::user()->isAdmin() || Auth::user()->id == $videoPlayer->user_id)
        {
            //delete table
            $videoPlayer->delete();
            return response()->json(
                [
                    "result" =>  true
                ]
            );
        }
        else{
            return response()->json([
                'error'=> 'Error',
                'message' => 'User is not owner of this videoPlayer'
            ]);
        }
    }
}
