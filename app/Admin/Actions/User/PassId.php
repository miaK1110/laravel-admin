<?php

namespace App\Admin\Actions\User;

use Encore\Admin\Actions\RowAction;

class PassId extends RowAction
{
    public $name = 'passId';

    public function handle($id)
    {
        return Modal::$customerId = $id;
    }
    public function html()
    {
        return "<a class='report-posts btn btn-sm btn-danger'><i class='fa fa-info-circle'></i>選ぶ</a>";
    }
}
