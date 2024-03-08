<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Unit;

use PewPew\Map\Tests\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('unit'), Group('pew-pew/map')]
abstract class TestCase extends BaseTestCase {}
