<?php
// insert_containers.php
include 'db_connection.php';

// Sample data for photography containers
$containers = array(
    array(
        'title' => 'Fashion photography',
        'description' => 'Fashion photography is a genre of photography that portrays clothing and other fashion items. This sometimes includes haute couture garments. It typically consists of a fashion photographer taking pictures of a dressed model in a photographic studio or an outside setting',
        'photo_url' => 'image/hbz040123welllucierox-011-641c73e85593b.png',
        'price' => 1500.00,
    ),
    array(
        'title' =>'Fine-art photography',
        'description' => 'Fine-art photography is photography created in line with the vision of the photographer as artist, using photography as a medium for creative expression.',
        'photo_url' => 'image/fineart.webp',
        'price' => '3000',

    ),
    array(
        'title' =>'Landscape photography',
        'description' => 'Landscape photography shows the spaces within the world, sometimes vast and unending, but other times microscopic',
        'photo_url' => 'image/land.jpg',
        'price' => '2000',

    ),
    array(
        'title' =>'Architectural photography',
        'description' => 'Architectural photography is the sub genre of the photography discipline where the primary emphasis is made to capturing photographs of buildings and similar architectural structures',
        'photo_url' => 'image/arc.webp',
        'price' => '5000',

    ),
    array(
        'title' =>'Candid photography',
        'description' => 'Candid photography is photography captured without creating a posed appearance. This style is also called street photography, spontaneous photography or snap shooting',
        'photo_url' => 'image/candid.png',
        'price' => '1000',

    ),
    array(
        'title' =>'Long-exposure photography',
        'description' => 'Long-exposure, time-exposure, or slow-shutter photography involves using a long-duration shutter speed to sharply capture the stationary elements of images while blurring, smearing, or obscuring the moving elements.',
        'photo_url' => 'image/long.jpeg',
        'price' => '3000',

    ),
    array(
        'title' =>'Astrophotography',
        'description' => 'Astrophotography, also known as astronomical imaging, is the photography or imaging of astronomical objects, celestial events, or areas of the night sky',
        'photo_url' => 'image/astro.jpeg',
        'price' => '6000',

    ),
    array(
        'title' =>'Macro photography',
        'description' => 'Macro photography is extreme close-up photography, usually of very small subjects and living organisms like insects, in which the size of the subject in the photograph is greater than life siz',
        'photo_url' => 'image/micro.jpg',
        'price' => '2000',

    ),
    array(
        'title' =>'Wildlife photography',
        'description' => 'Wildlife photography is a genre of photography concerned with documenting various forms of wildlife in their natural habitat. As well as requiring photography skills, wildlife photographers may need field craft skills.',
        'photo_url' => 'image/wild.WEBP',
        'price' => '3000',

    ),
    array(
        'title' =>'Aerial Photography',
        'description' => 'Aerial photography involves images captured from a bird’s-eye view, usually from great heights.',
        'photo_url' => 'image/aerial.webp',
        'price' => '10000',

    ),
    array(
        'title' =>'Aesthetic Photography',
        'description' => 'At the most basic level, if a photograph is aesthetic, it is simply pleasing to the eye, positive in appearance, eye-catching, or able to trigger an emotional response in a viewer.',
        'photo_url' => 'image/aest.jpg',
        'price' => '4050',

    ), array(
        'title' =>'Automotive Photography',
        'description' => 'Being paid to take photos of fancy cars must be every schoolboy’s dream, but there’s a lot more to automotive photography than meets the eye… and it often doesn’t involve the kinds of ‘dream cars’ we imagine.',
        'photo_url' => 'image/auto.jpg',
        'price' => '4300',

    ), array(
        'title' =>'Wedding photography',
        'description' => 'Wedding photography is a specialty in photography that is primarily focused on the photography of events and activities relating to weddings.',
        'photo_url' => 'image/wedding/img40.jpg',
        'price' => '5000',

    ), array(
        'title' =>'Cyberpunk Photography',
        'description' => 'The cyberpunk aesthetic is a subculture represented in artwork, photography, and filmmaking.',
        'photo_url' => 'image/cyber.jpg',
        'price' => '4399',

    ), array(
        'title' =>'Dance Photography',
        'description' => 'Similar to the concert genre, dance photography involves photographing performers on stage. This could be for classical ballet, modern dance, or any other style.',
        'photo_url' => 'image/dance.jpg',
        'price' => '3999',

    ), array(
        'title' =>'Event Photography',
        'description' => 'Event photography is a more generalised genre where a photographer is hired to capture special events. These could include family shoots, engagements, birthday parties, weddings, festivals, or corporate events and promotions.',
        'photo_url' => 'image/event.webp',
        'price' => '2999',

    ), array(
        'title' =>'Fitness Photography',
        'description' => 'Gym use has exploded in recent times, and so has the desire to become social media’s next big fitness model. Photos are obviously a big part of promotion in this industry, so honing your lighting, posing and shooting skills can help you succeed as a fitness photographer.',
        'photo_url' => 'image/fitness.jpg',
        'price' => '3000',

    ),
    // Add more containers here as needed
);

// Insert each container into the database
foreach ($containers as $container) {
    $title = $container['title'];
    $description = $container['description'];
    $photo_url = $container['photo_url'];
    $price = $container['price'];

    $sql = "INSERT INTO photography_containers (title, description, photo_url, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $title, $description, $photo_url, $price);
    $stmt->execute();
}

$conn->close();

echo "Containers inserted successfully.";
?>
