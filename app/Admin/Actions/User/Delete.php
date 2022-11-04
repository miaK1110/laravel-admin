<?php

namespace App\Admin\Actions\User;

use Encore\Admin\Actions\RowAction;

class Delete extends RowAction
{
    public $name = 'delete';

    public function handle()
    {
        // 削除処理
        $this->row->delete();

        return $this->response()->success('Delete Successful')->refresh();
    }


    public function dialog()
    {
        // $auths = $this->row->id;
  
        $message = '本当に削除しますか？';
        $this->confirm($message);
    }

    public function html()
    {
        return "<a class='report-posts btn btn-sm btn-danger'><i class='fa fa-info-circle'></i>Report</a>";
    }

}