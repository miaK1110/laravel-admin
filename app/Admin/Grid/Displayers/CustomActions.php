<?php

namespace App\Admin\Grid\Displayers;

use Encore\Admin\Grid\Displayers\Actions;

class CustomActions extends Actions
{
    protected function renderView()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getRouteKey()}" class="{$this->grid->getGridRowName()}-view btn btn-sm btn-default">
    <i class="fa fa-eye"></i> View
</a>
EOT;
    }

    protected function renderEdit()
    {
        return <<<EOT
<a href="{$this->getResource()}/{$this->getRouteKey()}/edit" class="{$this->grid->getGridRowName()}-edit btn btn-sm btn-default">
    <i class="fa fa-edit"></i> Edit
</a>
EOT;
    }

    protected function renderDelete()
    {
        $this->setupDeleteScript();

        return <<<EOT
<a href="javascript:void(0);" data-id="{$this->getKey()}" class="{$this->grid->getGridRowName()}-delete btn btn-sm btn-default">
    <i class="fa fa-trash"></i> Delete
</a>
EOT;
    }
}
