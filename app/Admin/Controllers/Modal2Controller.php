<?php

namespace App\Admin\Controllers;

use App\Models\Pic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;


class Modal2Controller extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '顧客情報検索';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Pic);

        $grid->disableCreateButton();
        // $grid->disableRowSelector();
        $grid->disableColumnSelector();
        $grid->disableTools();
        $grid->disableExport();

        $grid->column('user', __('ユーザー名/ユーザーID'))->display(function () {
            return   '<div class="row">'
                . $this->name . '&emsp;' . $this->kana .
                '</div><div class="row">'
                . $this->login_id .
                '</div>';
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete(); // 削除無効
            $actions->disableEdit(); // 編集無効
            $actions->disableView(); // 詳細表示無効

            $id = $actions->getKey();
            $actions->add(new Delete($id));
            // $actions->append(new Delete($id));
        });

        $grid->filter(function (Grid\Filter $filter) {


            // フィルターを常に表示
            $filter->expand();

            // ID検索を無効化
            $filter->disableIdFilter();

            // 名前検索とひらがな検索
            $filter->where(function ($query) {
                $query->where('name', 'like', "%{$this->input}%")
                    ->orWhere('kana', 'like', "%{$this->input}%");
            }, 'ユーザー名');
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
        $show = new Show(Pic::findOrFail($id));

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
        $form = new Form(new Pic);
        return $form;
    }
}
