<?php
/**
 * Created by PhpStorm.
 * User: hexi
 * Date: 2018/4/29
 * Time: 下午2:54
 */

namespace app\commands;


use app\models\YouLuCatModel;
use linslin\yii2\curl\Curl;
use yii\console\Controller;

class YouluController extends Controller
{
    public function actionCat()
    {
        $curl = new Curl();
        $result = $curl->get("http://www.youlu.net/classify/");


        preg_match_all("/href=\"(\/classify\/[^\"]+)\">([^<]+)<\/a><span class=\"bookCount\">\((\d+)\)<\/span>/", $result, $matches);
        foreach ($matches[1] as $key => $val) {
            $model = new YouLuCatModel();
            $model->url = "http://www.youlu.net" . $val;
            $model->created_at = $model->updated_at = time();
            $model->name = $matches[2][$key];
            $model->num = $matches[3][$key];
            $model->save();
        }
    }
}