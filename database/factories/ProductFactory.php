<?php

namespace Database\Factories;

use App\Models\ProductType;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Array of products categorized by type
     *
     * @var array
     */
    protected $categorizedProducts = [
        'Meet' => [
            'Chicken', 'Beef', 'Lamb', 'Turkey', 'Sausage', 'Bacon', 'Ham', 'Ground Beef',
            'Steak', 'Veal', 'Duck', 'Venison', 'Bison', 'Pork Chops', 'Ribs', 'Pork Tenderloin',
            'Meatballs', 'Beef Brisket', 'Chicken Wings', 'Chicken Breast', 'Chicken Thighs'
        ],
        'Spices' => [
            'Salt', 'Pepper', 'Cinnamon', 'Oregano', 'Basil', 'Thyme', 'Rosemary', 'Paprika',
            'Cumin', 'Turmeric', 'Ginger', 'Nutmeg', 'Vanilla Extract', 'Chili Powder', 'Cardamom',
            'Cloves', 'Coriander', 'Bay Leaves', 'Saffron', 'Allspice', 'Cayenne Pepper', 'Sage'
        ],
        'Oils' => [
            'Olive Oil', 'Vegetable Oil', 'Coconut Oil', 'Sesame Oil', 'Canola Oil', 'Peanut Oil',
            'Avocado Oil', 'Sunflower Oil', 'Corn Oil', 'Grapeseed Oil', 'Flaxseed Oil', 'Walnut Oil',
            'Truffle Oil', 'Chili Oil', 'Almond Oil'
        ],
        'Dairy' => [
            'Milk', 'Cheese', 'Yogurt', 'Butter', 'Eggs', 'Cream', 'Sour Cream', 'Cottage Cheese',
            'Cream Cheese', 'Mozzarella', 'Cheddar', 'Swiss Cheese', 'Parmesan', 'Feta', 'Gouda',
            'Blue Cheese', 'Buttermilk', 'Whipped Cream', 'Half and Half', 'Ricotta'
        ],
        'Grains' => [
            'Rice', 'Pasta', 'Flour', 'Oats', 'Barley', 'Quinoa', 'Couscous', 'Cornmeal',
            'Bread Crumbs', 'Wheat Germ', 'Bran', 'Brown Rice', 'Wild Rice', 'Arborio Rice',
            'Farro', 'Bulgur', 'Millet', 'Rye', 'Polenta', 'Buckwheat', 'Cereals'
        ],
        'Fruits' => [
            'Apple', 'Orange', 'Banana', 'Strawberry', 'Blueberry', 'Grape', 'Watermelon', 'Mango',
            'Pineapple', 'Peach', 'Pear', 'Plum', 'Cherry', 'Kiwi', 'Lemon', 'Lime', 'Avocado',
            'Coconut', 'Pomegranate', 'Grapefruit', 'Cantaloupe', 'Raspberry', 'Blackberry',
            'Dragon Fruit', 'Apricot', 'Fig'
        ],
        'Vegetables' => [
            'Potato', 'Tomato', 'Carrot', 'Broccoli', 'Spinach', 'Onion', 'Garlic', 'Cucumber',
            'Bell Pepper', 'Zucchini', 'Eggplant', 'Cauliflower', 'Lettuce', 'Cabbage', 'Asparagus',
            'Corn', 'Radish', 'Peas', 'Pumpkin', 'Sweet Potato', 'Kale', 'Leek', 'Celery', 'Artichoke',
            'Brussels Sprouts', 'Mushrooms', 'Beets'
        ],
        'Seafood' => [
            'Fish', 'Shrimp', 'Lobster', 'Crab', 'Salmon', 'Tuna', 'Tilapia', 'Cod', 'Halibut',
            'Scallops', 'Mussels', 'Clams', 'Squid', 'Octopus', 'Anchovies', 'Sardines', 'Trout',
            'Catfish', 'Sea Bass', 'Oysters', 'Calamari', 'Crayfish', 'Eel'
        ],
        'Bakery' => [
            'Bread', 'Bagels', 'Tortillas', 'Pita Bread', 'Crackers', 'Muffins', 'Donuts', 'Croissants',
            'Cookies', 'Cake', 'Pie', 'Brownies', 'Cupcakes', 'Rolls', 'Baguette', 'Brioche',
            'Sourdough Bread', 'Ciabatta', 'Focaccia', 'Biscuits', 'Danish Pastry'
        ],
        'Beverages' => [
            'Coffee', 'Tea', 'Juice', 'Water', 'Soda', 'Sparkling Water', 'Iced Tea', 'Lemonade',
            'Energy Drink', 'Sports Drink', 'Beer', 'Wine', 'Whiskey', 'Vodka', 'Tequila', 'Rum',
            'Gin', 'Smoothie', 'Kombucha', 'Hot Chocolate', 'Cider', 'Cocktail Mixers', 'Milk Alternatives'
        ]
    ];

    /**
     * Array of local food image paths
     *
     * @var array
     */
    protected $foodImages = [
        'uploads/products/thumbnail/products-1.png',
        'uploads/products/thumbnail/products-2.png',
        'uploads/products/thumbnail/products-3.png',
        'uploads/products/thumbnail/products-4.png',
        'uploads/products/thumbnail/products-5.png',
        'uploads/products/thumbnail/products-6.png',
        'uploads/products/thumbnail/products-7.jpg',
        'uploads/products/thumbnail/products-8.png',
        'uploads/products/thumbnail/products-9.png',
        'uploads/products/thumbnail/products-10.png',
        'uploads/products/thumbnail/products-11.png',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random product type from the database
        $productType = ProductType::query()->inRandomOrder()->first();
        $productTypeName = $productType->product_type;

        // Get a product name that matches the product type
        $productName = $this->getProductForType($productTypeName);

        // Get a random image from the local images array
        $imagePath = $this->faker->randomElement($this->foodImages);

        return [
            'product_name' => $productName,
            'thumbnail' => $imagePath,
            'product_type_id' => $productType->id,
            'unit_id' => function () {
                return Unit::query()->inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                return User::query()->inRandomOrder()->first()->id;
            }
        ];
    }

    /**
     * Get a product name that matches the given product type
     *
     * @param string $typeName
     * @return string
     */
    protected function getProductForType(string $typeName): string
    {
        // If the type exists in our categories, get a random product from that category
        if (array_key_exists($typeName, $this->categorizedProducts)) {
            $products = $this->categorizedProducts[$typeName];
            return $this->faker->randomElement($products);
        }

        // If the type doesn't match our categories, get a random product from all categories
        $allProducts = array_merge(...array_values($this->categorizedProducts));
        return $this->faker->randomElement($allProducts);
    }
}
