<?php
import('Bibitter');

class BibitterAPI extends Flow
{
    public function current_json(){
        header('Content-Type: application/json');
        echo json_encode(array('current' => $this->get_current()));
        exit;
    }
    public function current_image(){
        $current = $this->get_current();
        $cache_key = 'api_image_'. $current. '.png';
        
        if(!file_exists(work_path('cache/'. $cache_key))){
            $image = imagecreatefrompng(path('resources/media/images/ex_api.png'));
            $color = imagecolorallocate($image, 51, 51, 51);
            $tb = imagettfbbox(36, 0, def('image_api_font'), $this->get_current());
            imagettftext($image, 36, 0, ceil((160 - $tb[2]) / 2), 60,
                $color, def('image_api_font'), $this->get_current());
            imagepng($image, work_path('cache/'. $cache_key));
            imagedestroy($image);
        }
        $interval = 180;
        header('Expires: '. gmdate('D, d M Y H:i:s', time()+$interval). ' GMT');
        header('Cache-Control: max-age='. $interval);
        header('Pragma: cache');
        header('Content-Type: image/png');
        readfile(work_path('cache/'. $cache_key));
        exit;
    }
    private function get_current(){
        return (int) C(BibitterCounter)->find_sum('times', Q::gt('updated', time()-1800));
    }
}