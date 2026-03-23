<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RysuReads - Online Bookstore</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- HEADER & NAVIGATION -->
    <?php include '../components/header.php'; ?>

    <!-- HERO SECTION -->
    <div class="main-content">
    <section class="hero-section">
        <div class="container text-center">
            <h1>Welcome to RysuReads</h1>
            <p class="mt-3">Your online bookstore for timeless classics and modern reads.</p>
            <a href="#catalog" class="btn-browse mt-3">Browse Collection</a>
        </div>
    </section>

    <!-- SEARCH SECTION -->
    <section class="py-5" id="search-section">
        <div class="container">
            <div class="search-wrapper">
                <input type="text" id="searchInput" class="form-control" placeholder="Search books by title...">
            </div>
        </div>
    </section>

    <!-- BOOK CATALOG -->
    <section class="pb-5" id="catalog">
        <div class="container">
            <h2 class="section-title">Featured Books</h2>

            <div class="row">

                <!-- Book 1 -->
                <div class="col-md-4 mb-4 book-card-wrapper">
                    <div class="card book-card">
                        <img src="images/item1.jpg" class="card-img-top" alt="The Great Gatsby">
                        <div class="card-body">
                            <h5 class="card-title">The Great Gatsby</h5>
                            <p class="card-text">A portrait of the Jazz Age and a cautionary tale of the American Dream.</p>
                            <div class="book-price">$12.99</div>
                            <a href="../pages/item_details.php?id=1" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Book 2 -->
                <div class="col-md-4 mb-4 book-card-wrapper">
                    <div class="card book-card">
                        <img src="images/item2.jpg" class="card-img-top" alt="To Kill a Mockingbird">
                        <div class="card-body">
                            <h5 class="card-title">To Kill a Mockingbird</h5>
                            <p class="card-text">A gripping tale of racial injustice and childhood innocence in the American South.</p>
                            <div class="book-price">$14.50</div>
                            <a href="../pages/item_details.php?id=2" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Book 3 -->
                <div class="col-md-4 mb-4 book-card-wrapper">
                    <div class="card book-card">
                        <img src="images/item3.jpg" class="card-img-top" alt="1984">
                        <div class="card-body">
                            <h5 class="card-title">1984</h5>
                            <p class="card-text">A dystopian masterpiece about totalitarianism, surveillance, and the erosion of truth.</p>
                            <div class="book-price">$11.99</div>
                            <a href="../pages/item_details.php?id=3" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Book 4 -->
                <div class="col-md-4 mb-4 book-card-wrapper">
                    <div class="card book-card">
                        <img src="images/item4.jpg" class="card-img-top" alt="Pride and Prejudice">
                        <div class="card-body">
                            <h5 class="card-title">Pride and Prejudice</h5>
                            <p class="card-text">A witty exploration of manners, morality, and marriage in Regency-era England.</p>
                            <div class="book-price">$10.99</div>
                            <a href="../pages/item_details.php?id=4" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Book 5 -->
                <div class="col-md-4 mb-4 book-card-wrapper">
                    <div class="card book-card">
                        <img src="images/item5.jpg" class="card-img-top" alt="The Hobbit">
                        <div class="card-body">
                            <h5 class="card-title">The Hobbit</h5>
                            <p class="card-text">An epic quest through Middle-earth filled with dragons, dwarves, and adventure.</p>
                            <div class="book-price">$15.99</div>
                            <a href="../pages/item_details.php?id=5" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>

                <!-- Book 6 -->
                <div class="col-md-4 mb-4 book-card-wrapper">
                    <div class="card book-card">
                        <img src="images/item6.jpg" class="card-img-top" alt="The Catcher in the Rye">
                        <div class="card-body">
                            <h5 class="card-title">The Catcher in the Rye</h5>
                            <p class="card-text">Follow Holden Caulfield as he grapples with adolescence, identity, and belonging.</p>
                            <div class="book-price">$13.49</div>
                            <a href="../pages/item_details.php?id=6" class="btn-view">View Details</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="py-5" id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-section">
                        <form onsubmit="event.preventDefault(); showMessage('Thank you! We will get back to you soon.');">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contactName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="contactName" placeholder="Your name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contactEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contactEmail" placeholder="Your email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="contactMessage" class="form-label">Message</label>
                                <textarea class="form-control" id="contactMessage" rows="4" placeholder="Your message..." required></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn-submit">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div><!-- end .main-content -->

    <!-- FOOTER -->
    <?php include '../components/footer.php'; ?>

    <!-- Custom JS -->
    <script src="js/script.js"></script>

</body>

</html>
