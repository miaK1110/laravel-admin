<?php

namespace App\Admin\Controllers;

use App\Models\SubCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SubCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'サブカテゴリー管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SubCategory);

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
        $show = new Show(SubCategory::findOrFail($id));

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
        $form = new Form(new SubCategory);
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
                $tools->add('<a href="http://localhost:8000/admin/category" class="btn btn-default">Back</a>');
            });
        });
        $form->text('name', 'サブカテゴリー名')->required();
        $form->multipleSelect('product_id', '関連商品')->options([1 => 'マフラー', 2 => '手袋', 3 => 'Option name']);
        return $form;
    }
}
