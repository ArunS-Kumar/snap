<?php

namespace App\Traits;

trait RequestHelperTrait
{

    // // merge route params + input params
    protected function validationData()
    {
        return array_merge(request()->all(), request()->route()->parameters());
    }
}