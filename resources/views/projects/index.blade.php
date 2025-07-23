<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--=============== FAVICON ===============-->
        <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

        <!--=============== REMIX ICONS ===============-->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

        <!--=============== CSS ===============-->
        <link rel="stylesheet" href="assets/css/style.css">

        <title>HarvestGuard</title>
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
        <style>
            .footer {
    background-color: #1e1e1e;
    color: #fff;
    padding: 50px 0;
    text-align: center;
}

.footer__container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    text-align: left;
    justify-content: center;
}

.footer__logo {
    display: flex;
    align-items: center;
    font-size: 22px;
    font-weight: bold;
    color:rgb(255, 255, 255);

}

.footer__logo img {
    width: 45px;
    margin-right: 10px;
    color:rgb(255, 255, 255);

}

.footer__tagline {
    font-size: 14px;
    opacity: 0.8;
    margin-top: 8px;
    color:rgb(255, 255, 255);

}

.footer__links h4,
.footer__contact h4 {
    font-size: 18px;
    margin-bottom: 12px;

}

.footer__links ul {
    list-style: none;
    padding: 0;
}

.footer__links li {
    margin-bottom: 8px;
    color:rgb(255, 255, 255);

}

.footer__links a {
    color:rgb(255, 255, 255);
    text-decoration: none;
    transition: 0.3s;
    
}

.footer__links a:hover {
    color: #f1c40f;
}

.footer__contact p {
    font-size: 14px;
    margin: 5px 0;
    color:rgb(255, 255, 255);

}

