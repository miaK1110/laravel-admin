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

    public function index()
    {
        return Article::content(function (Content $content) {

            // optional
            $content->header('page header');

            // optional
            $content->description('page description');

            // add breadcrumb since v1.5.7
            $content->breadcrumb(
                ['text' => 'Dashboard', 'url' => '/admin'],
                ['text' => 'User management', 'url' => '/admin/users'],
                ['text' => 'Edit user']
            );

            // Fill the page body part, you can put any renderable objects here
            $content->body('hello world');

            // Add another contents into body
            $content->body('foo bar');

            // method `row` is alias for `body`
            $content->row('hello world');

            // Direct rendering view, Since v1.6.12
            $content->view('dashboard', ['data' => 'foo']);
        });
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);

        $grid->column('title')->display(function ($title, $column) {

            if ($title !== 'error') {
                return $title;
            }

            // var_dump($title);

            // return $column->setAttributes(['style' => 'background-color:red;']);
        });
        $grid->column('article.title', __('カテゴリー'));
        $grid->column('sub_title', __('サブタイトル'));
        $grid->column('description', __('説明'));
        $grid->column('released', __('公開設定'))->bool();
        $grid->column('thumbnail', __('サムネイル'))->image('', '60', '60');

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('title', __('タイトル'));
            $filter->like('article.title', __('カテゴリー'));
            $filter->between('created_at', __('作成日'))->datetime();
            // $filter->between("DATE", '日時')->default(["start" => date("Y/m/d"), "end" => date("Y/m/d 23:59:99")]);
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
