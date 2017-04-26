<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the BynqIO\CalculatieTool.com.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  CalculatieTool
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

namespace BynqIO\CalculatieTool\Http\Composers;

use BynqIO\CalculatieTool\Models\MessageBox;
use Illuminate\View\View;

use Auth;

class HeaderComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $items = MessageBox::where('user_id', Auth::id())->where('active', true)->whereNull('read')->get();
        $view->with('notifications', $items);
    }
}