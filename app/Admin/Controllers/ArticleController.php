<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Models\ArticleType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;

class ArticleController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Article';

    public function index(Content $content)
    {
        $id = 1;
        // return $this->detail($id);
        // return $this->edit($id, $content);
        // return  $content->view('dashboard', ['data' => 'foo']);
        return $content->view('admin/calender.calender');
        // return view('laravel-admin/test-page','admin::content' => $content]);
    }
    // public function update(Content $content)
    // {


    //     return $this->form()->update(1);
    // }

    // /**
    //  * Edit interface.
    //  *
    //  * @param mixed $id
    //  * @param Content $content
    //  * @return Content
    //  */
    // public function edit($id, Content $content)
    // {

    //     return $content
    //         ->header('Edit')
    //         ->description('description')
    //         ->body($this->form()->edit($id));
    // }

    /**
     * Make a grid builder.s
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article);

        // $grid->tools(function ($tools) {
        //     $tools->append("<a href='your-create-URI' class='btn btn-default'>Create</a>");
        // });

        // $grid->column('title')->display(function ($title, $column) {

        //     if ($title !== 'error') {
        //         return $title;
        //     }

        // var_dump($title);

        // return $column->setAttributes(['style' => 'background-color:red;']);
        // });
        $grid->column('article.title', __('カテゴリー'))->editable();
        $grid->column('sub_title', __('サブタイトル'))->editable();
        $grid->column('description', __('説明'))->editable();
        $grid->column('released', __('公開設定'))->bool()->editable();
        $grid->column('thumbnail', __('サムネイル'))->image('', '60', '60');

        // $grid->filter(function ($filter) {
        //     $filter->disableIdFilter();
        //     $filter->like('title', __('タイトル'));
        //     $filter->like('article.title', __('カテゴリー'));
        //     $filter->between('created_at', __('作成日'))->datetime();
        //     // $filter->between("DATE", '日時')->default(["start" => date("Y/m/d"), "end" => date("Y/m/d 23:59:99")]);
        // });
        // $form = new Form(new Article());

        // $form->select('type_id', __('カテゴリー'))->options((new ArticleType())::selectOptions());
        // $form->text('title', __('タイトル'))->required();
        // $form->text('sub_title', __('サブタイトル'));
        // $form->textarea('description', __('説明'))->required();
        // image()を使用するにはディスクスペースが必要なのでconfig/filesystems.phpのdisksのpublicの下に以下を記載
        // 'admin' => [
        //     'driver' => 'local',
        //     'root' => public_path('uploads'),
        //     'url' => env('APP_URL') . 'uploads/',
        //     'visibility' => 'public',
        // ],
        // その後public配下にuploadsディレクトリを配置
        // $form->image('thumbnail', __('サムネイル'));
        // $states = [
        //     'on' => ['value' => 1, 'text' => '公開'],
        //     'off' => ['value' => 0, 'text' => '非公開']
        // ];
        // $form->switch('released', __('公開設定'))->states($states);

        // return $form;
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
        $show->panel()
            ->tools(function ($tools) {
                // $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();

                $tools->append("<a href='/articles/1/edit' class='btn btn-default'>編集</a>");
            });;
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
        $form->tools(function (Form\Tools $tools) {

            // Disable `List` btn.
            $tools->disableList();

            // Disable `Delete` btn.
            $tools->disableDelete();

            // Disable `Veiw` btn.
            $tools->disableView();

            $tools->add('<button href="http://localhost:8000/admin/articles/1/edit">aaaa11a</button>');
        });
        $form->footer(function ($footer) {

            // disable reset btn
            $footer->disableReset();

            // disable submit btn
            // $footer->disableSubmit();

            // disable `View` checkbox
            $footer->disableViewCheck();

            // disable `Continue editing` checkbox
            $footer->disableEditingCheck();

            // disable `Continue Creating` checkbox
            $footer->disableCreatingCheck();
        });

        // $form->saving(function (Form $form) {

        //     // redirect url
        //     return redirect('/admin/users');
        // });

        return $form;
    }
}
