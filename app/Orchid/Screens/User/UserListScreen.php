<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use Orchid\Screen\Layout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Support\Facades\Alert;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserListLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;

class UserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'User';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'All registered users';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * Query data.
     *
     * @return array
     */
    public function query() : array
    {
        return  [
            'users'  => User::with('roles')
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar() : array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout() : array
    {
        return [
            UserFiltersLayout::class,
            UserListLayout::class,

            Layout::modals([
                'oneAsyncModal' => [
                    UserEditLayout::class,
                ],
            ])->async('asyncGetUser'),
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetUser(User $user) : array
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(User $user, Request $request)
    {
        $user->fill($request->get('user'))
            ->save();

        Alert::info(__('User was saved.'));

        return back();
    }
}
