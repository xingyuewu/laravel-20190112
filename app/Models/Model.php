<?php
/**
 * Created by PhpStorm.
 * User: 齐正宁
 * Date: 2019/4/2
 * Time: 10:27
 */

namespace App\Models;

use Encore\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    //更新人id
    const LAST_MODIFY_ID = 'last_modify_id';
    //未审核
    const UNREVIEWED = 0;
    //审核通过
    const REVIEW_PASS = 1;
    //审核失败
    const REVIEW_FAILURE = 2;
    //图片地址目录
    const FILE_DIR = 'uploads';

    /**
     * @return array
     * 审核状态
     */
    public static function reviewStatus($status = null){
        $statusArr = [
            self::UNREVIEWED => trans('admin.unreviewed'),
            self::REVIEW_PASS => trans('admin.review_pass'),
            self::REVIEW_FAILURE => trans('admin.review_failure'),
        ];
        if($status === null){
           return $statusArr;
        }else{
            return $statusArr[$status];
        }

    }

    /**
     * 维护更新人字段
     */
    public static function boot(){
        parent::boot();
        static::saving(function ($model){
            if(isset($model->attributes[self::LAST_MODIFY_ID])){
                $model->attributes[self::LAST_MODIFY_ID] = Admin::user()->id;
            }
        });
    }

    /**
     * @param $last_modify_id
     * @return mixed
     * 更新人用户名
     */
    public static function getUsernameByLastModifyId($last_modify_id){
        return Admin::user()->username;
    }

    /**
     * @return string
     * 图片地址
     */
    public static function fileURL(){
        return env('APP_URL').'/'.self::FILE_DIR;
    }
}
