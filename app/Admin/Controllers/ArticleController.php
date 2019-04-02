<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ArticleController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('admin.article'))
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.article'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.article'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.article'))
            ->description(trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);

        $grid->id(trans('article.id'))->sortable();
        $grid->title(trans('article.title'));
        $grid->pic(trans('article.pic'))->image(Article::fileURL(),50,50);
        $grid->summary(trans('article.summary'));
        $grid->author(trans('article.author'));
        $grid->status(trans('article.status'))->display(function ($status){
            return Article::articleReviewStatus($status);
        });
        $grid->created_at(trans('admin.created_at'))->sortable();
        $grid->updated_at(trans('admin.updated_at'))->sortable();
        $grid->last_modify_id(trans('admin.last_modify_id'))->display(function ($last_modify_id){
            return Article::getUsernameByLastModifyId($last_modify_id);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Article::findOrFail($id));

        $show->id(trans('article.id'));
        $show->title(trans('article.title'));
        $show->pic(trans('article.pic'))->image();
        $show->summary(trans('article.summary'));
        $show->content(trans('article.content'));
        $show->author(trans('article.author'));
        $show->status(trans('article.status'))->unescape()->as(function ($status){
           return Article::articleReviewStatus($status);
        });
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));
        $show->last_modify_id(trans('admin.last_modify_id'))->as(function ($last_modify_id){
            return Article::getUsernameByLastModifyId($last_modify_id);
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Article);

        //标题
        $form->text('title', trans('article.title'))->rules('required|max:255',[
            'required'=>trans('article.title.required'),
            'max'=>trans('article.title.max'),
        ]);
        //简介
        $form->text('summary', trans('article.summary'))->rules('required|max:255',[
            'required'=>trans('article.summary.required'),
            'max'=>trans('article.summary.max'),
        ]);
        //缩略图
        $form->image('pic', trans('article.pic'));
        //内容
        $form->textarea('content', trans('article.content'));
        //作者
        $form->text('author', trans('article.author'))->rules('required|max:255',[
            'required'=>trans('article.author.required'),
            'max'=>trans('article.author.max'),
        ]);
        //审核状态
        $form->select('status',trans('article.status'))->options(Article::reviewStatus())->default(Article::UNREVIEWED);

        return $form;
    }
}
