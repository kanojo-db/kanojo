<?php

declare(strict_types=1);

namespace App\Contracts;

interface PopularityContract
{
    /**
     * Returns the popularity of the model as a float number between 0 and 1.
     */
    public function computePopularity(): float;
}
