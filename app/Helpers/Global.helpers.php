<?php

use App\Enums\{StatusEnum};
use App\Models\Folder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use App\Repositories\Folder\FolderRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Models\Media;

function img($path)
{
    return asset('storage/' . $path);
}

function exportXLS($title, $heads, $exData, $callback)
{
    return view('system.partials.divs.export_xls', compact('title', 'heads', 'exData', 'callback'));
}

function pda($ob)
{
    print_r($ob->toArray());
    die;
}

function pd($ob)
{
    print_r($ob);
    die;
}

function direction()
{
    if (lang() == 'ar') {
        return '.rtl';
    } else {
        return '';
    }
}

function lang()
{
    $language = 'en';
    if (app()->getLocale() == 'ar')
        $language = 'ar';
    return $language;
}

function opencart_lang()
{
    $language = 'en-gb';
    if (app()->getLocale() == 'ar')
        $language = 'ar';
    return $language;
}

function languageId()
{
    $language_id = 1;
    if (lang() == 'ar')
        $language_id = 2;
    return $language_id;
}

function whereBetween(&$eloquent, $columnName, $form, $to)
{
    if (!empty($form) && empty($to)) {
        $eloquent->whereRaw("$columnName >= ?", [$form]);
    } elseif (empty($form) && !empty($to)) {
        $eloquent->whereRaw("$columnName <= ?", [$to]);
    } elseif (!empty($form) && !empty($to)) {
        $eloquent->where(function ($query) use ($columnName, $form, $to) {
            $query->whereRaw("$columnName BETWEEN ? AND ?", [$form, $to]);
        });
    }
}

function imageResize($imagePath, $width, $height)
{
    $vImagePath = $imagePath;
    $imagePath = storage_path('app/public/' . $imagePath);

    if (File::exists($imagePath) && explode('/', File::mimeType($imagePath))[0] == 'image') {
        $resizedFileName = File::dirname($imagePath) . '/' . File::name($imagePath) . '_' . $width . 'X' . $height . '.' . File::extension($imagePath);

        if (!Storage::exists($resizedFileName)) {
            Image::make($imagePath)
                ->resize($width, $height)
                ->save($resizedFileName);
        }

        return File::dirname($vImagePath) . '/' . File::name($imagePath) . '_' . $width . 'X' . $height . '.' . File::extension($imagePath);

        //        return $resizedFileName;
    }


    return false;
}




function image($imagePath, $width, $height)
{
    return imageResize($imagePath, $width, $height);
}


function generateMenu(array $array)
{
    return view('system.partials.divs.generate_menu', compact('array'));
}


function MenuRoute($routename)
{
    $requestRoute = request()->route()->getName();
    if (is_array($routename)) {
        if (in_array($requestRoute, $routename)) {
            return true;
        }
        return false;
    }

    return ($requestRoute == $routename) ? true : false;
}

function flash_msg($type, $msg)
{
    \request()->session()->flash('msg', $msg);
    \request()->session()->flash('type', $type);
}

function ignoredRoutes()
{
    return [
        'log-viewer.index',
        'system.order.print',
        'system.order.totals',
        'system.order.picking-totals',
        'system.change',
        'system.change',
        'system.checkout-branch',
        'system.git-branches',
         'system.dashboard',
         'login',
        'logout',
        'system.misc.ajax',
        'system.user.change-password',
        'system.user.change-password-post',
        'system.notifications.url',
        'system.notifications.index',
        'system.user.user-sessions',
        'system.user.profile',
        'system.user.show-profile',
        'system.user.update-profile',
        'system.code',
        'system.code-post'
    ];
}

function userCan($routename, $userId = null)
{

    if ($userId && $userId == request()->user()->id) {
        $userId = null;
    }

    $userObj = $userId ? \App\Models\User::where('id', $userId)->first() : auth('user')->user();

    static $permissions;
    if (is_null($permissions)) {
        $permissions = \App\Models\User::UserPerms($userObj->id)->toArray();
    }
    $permissions = array_merge($permissions, ignoredRoutes());
    if (is_array($routename)) {
        $arr = array_diff($routename, $permissions);
        return (!$arr) ? true : ((count($arr) == count($routename)) ? false : true);
    } else {
        return (in_array($routename, $permissions)) ? true : false;
    }
}

