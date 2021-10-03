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

    public function update(?string $title, ?string $isbn, ?int $pages, ?string $topic, ?string $description): void
    {
        $this->title = is_null($title)? $this->title : $title;
        $this->isbn = is_null($isbn)? $this->isbn : $isbn;
        $this->pages = is_null($pages)? $this->pages : $pages;
        $this->topic = is_null($topic)? $this->topic : $topic;
        $this->description = is_null($description)? $this->description : $description;
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