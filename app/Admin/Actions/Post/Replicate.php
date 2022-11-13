<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use App\Admin\Controllers\ModalController;

class Replicate extends RowAction
{
    public $name = '選ぶ';

    public function handle($id)
    {
        // ModalController::$customerId = $id;
        // return responce()->$id;
    }
    public function html()
    {
        return "<a class='report-posts btn btn-sm btn-danger'><i class='fa fa-info-circle'></i>選ぶ</a>";
    }
}
