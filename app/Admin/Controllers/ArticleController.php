<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Models\ArticleType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ArticleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Article';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);

        $grid->column('title', __('タイトル'));
        $grid->column('article.title', __('カテゴリー'));
        $grid->column('sub_title', __('サブタイトル'));
        $grid->column('description', __('説明'));
        $grid->column('released', __('公開設定'))->bool();
        $grid->column('thumbnail', __('サムネイル'))->image('', '60', '60');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title', __('タイトル'));
            $filter->like('article.title', __('カテゴリー'));
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Article::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Article());

        $form->select('type_id', __('カテゴリー'))->options((new ArticleType())::selectOptions());
        $form->text('title', __('タイトル'))->required();
        $form->text('sub_title', __('サブタイトル'));
        $form->textarea('description', __('説明'))->required();
        // image()を使用するにはディスクスペースが必要なのでconfig/filesystems.phpのdisksのpublicの下に以下を記載
        // 'admin' => [
        //     'driver' => 'local',
        //     'root' => public_path('uploads'),
        //     'url' => env('APP_URL') . 'uploads/',
        //     'visibility' => 'public',
        // ],
        // その後public配下にuploadsディレクトリを配置
        $form->image('thumbnail', __('サムネイル'));
        $states = [
            'on' => ['value' => 1, 'text' => '公開'],
            'off' => ['value' => 0, 'text' => '非公開']
        ];
        $form->switch('released', __('公開設定'))->states($states);

        return $form;
    }
}
