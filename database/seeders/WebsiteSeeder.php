<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{

    protected $homeSection = <<<EOT
<section class="hero text-center" id="home">
    <div class="container">
        <h1 class="display-2 fw-bold mb-4">Flavor Fiesta</h1>
        <p class="lead fs-3 mb-5">
            A culinary journey through international flavors
        </p>
        <div>
            <a href="#menu" class="btn btn-primary btn-lg me-3">
                <i class="bi bi-journal-richtext"></i> View Menu
            </a>
        </div>
    </div>
</section>

<section class="py-5 bg-custom-accent">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="p-4">
                    <i class="bi bi-award text-custom-primary" style="font-size: 3rem"></i>
                    <h3 class="mt-3">Premium Quality</h3>
                    <p>
                        We use only the freshest ingredients sourced from local farmers
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="p-4">
                    <i class="bi bi-people text-custom-primary" style="font-size: 3rem"></i>
                    <h3 class="mt-3">Expert Chefs</h3>
                    <p>Our international team brings global flavors to your plate</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4">
                    <i class="bi bi-truck text-custom-primary" style="font-size: 3rem"></i>
                    <h3 class="mt-3">Fast Delivery</h3>
                    <p>Hot meals delivered to your doorstep within 30 minutes</p>
                </div>
            </div>
        </div>
    </div>
</section>
EOT;

    protected $aboutSection = <<<EOT
<section class="py-5" id="about">
    <div class="container">
        <h2 class="text-center section-title">Our Story</h2>
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="https://placehold.co/600x600?text=Our+Story" class="img-fluid about-img" alt="Restaurant Interior">
            </div>
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <h3 class="mb-4 text-custom-primary">
                        Passion for Flavor Since 2010
                    </h3>
                    <p class="lead mb-4">
                        Flavor Fiesta began with a simple mission: to create
                        extraordinary culinary experiences that celebrate global flavors
                        and local ingredients.
                    </p>
                    <p>
                        Founded by Chef Maria Rodriguez, our restaurant brings together
                        traditional cooking techniques and innovative flavor
                        combinations. Every dish tells a story of culture, tradition,
                        and passion.
                    </p>
                    <p>
                        We believe in sustainable practices and work closely with local
                        farmers to source the freshest ingredients. Our menu changes
                        with the seasons to offer you the best flavors year-round.
                    </p>
                    <div class="row mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-custom-primary me-2"></i>
                                <span>Farm-to-Table Ingredients</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-custom-primary me-2"></i>
                                <span>Award-Winning Chefs</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-custom-primary me-2"></i>
                                <span>Sustainable Practices</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill text-custom-primary me-2"></i>
                                <span>Seasonal Menu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

EOT;

    protected $chefSection = <<<EOT
<section class="py-5 bg-custom-primary">
    <div class="container">
        <h2 class="text-center section-title text-white">
            Meet Our Culinary Team
        </h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 border-0">
                    <img src="https://placehold.co/200x200?text=Our+Story" class="card-img-top" alt="Chef Maria">
                    <div class="card-body text-center">
                        <h4 class="card-title">Chef Maria Rodriguez</h4>
                        <p class="text-muted">Executive Chef</p>
                        <p class="card-text">
                            With over 20 years of culinary experience, Chef Maria brings
                            her passion for global flavors and innovative techniques to
                            every dish.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 border-0">
                    <img src="https://placehold.co/200x200?text=Our+Story" class="card-img-top" alt="Chef James">
                    <div class="card-body text-center">
                        <h4 class="card-title">Chef James Chen</h4>
                        <p class="text-muted">Head Chef</p>
                        <p class="card-text">
                            Specializing in Asian fusion cuisine, Chef James creates
                            unique flavor combinations that delight and surprise.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 border-0">
                    <img src="https://placehold.co/200x200?text=Our+Story" class="card-img-top" alt="Chef Sophia">
                    <div class="card-body text-center">
                        <h4 class="card-title">Chef Sophia Laurent</h4>
                        <p class="text-muted">Pastry Chef</p>
                        <p class="card-text">
                            A master of sweet creations, Chef Sophia crafts desserts that
                            are not only delicious but also works of art.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
EOT;

    protected $gallerySection = <<<EOT
<section class="py-5" id="gallery">
    <div class="container">
        <h2 class="text-center section-title">Food Gallery</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <img src="https://placehold.co/800x800?text=Our+Story" class="img-fluid rounded menu-card" alt="Food Gallery">
            </div>
            <div class="col-md-4">
                <img src="https://placehold.co/800x800?text=Our+Story" class="img-fluid rounded menu-card" alt="Food Gallery">
            </div>
            <div class="col-md-4">
                <img src="https://placehold.co/800x800?text=Our+Story" class="img-fluid rounded menu-card" alt="Food Gallery">
            </div>
            <div class="col-md-4">
                <img src="https://placehold.co/800x800?text=Our+Story" class="img-fluid rounded menu-card" alt="Food Gallery">
            </div>
            <div class="col-md-4">
                <img src="https://placehold.co/800x800?text=Our+Story" class="img-fluid rounded menu-card" alt="Food Gallery">
            </div>
            <div class="col-md-4">
                <img src="https://placehold.co/800x800?text=Our+Story" class="img-fluid rounded menu-card" alt="Food Gallery">
            </div>
        </div>
    </div>
</section>
EOT;

    protected $testimonialSection = <<<EOT
<section class="py-5 bg-custom-accent">
    <div class="container">
        <h2 class="text-center section-title">What Our Customers Say</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 border-0">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text">
                            "The food was absolutely amazing! The flavors were perfectly
                            balanced and the presentation was beautiful. Will definitely
                            be coming back!"
                        </p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://placehold.co/50x50?text=Our+Story" class="rounded-circle me-3" alt="Customer">
                            <div>
                                <h5 class="mb-0">Sarah Johnson</h5>
                                <small class="text-muted">Food Blogger</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 border-0">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text">
                            "I love how they offer different portion sizes. The half
                            portion is perfect for lunch, and the family size is great
                            when we bring friends. Everything is always fresh and
                            delicious."
                        </p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://placehold.co/50x50?text=Our+Story" class="rounded-circle me-3" alt="Customer">
                            <div>
                                <h5 class="mb-0">Michael Taylor</h5>
                                <small class="text-muted">Regular Customer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card menu-card h-100 border-0">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                            <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <p class="card-text">
                            "The atmosphere is wonderful and the staff is so attentive.
                            Their seasonal menu keeps me coming back to try new dishes.
                            The Surf &amp; Turf special was phenomenal!"
                        </p>
                        <div class="d-flex align-items-center mt-3">
                            <img src="https://placehold.co/50x50?text=Our+Story" class="rounded-circle me-3" alt="Customer">
                            <div>
                                <h5 class="mb-0">Jessica Lee</h5>
                                <small class="text-muted">Food Critic</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
EOT;

    protected $contactSection = <<<EOT
<section class="py-5" id="contact">
    <div class="container">
        <h2 class="text-center section-title">Contact &amp; Reservations</h2>
        <div class="row">
            <div class="col-lg-6  mb-lg-0">
                <div class="card menu-card h-100 border-0">
                    <img src="https://placehold.co/200x200?text=Our+Location" class="img-fluid rounded" alt="Map">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card menu-card h-100 border-0">
                    <div class="card-body">
                        <div class="bg-light p-4 rounded mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-geo-alt text-custom-primary me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-0">Address</h5>
                                    <p class="mb-0">
                                        123 Culinary Boulevard, Gourmet City, GC 12345
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-telephone text-custom-primary me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-0">Phone</h5>
                                    <p class="mb-0">(123) 456-7890</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="bi bi-envelope text-custom-primary me-3 fs-4"></i>
                                <div>
                                    <h5 class="mb-0">Email</h5>
                                    <p class="mb-0">info@flavorfiesta.com</p>
                                </div>
                            </div>
                        </div>
                        <h4 class="mb-3">Opening Hours</h4>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td class="fw-bold">Monday - Thursday:</td>
                                    <td>11:00 AM - 10:00 PM</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Friday - Saturday:</td>
                                    <td>11:00 AM - 11:00 PM</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Sunday:</td>
                                    <td>10:00 AM - 9:00 PM</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
EOT;

    protected $footerSection = <<<EOT
<footer class="footer">
<div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h4 class="mb-4">Flavor Fiesta</h4>
                <p>
                    Experience a culinary journey through international flavors in a
                    welcoming atmosphere. We're passionate about creating memorable
                    dining experiences.
                </p>
                <div class="mt-4">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-yelp"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h4 class="mb-4">Quick Links</h4>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#home" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Home</a>
                    </li>
                    <li class="mb-2">
                        <a href="#menu" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Menu</a>
                    </li>
                    <li class="mb-2">
                        <a href="#about" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2"></i>About Us</a>
                    </li>
                    <li class="mb-2">
                        <a href="#gallery" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Gallery</a>
                    </li>
                    <li class="mb-2">
                        <a href="#contact" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-2"></i>Contact</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h4 class="mb-4">Newsletter</h4>
                <p>
                    Subscribe to our newsletter for special offers and updates on new
                    menu items.
                </p>
                <form class="mt-4">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Your Email" aria-label="Email" aria-describedby="subscribe-btn">
                        <button class="btn btn-primary" type="button" id="subscribe-btn">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <hr class="mt-4 mb-4 bg-light">
        <div class="text-center">
            <p class="mb-0">© 2025 Flavor Fiesta. All rights reserved.</p>
        </div>
    </div>
</footer>
EOT;

    protected $menus = <<<EOT
<section id="menus"></section>
EOT;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('web_page_sections')->insert([
            [
                'name' => 'Home',
                'section_id' => 'home',
                'content' => $this->homeSection,
                'in_navbar' => true,
                'is_active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Menu',
                'section_id' => 'menu',
                'content' => $this->menus,
                'in_navbar' => true,
                'is_active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'About Us',
                'section_id' => 'about',
                'content' => $this->aboutSection,
                'in_navbar' => true,
                'is_active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chef',
                'section_id' => 'chef',
                'content' => $this->chefSection,
                'in_navbar' => false,
                'is_active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gallery',
                'section_id' => 'gallery',
                'content' => $this->gallerySection,
                'in_navbar' => true,
                'is_active' => true,
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Testimonial',
                'section_id' => 'testimonial',
                'content' => $this->testimonialSection,
                'in_navbar' => false,
                'is_active' => true,
                'order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Contact',
                'section_id' => 'contact',
                'content' => $this->contactSection,
                'in_navbar' => true,
                'is_active' => true,
                'order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Footer',
                'section_id' => 'footer',
                'content' => $this->footerSection,
                'in_navbar' => false,
                'is_active' => true,
                'order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
