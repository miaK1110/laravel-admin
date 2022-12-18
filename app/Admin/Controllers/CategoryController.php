<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'カテゴリー管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);

        $grid->sortable();
        // delete checkbox from grid
        $grid->option('show_row_selector', false);
        $grid->column('id')->sortable();
        $grid->column('name')->sortable();

        $grid->filter(function (Grid\Filter $filter) {
            // フィルターを常に表示
            $filter->expand();
            // ID検索を無効化
            $filter->disableIdFilter();
            // 名前検索とひらがな検索
            $filter->like('name', 'カテゴリーID');
        });

        $grid->actions(function ($actions) {
            $actions->prepend('<a href="/admin/category/' . $actions->getKey() . '/subcategory"><button type="button" class="btn btn-primary btn-xs">サブカテゴリ―追加</button></a></br>');
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
        $show = new Show(Category::findOrFail($id));

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
        $form = new Form(new Category);
        // リセットボタン無効化
        $form->disableReset();

        Form::init(function (Form $form) {

            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewCheck();

            $form->tools(function (Form\Tools $tools) {
                // Disable `List` btn.
                $tools->disableList();
                // Disable `Delete` btn.
                $tools->disableDelete();
                // Disable `Veiw` btn.
                $tools->disableView();
                // 戻る button
                $tools->add('<a href="http://localhost:8000/admin/pics" class="btn btn-default">Back</a>');
            });
        });

        $form->text('name', 'カテゴリー名')->required();

        return $form;
    }
}
