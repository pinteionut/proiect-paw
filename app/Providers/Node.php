<?php

namespace App\Providers;

class Node
{
  public $id, $top, $right, $bottom, $left, $occupied_by;
  public $next; // Folosit pentru parcurgerea tuturor nodurilor


  function __construct($id, $top, $right, $bottom, $left, $previous)
  {
    $this->id = $id;
    $this->top = $top;
    if ($this->top) {
      $this->top->bottom = $this;
    }
    $this->right = $right;
    if ($this->right) {
      $this->right->left = $this;
    }
    $this->bottom = $bottom;
    if ($this->bottom) {
      $this->bottom->top = $this;
    }
    $this->left = $left;
    if ($this->left) {
      $this->left->right = $this;
    }

    if ($previous) {
      $previous->next = $this;
    }
  }

  public function in_mill()
  {
    if (!$this->occupied_by) {
      return false;
    }

    if ($this->left && $this->right && $this->left->occupied_by == $this->occupied_by && $this->right->occupied_by == $this->occupied_by) {
      return [$this->left->id, $this->id, $this->right->id];
    }

    if ($this->top && $this->bottom && $this->top->occupied_by == $this->occupied_by && $this->bottom->occupied_by == $this->occupied_by) {
      return [$this->top->id, $this->id, $this->bottom->id];
    }

    if ($this->left && $this->left->left && $this->left->occupied_by == $this->occupied_by && $this->left->left->occupied_by == $this->occupied_by) {
      return [$this->left->id, $this->id, $this->left->left->id];
    }

    if ($this->right && $this->right->right && $this->right->occupied_by == $this->occupied_by && $this->right->right->occupied_by == $this->occupied_by) {
      return [$this->right->id, $this->id, $this->right->right->id];
    }

    if ($this->top && $this->top->top && $this->top->occupied_by == $this->occupied_by && $this->top->top->occupied_by == $this->occupied_by) {
      return [$this->top->id, $this->id, $this->top->top->id];
    }

    if ($this->bottom && $this->bottom->bottom && $this->bottom->occupied_by == $this->occupied_by && $this->bottom->bottom->occupied_by == $this->occupied_by) {
      return [$this->bottom->id, $this->id, $this->bottom->bottom->id];
    }

    return false;
  }

  public function empty_neighbors()
  {
    $empty_neighbors = [];
    if ($this->top && !$this->top->occupied_by) {
      $empty_neighbors[] = $this->top->id;
    }
    if ($this->right && !$this->right->occupied_by) {
      $empty_neighbors[] = $this->right->id;
    }
    if ($this->bottom && !$this->bottom->occupied_by) {
      $empty_neighbors[] = $this->bottom->id;
    }
    if ($this->left && !$this->left->occupied_by) {
      $empty_neighbors[] = $this->left->id;
    }

    if (empty($empty_neighbors)) {
      return false;
    }

    return $empty_neighbors;
  }
}
