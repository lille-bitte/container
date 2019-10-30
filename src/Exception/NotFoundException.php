<?php

namespace LilleBitte\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;
use Exception;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}
