<?php

namespace App\Services;

// import the Intervention Image Manager Class

use App\Imports\DataImport;
use App\Repositories\ActivityLog\ActivityLogRepository;
use Intervention\Image\ImageManager;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Excel;

abstract class BaseService extends Service
{
    protected $retunData = [];

    public function __construct()
    {
        $this->retunData['datatableID'] = 'datatable-main';
        $this->retunData['datatableURL'] = url()->full();
        $this->retunData['datatableVar'] = md5('datatable-main');
        $this->retunData['dataTableSort']['column'] = 0;
        $this->retunData['dataTableSort']['order'] = 'desc';

    }

    public function clearRetunData()
    {
        $this->retunData = [];
    }

    public function datatableURL(string $datatableURL)
    {
        $this->retunData['datatableURL'] = $datatableURL;
    }

    public function datatableID(string $datatableID)
    {
        $this->retunData['datatableID'] = $datatableID;
        $this->retunData['datatableVar'] = md5($datatableID);

    }

    public function pageTitle(string $title)
    {
        $this->retunData['pageTitle'] = __($title);
    }

    public function jsColumns(array $columns)
    {
        $this->retunData['jsColumns'] = $columns;
    }
    public function customClass( $custom_class ='')
    {
        if(isset($custom_class)) {
            $this->retunData['customClass'] = $custom_class;
        }
    }

    public function dataTableSort($column = null, $order = null)
    {
        if (isset($column))
            $this->retunData['dataTableSort']['column'] = $column;
        if (isset($order))
            $this->retunData['dataTableSort']['order'] = $order;
    }

    public function tableColumns(array $columns)
    {
        $this->retunData['tableColumns'] = $columns;
    }

    public function addButton(string $route, string $title = '')
    {
        $this->retunData['addButton'] = ['route' => $route, 'title' => $title];
    }

    public function addModalButton(string $route, string $title = '', string $modalId = '',$icon ='ki-outline ki-folder-up fs-2')
    {
        $this->retunData['addModalButton'][] = ['route' => $route, 'title' => $title, 'modalId' => $modalId,'icon' => $icon];
    }

    public function addModalIcon(string $route, string $modalId = '')
    {
        $this->retunData['addModalIcon'] = ['route' => $route, 'modalId' => $modalId];
    }

    public function downloadExcel(bool $download)
    {
        // TODO: Implement downloadExcel() method.
    }

    public function showAdvancedFilter(bool $boolean)
    {
        $this->retunData['showAdvancedFilter'] = $boolean;
    }

    public function filterIgnoreColumns(array $columns)
    {
        $this->retunData['filterIgnoreColumns'] = $columns;
    }

    public function otherData(array $data)
    {
        $this->retunData = array_merge($this->retunData, $data);
    }

    public function actionButtons($button)
    {
        $this->retunData['actionButtons'][] = $button;
    }

    public function editModalButton($button)
    {
        $this->retunData['editModalButton'] = $button;
    }

    public function actionButtonsRender($model = null, $model_id = null)
    {
        $buttons = $this->retunData['actionButtons'] ?? [];
        $this->retunData['actionButtons'] = [];
        return view('system.actionButtons',
            [
                'actionButtons' => $buttons,
                'model' => str_replace('\\', '\\\\', $model),
                'model_id' => $model_id
            ]);
    }

    public function uploadCatalogS3($file, $folderName, $type = false)
    {


        $directory = 'image/' . $folderName;

        $name_only = time() . "_" . md5(time());
        $name_only = rand(0, 99999999) . '_' . basename(html_entity_decode(preg_replace("/[^a-z0-9\_\-\.]/i", '', $name_only), ENT_QUOTES, 'UTF-8'));


        $extension = $file->getClientOriginalExtension();
        $mineType = $file->getClientMimeType();
        $original_name = '';

        if ($type == 'Img360') {
            $sizes = [['w' => '100', 'h' => '100'], ['w' => '150', 'h' => '200'], ['w' => '228', 'h' => '228'], ['w' => '1200', 'h' => '1200'], ['w' => '1500', 'h' => '1500']];
        } else if ($type == 'Catalog') {
            $sizes = [['w' => 'original', 'h' => 'original'], ['w' => '100', 'h' => '100'], ['w' => '1500', 'h' => '1500']];
        } else if ($type == 'product') {
            $sizes = [['w' => 'original', 'h' => 'original'], ['w' => '100', 'h' => '100'], ['w' => '150', 'h' => '200'], ['w' => '228', 'h' => '228'], ['w' => '500', 'h' => '500'], ['w' => '680', 'h' => '680'], ['w' => '1500', 'h' => '1500']];
        } else {
            $sizes = [['w' => 'original', 'h' => 'original'], ['w' => '100', 'h' => '100'], ['w' => '150', 'h' => '200'], ['w' => '228', 'h' => '228'], ['w' => '268', 'h' => '50'], ['w' => '500', 'h' => '500'], ['w' => '1140', 'h' => '380'], ['w' => '1140', 'h' => '300'], ['w' => '1200', 'h' => '498'], ['w' => '600', 'h' => '189'], ['w' => '750', 'h' => '460'], ['w' => '532', 'h' => '553'], ['w' => '1242', 'h' => '810'], ['w' => '1150', 'h' => '380'], ['w' => '432', 'h' => '370'], ['w' => '1456', 'h' => '264'], ['w' => '750', 'h' => '500'], ['w' => '571', 'h' => '300'], ['w' => '1500', 'h' => '1500']];
        }


//$dat = [];


        foreach ($sizes as $size) {
            $image = \Image::make($file);
            if ($size['w'] == 'original') {
                $new_name = $directory . '/' . $name_only . '.' . $extension;
                $original_name = $folderName . '/' . $name_only . '.' . $extension;

                $image->stream();
            } else {
                if ($mineType == 'image/png') {
                    $image->resize($size['w'], $size['h'])->save($file, 100, $mineType);
                } else {
                    $image = \Image::make($file)->resize($size['w'], $size['h']);
                    $image->save($file, 100);
                }
                $new_name = $directory . '/' . $name_only . '-' . $size['w'] . 'x' . $size['h'] . '.' . $extension;
            }
            Storage::disk('s3')->put($new_name, $image->__toString());


            // $dat[] = $file_name;
        }


        return $original_name;
    }


