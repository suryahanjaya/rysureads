<?php

require_once __DIR__ . '/app.php';

if (!class_exists('mysqli')) {
    class mysqli_result
    {
        private array $rows;
        private int $cursor = 0;
        public int $num_rows;

        public function __construct(array $rows)
        {
            $this->rows = array_values($rows);
            $this->num_rows = count($this->rows);
        }

        public function fetch_assoc(): ?array
        {
            if (!isset($this->rows[$this->cursor])) {
                return null;
            }

            return $this->rows[$this->cursor++];
        }
    }

    class mysqli_stmt
    {
        private \PDO $pdo;
        private string $sql;
        private array $boundValues = [];
        public int $insert_id = 0;

        public function __construct(\PDO $pdo, string $sql)
        {
            $this->pdo = $pdo;
            $this->sql = $sql;
        }

        public function bind_param(string $types, &...$vars): bool
        {
            $this->boundValues = [];
            foreach ($vars as $var) {
                $this->boundValues[] = $var;
            }
            return true;
        }

        public function execute(array $params = []): bool
        {
            $values = $params ?: $this->boundValues;
            $stmt = $this->pdo->prepare($this->sql);

            foreach (array_values($values) as $index => $value) {
                $type = \PDO::PARAM_STR;
                if (is_int($value)) {
                    $type = \PDO::PARAM_INT;
                } elseif (is_bool($value)) {
                    $type = \PDO::PARAM_BOOL;
                } elseif ($value === null) {
                    $type = \PDO::PARAM_NULL;
                }

                $stmt->bindValue($index + 1, $value, $type);
            }

            $ok = $stmt->execute();
            $this->insert_id = (int) $this->pdo->lastInsertId();
            $this->lastStatement = $stmt;
            return $ok;
        }

        private ?\PDOStatement $lastStatement = null;

        public function get_result(): mysqli_result
        {
            $rows = $this->lastStatement ? $this->lastStatement->fetchAll(\PDO::FETCH_ASSOC) : [];
            return new mysqli_result($rows);
        }

        public function close(): void
        {
            $this->lastStatement = null;
        }
    }

    class mysqli
    {
        private \PDO $pdo;
        public string $connect_error = '';
        public int $insert_id = 0;

        public function __construct(string $host, string $user, string $pass, string $dbname)
        {
            try {
                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                $this->pdo = new \PDO($dsn, $user, $pass, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => true,
                ]);
            } catch (\Throwable $e) {
                $this->connect_error = $e->getMessage();
            }
        }

        public function set_charset(string $charset): bool
        {
            return true;
        }

        public function query(string $sql): mysqli_result|bool
        {
            $stmt = $this->pdo->query($sql);
            if ($stmt === false) {
                return false;
            }

            return new mysqli_result($stmt->fetchAll(\PDO::FETCH_ASSOC));
        }

        public function exec(string $sql): int|false
        {
            return $this->pdo->exec($sql);
        }

        public function prepare(string $sql): mysqli_stmt
        {
            return new mysqli_stmt($this->pdo, $sql);
        }

        public function close(): void
        {
        }
    }
}

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'rysureads';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error !== '') {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

function ensure_schema(mysqli $conn): void
{
    $conn->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        slug VARCHAR(120) NOT NULL UNIQUE
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        slug VARCHAR(180) NOT NULL UNIQUE,
        price DECIMAL(10,2) NOT NULL DEFAULT 0,
        rating DECIMAL(3,1) NOT NULL DEFAULT 4.5,
        category_id INT NOT NULL,
        description TEXT NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS item_locations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        location_name VARCHAR(150) NOT NULL,
        address VARCHAR(255) NOT NULL,
        map_url VARCHAR(255) NOT NULL,
        availability_note VARCHAR(255) DEFAULT NULL
    )");

    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(20) NOT NULL DEFAULT 'user',
        reset_token VARCHAR(100) DEFAULT NULL,
        reset_expires_at DATETIME DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");

    $hasSlug = $conn->query("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'categories' AND column_name = 'slug'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasSlug === 0) {
        $conn->exec("ALTER TABLE categories ADD COLUMN slug VARCHAR(120) NULL");
        $conn->exec("UPDATE categories SET slug = LOWER(REPLACE(TRIM(name), ' ', '-')) WHERE slug IS NULL OR slug = ''");
        $conn->exec("ALTER TABLE categories MODIFY slug VARCHAR(120) NOT NULL");
        $conn->exec("ALTER TABLE categories ADD UNIQUE KEY slug_unique (slug)");
    }

    $hasItemSlug = $conn->query("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'items' AND column_name = 'slug'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasItemSlug === 0) {
        $conn->exec("ALTER TABLE items ADD COLUMN slug VARCHAR(180) NULL");
        $conn->exec("UPDATE items SET slug = LOWER(REPLACE(TRIM(name), ' ', '-')) WHERE slug IS NULL OR slug = ''");
        $conn->exec("ALTER TABLE items MODIFY slug VARCHAR(180) NOT NULL");
        $conn->exec("ALTER TABLE items ADD UNIQUE KEY item_slug_unique (slug)");
    }

    $hasRating = $conn->query("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'items' AND column_name = 'rating'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasRating === 0) {
        $conn->exec("ALTER TABLE items ADD COLUMN rating DECIMAL(3,1) NOT NULL DEFAULT 4.5");
    }

    $hasResetToken = $conn->query("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'reset_token'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasResetToken === 0) {
        $conn->exec("ALTER TABLE users ADD COLUMN reset_token VARCHAR(100) DEFAULT NULL");
    }

    $hasResetExpires = $conn->query("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'reset_expires_at'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasResetExpires === 0) {
        $conn->exec("ALTER TABLE users ADD COLUMN reset_expires_at DATETIME DEFAULT NULL");
    }

    $hasRole = $conn->query("SELECT COUNT(*) AS c FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'role'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasRole === 0) {
        $conn->exec("ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'");
    }

    $hasLocationsTable = $conn->query("SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'item_locations'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasLocationsTable === 0) {
        $conn->exec("CREATE TABLE item_locations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            item_id INT NOT NULL,
            location_name VARCHAR(150) NOT NULL,
            address VARCHAR(255) NOT NULL,
            map_url VARCHAR(255) NOT NULL,
            availability_note VARCHAR(255) DEFAULT NULL
        )");
    }

    $hasLocationUnique = $conn->query("SELECT COUNT(*) AS c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'item_locations' AND index_name = 'item_location_unique'")->fetch_assoc()['c'] ?? 0;
    if ((int) $hasLocationUnique === 0) {
        $conn->exec("ALTER TABLE item_locations ADD UNIQUE KEY item_location_unique (item_id, location_name)");
    }
}

