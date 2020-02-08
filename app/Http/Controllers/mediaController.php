<?php

namespace App\Http\Controllers;

// use Response;
use Illuminate\Http\Request;
use Response, Input, AWS;


class mediaController extends Controller
{
    private function upload($file, $folder = null, $filename = null){

        // get filename

        // S3: Do Space uploader
        $s3 = AWS::createClient('s3');
        $s3->putObject(array(
            'Bucket'     => env('AWS_BUCKET'),
            'Key'        => "kontena/" . $folder .  "/" . $filename,
            'SourceFile' => $file->image,
            'ACL'        => 'public-read'
        ));

        $rslt = $s3->getObject(array(
            'Bucket'     => env('AWS_BUCKET'),
            'Key'        => "kontena/" . $folder .  "/" . $filename,
            'SourceFile' => Input::file('cover_image'),
        ));

        // return url
        return $rslt['@metadata']['effectiveUri'];
    }

    //
    public function uploadImagePromotion(Request $request){
        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        $result = $this->upload($request, "Promotion", date("Y/m/d-") .time());

        return Response::json([
            'url' => $result
        ], 200);
    }

    public function uploadImageArticle(Request $request){
        // $request->validate([
        //     'image' => 'required|image|mimes:png|max:2048',
        // ]);
        $result = $this->upload($request, 'Article', date("Y/m/d-") .time());

        return Response::json([
            'url' => $result
        ], 200);
    }
}
