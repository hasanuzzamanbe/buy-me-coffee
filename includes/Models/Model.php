<?php

namespace BuyMeCoffee\Models;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

abstract class Model
{
    protected $guarded = ['id', 'ID'];
    protected $table;

    public function __construct()
    {
        // Ensure that the table is set. This can be overridden in child classes.
        if (empty($this->table)) {
            throw new \Exception("Table name must be set in the child class.");
        }
    }
    public function create(array $data)
    {
        return $this->getQuery()->insert($this->filterGuarded($data));
    }

    public function all()
    {
        return $this->getQuery()->get();
    }

    public function find($id)
    {
        return $this->getQuery()->where('id', $id)->first();
    }

    public function delete($id)
    {
        return $this->getQuery()->where('id', $id)->delete();
    }

    public function getQuery()
    {
        return buyMeCoffeeQuery()->table($this->table);
    }

    protected function filterGuarded(array $data)
    {
        return array_filter($data, function($key) {
            return !in_array($key, $this->guarded);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function raw($query)
    {
        return buyMeCoffeeQuery()->raw($query);
    }
}