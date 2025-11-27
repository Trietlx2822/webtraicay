<?php session_start(); ?>
echo "
<pre>"; print_r($_SESSION); echo "</pre>";
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Complete Responsive MT Store Website</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="style.css">

    <style>
    .cart-icon {
        position: relative;
        display: inline-block;
        cursor: pointer;
        font-size: 1.5rem;
    }

    .cart-icon i {
        color: #333;
        transition: 0.3s;
    }

    .cart-icon:hover i {
        color: #3bb77e;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #e74c3c;
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        width: 18px;
        height: 18px;
        line-height: 18px;
        text-align: center;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }
    </style>


</head>

<body>

    <header class="header">
        <a href="index.php" class="logo"><i class="fas fa-shopping-cart"></i>MT Store</a>
        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#product">Product</a>


            <a href="#blog">Blog</a>
            <a href="#contact">Contact</a>
            <a href="review.php">Review</a>
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1): ?>
            <a href="update.php">Manage</a>
            <?php endif; ?>
            <a href="order_history.php">Oder History</a>
        </nav>
        <div class="icons">
            <div class="cart-icon" onclick="location.href='cart.php'" style="position:relative; display:inline-block;">
                <i class="fas fa-shopping-basket" style="font-size:1.7rem; vertical-align:middle;"></i>
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $cart_count += $item['quantity'] ?? 1;
                    }
                }
                if ($cart_count > 0):
                ?>
                <span class="cart-badge"><?= $cart_count ?></span>
                <?php endif; ?>
            </div>
            <div id="login-btn" class="fas fa-user">
                <script>
                document.getElementById("login-btn").addEventListener("click", function() {
                    // Chuyển hướng đến trang logout.php khi nhấn vào div
                    window.location.href = "login.php";
                });
                </script>
            </div>


            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1): ?>
            <div id="menu-btn" class="fas fa-bars" title="Admin menu"></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
            <div id="sign-out" class="fas fa-sign-out-alt"></div>
            <script>
            document.getElementById("sign-out").addEventListener("click", function() {
                // Chuyển hướng đến trang logout.php khi nhấn vào div
                window.location.href = "logout.php";
            });
            </script>
            <?php endif; ?>
        </div>

        <!--login
        
        <form action="" class="login-form">
            <h3>Login form</h3>
            <input type="email" placeholder="Enter your Email" class="box">
            <input type="password" placeholder="Enter your password" class="box">

            <div class="remember">
                <input type="checkbox" name="" id="remember-me">
                <label for="remember-me">Remember Me</label>
            </div>
            <a href="product.php" class="btn">Login</a>
        </form>-->
    </header>
    <!--home-->
    <section class="home" id="home"
        style="background-image: url('../font-end/images/h3.png'); background-size: cover; background-position: center;">

        <div class="content">
            <h3>Buy Best<span> Organic<br> Product</span> Online</h3>
            <a href="product.php" class="btn">Get Your Now</a>
        </div>
    </section>

    <!--banner-->
    <section class="banner-container">
        <div class="banner">
            <img src="images/banner1.png" alt="banner">
            <div class="content">
                <span>Limited Sales</span>
                <h3>Fresh Garden Salad</h3>
                <a href="product.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="banner">
            <img src="images/banner2.png" alt="banner">
            <div class="content">
                <span>Limited Sales</span>
                <h3>Mixed Veggie & Fruit Fit Bowl</h3>
                <a href="product.php" class="btn">Shop Now</a>
            </div>
        </div>
        <div class="banner">
            <img src="images/banner3.png" alt="banner">
            <div class="content">
                <span>Limited Sales</span>
                <h3>Creamy Caesar Salad with Croutons</h3>
                <a href="product.php" class="btn">Shop Now</a>
            </div>
        </div>
    </section>

    <!--About-->
    <section class="about" id="about">
        <h1 class="heading">About <span>Us</span></h1>
        <div class="row">
            <div class="content">
                <h3>We make organic food in market</h3>
                <div class="divider"></div>
                <p>At Organic Market, we are passionate about bringing the freshest,
                    healthiest organic foods to your table. Our mission is to support sustainable farming and provide
                    you with nutritious,
                    chemical-free produce that nourishes both body and soul.</p>
                <p>With a focus on quality and eco-friendly practices, we aim to make organic living accessible to
                    everyone.
                    Join us in our journey to promote wellness and a greener planet!</p>
                <a href="about.php" class="btn">Read more</a>
            </div>

            <div class="image"><img src="images/about1.png" alt=""></div>
            <div class="image"><img src="images/about2.png" alt=""></div>

        </div>

    </section>

    <!--category-->
    <section id="category">
        <h1 class="heading">Our <span>Category</span></h1>
        <div class="category-container">
            <a href="product.php" class="category-box">
                <img src="images/fish.png" alt="Fish">
                <span>Fish & Meat</span>
            </a>
            <a href="product.php" class="category-box">
                <img src="images/Vegetables.png" alt="Vegetables">
                <span>Vegetables</span>
            </a>
            <a href="product.php" class="category-box">
                <img src="images/medicine.png" alt="Medicine">
                <span>Medicine</span>
            </a>
            <a href="product.php" class="category-box">
                <img src="images/baby.png" alt="Baby">
                <span>Baby</span>
            </a>
            <a href="product.php" class="category-box">
                <img src="images/office.png" alt="Office">
                <span>Office</span>
            </a>
            <a href="product.php" class="category-box">
                <img src="images/beauty.png" alt="Beauty">
                <span>Beauty</span>
            </a>
            <a href="product.php" class="category-box">
                <img src="images/Gardening.png" alt="Gardening">
                <span>Gardening</span>
            </a>
        </div>
    </section>

    <!--Product-->
    <section class="product" id="product">
        <h1 class="heading">Vegetables <span>Items</span></h1>
        <div class="box-container">
            <div class="box">
                <div class="image">
                    <img src="images/p1.png" alt="">
                </div>
                <div class="content">
                    <h3>Vegetables</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart btn-wishlist" data-id="1"></i>-->
                    <a href="product_detail.php?id=6" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p2.png" alt="">
                </div>
                <div class="content">
                    <h3>Tomato</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=1" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p3.png" alt="">
                </div>
                <div class="content">
                    <h3>Broccoli</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=2" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p4.png" alt="">
                </div>
                <div class="content">
                    <h3>Carrot</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=9" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p5.png" alt="">
                </div>
                <div class="content">
                    <h3>Green Cabbage</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=5" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p6.png" alt="">
                </div>
                <div class="content">
                    <h3>Green Leaf Lettuce</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=11" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p7.png" alt="">
                </div>
                <div class="content">
                    <h3>Salat</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=12" class="fas fa-eye"></a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/p8.png" alt="">
                </div>
                <div class="content">
                    <h3>WaterMelon</h3>
                    <div class="price">$10.00</div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="fas fa-shopping-cart"></a>
                    <!-- <i class="fas fa-shopping-cart"></i>-->
                    <!--<i class="fas fa-heart"></i>-->
                    <a href="product_detail.php?id=13" class="fas fa-eye"></a>
                </div>
            </div>
        </div>
    </section>

    <!--product 2-->
    <section class="product" id="product">
        <h1 class="heading">Fish <span>Items</span></h1>
        <div class="box-container">
            <div class="box">
                <div class="image">
                    <img src="images/f1.png" alt="">
                </div>
                <div class="content">
                    <h3>Mixed Fish Selection</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/f2.png" alt="">
                </div>
                <div class="content">
                    <h3>Mixed Seafood Selection</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/f3.png" alt="">
                </div>
                <div class="content">
                    <h3>Sea Bream</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/f4.png" alt="">
                </div>
                <div class="content">
                    <h3>Fresh Yellowtail and Snapper</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>

        </div>
    </section>

    <!--product 3- meat-->
    <section class="product" id="product">
        <h1 class="heading">Meat <span>Items</span></h1>
        <div class="box-container">
            <div class="box">
                <div class="image">
                    <img src="images/m1.png" alt="">
                </div>
                <div class="content">
                    <h3>Beef Cubes</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/m2.png" alt="">
                </div>
                <div class="content">
                    <h3>Lamb Chops</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/m3.png" alt="">
                </div>
                <div class="content">
                    <h3>Rack of Lamb</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/m4.png" alt="">
                </div>
                <div class="content">
                    <h3>Beef Tenderloin</h3>
                    <div class="price">Per KG= <span>$10.00</span></div>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <a href="product.php" class="btn">Oder Now</a>
                </div>
            </div>

        </div>
    </section>

    <!--Package-->
    <section class="bundle">
        <h1 class="heading">Package <span>Items</span></h1>
        <div class="product-container">
            <div class="product-box">
                <img src="images/pack1.jpg" alt="">
                <strong>Seafood Big Pack</strong>
                <span class="quantity">A variety of fresh fish perfect for grilling, frying, or steaming.
                    Packed with protein and omega-3 fatty acids to support a healthy diet.</span>
                <span class="price">$10.00</span>

                <a href="product.php" class="btn">
                    <i class="fas fa-shopping-basket">Add to cart</i>
                </a>

                <a href="#" class="like-btn">
                    <i class="far fa-heart"></i>
                </a>
            </div>
            <div class="product-box">
                <img src="images/pack2.jpg" alt="">
                <strong>Vegetable Big Pack</strong>
                <span class="quantity">A colorful selection of farm-fresh vegetables including tomatoes, potatoes, and
                    more.
                    Ideal for soups, stir-fries, or healthy meal prep.</span>
                <span class="price">$10.00</span>

                <a href="product.php" class="btn">
                    <i class="fas fa-shopping-basket">Add to cart</i>
                </a>

                <a href="#" class="like-btn">
                    <i class="far fa-heart"></i>
                </a>
            </div>
            <div class="product-box">
                <img src="images/pack3.png" alt="">
                <strong>Fruit & Veggie Big Pack</strong>
                <span class="quantity">A balanced mix of fresh fruits and vegetables. Great for families looking to
                    maintain a healthy lifestyle with natural nutrients.</span>
                <span class="price">$10.00</span>

                <a href="product.php" class="btn">
                    <i class="fas fa-shopping-basket">Add to cart</i>
                </a>

                <a href="#" class="like-btn">
                    <i class="far fa-heart"></i>
                </a>
            </div>
            <div class="product-box">
                <img src="images/pack4.png" alt="">
                <strong>Lamb Meat Big Pack</strong>
                <span class="quantity">Premium cuts of lamb including French-cut racks. Ideal for roasting or grilling.
                    Tender, flavorful, and protein-rich.</span>
                <span class="price">$10.00</span>

                <a href="product.php" class="btn">
                    <i class="fas fa-shopping-basket">Add to cart</i>
                </a>

                <a href="#" class="like-btn">
                    <i class="far fa-heart"></i>
                </a>
            </div>
        </div>
    </section>

    <!--Blog-->
    <section class="blog" id="blog">
        <h1 class="heading">Our <span>Blog</span></h1>

        <div class="box-container">
            <div class="box">
                <div class="image">
                    <img src="images/blog1.png" alt="">
                </div>
                <div class="content">
                    <div class="icons">
                        <a href="#"><i class="fas fa-user"></i> By Admin</a>
                    </div>
                    <h3>Fresh Eats Blog</h3>
                    <p>Welcome to Fresh Eats Blog, your go-to source for delicious and healthy recipes featuring organic
                        vegetables.
                        Our blog is packed with expert tips, nutritious meal ideas, and the latest trends in organic
                        eating.
                        Whether you're a seasoned chef or a beginner, join us to explore a world of vibrant flavors and
                        wellness inspiration!</p>
                    <a href="blog.php" class="btn">Read Moer</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/blog2.png" alt="">
                </div>
                <div class="content">
                    <div class="icons">
                        <a href="#"><i class="fas fa-user"></i> By Admin</a>
                    </div>
                    <h3>Wellness Tips</h3>
                    <p>Dive into Wellness Tips at Organic Market, your guide to a healthier lifestyle with organic
                        vegetables.
                        Learn expert tips on nutrition, wellness routines, and how to maximize the benefits of fresh
                        produce.
                        Start your journey to well-being with us today!</p>
                    <a href="blog.php" class="btn">Read Moer</a>
                </div>
            </div>
            <div class="box">
                <div class="image">
                    <img src="images/blog3.png" alt="">
                </div>
                <div class="content">
                    <div class="icons">
                        <a href="#"><i class="fas fa-user"></i> By Admin</a>
                    </div>
                    <h3>Healthy Vegetables Try</h3>
                    <p>welcome to Healthy Vegetables Try Bolg where we dive into the world of organic vegetables that
                        boost your health.
                        Discover expert advice, tasty recipes, and the amazing benefits of nutrient-rich veggies like
                        spinach, carrots, and broccoli.
                        Join us to nourish your body with nature’s finest!</p>
                    <a href="blog.php" class="btn">Read Moer</a>
                </div>
            </div>
        </div>
    </section>

    <!--Review
    <section class="review" id="review">
        <h1 class="heading">Our <span>Review</span></h1>
        <div class="swiper review-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide">
                    <p>I love the organic spinach from Organic Market! It’s fresh, vibrant, and packed with nutrients.
                        Perfect for my salads and smoothies—my body feels energized after every meal.
                    </p>
                    <i class="fas fa-quote-right"></i>
                    <div class="user">
                        <img src="images/review-1.png" alt="">
                        <div class="user-info">
                            <h3>Jack de</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <p>The quinoa and chia seed mix is a game-changer for my breakfast routine. It’s 100% organic,
                        easy to prepare, and keeps me full for hours.
                        My digestion has improved so much since I started eating this.
                        Amazing product!</p>
                    <i class="fas fa-quote-right"></i>
                    <div class="user">
                        <img src="images/review-2.png" alt="">
                        <div class="user-info">
                            <h3>Marina</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <p>These organic carrot sticks are so crisp and naturally sweet! I snack on them daily,
                        and they’re great for my skin and eyes.
                        Knowing they’re free from chemicals makes me feel good about what I’m eating. Love it!</p>
                    <i class="fas fa-quote-right"></i>
                    <div class="user">
                        <img src="images/review-3.png" alt="">
                        <div class="user-info">
                            <h3>Jack pop</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <p>Organic Market’s broccoli florets are the best I’ve tried.
                        They’re fresh, tender, and full of flavor.
                        I’ve been adding them to my stir-fries, and they make every dish healthier.
                        My family loves them too—great quality!</p>
                    <i class="fas fa-quote-right"></i>
                    <div class="user">
                        <img src="images/review-4.png" alt="">
                        <div class="user-info">
                            <h3>Tom end</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <p>Organic Market’s broccoli florets are the best I’ve tried.
                        They’re fresh, tender, and full of flavor.
                        I’ve been adding them to my stir-fries, and they make every dish healthier.
                        My family loves them too—great quality!</p>
                    <i class="fas fa-quote-right"></i>
                    <div class="user">
                        <img src="images/review-5.png" alt="">
                        <div class="user-info">
                            <h3>Jonh jon</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide slide">
                    <p>he quinoa and chia seed mix is a game-changer for my breakfast routine. It’s 100% organic,
                        easy to prepare, and keeps me full for hours.
                        My digestion has improved so much since I started eating this.
                        Amazing product!</p>
                    <i class="fas fa-quote-right"></i>
                    <div class="user">
                        <img src="images/review-6.png" alt="">
                        <div class="user-info">
                            <h3>Shamera</h3>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!--contact page-->
    <section class="contact" id="contact">
        <h1 class="heading">Contact <span>Us</span></h1>

        <div class="row">
            <div class="image">
                <img src="images/contact.png" alt="">
            </div>
            <form action="">
                <div class="inputBox">
                    <input type="text" placeholder="first name">
                    <input type="text" placeholder="last name">
                </div>
                <div class="inputBox">
                    <input type="email" placeholder="enter your email">
                    <input type="number" placeholder="phone">
                </div>
                <textarea name="" id="" placeholder="message"></textarea>\
                <input type="submit" value="Order Now" class="btn">
            </form>
        </div>
    </section>

    <!--footer-->
    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>Find us here</h3>
                <p>Visit us at Organic Market, located at Long Xuyen, An Giang, where fresh organic goodness awaits!
                    We’re open Monday to Saturday,
                    8 AM to 6 PM, ready to help you find the best in clean, healthy food.
                    Drop by and explore our range of organic vegetables, fruits, and more—your body will thank you!.</p>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-instagram"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                </div>
            </div>
            <div class="box">
                <h3>Phone</h3>
                <p>+84354644361</p>
                <a href="#" class="link">minhtriet2822@gmail.com</a>

            </div>
            <div class="box">
                <h3>Locaitions</h3>
                <p>MT Store <br> Long Xuyen <br> An Giang</p>


            </div>
        </div>
        <div class="credit">Created by <span>MT Store </span>all rights reserved</div>
    </section>




    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script src="script.js"></script>
    <script type="module">
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-analytics.js";

    // https://firebase.google.com/docs/web/setup#available-libraries


    const firebaseConfig = {
        apiKey: "AIzaSyALZFg_aPSDqUdPLx1MU56yGpZoBMIBy4o",
        authDomain: "fresh-grocery-website.firebaseapp.com",
        projectId: "fresh-grocery-website",
        storageBucket: "fresh-grocery-website.appspot.com",
        messagingSenderId: "1011036522168",
        appId: "1:1011036522168:web:5185fc42b9964538e9164d",
        measurementId: "G-7922W5TCWL"
    };


    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    </script>
    <script src="wishlist.js"></script>
</body>

</html>