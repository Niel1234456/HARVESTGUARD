<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Help Page</title>
    <link rel="stylesheet" href="{{ asset('assets/css/help.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">

</head>
<style>
    .lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    text-align: center;
    z-index: 9999;
    overflow-y: auto; /* Enable vertical scrolling */
}
    .lightbox img {
    margin-top: 30%;
    max-width: 150%;
    max-height: 150%;
    border-radius: 10px;
    transform: scale(0.85);
    opacity: 0;
    animation: zoomIn 0.3s ease-in-out forwards;
}
    .home__img {
    
    max-width: 400px;
    height: 500px;
    }
    h2 {
    font-size: 2.5rem;
    color: #ffffff;
    font-weight: 700;
    text-align: center;
    margin-left: 1.5%;
    padding: 10px;
    background: linear-gradient(to right, #4CAF50, #2E8B57);
    color: #fff;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-shadow: 1px 1px 2px rgb(250, 248, 248);
    transition: all 0.3s ease;
    width: 97.5%;

}

h2:hover {
    color: #d3de00;
    background: linear-gradient(to right, #2E8B57, #4CAF50);
    transform: scale(1.05);
    cursor: pointer;
}
@media screen and (max-width: 768px) {

    h2 {
        font-size: 20px;
    }
}
</style>
</head>
@include('admin.navbar')
@include('admin.header')
<body>
<div class="user-manual">
    <h2>Admin User Manual</h2>


        @for ($i = 11; $i <= 22; $i++)
            <a href="#lightbox{{$i}}">
                <img src="{{ asset('assets/img/'.$i.'.png') }}" alt="User Manual Guide" class="home__img">
            </a>

            <!-- Lightbox for Each Image -->
            <div id="lightbox{{$i}}" class="lightbox">
                <a href="#" class="close">&times;</a>
                <img src="{{ asset('assets/img/'.$i.'.png') }}" alt="User Manual Guide">
            </div>
        @endfor

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(){
        $('#hamburger-icon').on('click', function(){
            $('#sidebar').toggleClass('active');
        });
    });
    $(document).ready(function(){
        // Close lightbox when clicking outside the image
        $('.lightbox').on('click', function(event) {
            if ($(event.target).is('.lightbox')) {
                window.location.href = "#"; // Redirect to remove the lightbox view
            }
        });
    });
</script>

</body>
</html>