ensure_schema($conn);

function seed_catalog_data(mysqli $conn): void
{
    $conn->query("INSERT IGNORE INTO categories (name, slug) VALUES
        ('Classic Literature', 'classic-literature'),
        ('Classic Fiction', 'classic-fiction'),
        ('Dystopian Fiction', 'dystopian-fiction'),
        ('Romance', 'romance'),
        ('Fantasy', 'fantasy'),
        ('Coming-of-Age', 'coming-of-age')");

    $conn->query("INSERT IGNORE INTO items (name, slug, price, rating, category_id, description, image) VALUES
        ('The Great Gatsby', 'the-great-gatsby', 12.99, 4.8, 1, 'A portrait of the Jazz Age and a cautionary tale of the American Dream.', 'images/item1.jpg'),
        ('To Kill a Mockingbird', 'to-kill-a-mockingbird', 14.50, 4.9, 2, 'A gripping tale of racial injustice and childhood innocence in the American South.', 'images/item2.jpg'),
        ('1984', '1984', 11.99, 4.9, 3, 'A dystopian masterpiece about surveillance, propaganda, and the erosion of truth.', 'images/item3.jpg'),
        ('Pride and Prejudice', 'pride-and-prejudice', 10.99, 4.7, 4, 'A witty exploration of manners, morality, and marriage in Regency-era England.', 'images/item4.jpg'),
        ('The Hobbit', 'the-hobbit', 15.99, 4.9, 5, 'An epic quest through Middle-earth filled with dragons, dwarves, and adventure.', 'images/item5.jpg'),
        ('The Catcher in the Rye', 'the-catcher-in-the-rye', 13.49, 4.4, 6, 'A coming-of-age story about adolescence, identity, and belonging.', 'images/item6.jpg')");

    $locations = [
        ['the-great-gatsby', 'District 1 Store', '41 Nguyen Hue, District 1, Ho Chi Minh City', 'https://maps.google.com/?q=41+Nguyen+Hue,+District+1,+Ho+Chi+Minh+City', 'In stock'],
        ['the-great-gatsby', 'Thu Duc Branch', '11 Vo Van Ngan, Thu Duc City', 'https://maps.google.com/?q=11+Vo+Van+Ngan,+Thu+Duc+City', 'Limited stock'],
        ['to-kill-a-mockingbird', 'District 3 Store', '220 Vo Thi Sau, District 3, Ho Chi Minh City', 'https://maps.google.com/?q=220+Vo+Thi+Sau,+District+3,+Ho+Chi+Minh+City', 'In stock'],
        ['1984', 'District 1 Store', '41 Nguyen Hue, District 1, Ho Chi Minh City', 'https://maps.google.com/?q=41+Nguyen+Hue,+District+1,+Ho+Chi+Minh+City', 'In stock'],
        ['pride-and-prejudice', 'District 5 Store', '905 Tran Hung Dao, District 5, Ho Chi Minh City', 'https://maps.google.com/?q=905+Tran+Hung+Dao,+District+5,+Ho+Chi+Minh+City', 'In stock'],
        ['the-hobbit', 'Thu Duc Branch', '11 Vo Van Ngan, Thu Duc City', 'https://maps.google.com/?q=11+Vo+Van+Ngan,+Thu+Duc+City', 'In stock'],
        ['the-catcher-in-the-rye', 'District 7 Store', '2 Tan Trao, District 7, Ho Chi Minh City', 'https://maps.google.com/?q=2+Tan+Trao,+District+7,+Ho+Chi+Minh+City', 'Pre-order'],
    ];

    foreach ($locations as [$slug, $locationName, $address, $mapUrl, $note]) {
        $stmt = $conn->prepare("INSERT IGNORE INTO item_locations (item_id, location_name, address, map_url, availability_note)
                                SELECT id, ?, ?, ?, ?
                                FROM items
                                WHERE slug = ?");
        $stmt->bind_param('sssss', $locationName, $address, $mapUrl, $note, $slug);
        $stmt->execute();
        $stmt->close();
    }

    $adminEmail = 'admin@rysureads.local';
    $adminPassword = password_hash('admin12345', PASSWORD_DEFAULT);
    $adminCheck = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $adminCheck->bind_param('s', $adminEmail);
    $adminCheck->execute();
    $existingAdmin = $adminCheck->get_result()->fetch_assoc();
    $adminCheck->close();

    if ($existingAdmin) {
        $updateAdmin = $conn->prepare('UPDATE users SET role = ? WHERE email = ?');
        $adminRole = 'admin';
        $updateAdmin->bind_param('ss', $adminRole, $adminEmail);
        $updateAdmin->execute();
        $updateAdmin->close();
    } else {
        $insertAdmin = $conn->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $adminName = 'Library Admin';
        $adminRole = 'admin';
        $insertAdmin->bind_param('ssss', $adminName, $adminEmail, $adminPassword, $adminRole);
        $insertAdmin->execute();
        $insertAdmin->close();
    }
}

seed_catalog_data($conn);
