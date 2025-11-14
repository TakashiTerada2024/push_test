<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Ncc01\User\Application\Usecase\RetrieveAuthenticatedUserInterface;

class AuthenticatedUserComposer
{
    /**
     * @param RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
     */
    public function __construct(
        private RetrieveAuthenticatedUserInterface $retrieveAuthenticatedUser
    ) {
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('authenticatedUser', $this->retrieveAuthenticatedUser->__invoke());
    }
}
