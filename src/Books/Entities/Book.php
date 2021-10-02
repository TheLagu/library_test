<?php

namespace Library\Books\Entities;

use DateTimeImmutable;
use Library\Shared\Traits\UuidTrait;
use Ramsey\Uuid\Uuid;

class Book
{
    use UuidTrait;

    private int $id;
    private string $encodedId;
    private string $title;
    private string $isbn;
    private int $pages;
    private ?string $topic;
    private DateTimeImmutable $created_at;

    private function __construct(string $title, string $isbn, int $pages)
    {
        $this->encodedId = Uuid::uuid4()->toString();
        $this->title = $title;
        $this->isbn = $isbn;
        $this->pages = $pages;
        $this->created_at = new DateTimeImmutable();
    }

    public static function create(string $title, string $isbn, int $pages, ?string $topic = null): Book
    {
        $entity = new self($title, $isbn, $pages);
        $entity->topic = $topic;

        return $entity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEncodedId(): string
    {
        return $this->encodedId;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }
}