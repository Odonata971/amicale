<?php

namespace MvcLite\Controllers;

use MvcLite\Controllers\Engine\Controller;
use MvcLite\Engine\InternalResources\Storage;
use MvcLite\Views\Engine\View;

class IndexController extends Engine\Controller
{

    public function __construct()
    {
        parent::__construct();

        // Empty constructor.
    }

    public function render(): void
    {
        View::render("index");
        // we pass the offers to the view

    }

}