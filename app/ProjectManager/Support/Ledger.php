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

namespace BynqIO\Dynq\ProjectManager\Support;

use Closure;

class Ledger
{
    /**
     * The flow paths provided for the project.
     *
     * @var array
     */
    protected $features = [];
    protected $original = true;
    protected $filter;
    protected $layer;
    protected $profit;
    protected $calculateRow;
    protected $optdata;

    private function registerFilter($parent)
    {
        $this->filter = function($section, $object) use ($parent) {
            return $parent->{$section . 'Filter'}($object);
        };
    }

    private function appendFeatures(array $features)
    {
        foreach ($features as $feature => $value) {
            $this->features[$feature] = $value;
        }
    }

    public function __construct($caller, array $features = [])
    {
        $this->registerFilter($caller);
        $this->appendFeatures($features);
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function features(array $items)
    {
        $this->appendFeatures($items);

        return $this;
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function original($orig = true)
    {
        $this->original = $orig;

        return $this;
    }

    /**
     * Register the application's event listeners.
     *
     * @return void
     */
    public function isOriginal()
    {
        return $this->original;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function data(array $data)
    {
        $this->optdata = $data;

        return $this;
    }

    public function layer(Closure $func)
    {
        $this->layer = $func;

        return $this;
    }

    public function profit(Closure $func)
    {
        $this->profit = $func;

        return $this;
    }

    public function calculateRow(Closure $func)
    {
        $this->calculateRow = $func;

        return $this;
    }

    protected function readOnly(array $data)
    {
        $this->features['level.new']           = false;
        $this->features['activity.options']    = false;
        $this->features['chapter.options']     = false;
        $this->features['tax.update']          = false;
        $this->features['rows.labor.edit']     = false;
        $this->features['rows.material.add']   = false;
        $this->features['rows.material.edit']  = false;
        $this->features['rows.other.add']      = false;
        $this->features['rows.other.edit']     = false;

        return $this;
    }

    /**
     * Get the flows.
     *
     * @return array
     */
    public function make()
    {
        $data = [
            'features' => $this->features,
            'original' => $this->original,
        ];

        if (isset($this->filter)) {
            $data['filter'] = $this->filter;
        }

        if (isset($this->layer)) {
            $data['layer'] = $this->layer;
        }

        if (isset($this->profit)) {
            $data['profit'] = $this->profit;
        }

        if (isset($this->calculateRow)) {
            $data['calculate_row'] = $this->calculateRow;
        }

        if (isset($this->optdata)) {
            $data = array_merge($data, $this->optdata);
        }

        return $data;
    }
}
