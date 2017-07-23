<?php

namespace EaseRequest;

abstract class EaseRequest
{
    use EaseRequestTrait;

    abstract function preRules();
}