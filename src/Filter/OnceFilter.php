<?php

declare(strict_types=1);

namespace Xtompie\Flux\Filter;

use PDO;
use Xtompie\Flux\Core\Filter;
use Xtompie\Flux\Core\SetUp;
use Xtompie\Flux\Core\TearDown;
use Xtompie\Flux\Util\Dir;

class OnceFilter implements Filter, SetUp, TearDown
{
    protected ?PDO $db = null;
    protected array $buffor = [];

    public static function new(string $storage): static
    {
        return new static(
            storage: $storage,
        );
    }

    public function __construct(
        protected string $storage,
    ) {
    }

    public function setUp(): void
    {
        Dir::touch($this->storage);
        $storage = Dir::ensureEndSlash($this->storage);
        $this->db = new PDO("sqlite:{$storage}ids.sqlite3");
        $stmt = $this->db->prepare("CREATE TABLE IF NOT EXISTS ids (id TEXT PRIMARY KEY);");
        $stmt->execute();
    }

    public function tearDown(): void
    {
        $this->commit();
    }

    public function filter(string $entry): ?string
    {
        if ($this->known($entry)) {
            return null;
        }

        return $entry;
    }

    protected function known(string $entry): bool
    {
        $hash = sha1($entry);

        if ($this->isRegistred($hash)) {
            return true;
        }

        $this->register($hash);
        return false;
    }

    protected function isRegistred(string $hash): bool
    {
        if (isset($this->buffor[$hash])) {
            return true;
        }

        $stmt = $this->db->prepare("SELECT * FROM ids WHERE id = '$hash'");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    protected function register(string $hash): void
    {
        $this->buffor[$hash] = true;

        if (count($this->buffor) >= 1000) {
            $this->commit();
        }
    }

    protected function commit(): void
    {
        if (!$this->buffor) {
            return;
        }

        $values = "('" . implode("'), ('", array_keys($this->buffor)) . "')";
        $stmt = $this->db->prepare("INSERT INTO ids (id) VALUES $values");
        $stmt->execute();
        $this->buffor = [];
    }
}