function formError($error, $fieldName, $checkHasError = false)
{

    if ($checkHasError) {
        if ($error->has($fieldName)) {
            return ' has-danger';
        } else {
            return null;
        }
    }

    if ($error->has($fieldName)) {
        return view('system.partials.errors.form_error', compact('error', 'fieldName', 'checkHasError'));
    } else {
        return null;
    }
}


function label($text, $required = '')
{
    return view('system.partials.labels.label', compact('text', 'required'));
}

function status_select_data()
{
    return ['' => '', '1' => __('Active'), '0' => __('In-Active')];
}

function orderDatatableCheckbox($value)
{
    return view('system.partials.divs.order_datatable_checkbox', compact('value'));
}

function status_select_data_filter()
{
    return ['0' => __('All'), '1' => __('Active'), '-1' => __('In-Active')];
}

function filter_btn()
{
    return view('system.partials.buttons.filter_btn');
}

function download_btn()
{
    return view('system.partials.buttons.download_btn');
}

function ajax_btn($action, $title, $params = [])
{
    return view('system.partials.buttons.ajax_btn', compact('action', 'title', 'params'));
}


function delete_links($route, $link, $params = [], $row_id = null)
{
    if (userCan($route))
        return view('system.partials.buttons.delete_links', compact('route', 'link','params','row_id'));
}

function add_links($link, $route, $title = '')
{
    if (userCan($route))
        return  view('system.partials.links.add_links', compact('title', 'link','route'));
}

function show_links($link, $route)
{
    if (userCan($link))
        return view('system.partials.links.show_links', compact('route', 'link','route'));
}

function edit_links($link, $route)
{
    if (userCan($link))
        return view('system.partials.links.edit_links', compact('route','link'));
}


function datatable_links($link, $route, $label, $attributes = [])
{
    if (userCan($link))
        return view('system.partials.links.datatable_links', compact('link', 'route', 'label', 'attributes'));
    return $label;
}
function view_delete($link, $route, $row_id = null)
{
    if (userCan($route))
        return view('system.partials.buttons.view_delete', compact('link', 'route', 'row_id'));
}

function datatable_menu_delete($link, $route, $row_id = null)
{
    if (userCan($route))
        return view('system.partials.buttons.datatable_menu_delete', compact('link', 'route', 'row_id'));
}

function datatable_site_link($ex_url)
{
    return view('system.partials.links.datatable_site_link', compact('ex_url'));
}

function datatable_menu_log($link)
{
    return view('system.partials.links.datatable_menu_log', compact('link'));
}

function datatable_menu_edit($link, $route)
{
    if (userCan($route))
        return view('system.partials.links.datatable_menu_edit', compact('link','route'));
}
function datatable_menu_button($link, $route, $icon = 'fa-check', $status = 'approve', $rowId = null)
{
    $btnClass = $icon == 'fa-check' ? 'btn-success' : 'btn-danger';
    if (userCan($route))
        return view('system.partials.buttons.datatable_menu_button', compact('link', 'route', 'icon', 'status', 'rowId', 'btnClass'));
}
function datatable_menu_link_to($link, $route, $icon = 'fa-check', $status = 'approve', $rowId = null)
{
    $btnClass = $icon == 'fa-check' ? 'btn-success' : 'btn-danger';
    if (userCan($route))
        return view('system.partials.links.datatable_menu_link_to', compact('link', 'route', 'icon', 'status', 'rowId', 'btnClass'));
}
function datatable_menu_show($link, $route, $target = null)
{
    if (userCan($route))
        return view('system.partials.links.datatable_menu_show', compact('link', 'route', 'target'));
}

function datatable_menu_link($link, $route,$label, $target = null)
{
    if (userCan($route))
        return view('system.partials.links.datatable_menu_link', compact('link', 'route','label', 'target'));
}


function datatable_menu_popup($linkId, $route, $type)
{
    if (userCan($route))
        return view('system.partials.buttons.datatable_menu_popup', compact('linkId', 'route', 'type'));
}
function link_modal($route, $title, $modalId, $icon)
{
    $color = ($modalId == 'subfolder-modal') ? 'info' : 'success';
    if (userCan($route))
        return view('system.partials.buttons.link_modal', compact('route', 'title', 'modalId', 'color', 'icon'));
}
function link_add_modal_icon($route, $modalId)
{
    if (userCan($route))
        return view('system.partials.buttons.link_add_modal_icon', compact('route', 'modalId'));
}
function link_modal_edit($route, $link, $modalId, $rowId = null, $prevStatus = null, $type = null, $updateUrl = null, $class = 'btn-sm')
{
    if (userCan($route))
        return view('system.partials.buttons.link_modal_edit', compact('route', 'link', 'modalId', 'rowId', 'prevStatus', 'type', 'updateUrl', 'class'));
}

