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

namespace BynqIO\CalculatieTool\Models\Traits;

use Auth;

trait Ownable
{
    public function isOwner()
    {
        return Auth::id() == $this->user_id;
    }

    //TODO: check user id
    protected function transferOwnership($id)
    {
        $this->user_id = $id;
    }
}
