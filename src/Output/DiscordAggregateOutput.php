<?php

declare(strict_types=1);

namespace Xtompie\Flux\Output;

use Xtompie\Flux\Core\Output;
use Xtompie\Flux\Core\TearDown;

class DiscordAggregateOutput implements Output, TearDown
{
    protected const MAX_MESSAGE_LENGTH = 2000;

    public static function new(
        string $webhook,
        string $prefix = '',
        string $suffix = '',
        int $limit = 10,
        bool $tail = false,
    ): static
    {
        return new static(
            webhook: $webhook,
            prefix: $prefix,
            suffix: $suffix,
            limit: $limit,
            tail: $tail,
        );
    }

    protected array $messages = [];

    public function __construct(
        protected string $webhook,
        protected string $prefix,
        protected string $suffix,
        protected int $limit,
        protected bool $tail,
    ) {
    }

    public function output(string $entry): void
    {
        $this->messages[] = $entry;
        if (count($this->messages) >= $this->limit) {
            if ($this->tail) {
                $this->messages = array_slice($this->messages, -$this->limit);
            } else {
                $this->messages = array_slice($this->messages, 0, $this->limit);
            }
        }
    }

    public function tearDown(): void
    {
        if ($this->messages) {
            $this->sendMessage(implode("\n", $this->messages));
        }
    }

    protected function sendMessage(string $message): void
    {
        $message = $this->prefix . $message . $this->suffix;
        $message = substr($message, 0, self::MAX_MESSAGE_LENGTH);

        file_get_contents(
            $this->webhook,
            false,
            stream_context_create(
                [
                    'http' => [
                        'header' => [
                            'Content-Type: application/json',
                        ],
                        'method'  => 'POST',
                        'content' => json_encode([
                            'content' => $message,
                        ]),
                    ],
                ]
            )
        );
    }
}