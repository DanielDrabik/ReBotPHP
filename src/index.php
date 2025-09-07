<?php

require __DIR__ . '/../vendor/autoload.php';

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use ReBot\Bot\Database;

$db = new Database();
$db->migrate();

$discord = new Discord([
    'token' => getenv('DISCORD_TOKEN'),
    'intents' => Intents::getDefaultIntents() | Intents::GUILD_MESSAGES | Intents::MESSAGE_CONTENT,
]);

$memes = $db->getAllMemes();

$discord->on(Event::MESSAGE_CREATE, function ($message) use ($memes, $discord) {
    echo $message->content . PHP_EOL;
    if ($message->author->id === $discord->user->id) {
        return;
    }

    $content = mb_strtolower($message->content); // case-insensitive

    foreach ($memes as $keyword => $response) {
        if (str_contains($content, mb_strtolower($keyword))) {
            $message->reply($response);
            return;
        }
    }
});

$discord->on(Event::MESSAGE_CREATE, function ($message) use ($db) {
    if (str_starts_with($message->content, '!addmeme ')) {
        $parts = explode(' ', $message->content, 3);

        if (count($parts) < 3) {
            $message->reply("❌ Usage: `!addmeme <keyword> <response>`");
            return;
        }

        [$command, $keyword, $response] = $parts;
        $db->insertMeme($keyword, $response);

        $message->reply("✅ Created response for **$keyword**!");
    }
});

$discord->run();