    /**
     * Optimizes PNG file with pngquant 1.8 or later (reduces file size of 24-bit/32-bit PNG images).
     *
     * You need to install pngquant 1.8 on the server (ancient version 1.0 won't work).
     * There's package for Debian/Ubuntu and RPM for other distributions on http://pngquant.org
     *
     * @param $path_to_png_file
     * @param int $max_quality
     * @return string|null
     * @throws Exception
     */
    function compress_png($path_to_png_file, $max_quality = 90, $_isImg360 = false)
    {
        if (!file_exists($path_to_png_file)) {
            throw new Exception("File does not exist: $path_to_png_file");
        }

        $max_quality = $_isImg360 ? 100 : $max_quality;
        $min_quality = $_isImg360 ? 99 : 60;

        // '-' makes it use stdout, required to save to $compressed_png_content variable
        // '<' makes it read from the given file path
        // escapeshellarg() makes this safe to use with any path
        $compressed_png_content = shell_exec("/usr/local/bin/pngquant --quality=$min_quality-$max_quality - < " . escapeshellarg($path_to_png_file));

        unlink($path_to_png_file);

        if (!$compressed_png_content) {
            throw new Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
        }

        return $compressed_png_content;
    }

    function compress_jpg($path_to_jpg_file, $max_quality = 90, $_isImg360 = false)
    {
        if (!file_exists($path_to_jpg_file)) {
            throw new Exception("File does not exist: $path_to_jpg_file");
        }

        // guarantee that quality won't be worse than that.
        $max_quality = $_isImg360 ? 100 : $max_quality;
        $min_quality = $_isImg360 ? 99 : 60;

        // '-' makes it use stdout, required to save to $compressed_png_content variable
        // '<' makes it read from the given file path
        // escapeshellarg() makes this safe to use with any path
        shell_exec("/usr/local/bin/jpegoptim -m60 " . escapeshellarg($path_to_jpg_file));


//        unlink($path_to_jpg_file);


//        if (!$compressed_jpg_content) {
//            throw new Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
//        }
//
//        return $compressed_jpg_content;
    }

    public function uploadFileS3($file, $folderName, $driver = 's3')
    {
        $file_name = '';
        $name_only = time() . "_" . md5(time());
        $name = $name_only . '.' . $file->getClientOriginalExtension();
        $success_upload = Storage::disk($driver)->put($folderName . '/' . $name, fopen($file, 'r+'));
        $file_name = $success_upload ? $folderName . '/' . $name_only . '.' . $file->getClientOriginalExtension() : $file_name;
        return $file_name;
    }


    public function uploadImage($image, $folderName)
    {
        return $image->store($folderName . '/' . date('y') . '/' . date('m'));
    }

    public function breadcrumb($text, $url = '')
    {
        $this->retunData['breadcrumb'][] = [
            'text' => __($text),
            'url' => $url ? route($url) : ''
        ];

        $this->retunData['breadcrumb'][] = [
            'text' => __($this->retunData['pageTitle']),
        ];

    }

    function token($length = 32): string
    {
        return Str::random($length);
    }

    function getCode($telephone)
    {

        switch (substr($telephone, 0, 3)) {
            case '966':
                return "sa";
                break;
            case "965":
                return "kw";
                break;
            case "971":
                return "ae";
                break;
            case "968":
                return "om";
                break;
            case "973":
                return "bh";
                break;
            default:
                return "sa";
        }
    }


    public function handleActivityLogData($newData,$oldData){
        if (empty($newData) && empty($oldData))
            return;

        if (!empty($oldData)) {
            $changed_new_data = [];
            $changed_old_data = [];

            foreach ($newData as $key => $row) {
                if ($newData[$key] != $oldData[$key]) {
                    $changed_old_data[$key] = $oldData[$key];
                    $changed_new_data[$key] = $newData[$key];
                }
            }
            if(empty($changed_new_data) && empty($changed_old_data))
                return ;
            $DATA = ['attributes' => $changed_new_data, 'old' => $changed_old_data];
        } else {
            $DATA = ['attributes' => $newData];
        }
        return $DATA;

    }


    public function ActivityLogManually($event,$subject_type,$subject_id,$newData,$oldData=[]){

        $DATA = $this->handleActivityLogData($newData,$oldData);

        $activityLogRepo = new ActivityLogRepository();
        $activityLogRepo->store([
            'log_name'=>config('activitylog.default_log_name'),
            'description'=>$event,
            'subject_id'=>$subject_id,
            'subject_type'=>$subject_type,
            'causer_id'=>\Auth::id(),
            'causer_type'=>\Auth::user()->modelPath,
            'ip'=>getRealIP(),
            'user_agent'=>getUserAgent(),
            'url'=>request()->url(),
            'properties'=>$DATA,
            'event'=>$event
         ]);
    }

    public function ActivityLog($event, $elquentModel, $newData, $oldData = [])
    {

        $DATA = $this->handleActivityLogData($newData,$oldData);


        activity()
            ->withProperties($DATA)
            ->performedOn($elquentModel)
            ->causedBy(auth()->user())
            ->event($event)
            ->log($event);


    }

    public function preview_excel($file){
        $data = Excel::toArray(new DataImport, $file);

        return $data;
    }
}



