<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\Translatable\HasTranslations;

trait LockColumns
{
    use HasTranslations;

    /**
     * Cast locked_columns as a JSON column
     */
    public function initializeLockColumns(): void
    {
        $this->casts['locked_columns'] = 'json';
    }

    /**
     * Set a given attribute on the model if column is not locked
     */
    public function setAttribute($key, $value)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // ALWAYS drop locked columns, except if the user is an admin or a
        // moderator.
        if ($this->isLocked($key) && ! $user->hasAnyRole(['admin', 'moderator'])) {
            return false;
        }

        // Since we are also using the HasTranslations trait on all lockable
        // models, we need to make sure we also do whatever it does when
        // setting an attribute
        if ($this->isTranslatableAttribute($key) && is_array($value)) {
            return $this->setTranslations($key, $value);
        }

        // Pass arrays and untranslatable attributes to the parent method.
        if (! $this->isTranslatableAttribute($key) || is_array($value)) {
            return parent::setAttribute($key, $value);
        }

        // If the attribute is translatable and not already translated, set a
        // translation for the current app locale.
        return $this->setTranslation($key, $this->getLocale(), $value);
    }

    /**
     * Check if column is locked
     */
    public function isLocked(string $column): bool
    {
        return in_array($column, (array) $this->locked_columns);
    }

    /**
     * Lock a specific column
     */
    public function lockColumn(string $column): bool
    {
        if ($this->isLocked($column)) {
            return false;
        }

        return $this->update([
            'locked_columns' => array_merge((array) $this->locked_columns, [$column]),
        ]);
    }

    /**
     * Unlock a specific column
     */
    public function unlockColumn(string $column): bool
    {
        if (! $this->isLocked($column)) {
            return false;
        }

        return $this->update([
            'locked_columns' => array_diff($this->locked_columns, [$column]),
        ]);
    }

    /**
     * Force update a value on a locked column
     */
    public function overrideLock(string $column, mixed $value): bool
    {
        $this->unlockColumn($column);
        $update = $this->update([$column => $value]);
        $this->lockColumn($column);

        return $update;
    }
}
