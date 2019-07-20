<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\TD;
use Orchid\Platform\Models\User;
use Orchid\Screen\Layouts\Table;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $data = 'users';

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            TD::set('id', 'ID')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->sort()
                ->link('platform.systems.users.edit', 'id'),

            TD::set('name', __('Name'))
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (User $user) {
                    // Please use view('path')
                    return "<div class='d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center'>
                                <span class='thumb-xs avatar m-r-xs d-none d-md-inline-block'>
                                  <img src='{$user->getAvatar()}' class='bg-light'>
                                </span>
                                <div class='ml-sm-3 ml-md-0 ml-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0'>
                                  <p class='mb-0'>{$user->getNameTitle()}</p>
                                  <small class='text-xs text-muted'>{$user->getSubTitle()}</small>
                                </div>
                            </div>";
                }),

            TD::set('email', __('Email'))
                ->loadModalAsync('oneAsyncModal', 'saveUser', 'id', 'email')
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::set('updated_at', __('Last edit'))
                ->sort(),
        ];
    }
}
