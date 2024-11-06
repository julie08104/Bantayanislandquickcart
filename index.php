<?php include 'header.php'; ?>

<div class="relative min-h-screen flex items-center justify-center">
    <!-- Centered button links container -->
    <div class="absolute z-10 flex justify-center items-center gap-4">
        <a class="w-full block min-w-20" href="customer">
            <div class="flex flex-col items-center justify-center bg-white shadow rounded p-4">
                <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
                <span>Customer</span>
            </div>
        </a>
        <a class="w-full block min-w-20" href="raider">
            <div class="flex flex-col items-center justify-center bg-white shadow rounded p-4">
                <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 0 0-2 2v9a1 1 0 0 0 1 1h.535a3.5 3.5 0 1 0 6.93 0h3.07a3.5 3.5 0 1 0 6.93 0H21a1 1 0 0 0 1-1v-4a.999.999 0 0 0-.106-.447l-2-4A1 1 0 0 0 19 6h-5a2 2 0 0 0-2-2H4Zm14.192 11.59.016.02a1.5 1.5 0 1 1-.016-.021Zm-10 0 .016.02a1.5 1.5 0 1 1-.016-.021Zm5.806-5.572v-2.02h4.396l1 2.02h-5.396Z" clip-rule="evenodd"/>
                </svg>
                <span>Rider</span>
            </div>
        </a>
        <a class="w-full block min-w-20" href="admin">
            <div class="flex flex-col items-center justify-center bg-white shadow rounded p-4">
                <svg class="w-10 h-10" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z" clip-rule="evenodd"/>
                </svg>
                <span>Admin</span>
            </div>
        </a>
    </div>
    
    <!-- Swiper Slider -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img1.png" alt="img1" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img2.png" alt="img2" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img3.png" alt="img3" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img4.png" alt="img4" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img5.png" alt="img5" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img6.png" alt="img6" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img7.png" alt="img7" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img8.png" alt="img8" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img9.png" alt="img9" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img10.png" alt="img10" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img11.png" alt="img11" /></div>
            <div class="swiper-slide"><img class="object-cover h-screen w-full" src="/images/img12.png" alt="img12" /></div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>


<?php include 'footer.php'; ?>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
      spaceBetween: 30,
      centeredSlides: true,
      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
</script>