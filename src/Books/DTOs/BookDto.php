<?php

namespace Library\Books\DTOs;

class BookDto
{
    private ?string $encoded_id;
    private ?string $isbn;
    private ?string $title;
    private ?string $description;
    private ?int $pages;
    private ?string $topic;

    public static function createFromArray(array $data): BookDto
    {
        $dto = new self();
        $dto->encoded_id = $data['encoded_id']?? null;
        $dto->isbn = $data['isbn']?? null;
        $dto->title = $data['title']?? null;
        $dto->description = $data['description']?? null;
        $dto->pages = $data['pages']?? null;
        $dto->topic = $data['topic']?? null;

        return $dto;
    }

    public function getEncodedId(): ?string
    {
        return $this->encoded_id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}