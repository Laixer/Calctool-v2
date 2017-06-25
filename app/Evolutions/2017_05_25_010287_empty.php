<?php

/**
 * Copyright (C) 2017 Bynq.io B.V.
 * All Rights Reserved
 *
 * This file is part of the Dynq project.
 *
 * Content can not be copied and/or distributed without the express
 * permission of the author.
 *
 * @package  Dynq
 * @author   Yorick de Wid <y.dewid@calculatietool.com>
 */

use BynqIO\Dynq\Models\User;

use Encryptor;

class EmptyTransform
{
    /**
     * Run the transform operations.
     *
     * @return void
     */
    public function up(User $user)
    {
        // dd($user);
    }

    /**
     * Reverse the transform operations.
     *
     * @return void
     */
    public function down(User $user)
    {
        //
    }
}
