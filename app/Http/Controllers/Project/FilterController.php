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

namespace BynqIO\Dynq\Http\Controllers\Project;

use Exception;
use Carbon\Carbon;
use BynqIO\Dynq\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    private function statusFilter($input, $builder)
    {
        switch ($input) {
            case 'open':
                return $builder->whereNull('project_close');
            case 'closed':
                return $builder->whereNotNull('project_close')->where('is_dilapidated', false);
            case 'dilapidated':
                return $builder->where('is_dilapidated', true);
        }
    }

    private function typeFilter($input, $builder)
    {
        switch ($input) {
            case 'calculatie':
                return $builder->whereHas('type', function ($query) {
                    $query->where('type_name', 'calculatie');
                });
            case 'regie':
                return $builder->whereHas('type', function ($query) {
                    $query->where('type_name', 'regie');
                });
            case 'snelle offerte en factuur':
                return $builder->whereHas('type', function ($query) {
                    $query->where('type_name', 'snelle offerte en factuur');
                });
        }
    }

    private function updatedFilter($input, $builder)
    {
        if (strpos($input, 'after:') !== false) {
            $date = substr($input, strlen('after:'));

            try {
                return $builder->where('updated_at', '>', Carbon::parse($date)->toIso8601String());
            } catch (Exception $err) {
                return null;
            }
        } else if (strpos($input, 'before:') !== false) {
            $date = substr($input, strlen('before:'));
            
            try {
                return $builder->where('updated_at', '<', Carbon::parse($date)->toIso8601String());
            } catch (Exception $err) {
                return null;
            }
        } else if (!empty($input)) {
            try {
                return $builder->where('updated_at', Carbon::parse($input)->toIso8601String());
            } catch (Exception $err) {
                return null;
            }
        }
    }

    private function sortFilter($input, $builder)
    {
        $order = null;
        if (strpos($input, ':asc') !== false) {
            $order = 'asc';
        }

        if (strpos($input, ':desc') !== false) {
            $order = 'desc';
        }

        if (!is_null($order)) {
            $input = explode(':', $input)[0];
        }

        switch ($input) {
            case 'name':
                return $builder->orderBy('project_name', $order);
            case 'client':
                return $builder->orderBy('client_id', $order);
            case 'created':
                return $builder->orderBy('created_at', $order);
            case 'updated':
                return $builder->orderBy('updated_at', $order);
        }
    }

    protected function findFilter($input, $projects)
    {
        $filterobject = null;
        foreach ($input as $key => $value) {
            $filter = "{$key}Filter";

            if (method_exists($this, $filter)) {
                $filterobject = $this->{$filter}($value, $projects);
            }
        }

        /* No filter matched, return original input with default filters */
        if (is_null($filterobject)) {
            return $projects->where('is_dilapidated', false)
                            ->orderBy('updated_at', 'desc')
                            ->get();
        }

        /* Filter matched, empty resultset */
        if ($filterobject->get()->isEmpty()) {
            return [];
        }

        return $filterobject->get();
    }

    public function __invoke(Request $request)
    {
        $projects = $this->findFilter($request->all(), $request->user()->projects());
        return view('project.all', ['projects' => $projects]);
    }

}
