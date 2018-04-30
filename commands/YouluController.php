<?php
/**
 * Created by PhpStorm.
 * User: hexi
 * Date: 2018/4/29
 * Time: 下午2:54
 */

namespace app\commands;


use app\models\YouLuBookModel;
use app\models\YouLuCatModel;
use linslin\yii2\curl\Curl;
use yii\console\Controller;

class YouluController extends Controller
{
    public function actionCat()
    {
        $curl   = new Curl();
        $result = $curl->get("http://www.youlu.net/classify/");

        preg_match_all("/href=\"(\/classify\/[^\"]+)\">([^<]+)<\/a><span class=\"bookCount\">\((\d+)\)<\/span>/",
            $result, $matches);
        foreach ($matches[1] as $key => $val) {
            $url = "http://www.youlu.net" . $val;
            if (YouLuCatModel::find()->where("url=:url", [
                'url' => $url,
            ])->exists()) {
                continue;
            }
            $model             = new YouLuCatModel();
            $model->url        = $url;
            $model->created_at = $model->updated_at = time();
            $model->name       = $matches[2][$key];
            $model->num        = $matches[3][$key];
            $model->save();
        }
    }

    public function actionList()
    {
        for(;;) {
            $one  = YouLuCatModel::find()->where("status=0")->one();
            if (!$one) {
                break;
            }
            YouLuCatModel::updateAll(['status' => 1], 'id=:id', [
                'id' => $one->id,
            ]);
            $curl = new Curl();
            $max  = ceil($one->num / 20);
            preg_match("/(http:\/\/www.youlu.net\/classify\/(\d+-\d+-\d+-))(\d).html/", $one->url, $matches);
            $prefix = $matches[1];
            for ($i = 1; $i <= $max; $i++) {
                $page_url = $prefix . $i . ".html";
                $result   = $curl->get($page_url);
                preg_match_all('/<div class="bName">\n<a href="(\/\d+)" target="_blank">([^<]+)<\/a>/m',
                    $result, $matches);
                foreach ($matches[1] as $key => $val) {
                    $url = "http://www.youlu.net" . $val;
                    if (YouLuBookModel::find()->where("url=:url", ['url' => $url])->exists()) {
                        continue;
                    }
                    $model             = new YouLuBookModel();
                    $model->url        = $url;
                    $model->updated_at = $model->created_at = time();
                    $model->name       = $matches[2][$key];
                    $model->cat_id     = $one->id;
                    $model->save();
                }

                if (count($matches[1]) != 20 && $i != $max) {
                    printf("get url error [%s] count[%d]\n", $page_url, count($matches[1]));
                }
            }

            YouLuCatModel::updateAll(['status' => 2], 'id=:id', [
                'id' => $one->id,
            ]);

            if ($one) {
                printf("cat url[%s] is done \n", $one->url);
            }
        }
    }

    public function actionTest()
    {
        $url = "http://www.youlu.net/classify/2-2622-166-6.html";
        $curl = new Curl();
        $result = $curl->get($url);
        preg_match_all('/<div class="bName">\n<a href="(\/\d+)" target="_blank">([^<]+)<\/a>/m',
            $result, $matches);
        var_dump($matches);
    }

    public function actionDetail()
    {
        for (;;) {
            $one  = YouLuBookModel::find()->select("url")->one();
            if (!$one) {
                return;
            }
            $book_url = $one->url;
            $curl = new Curl();
            $result = $curl->get($book_url);
            preg_match("/<li class=\"t1\">[^<]+<a [^>]+>([^<]+)<\/a><\/li>/", $result, $matches);
            if (!empty($matches[1])) {
                $author = $matches[1];
            }
            preg_match("/<li class=\"t2\">出版社：<a[^>]+>([^<]+)<\/a><\/li>/", $result, $matches);
            if (!empty($matches[1])) {
                $isbn = $matches[1];
            }
            $publisher = $matches[1];
            preg_match("/<li class=\"t3\">ISBN：<span>([^<]+)<\/span><\/li>/", $result, $matches);
            if (!empty($matches[1])) {
                $isbn = $matches[1];
            }
            preg_match("/<li class=\"t1\">出版日期：<span>([^<]+)<\/span><\/li>/", $result, $matches);
            if (!empty($matches[1])) {
                $publish_time = date("Y-m-d H:i:s", strtotime($matches[1]));
            }
            preg_match("/<a href=\"\/picBook\/\?bookId=\d+\"[^>]+><img src=\"([^\"]+)\"[^>]+><\/a>/", $result, $matches);
            if (!empty($matches[1])) {
                $img_url = $matches[1];
            }
            YouLuBookModel::updateAll([
                'author' => $author?? "",
                'publish_time' => $publish_time ?? "",
                'publisher' => $publisher ?? "",
                'isbn' =>$isbn ?? "",
                'img_url' => $img_url ?? "",
            ], 'url=:url', [
                'url' => $book_url,
            ]);
        }

    }
}