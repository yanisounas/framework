<?php

namespace Framework\ORM\Enum;

enum QueryMethod
{
    case SELECT;
    case UPDATE;
    case DELETE;
    case INSERT;
    case CUSTOM;
}