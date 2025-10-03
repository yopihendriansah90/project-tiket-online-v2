<?php

namespace Tests\Unit;

use App\Enums\EventStatus;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EventStatusTest extends TestCase
{
    #[Test]
    public function it_has_correct_enum_values()
    {
        $this->assertEquals('draft', EventStatus::DRAFT->value);
        $this->assertEquals('published', EventStatus::PUBLISHED->value);
        $this->assertEquals('completed', EventStatus::COMPLETED->value);
        $this->assertEquals('cancelled', EventStatus::CANCELLED->value);
    }

    #[Test]
    public function it_returns_correct_labels()
    {
        $this->assertEquals('Draft', EventStatus::DRAFT->getLabel());
        $this->assertEquals('Dipublikasi', EventStatus::PUBLISHED->getLabel());
        $this->assertEquals('Selesai', EventStatus::COMPLETED->getLabel());
        $this->assertEquals('Dibatalkan', EventStatus::CANCELLED->getLabel());
    }

    #[Test]
    public function it_returns_correct_colors()
    {
        $this->assertEquals('gray', EventStatus::DRAFT->getColor());
        $this->assertEquals('success', EventStatus::PUBLISHED->getColor());
        $this->assertEquals('info', EventStatus::COMPLETED->getColor());
        $this->assertEquals('danger', EventStatus::CANCELLED->getColor());
    }
}