function link_modal_show($route, $link, $modalId, $rowId = null, $prevStatus = null, $type = null, $updateUrl = null, $class = 'btn-sm')
{
    if (userCan($route))
        return view('system.partials.buttons.link_modal_show', compact('route', 'link', 'modalId', 'rowId', 'prevStatus', 'type', 'updateUrl', 'class'));
}

function datatable_menu_show_onclick($link, $route, $id)
{
    if (userCan($route))
        return view('system.partials.buttons.datatable_menu_show_onclick', compact('route', 'link', 'id'));
}


function links($link, $route, $label, $attributes = [])
{
    if (userCan($link))
        return view('system.partials.links.links', compact('link', 'route', 'label', 'attributes'));
}
function datatable_text_modal($route, $link, $modalId, $rowId = null, $prevStatus = null, $type = null, $updateUrl = null, $title)
{
    return view('system.partials.links.datatable_text_modal', compact('link', 'route', 'modalId', 'rowId', 'prevStatus', 'type', 'updateUrl', 'title'));
}

function recursiveFind(array $array, $needle)
{
    $response = [];
    $iterator = new RecursiveArrayIterator($array);
    $recursive = new RecursiveIteratorIterator(
        $iterator,
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            $response[] = $value;
        }
    }
    return ((count($response) == '1') ? $response : $response);
}

function getRealIP()
{
    return env('HTTP_CF_CONNECTING_IP') ?? env('REMOTE_ADDR');
}

function getUserAgent()
{
    return request()->server('HTTP_USER_AGENT');
}
function getLanguageID()
{
    languageId();
}

function status_icon($status)
{
    return view('system.partials.icons.status_icon', compact('status'));
}

function status_icon_square($status)
{
    return view('system.partials.icons.status_icon_square', compact('status'));
}

function status_style($status)
{
    return view('system.partials.divs.status_style', compact('status'));
}

function BooleanStyle($boolean)
{
    return view('system.partials.spans.boolean_style', compact('boolean'));
}
function errorLog($message)
{
    Bugsnag::notifyException(new RuntimeException($message));
    Log::error($message);
}

function fixMobileNumber($Mobile)
{
    $regionCode = "SA";
    $telephone = toEnglishNumrics($Mobile);
    if (strncmp($telephone, "9", 1) === 0) {
        $telephone = "+" . $telephone;
        $regionCode = null;
    }
    $phoneUtil = PhoneNumberUtil::getInstance();
    try {
        $parsed_telephone = $phoneUtil->parse($telephone, $regionCode);
        (string)$data['country_code'] = (string)$parsed_telephone->getCountryCode();
        (string)$data['national_number'] = (string)$parsed_telephone->getNationalNumber();
        $Mobile = $data['country_code'] . $data['national_number'];
    } catch (NumberParseException $e) {
        errorLog($e->getMessage());
    }
    return $Mobile;
}
function toEnglishNumrics($number)
{
    $number = str_replace('+', '', $number);
    $number = str_replace('٠', '0', $number);
    $number = str_replace('١', '1', $number);
    $number = str_replace('٢', '2', $number);
    $number = str_replace('٣', '3', $number);
    $number = str_replace('٤', '4', $number);
    $number = str_replace('٥', '5', $number);
    $number = str_replace('٦', '6', $number);
    $number = str_replace('٧', '7', $number);
    $number = str_replace('٨', '8', $number);
    $number = str_replace('٩', '9', $number);
    $number = str_replace(' ', '', $number);

    return $number;
}
function preparePhone($phone)
{
    $new_phone = preg_replace('/^(\+|0)+/', '', $phone);
    //    $is_matched = preg_match('/^[966|971|974|968|965|973]/',$new_phone);
    $is_matched = substr($new_phone, 0, 3);
    if ($is_matched != '966') {
        $new_phone = '966' . $new_phone;
    }
    return $new_phone;
}

function changeDateFormat($date)
{
    $date = new \DateTime($date);
    return $date->format('Y-m-d H:i');
}

