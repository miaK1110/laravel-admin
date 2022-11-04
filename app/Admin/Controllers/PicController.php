<?php

namespace App\Admin\Controllers;

use App\Models\Pic;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use App\Admin\Extensions\GridView;
use Encore\Admin\Grid\Displayers\DropdownActions;
use App\Admin\Grid\Displayers\CustomActions;
use Encore\Admin\Show;
use App\Admin\Controllers\Request;
use App\Admin\Actions\User\Delete;

class PicController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '担当者管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Pic);

        $grid->sortable();

        // $grid->order_column()->orderable();
        
        // delete checkbox from grid
        // $grid->option('show_row_selector' , false);

        // $grid->fixColumns(4, -3);

        $grid->column('id')->sortable();

        $grid->column('order_column');

        $grid->column('user', __('ユーザー名/ユーザーID'))->display(function () {

            return   '<div class="row">'
            . $this->name . '&emsp;' . $this->kana .
            '</div><div class="row">'
            . $this->login_id .
            '</div>';
        });
        $grid->column('email', __('メールアドレス'));

        // here is the command to make custom action
        // php artisan admin:action User\\Delete --grid-row --name="delete"

        $grid->actions(function ($actions) {
            $actions->disableDelete(); // 削除無効
            $actions->disableEdit(); // 編集無効
            $actions->disableView(); // 詳細表示無効

            $id = $actions->getKey();
            $actions->prepend('before');
            $actions->prepend('<a href="/admin/pics/' . $id . '/edit"><button type="button" class="btn btn-primary btn-xs">編集</button></a></br>');
            $actions->append('<a href="/admin/pics/' . $id . '/delete"><button type="button" class="btn btn-danger btn-xs">削除</button></a></br>');
            
            // they dont work atm bc I used Actions instead of DropDownActions
            // $actions->add(new Delete($id));
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

            $filter->like('login_id', 'ログインID');
            $filter->like('email', 'メールアドレス');
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
        
            // modoru button
            $tools->add('<a href="http://localhost:8000/admin/pics" class="btn btn-default">Back</a>');
        });

        });

        $form->text('name', __('苗字'))->required();
        $form->text('kana', __('かな'))->required();
        $form->text('email', __('メールアドレス'));
        $form->image('pic', __('ユーザー画像'));
        $form->text('pair_id', __('ペアID'));
        $form->text('slack_id', __('Slack ID'));
        $form->text('line_url', __('LINE URL'));

        $form->divider();

        if ($form->isEditing()) {
            $form->text('login_id', __('ログインID'))->help('変更できません')->disable();
            $form->password('password', __('パスワード'))->rules('confirmed')->help('変更する場合のみ入力');
        } else {
            $form->text('login_id', __('ログインID'));
            $form->password('password', __('パスワード'))->rules('confirmed');
        }

        $form->password('password_confirmation', __(''))->help('上記と同じものを入力');

        return $form;
    }
}
