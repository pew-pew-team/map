<?php

declare(strict_types=1);

namespace PewPew\Map\Tests\Functional;

use PewPew\Map\Tests\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('functional'), Group('pew-pew/map')]
abstract class TestCase extends BaseTestCase {}