function folderName($name,$id){
    return view('system.partials.links.folder_name', compact('name', 'id'));
}



function imageIconLink($path)
{
    return view('system.partials.links.image_icon_link', compact('path'));
}

function fixSeo($string)
{
    $string = str_replace(' -', '', trim($string));
    $string = str_replace('&', '', trim($string));
    $string = str_replace('amp;', '', trim($string));
    $string = str_replace('`', '', $string);
    try {
        $string = str_replace("'", '', $string);
    } catch (Exception $e) {
    }
    $string = str_replace(' ', '-', $string);
    $string = str_replace('%', 'percent', $string);
    $string = str_replace('+', 'plus', $string);

    $string = trim($string);

    return strtolower($string);
}



function selectAllCheckbox()
{
    return view('system.partials.divs.select_all_checkbox');
}

//function orderDatatableCheckbox($value)
//{
//    return view('system.partials.divs.order_datatable_checkbox', compact('value'));
//}


function datatableImage($path)
{
    return view('system.partials.imgs.datatable_image', compact('path'));
}

function datatableImageFullPath($path)
{
    return view('system.partials.imgs.datatable_image_full_path', compact('path'));
}

function imageS3($path)
{
    if(empty($path)){
        return asset('/assets/media/misc/no_image.svg');
    }

    return  env('front_image_url') . $path;
}

function datatable_datetime($date_time)
{
    return view('system.partials.divs.datatable_datetime' , compact('date_time'));
}

function datatable_product_price($price, $special_price)
{
    return view('system.partials.divs.datatable_product_price' , compact('price', 'special_price'));
}

function amount($number, $decimal = 2)
{
    $ex = explode('.', $number);
    if (isset($ex[1]) && $ex[1] > 0  && !empty($decimal)) {
        return number_format($number, $decimal);
    } else {
        return  number_format($number, 0);
    }
}

function break_word($text)
{
    return view('system.partials.spans.break_word' , compact('text'));
}

function more_categories($text)
{
    return view('system.partials.buttons.more_categories' , compact('text'));
}




function get_types_trans($text)
{
    $types = get_types();

    return isset($types[$text]) ? $types[$text] : '';
}

const MIN_LENGTH = 8;

function generateCoupon($options = [])
{
    $length      = $options['length'] ?? MIN_LENGTH;
    $prefix      = $options['prefix'] ?? '';
    $suffix      = $options['suffix'] ?? '';
    $useLetters  = $options['letters'] ?? true;
    $useNumbers  = $options['numbers'] ?? false;
    $useSymbols  = $options['symbols'] ?? false;
    $useMixedCase = $options['mixed_case'] ?? false;
    $mask        = $options['mask'] ?? false;

    $uppercase   = range('A', 'Z');
    $lowercase   = range('a', 'z');
    $numbers     = range(0, 9);
    $symbols     = ['`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_', '=', '+', '\\', '|', '/', '[', ']', '{', '}', '"', "'", ';', ':', '<', '>', ',', '.', '?'];

    $characters = [];

    if ($useLetters) {
        $characters = $useMixedCase ? array_merge($lowercase, $uppercase) : $uppercase;
    }

    if ($useNumbers) {
        $characters = array_merge($characters, $numbers);
    }

    if ($useSymbols) {
        $characters = array_merge($characters, $symbols);
    }

    $coupon = '';

    if ($mask) {
        for ($i = 0; $i < strlen($mask); $i++) {
            if ($mask[$i] === 'X') {
                $coupon .= $characters[random_int(0, count($characters) - 1)];
            } else {
                $coupon .= $mask[$i];
            }
        }
    } else {
        for ($i = 0; $i < $length; $i++) {
            $coupon .= $characters[random_int(0, count($characters) - 1)];
        }
    }

    return $prefix . $coupon . $suffix;
}
function branch_name()
{
    $runner = new \CzProject\GitPhp\Runners\CliRunner(env('CLI_RUNNER', 'echo "P@ssw0rd" | sudo -S -u root git'));
    $git = new \CzProject\GitPhp\Git($runner);
    $repo = $git->open(env('PROJECT_PATH', "/var/www/html/wms"));

    return $repo->getCurrentBranchName();
}

function language_data()
{
    return ['' => '', 'en-gb' => __('English'), 'ar' => __('عربي')];
}

