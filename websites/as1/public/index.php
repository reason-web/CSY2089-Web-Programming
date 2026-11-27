<?php
require 'db.php';

session_start();

$sql = "SELECT auction.*, category.name AS categoryName,
        user.name AS sellerName,
        COALESCE(MAX(bid.bid), 0) AS currentBid
        FROM auction
        JOIN category ON auction.categoryId = category.categoryId
        JOIN user ON auction.userId = user.userId
        LEFT JOIN bid ON auction.auctionId = bid.auctionId
        GROUP BY auction.auctionId
        ORDER BY auction.endDate ASC
        LIMIT 10";

$stmt = $pdo->query($sql);
$auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
$categoryStmt = $pdo->query("SELECT * FROM category ORDER BY name");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html>
<html>
	<head>
		<title>Carbuy Auctions</title>
		<link rel="stylesheet" href="carbuy.css" />
	</head>

	<body>
		<header>
			<h1><span class="C">C</span>
 			<span class="a">a</span>
			<span class="r">r</span>
			<span class="b">b</span>
			<span class="u">u</span>
			<span class="y">y</span></h1>

			<form action="#">
				<input type="text" name="search" placeholder="Search for a car" />
				<input type="submit" name="submit" value="Search" />
			</form>
		</header>

		<nav>
			<ul>
				<li><a class="categoryLink" href="#">Estate</a></li>
				<li><a class="categoryLink" href="#">Electric</a></li>
				<li><a class="categoryLink" href="#">Coupe</a></li>
				<li><a class="categoryLink" href="#">Saloon</a></li>
				<li><a class="categoryLink" href="#">4x4</a></li>
				<li><a class="categoryLink" href="#">Sports</a></li>
				<li><a class="categoryLink" href="#">Hybrid</a></li>
				<li><a class="categoryLink" href="#">More</a></li>
			</ul>
		</nav>
		<img src="banners/1.jpg" alt="Banner" />

		<main>
			<h1>Latest Car Listings / Search Results / Category listing</h1>
			
				<li>
					<img src="car.png" alt="car name">
					<article>
						<h2>Car model and make</h2>
						<h3>Car category</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales ornare purus, non laoreet dolor sagittis id. Vestibulum lobortis laoreet nibh, eu luctus purus volutpat sit amet. Proin nec iaculis nulla. Vivamus nec tempus quam, sed dapibus massa. Etiam metus nunc, cursus vitae ex nec, scelerisque dapibus eros. Donec ac diam a ipsum accumsan aliquet non quis orci. Etiam in sapien non erat dapibus rhoncus porta at lorem. Suspendisse est urna, egestas ut purus quis, facilisis porta tellus. Pellentesque luctus dolor ut quam luctus, nec porttitor risus dictum. Aliquam sed arcu vehicula, tempor velit consectetur, feugiat mauris. Sed non pellentesque quam. Integer in tempus enim.</p>
<form method="GET" action="search.php">
    <input type="text" name="search" placeholder="Search cars..." required>
    <button type="submit">Search</button>
</form><h2>Categories</h2>

<?php foreach ($categories as $category): ?>
    <a class="categoryLink" href="search.php?category=<?php echo $category['categoryId']; ?>">
        <?php echo htmlspecialchars($category['name']); ?>
    </a>
<?php endforeach; ?>
				<ul class="carList">
<?php foreach ($auctions as $auction): ?>
    <li>
        <img src="car.png" alt="Car">
        <article>
            <h2><?php echo htmlspecialchars($auction['title']); ?></h2>
            <h3><?php echo htmlspecialchars($auction['categoryName']); ?></h3>
            <p><?php echo htmlspecialchars($auction['description']); ?></p>
            <p class="price">
                Current bid: £<?php echo number_format($auction['currentBid'], 2); ?>
            </p>
            <a href="auction.php?id=<?php echo $auction['auctionId']; ?>" class="more auctionLink">More &gt;&gt;</a>
        </article>
    </li>
<?php endforeach; ?>
</ul>

			<hr />

			<h1>Car Page</h1>
			<article class="car">

					<img src="car.png" alt="car name">
					<section class="details">
						<h2>Car model and make</h2>
						<h3>Car category</h3>
						<p>Auction created by <a href="#">User.Name</a></p>
						<p class="price">Current bid: £4000</p>
						<time>Time left: 8 hours 3 minutes</time>
						<form action="#" class="bid">
							<input type="text" name="bid" placeholder="Enter bid amount" />
							<input type="submit" value="Place bid" />
						</form>
					</section>
					<section class="description">
					<p>
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales ornare purus, non laoreet dolor sagittis id. Vestibulum lobortis laoreet nibh, eu luctus purus volutpat sit amet. Proin nec iaculis nulla. Vivamus nec tempus quam, sed dapibus massa. Etiam metus nunc, cursus vitae ex nec, scelerisque dapibus eros. Donec ac diam a ipsum accumsan aliquet non quis orci. Etiam in sapien non erat dapibus rhoncus porta at lorem. Suspendisse est urna, egestas ut purus quis, facilisis porta tellus. Pellentesque luctus dolor ut quam luctus, nec porttitor risus dictum. Aliquam sed arcu vehicula, tempor velit consectetur, feugiat mauris. Sed non pellentesque quam. Integer in tempus enim.</p>


					</section>

					<section class="reviews">
						<h2>Reviews of User.Name </h2>
						<ul>
							<li><strong>John said </strong> great car seller! Car was as advertised and delivery was quick <em>29/01/2024</em></li>
							<li><strong>Dave said </strong> disappointing, Car was slightly damaged and arrived slowly.<em>22/12/2023</em></li>
							<li><strong>Susan said </strong> great value but the delivery was slow <em>22/07/2023</em></li>

						</ul>

						<form>
							<label>Add your review</label> <textarea name="reviewtext"></textarea>

							<input type="submit" name="submit" value="Add Review" />
						</form>
					</section>
					</article>

					<hr />
					<h1>Sample Form</h1>

					<form action="#">
						<label>Text box</label> <input type="text" />
						<label>Another Text box</label> <input type="text" />
						<input type="checkbox" /> <label>Checkbox</label>
						<input type="radio" /> <label>Radio</label>
						<input type="submit" value="Submit" />

					</form>



			<footer>
				&copy; Carbuy 2024
			</footer>
		</main>
	</body>
</html>