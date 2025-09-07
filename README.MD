# ReBotPHP - Discord Bot in PHP

A lightweight Discord bot written in PHP, running inside Docker.  
It uses **SQLite** as storage for memes and keywords.

---

## 🚀 Getting Started

### 1. Clone the repository
```bash
git clone https://github.com/your-username/ReBotPHP.git
cd ReBotPHP
```

### 2. Configure environment variables
Copy the sample .env file and fill in your Discord bot token:
```bash
cp .env.sample .env
```

Inside .env, set:
```bash
DISCORD_TOKEN=your_discord_bot_token
```

### 3. Database
The bot uses SQLite.
If the database file does not exist yet, create it manually:
```bash
touch database/rebot.sqlite
```

### 4. Build and start containers
```bash
docker-compose build
docker-compose up -d
```

### 🎭 Bot Features

Keyword reactions
The bot listens to all messages on channels it has access to.
If a message contains a registered keyword, the bot replies with the associated meme or text.

Example:
```
User: Let's drink something!
Bot: Did someone said drink?
```

Add new memes dynamically
Users can add new memes via command:
```
!addmeme <keyword> <response>
```

### 📥 Importing Memes from JSON

You can bulk import memes using a JSON file.
Example memes.json:
```json
{
    "drink": "Did someone said drink?",
    "xD": "xD"
}
```

Steps:
1. Place your memes.json file inside the src/ directory.
2. Run the import script:

```bash
docker-compose exec bot php src/import_memes.php
```

3. ✅ All memes from JSON will be inserted into the SQLite database.