function color_span($color, $status = null)
{
    $status = $status ? $status : $color;
    return view('system.partials.spans.color_span', compact('color', 'status'));
}

function phone_direction($phone)
{
    return view('system.partials.spans.phone_direction', compact('phone'));
}

function quantity_locations($data){
    return view('system.partials.spans.quantity_locations',compact('data'));
}

function datatableImageDefault($path)
{
    return view('system.partials.imgs.datatable_image_path', compact('path'));
}

function order_receives(){
    return [
        '' => '',
        'all'=>__('All times'),
        'past_12'=>__('Past 12 hours'),
        'past_24'=>__('Past 24 hours'),
        'today'=>__('Today'),
        'yesterday'=>__('Yesterday'),
        'last_2'=>__('Last 2 days'),
        'last_3'=>__('Last 3 days'),
        'last_5'=>__('Last 5 days'),
        'last_7'=>__('Last 7 days'),
        'last_15'=>__('Last 15 days'),
        'last_30'=>__('Last 30 days'),
        'current_month'=>__('Current month'),
        'last_month'=>__('Last Month'),
        'this_year'=>__('This year')
    ];
}

function datatable_badge($value,$class){
    return view('system.partials.spans.badge', compact('value', 'class'));

}
function color_time($value)
{
    return view('system.partials.spans.time', compact('value'));
}

function color_date($value)
{
    return view('system.partials.spans.date', compact('value'));
}

function status_enum($key = null, $withLang = false)
{
    if ($withLang) {
        return StatusEnum::values_lang();
    }
    if (!empty($key))
        return StatusEnum::values()[$key];

    return StatusEnum::values();
}

function storeImageMedia ($imageRequest = null , $imageDir = null , $resizeWidth = 500, $type = null , $mediaable_id = null , $mediaable_type )
{

    if (!$mediaable_id || !$mediaable_type || !$imageRequest) {
        throw new \Exception("Invalid mediaable_type , mediaable_id or imageRequest");
    }

    ini_set('memory_limit', '-1');
    $image_request = $imageRequest;
    $image_path = date("Y-m-d") . '/';
    $imageName = date('mdYHis') . uniqid() . '.' . $image_request->getClientOriginalExtension();

    if (!is_dir(public_path('storage/' . $imageDir . '/attachments/' . $image_path))) {
        File::makeDirectory(public_path('storage/' . $imageDir . '/attachments/' . $image_path), $mode = 0777, true, true);
    }

    Image::make($image_request)
    ->resize($resizeWidth, null, function ($constraint) {
        $constraint->aspectRatio();
    })
    ->save(public_path('storage/' . $imageDir . '/attachments/') . $image_path . $imageName, 91);

    return Media::create([
        'filename' =>  $imageName,
        'mime' => $image_request->getClientMimeType(),
        'type' => $type,
        'mediaable_id' => $mediaable_id,
        'mediaable_type' => $mediaable_type,
        'url' => url('') . '/storage/' . $imageDir . '/attachments/' . $image_path . $imageName
    ]);

}

function storeFile($fileRequest = null, $fileDir = 'chat', $type = null, $mediaable_id = null, $mediaable_type = null)
{
    
    if (!$mediaable_id || !$mediaable_type || !$fileRequest) {
        throw new \Exception("Invalid mediaable_type , mediaable_id or fileRequest");
    }

    $file_request = $fileRequest;
    $file_path = date("Y-m-d") . '/';
    $fileName = date('mdYHis') . uniqid() . '.' . $file_request->getClientOriginalExtension();

    if (!is_dir(public_path('storage/' . $fileDir . '/attachments/' . $file_path))) {
        File::makeDirectory(public_path('storage/' . $fileDir . '/attachments/' . $file_path), $mode = 0777, true, true);
    }

    $fileRequest->move(public_path('storage/' . $fileDir . '/attachments/' . $file_path), $fileName);

    return Media::create([
        'filename' => $fileName,
        'mime' => $fileRequest->getClientMimeType(),
        'type' => $type,
        'mediaable_id' => $mediaable_id,
        'mediaable_type' => $mediaable_type,
        'url' => url('') . '/storage/' . $fileDir . '/attachments/' . $file_path . $fileName
    ]);
}

function deleteImageMedia ($path)
{
    $imagePath = public_path(str_replace(url('/'), '', $path));
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}