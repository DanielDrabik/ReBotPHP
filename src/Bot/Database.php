<?php

namespace ReBot\Bot;

use PDO;

class Database {
    private PDO $pdo;

    public function __construct(string $path = __DIR__ . '/../../database/rebot.sqlite') {
        $this->pdo = new PDO("sqlite:" . $path);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function migrate(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS memes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                keyword TEXT NOT NULL,
                response TEXT NOT NULL
            )
        ");
    }

    public function insertMeme(string $keyword, string $response): void {
        $stmt = $this->pdo->prepare("INSERT INTO memes (keyword, response) VALUES (:keyword, :response)");
        $stmt->execute(['keyword' => $keyword, 'response' => $response]);
    }

    public function getMeme(string $keyword): ?string {
        $stmt = $this->pdo->prepare("SELECT response FROM memes WHERE keyword = :keyword ORDER BY RANDOM() LIMIT 1");
        $stmt->execute(['keyword' => $keyword]);
        return $stmt->fetchColumn() ?: null;
    }

    public function importFromJson(string $jsonFile): void
    {
        if (!file_exists($jsonFile)) {
            throw new \RuntimeException("File $jsonFile does not exists");
        }

        $data = json_decode(file_get_contents($jsonFile), true);

        if (!is_array($data)) {
            throw new \RuntimeException("Could not parse JSON file from file $jsonFile");
        }

        $stmt = $this->pdo->prepare("INSERT OR REPLACE INTO memes (keyword, response) VALUES (:keyword, :response)");

        foreach ($data as $keyword => $response) {
            $stmt->execute([
                ':keyword' => trim($keyword),
                ':response' => trim($response),
            ]);
        }
    }

    public function getAllMemes(): array
    {
        $stmt = $this->pdo->query("SELECT keyword, response FROM memes");
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}
