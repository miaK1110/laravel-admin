<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Pic extends Model implements Sortable
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];

    // /**
    //  * Make a form builder.
    //  *
    //  * @return Form
    //  */
    // protected function form()
    // {
    //     $form = new Form(new User());
    //     $form->image('pic', 'プロフ画像')->uniqueName();
    //     return $form;
    // }
    use \Encore\Admin\Traits\Resizable;
    // To access thumbnail
    // $photo->thumbnail('small','photo_column');

}
