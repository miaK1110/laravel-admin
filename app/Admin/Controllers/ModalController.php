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
use Image;
use Encore\Admin\Layout\Content;
use App\Admin\Actions\User\passId;
use App\Admin\Actions\Post\Replicate;


class ModalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '担当者管理';
    protected $customerInfo = null;
    public static $customerId;



    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {

        dump(ModalController::$customerId);
        $grid = new Grid(new Pic);

        $grid->sortable();

        $grid->option('show_row_selector', false);
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
            // append/prependを使うとアクションボタンにテキストなどをいれることもできる
            // $actions->prepend('before');
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

        dump(ModalController::$customerId);

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

        $form->isEditing() ? $form->html('<h3>登録情報編集</h3>') : $form->html('<h3>新規登録</h3>');

        $form->text('name', __('苗字'))->required();
        $form->text('kana', __('かな'))->required();
        $form->text('email', __('メールアドレス'));
        $form->image('pic', __('ユーザー画像'))->thumbnail('small', $width = 300, $height = 300);
        $form->text('pair_id', __('ペアID'));
        $form->text('slack_id', __('Slack ID'));
        $form->text('line_url', __('LINE URL'));

        $form->html('<a href="http://localhost:8000/admin/customers" target="_blank" rel="nooper"><button type="button" class="btn btn-info" >顧客情報を開く</button></a>');

        $form->html('<h3>ログイン情報</h3>');
        $form->divider();

        if ($form->isEditing()) {
            $form->text('login_id', __('ログインID'))->help('変更できません')->readOnly();
            $form->password('password', __('パスワード'))->help('変更する場合のみ入力');
        } else {
            $form->text('login_id', __('ログインID'));
            $form->password('password', __('パスワード'))->rules('confirmed');
        }

        // $form->password('password_confirmation', __(''))->help('上記と同じものを入力');

        return $form;
    }
    protected function modal(Content $content)
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

            // the array of data for the current row
            // $actions->row;

            // gets the current row primary key value
            // $actions->getKey();

            $id = $actions->getKey();
            $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');

            $actions->append('<a href=""><i class="fa fa-eye"></i></a>');
            $actions->append('<a href="/search/' . $actions->getKey() . '"><button type="button" class="btn btn-info btn-s">選ぶ</button></a></br>');
            $actions->append('<a href="/search/' . $actions->getKey() . '"><button type="button" class="btn btn-info btn-s">選ぶ</button></a></br>');
            // idがあるならstatic変数にいれる
            // if ($id) {
            //     ModalController::$customerId = $id;
            // }
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

        return $content->header('顧客情報検索')->description('なんか')->body($grid);
    }

    protected function getCustomer($id)
    {
        $customerInfo = Pic::where('id', $id)->first();
        var_dump($id);

        Admin::script('alert("hello world");');

        if (isset($customerInfo)) {
            return $this->$customerInfo = $customerInfo;
        }
        return;
    }
    // return view("admin.modal");
    // 別タブでページを開く
    // <a href="https//localhost:8000/modal/customers" target="_blank">顧客一覧を開く</a>
}
