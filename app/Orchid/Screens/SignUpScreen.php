<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Link;
use Orchid\Screen\Screen;

class SignUpScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'SignUpScreen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'SignUpScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [];
    }
}
