<?php
use Illuminate\Support\Facades\Auth;

if(!function_exists("www_view")) {
    function www_view($path, $args=[]) {
        $prefix = "www";
        return view($prefix."::".$path, $args);
    }
}

if(!function_exists("www_slot_view")) {
    function www_slot_view($path, $args=[], $slot=null) {
        $prefix = "www";

        if(!$slot) {
            return view($prefix."::".$path, $args);
        }

        return view($prefix."::".$slot.".".$path, $args);
    }
}


/**
 * 현재의 슬롯
 */
if(!function_exists("www_slot")) {
    function www_slot() {
        $activeSlot = "";

        // 여기에 인증된 사용자에 대한 처리를 추가합니다.
        $user = Auth::user();
        //dd($user);
        $slots = config("jiny.site.userslot");
        if($user){
            if($slots && isset($slots[$user->id])) {
                $activeSlot = $slots[$user->id];
                return $activeSlot;
            }
        }




        // 설정파일에서 active slot을 읽어옴
        $slots = config("jiny.site.slot");
        if($slots) {
            foreach($slots as $slot => $item) {
                //dump($item);
                if($item['active']) {
                    $activeSlot = $item['name'];//slot;
                    //dd($activeSlot);
                    return $activeSlot;
                }
            }
        }


        return $activeSlot;
        //return false;

    }
}

## 사이트의 공용 설정을 읽어 옵니다.
function siteInfo($key=null) {
    $obj = \Jiny\Site\SiteSettings::instance("site_info");
    return $obj->get($key);
}

function siteSetting($key=null) {
    $obj = \Jiny\Site\SiteSettings::instance("site_setting");
    return $obj->get($key);
}


function site_log_sum($year=null, $month=null, $day=null)
{
    $db = DB::table('site_log');
    if($year) {
        $db->where('year', $year);
    }
    if($month) {
        $db->where('month', $month);
    }

    if($day) {
        $db->where('day', $day);
    }

    $sum = $db->sum('cnt');
    return $sum;
}

function menu($file) {
    return menuLoad($file);
}

function menuLoad($file) {

    $path = resource_path('menus');
    $filename = $path.DIRECTORY_SEPARATOR.$file;
    if(file_exists($filename)) {
        $json = file_get_contents($filename);
        return json_decode($json,true);
    }

    return [];
}
