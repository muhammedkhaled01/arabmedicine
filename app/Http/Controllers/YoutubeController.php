<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YouTube\YouTubeDownloader;
use YouTube\Exception\YouTubeException;
class YoutubeController extends Controller
{
    //
    // return response($response, 200);
    public function get_youtube_qualities(Request $request)
    {
        $youtube = new YouTubeDownloader();

    try {
        $formats = [];
        $full_formats = [];
        $downloadOptions = $youtube->getDownloadLinks($request->youtube_url);
        if ($downloadOptions->getAllFormats()) {
            
            foreach($downloadOptions->getCombinedFormats() as $format){
                array_push($formats, ['quality' => $format->qualityLabel, 'url' => $format->url]);
                array_push($full_formats, $format);
            }
        } else {
            $data['url'] = [];
            return response($data, 203);
        }

        $data['url'] = $formats;
        return response($data, 200);
        
        } catch (YouTubeException $e) {
            $data['url'] = [];
            return response($data, 201);
        }
    }
}
