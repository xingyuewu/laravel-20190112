<?php

namespace App\Models;


class Article extends Model
{
    protected $table = 'article';

    /**
     * @param $status
     * @return string
     * 文章审核状态
     */
    public static function articleReviewStatus($status){
        $statusText = Article::reviewStatus($status);
        switch ($status){
            case 0:
                return "<span class='label bg-yellow'>$statusText</span>";
                break;
            case 1:
                return "<span class='label bg-green'>$statusText</span>";
                break;
            case 2:
                return "<span class='label bg-red'>$statusText</span>";
                break;
        }
    }
}
