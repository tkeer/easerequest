<?php

namespace EaseRequest;

use Illuminate\Foundation\Http\FormRequest;

abstract class EaseRequest extends FormRequest
{
    use EaseRequestTrait;

    abstract function preRules();
}