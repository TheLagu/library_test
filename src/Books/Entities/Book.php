<?php

namespace Library\Books\Entities;

use DateTimeImmutable;
use Library\Shared\Traits\UuidTrait;
use Ramsey\Uuid\Uuid;

class Book
{
    use UuidTrait;

    private int $id;
    private string $encoded_id;
    private string $title;
    private string $isbn;
    private int $pages;
    private ?string $description;
    private ?string $topic;
    private DateTimeImmutable $created_at;

    private function __construct(string $title, string $isbn, int $pages)
    {
        $this->encoded_id = Uuid::uuid4()->toString();
        $this->title = $title;
        $this->isbn = $isbn;
        $this->pages = $pages;
        $this->created_at = new DateTimeImmutable();
    }

    public static function create(string $title, string $isbn, int $pages, ?string $topic = null, ?string $description = null): Book
    {
        $entity = new self($title, $isbn, $pages);
        $entity->topic = $topic;
        $entity->description = $description;

        return $entity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEncodedId(): string
    {
        return $this->encoded_id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }
}