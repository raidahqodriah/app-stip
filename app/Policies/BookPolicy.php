<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Student;
use App\Models\Library\Book;
use Illuminate\Contracts\Auth\Authenticatable;

class BookPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return $user->can('library.book.view');
        }

        if ($user instanceof Student) {
            return true;
        }

        return false;
    }

    public function view(Authenticatable $user, Book $book): bool
    {
        if ($user instanceof Employee) {
            return $user->can('library.book.view');
        }

        if ($user instanceof Student) {
            return true;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('library.book.manage');
    }

    public function update(Authenticatable $user, Book $book): bool
    {
        return $user instanceof Employee && $user->can('library.book.manage');
    }

    public function delete(Authenticatable $user, Book $book): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        if ($book->circulations()->where('status', 'borrowed')->exists()) {
            return false;
        }

        return $user->can('library.book.manage');
    }
}
