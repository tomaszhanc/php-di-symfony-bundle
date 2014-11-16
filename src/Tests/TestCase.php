<?php

namespace DI\Bundle\Symfony\Tests;

abstract class TestCase  extends \PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        \Mockery::close();
    }
} 