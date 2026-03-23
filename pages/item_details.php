<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details - RysuReads</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../public/css/style.css" rel="stylesheet">
</head>

<body>

    <!-- HEADER & NAVIGATION -->
    <?php include '../components/header.php'; ?>

    <!-- ITEM DETAILS -->
    <div class="main-content">
    <section class="py-5">
        <div class="container">

            <a href="../public/index.php" class="btn-back">< Back to Catalog</a>

            <?php
            // Book data array
            $books = [
                1 => [
                    'name'        => 'The Great Gatsby',
                    'author'      => 'F. Scott Fitzgerald',
                    'image'       => '../public/images/item1.jpg',
                    'description' => 'The Great Gatsby is a 1925 novel that follows narrator Nick Carraway and his mysterious, wealthy neighbor Jay Gatsby. Set during the Roaring Twenties on Long Island, the story explores themes of decadence, idealism, and the American Dream. Gatsby\'s obsessive pursuit of his former love, Daisy Buchanan, drives the narrative toward its tragic conclusion.',
                    'price'       => '$12.99',
                    'category'    => 'Classic Literature',
                ],
                2 => [
                    'name'        => 'To Kill a Mockingbird',
                    'author'      => 'Harper Lee',
                    'image'       => '../public/images/item2.jpg',
                    'description' => 'Published in 1960, To Kill a Mockingbird is set in the fictional town of Maycomb, Alabama during the Great Depression. Through the eyes of young Scout Finch, the novel addresses serious issues of racial injustice and moral growth. Her father, Atticus Finch, serves as a moral hero as he defends a Black man falsely accused of a crime.',
                    'price'       => '$14.50',
                    'category'    => 'Classic Fiction',
                ],
                3 => [
                    'name'        => '1984',
                    'author'      => 'George Orwell',
                    'image'       => '../public/images/item3.jpg',
                    'description' => 'Published in 1949, 1984 is a dystopian novel set in a totalitarian society ruled by Big Brother. The story follows Winston Smith, a low-ranking member of the Party, who secretly harbors rebellious thoughts. The novel explores surveillance, propaganda, and the destruction of individual freedom.',
                    'price'       => '$11.99',
                    'category'    => 'Dystopian Fiction',
                ],
                4 => [
                    'name'        => 'Pride and Prejudice',
                    'author'      => 'Jane Austen',
                    'image'       => '../public/images/item4.jpg',
                    'description' => 'First published in 1813, Pride and Prejudice follows Elizabeth Bennet as she navigates issues of manners, morality, education, and marriage in the landed gentry of the British Regency. The witty interplay between Elizabeth and Mr. Darcy remains one of the most celebrated love stories in English literature.',
                    'price'       => '$10.99',
                    'category'    => 'Romance',
                ],
                5 => [
                    'name'        => 'The Hobbit',
                    'author'      => 'J.R.R. Tolkien',
                    'image'       => '../public/images/item5.jpg',
                    'description' => 'Published in 1937, The Hobbit follows Bilbo Baggins, a hobbit who is swept into an epic quest to reclaim the lost Dwarf Kingdom of Erebor from the fearsome dragon Smaug. Along the way, Bilbo discovers courage, friendship, and a mysterious ring that would shape the fate of Middle-earth.',
                    'price'       => '$15.99',
                    'category'    => 'Fantasy',
                ],
                6 => [
                    'name'        => 'The Catcher in the Rye',
                    'author'      => 'J.D. Salinger',
                    'image'       => '../public/images/item6.jpg',
                    'description' => 'Published in 1951, The Catcher in the Rye follows Holden Caulfield, a teenager navigating life in New York City after being expelled from prep school. The novel delves into themes of alienation, identity, and the loss of innocence, making it one of the most influential coming-of-age stories ever written.',
                    'price'       => '$13.49',
                    'category'    => 'Coming-of-Age',
                ],
            ];

            // Get book ID from URL
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

            // Default to book 1 if invalid
            if (!isset($books[$id])) {
                $id = 1;
            }

            $book = $books[$id];
            ?>

            <div class="row">
                <div class="col-md-5 mb-4">
                    <img src="<?php echo $book['image']; ?>" class="detail-img" alt="<?php echo $book['name']; ?>">
                </div>
                <div class="col-md-7">
                    <span class="detail-category"><?php echo $book['category']; ?></span>
                    <h2 class="detail-title"><?php echo $book['name']; ?></h2>
                    <p style="color: #3D8D7A; font-weight: 500;">by <?php echo $book['author']; ?></p>
                    <p class="detail-price">Price: <?php echo $book['price']; ?></p>
                    <p class="detail-description"><?php echo $book['description']; ?></p>
                    <button class="btn-view" onclick="showMessage('<?php echo $book['name']; ?> added to cart!')">Add to Cart</button>
                </div>
            </div>

        </div>
    </section>
    </div><!-- end .main-content -->

    <!-- FOOTER -->
    <?php include '../components/footer.php'; ?>

    <!-- Custom JS -->
    <script src="../public/js/script.js"></script>

</body>

</html>