.footer__contact i {
    color: #f1c40f;
    margin-right: 5px;

}
/* General Contact Section Styling */
.contact__container {
    display: grid;
    gap: 2rem;
    padding: 3rem;
    background: #f9f9f9; /* Light gray background */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Box that contains all information */
.contact__box {
    max-width: 600px;
    margin: 0 auto;
}

/* Title Styling */
.section__title {
    font-size: 1.8rem;
    font-weight: bold;
    color: #2c3e50; /* Dark Blue Gray */
    margin-bottom: 1.5rem;
    line-height: 1.4;
}

/* Contact Info Styling */
.contact__data {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Individual Contact Item */
.contact__information {
    background: #fff;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease-in-out;
}

/* Hover Effect */
.contact__information:hover {
    transform: translateY(-5px);
}

/* Subtitle */
.contact__subtitle {
    font-size: 1.2rem;
    font-weight: 600;
    color: #34495e; /* Dark Gray */
}

/* Description */
.contact__description {
    font-size: 1rem;
    color: #555;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Icons */
.contact__icon {
    font-size: 1.5rem;
    color: #3498db; /* Blue */
}




        </style>
    <body>
        <!--==================== HEADER ====================-->
        <header class="header" id="header">
            <nav class="nav container">
            <a href="/" class="nav__logo">
                <img src="assets/img/logo.png" alt="HarvestGuard Logo" class="nav__logo-image" />
                <span class="nav__logo-text">HarvestGuard</span>
            </a>

                <div class="nav__menu" id="nav-menu">
                    <ul class="nav__list">
                    <ul class="nav__list">
                <li class="nav__item">
                    <a href="#home" class="nav__link">Home</a>
                </li>
                <li class="nav__item">
                    <a href="#about" class="nav__link">About</a>
                </li>
                <li class="nav__item">
                    <a href="#products" class="nav__link">Service</a>
                </li>
                <li class="nav__item">
                    <a href="#faqs" class="nav__link">FAQs</a>
                </li>
                <li class="nav__item">
                    <a href="#contact" class="nav__link">Contact</a>
                </li>

                        <nav>
    <ul class="nav__list">
        <li class="nav__item">
            <a href="#" id="login-link" class="nav__link">Login as</a>
            <ul class="role-options" id="role-options">
                <li><a href="{{ route('farmer.login') }}">Farmer</a></li>
                <li><a href="{{ route('admin.login') }}">Administrator</a></li>
            </ul>
        </li>
    </ul>
</nav>

             <script>
                function updateAction() {
                var role = document.getElementById('role').value;
                var loginLink = document.getElementById('login-link');
                if (role === 'farmer') {
                loginLink.href = "{{ route('farmer.login') }}";
                } else if (role === 'admin') {
                loginLink.href = "{{ route('admin.login') }}";
                }
                }// Initialize the login link based on the default selection
                updateAction();
</script>

                    </ul>

                    <div class="nav__close" id="nav-close">
                        <i class="ri-close-line"></i>
                    </div>
                </div>

                <div class="nav__btns">
                    <!-- Theme change button -->
                    <i class="ri-moon-line change-theme" id="theme-button"></i>

                    <div class="nav__toggle" id="nav-toggle">
                        <i class="ri-menu-line"></i>
                    </div>
                </div>
            </nav>
        </header>
        <main class="main">
    <!--==================== HOME ====================-->
    <section class="home" id="home" style="position: relative; background: url('/assets/img/daily-tribune_import_wp-content_uploads_2023_12_ELNINO_KING_12112023-7-scaled.avif'); background-size: cover;">

  <div class="home__container container grid">
    <img src="assets/img/Green and White Flat Illustrative Feeding Plant Agriculture Logo (1).png" alt="" class="home__img">
    <div class="home__data">
      <h1 class="home__title">
       <b>HarvestGuard</b>
      </h1>
      <h2 class="home__description">
      <b>Protecting your crops, And Providing your needs.</b>
            </h2>
            <p>This system is design for the City of 
                <br>Agriculture office of Carmona City, Cavite</p><br>
            
      <a href="#about" class="button button--flex">
        Explore <i class="ri-arrow-right-down-line button__icon"></i>
      </a>
    </div>

    <div class="home__social">
      <span class="home__social-follow">Follow Us</span>
      <div class="home__social-links">
        <a href="https://www.youtube.com/@HarvestGuard" target="_blank" class="home__social-link">
          <i class="ri-youtube-fill"></i>
        </a>
        <a href="https://www.facebook.com/profile.php?id=61565542956650" target="_blank" class="home__social-link">
          <i class="ri-facebook-fill"></i>
        </a>
        <a href="https://twitter.com/" target="_blank" class="home__social-link">
          <i class="ri-twitter-fill"></i>
        </a>
      </div>
    </div>
  </div>
</section>
</section>


            <!--==================== ABOUT ====================-->
            <section class="about section container" id="about">
                <div class="about__container grid">
                    <img src="assets/img/giphy.gif" alt="" class="about__img">

                    <div class="about__data">
                        <h2 class="section__title about__title">
                         HarvestGuard Web App
                        </h2>

                        <p class="about__description">
                        Ang <strong>HARVESTGUARD ay isang Decision Support System (DSS) </strong> na idinisenyo upang makatulong sa mga magsasaka sa pamamagitan:
                        </p>

                        <div class="about__details">
                            <p1 class="about__details-description">
                                <i class="ri-checkbox-fill about__details-icon"></i>
                                pagkilala ng posibleng sakit gamit ang image recognition technology, at pagbibigay ng detalyadong impormasyon tungkol sa mga solusyon sa problema.
                            </p1>
                            <p1 class="about__details-description">
                                <i class="ri-checkbox-fill about__details-icon"></i>
                                Pinapadali pamamahala ng imbentaryo, tulad ng abono, buto, at kagamitan, at nagbibigay-daan sa madaling pag-request ng suplay o tulong mula sa mga administrador.
                            </p1>
                            <p1 class="about__details-description">
                                <i class="ri-checkbox-fill about__details-icon"></i>
                               Gumagamit ng Descriptive Analytics upang mag lahad ng mahahalagang datos at tumulong sa agarang paggawa ng Desisyon
                            </p1>
                            <p1 class="about__details-description">
                                <i class="ri-checkbox-fill about__details-icon"></i>
                               Mas pinadadali, at ginagawang episyente ang pangangalaga sa mga pananim, na naglalayong mapabuti ang ani at kabuuang produksyon ng sakahan.
                                </p1>
                        </div>
                    </div>
                </div>
            </section>

            <!--==================== STEPS ====================-->
            <section class="steps section container">
                <div class="steps__bg">
                    <h2 class="section__title-center steps__title">
                        Municipal Agriculture Office <br> 
                        Mission And Vision 
                    </h2>

                    <div class="steps__container grid">
                        <div class="steps__card">
                            <center><div class="steps__card-number">MISSION</div></center>
                            <p class="steps__card-description">
                            Promote Economic growth and development in agriculture in the Municipality Increasing farmers income and improve quality of the life beyond bare subsistence level towards the attainment of food security in society characterized by justine and equity                            </p>
                        </div>

                        <div class="steps__card">
                            <center><div class="steps__card-number">VISION</div></center>
                            <p class="steps__card-description">
                            Unified farmers to increase income thereby contributing to the achievement of the municipal goals alleviating poverty, generating productive opportunities, fostering social justice and equity, and promoting sustainable economic growth</p>
                        </div>
                    </div>
                </div>
            </section>

            <!--==================== PRODUCTS ====================-->
            <section class="product section container" id="products">
                <h2 class="section__title-center">
                    MGA SERBISYONG MAGAGAMIT SA <br>HarvestGuard Web App
                </h2>

                <p class="product__description">
                   <center>Narito ang mga serbisyong maaaring magamit ng mga Farmers at Administrators sa HarvestGuard Web App</center><br>
                </p>

                <div class="services__container grid">
        <!-- Service 1 -->
        <div class="service__card">
            
        <img src="assets/img/a.png" alt="Service 1" class="service__img">
            <h3 class="service__title">Disease Detection <br> <br></h3>
        </div>
        <!-- Service 2 -->
        <div class="service__card">
        <img src="assets/img/b.png" alt="Service 2" class="service__img">
            <h3 class="service__title">Realtime Weather forecast<br><br></h3>
        </div>
        <!-- Service 3 -->
        <div class="service__card">
        <img src="assets/img/c.png" alt="Service 3" class="service__img">
            <h3 class="service__title">Decision Support System<br><br></h3>
        </div>
        <!-- Service 4 -->
        <div class="service__card">
        <img src="assets/img/d.png" alt="Service 4" class="service__img">
            <h3 class="service__title">Resource Monitoring <br><br></h3>
        </div>
    </div> <br><br>

            <!--==================== QUESTIONS ====================-->
            <section class="questions section" id="faqs">
                <h2 class="section__title-center questions__title container">
                   <center>Mga Kadalasang katanungan sa HarvestGuard </center> 
                </h2>

                <div class="questions__container container grid">
                    <div class="questions__group">
                        <div class="questions__item">
                            <header class="questions__header">
                                <i class="ri-add-line questions__icon"></i>
                                <h3 class="questions__item-title">
                                Ano ba ang Maitutulong ng <strong>HARVESTGUARD</strong> sa aming mga Pananim?
                                </h3>
                            </header>

                            <div class="questions__content">
                                <p class="questions__description">
                                    Ang <strong>HARVESTGUARD</strong> ay makatutulong sa mga pananim ng mga magsasaka sa pamamagitan ng pagbibigay ng real-time na impormasyon tungkol sa kanilang kalagayan, pag-detect ng mga posibleng sakit gamit ang image recognition technology, at pagbibigay ng detalyadong solusyon para sa agarang aksyon. Pinapadali nito ang pamamahala ng imbentaryo ng suplay at kagamitan, na tumutulong sa mga magsasaka upang matiyak na laging sapat ang kanilang pangangailangan. Bukod dito, nag-aalok ang sistema ng analytics na nagbibigay ng mahalagang insights sa mga trend ng ani at kondisyon ng lupa, na nakatutulong sa paggawa ng mas mahusay na desisyon para sa pagpapabuti ng ani at pagpapanatili ng kalusugan ng pananim.</p>
                                
                            </div>
                        </div>

                        <div class="questions__item">
                            <header class="questions__header">
                                <i class="ri-add-line questions__icon"></i>
                                <h3 class="questions__item-title">
                                   Sumusunod ba ang <strong>HARVESTGUARD</strong> sa palatuntunin ng batas na <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>?
                                </h3>
                            </header>

                            <div class="questions__content">
                                <p class="questions__description">
                                Ang HarvestGuard ay nakatuon sa pagprotekta sa inyong personal na impormasyon alinsunod sa Data Privacy Act of 2012 (Republic Act No. 10173) ng Pilipinas. Sa pamamagitan ng pagrehistro at pag-login sa HarvestGuard system, kayo ay pumapayag sa pangangalap at pagproseso ng inyong personal na datos, kabilang ang inyong buong pangalan, email address, username, password, IP address, numero ng telepono, at posisyon/tungkulin. Ang impormasyong ito ay kinakailangan para sa paglikha ng inyong account, pagpapatunay ng inyong pagkakakilanlan, pagbibigay ng secure na access, at pagpapadali ng komunikasyon sa loob ng sistema. Kami ay nagsasagawa ng makatwirang hakbang, tulad ng encryption at access controls, upang matiyak ang seguridad at pagiging kumpidensyal ng inyong datos. Nagsasagawa rin kami ng regular na pagsusuri upang maiwasan ang hindi awtorisadong access. <br>
                                 Mayroon kayong karapatang mag-access, magwasto, o humiling ng pagbura ng inyong personal na datos alinsunod sa mga probisyon ng Data Privacy Act. Ang inyong personal na datos ay hindi ibabahagi sa mga ikatlong partido maliban kung kinakailangan ng batas o para sa mahahalagang serbisyo, at ang anumang kasosyo sa ikatlong partido ay kailangang sumunod sa mga kasunduan na poprotektahan ang inyong pribasya. Maaaring gumamit ang HarvestGuard ng cookies at iba pang tracking technologies upang mapahusay ang inyong karanasan; gayunpaman, maaari ninyong pamahalaan ang mga setting na ito sa pamamagitan ng inyong browser. Ang inyong datos ay itatago lamang hangga’t kinakailangan para sa layuning ito at maayos na wawasakin kapag hindi na kailangan.
                                Sa patuloy na paggamit ng sistema, kinikilala ninyo at pumapayag sa pangangalap at pagproseso ng inyong personal na datos gaya ng inilarawan sa Pahayag ng Batas sa Pribasya na ito.
                                </p>
                            </div>
                        </div>

                        <div class="questions__item">
                            <header class="questions__header">
                                <i class="ri-add-line questions__icon"></i>
                                <h3 class="questions__item-title">
                                    Ano ang Kadalasang Sakit na dumadapo sa mga Pananim?
                                </h3>
                            </header>

                            <div class="questions__content">
                                <p class="questions__description"> ito ang mga iilan sa mga sakit na Maari makita sa mga Pananim
                                    <br>
                                    <strong>Tomato Yellow Leaf Curl Virus</strong> - ang sakit na ito sa halaman ay ang pagkakadilaw ng mga dahon na magiging sanhi sa pagkabulok
                                    <br><strong>Rice Blast</strong> – Isang fungal disease na nakakaapekto sa palay.
                                    <br><strong>Black Blight</strong> - isang uri na sakit na makikita sa halaman na may itim itim na bilog na maari masakop ang buong bunga o pananim
                                    <br><strong>Late Blight</strong> – Nakakaapekto sa kamatis at patatas, lalo na sa panahon ng malamig at mahalumigmig.
                                    <br><strong>Powdery Mildew</strong> – Karaniwan sa mga gulay tulad ng pipino at talong.
                                    <br><strong>Bacterial Wilt</strong> – Nakakaapekto sa maraming uri ng gulay, tulad ng kamatis at sili.
                                 </p>
                            </div>
                        </div>
                    </div>

                    <div class="questions__group">
                        <div class="questions__item">
                            <header class="questions__header">
                                <i class="ri-add-line questions__icon"></i>
                                <h3 class="questions__item-title">
                                    Ano ano mga pananim ang maari namen tignan kung may sakit gamit ang <strong>HARVESTGUARD</strong>?
                                </h3>
                            </header>

                            <div class="questions__content">
                                <p class="questions__description">
                                Ang HARVESTGUARD ay idinisenyo upang makilala ang sakit sa iba't ibang pananim, Narito ang iilan sa mga halaman na maaring tignan kung may sakit: <br><Strong> Palay, Mais, Kamatis, Patatas, Gulay tulad ng talong, sili, at pipino, Prutas tulad ng saging at mangga </Strong></p>
                            </div>
                        </div>

                        <div class="questions__item">
                            <header class="questions__header">
                                <i class="ri-add-line questions__icon"></i>
                                <h3 class="questions__item-title">
                                    Paano mas mapapadali ang pagtugon ng mga admin sa <strong>City Agriculture Office ng Carmona City</strong> sa aming mga farmers?
                                </h3>
                            </header>

                            <div class="questions__content">
                                <p class="questions__description">
                                Ang <strong>HARVESTGUARD</strong> ay nagpapadala ng real-time na impormasyon sa mga admin ng City Agriculture Office upang mas mabilis nilang makita ang mga problema ng mga magsasaka. Sa pamamagitan ng <strong>centralized system</strong>, nakikita nila ang mga request para sa suplay o tulong at ang kondisyon ng pananim sa isang dashboard. Ang automated na ulat mula sa mga magsasaka ay nagbibigay ng tumpak na datos, kaya’t mas madali nilang maibigay ang tamang solusyon o tulong. Ang transparent at mabilis na komunikasyon sa pagitan ng mga magsasaka at administrador ay nakakatulong para sa mas epektibong aksyon.</p>
                            </div>
                        </div>

                        <div class="questions__item">
                            <header class="questions__header">
                                <i class="ri-add-line questions__icon"></i>
                                <h3 class="questions__item-title">
                                    Paano ba gamitin ang <strong>HarvestGuard Web App</strong>?
                            </h3>
                            </header>

                            <div class="questions__content">
                                <p class="questions__description">
                                    Narito ang isang Youtube Link tutorial upang mapanood paano magamit ng Farmer ang HarvestGuard Web App
                                    <a href="https://youtu.be/InVQiXDLbIg" target="_blank">https://youtu.be/InVQiXDLbIg</a>
                                </p>
                                <p class="questions__description">
                                    Narito rin ang isang Youtube Link tutorial upang mapanood paano magamit ng Administrator ang HarvestGuard Web App
                                    <a href="https://youtu.be/sYrUw30QRZk" target="_blank">https://youtu.be/sYrUw30QRZk</a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <!--==================== CONTACT ====================-->
            <section class="contact section container" id="contact">                
                
                </div>
            </section>
        </main>

        <!--==================== FOOTER ====================-->
<footer class="footer section">
    <div class="footer__container container grid">
        <!-- Company Info -->
        <center><div class="footer__content">
            <a href="#" class="footer__logo">
                <img src="assets/img/logo.png" alt="HarvestGuard Logo" class="footer__logo-image" />
                <span>HARVESTGUARD</span>
            </a>
            <p class="footer__tagline">
                Protect your crops, and Providing your Needs
            </p>
        </div></center>

        <!-- Quick Links -->
        <div class="footer__links">
            <center>
            <h4>Quick Links</h4>
            <ul> <b>
                <li><a href="#home" class="custom-nav-link custom-active">Home</a></li>
                <li><a href="#about" class="custom-nav-link">About</a></li>
                <li><a href="#services" class="custom-nav-link">Services</a></li>
                <li><a href="#faqs" class="custom-nav-link">FAQs</a></li>
                <li><a href="#contact" class="custom-nav-link">Contact</a></li>
                <li><a href="{{ route('farmer.login') }}">Login as Farmer</a></li>
                <li><a href="{{ route('admin.login') }}">Login as Administrator</a></li>
                </b>
            </ul></center>
        </div>

        <!-- Contact Information -->
        <div class="footer__contact">
            <center><h4>Contact Us</h4>
            <p><i class="ri-map-pin-line"></i> Carmona, Philippines</p>
            <p><i class="ri-mail-line"></i> harvestguard00@gmail.com</p>
            <p><i class="ri-phone-line"></i> +63 912 345 6789</p>
        </div></center>
    </div>



    <!-- Copyright Notice -->
    <div class="footer__bottom">
        <p class="footer__copy">&#169; 2025 HarvestGuard. All Rights Reserved.</p>
    </div>
</footer>

<!--=============== SCROLL UP ===============-->
<a href="#" class="scrollup" id="scroll-up"> 
    <i class="ri-arrow-up-fill scrollup__icon"></i>
</a>

<!--=============== SCROLL REVEAL ===============-->
<script src="assets/js/scrollreveal.min.js"></script>

<!--=============== MAIN JS ===============-->
<script src="assets/js/main.js"></script>
