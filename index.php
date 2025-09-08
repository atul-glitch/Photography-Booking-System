<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photography Booking</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    
</head>
<body>
    <header>
        <nav>
            <div class="logo">AAR<span>Photography</span></div>
            <div class="menu">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#gallery">Albums</a>
                
            </div>
            <div class="icon">
                <i class="fa-solid fa-magnifying-glass"></i>
                <a href="login.php">Login</a>
            </div>
        </nav>
        <section class="h-text" id="home">
            <i class="fa-solid fa-camera" id="camera"></i>
            <h1>Capturing the moments that captivate your heart.</h1>
            <div class="icon">
            <a class="btn " href="homepage.php">Book Now</a>
            </div>
        </section>
    </header>
    <section class="filter-gallery" id="gallery">
        <div class="portfolio-menu">
            <ul>
                <li class="active" data-filter="*">All</li>
                <li  data-filter=".model">Model</li>
                <li  data-filter=".wildlife">Wildlife</li>
                <li  data-filter=".architectural">Architectural</li>
             </ul>
        </div>
        <div class="portfolio-item">
              <div class="item model">
                <img src="./image/model/model-1.jpeg" width="100" height="100">
                <img src="./image/model/model-2.jpeg" width="100" height="100">
                <img src="./image/model/model-3.jpeg" width="100" height="100">
             </div>
             <div class="item wildlife">
                <img src="./image/wildlife/waterfall.jpg" width="100" height="100">
                <img src="./image/wildlife/wild-1.jpeg" width="100" height="100">
                <img src="./image/wildlife/wild3.jpeg" width="100" height="100">
             </div>
             <div class="item architectural">
                <img src="./image/architec/arc1.jpg" width="100" height="100">
                <img src="./image/architec/arc2.jpeg" width="100" height="100">
                <img src="./image/architec/arc3.jpeg" width="100" height="100">

             </div>
        </div>
    </section>

    <section class="member" id="about">
        <div class="member-info">
            <h1>Our <span>Informations</span></h1>
            <p>What our member says</p>
        </div>
        <div class="member-card">
            <img src="./image/member/20210126181654_IMG_0048 (2).jpg" width="100" height="100"><img src="./image/member/WhatsApp Image 2023-07-28 at 21.07.56.jpeg" width="100" height="100"><img src="./image/member/WhatsApp Image 2023-07-28 at 20.58.55.jpeg" width="100" height="100">
            <p>"That frame of mind that you need to make fine pictures of a very wonderful subject; you cannot do it by not being lost yourself."</p>
            <h2>Ritik , Alice and Atul </h2>
            <img src="./image/icon/icon1.png" width="100">
            <img src="./image/icon/icon2.png" width="100">
            <img src="./image/icon/icon3.png" width="100">
            <img src="./image/icon/icon4.png" width="100">
        </div>
        <div class="m-images">
            <img src="./image/Wedding/img36.jpg" >
            <img src="./image/Wedding/img16.jpg" >
            <img src="./image/Wedding/img24.jpg" >
            <img src="./image/Wedding/img4.jpg" >
            <img src="./image/Wedding/img40.jpg" >
            <img src="./image/Wedding/img48.jpg" >
            <img src="./image/Wedding/img12.jpg" >
            <img src="./image/Wedding/img20.jpg" >
            <img src="./image/Wedding/img52.jpg" >
            <img src="./image/wedd pic/WhatsApp Image 2023-07-08 at 19.21.56.jpeg" >

     </div>
     
    </section>
    

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    

    <script type="text/javascript">
    $('.portfolio-item').isotope({
        // options
        itemSelector: '.item',
        layoutMode: 'fitRows'
      });
      $('.portfolio-menu ul li').click(function(){
        $('.portfolio-menu ul li').removeClass('active');
        $(this).addClass('active');
    

        
			var selector = $(this).attr('data-filter');
		$('.portfolio-item').isotope({
			filter:selector
		});
		return false;
    });
      
      </script>
      
</body>

    <p style="text-align: center;">©
        <script>document.write(new Date().getFullYear())</script> AAR PHOTOGRAPHY
    </p>
    
</footer>
</html>