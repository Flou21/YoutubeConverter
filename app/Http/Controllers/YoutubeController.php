<?php

namespace App\Http\Controllers;

use App\Models\Youtube\DownloadTypes;
use App\VideoStream;
use App\YoutubeData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class YoutubeController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function convert(Request $request): Response
    {
        $body = json_decode($request->getContent());
        $url = $body->url;
        $type = $body->type;
        $downloadId = uniqid('', true);
        $downloadLink = $this->getServerUrl() . '/youtube/' . $downloadId;


        $result = shell_exec('/bin/bash ../app/Scripts/youtubeDownload.sh ' . $url . ' ' . $type . ' ' . $downloadId);

        $title = substr($result, strpos($result, 'Deleting original file') + 23, strlen($result) - strpos($result, '(pass -k to') - 60);

        $data = new YoutubeData();
        $data->title = $title;
        $data->filename = $downloadId;
        $data->save();

        return new Response(['download_link' => $downloadLink, 'title' => $title, 'uid' => $downloadId]);
    }

    public function download(Request $request)
    {
        $body = json_decode($request->getContent());
        $uid = $body->uid;

        // $tempVideo = tempnam(sys_get_temp_dir(), $uid);
        // copy($path, $tempVideo);


        $path = public_path('./youtube/' . $uid);

        $stream = new VideoStream($path);

        return response()->stream(function() use ($stream) {
           $stream->start();
        });
    }

    public function types()
    {
        return new Response([
            DownloadTypes::VIDEO, DownloadTypes::AUDIO, DownloadTypes::THUMBNAIL
        ]);
    }

    private function getServerUrl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
        else
            $link = "http";

        // Here append the common URL characters.
        $link .= "://";

        // Append the host(domain name, ip) to the URL.
        $link .= $_SERVER['HTTP_HOST'];

        return $link;
    }
}
