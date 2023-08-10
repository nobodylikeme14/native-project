window.onload = function() {
    //AOS Init
    setTimeout(function() {
        AOS.init({
            duration: 600,
            once: true
        });
    }, 1000);

    fetch("assets/produk.json").then(response => response.json()).then(data => {
        //Produk Unggulan
        var produkCarouselEl = document.getElementById("produk-unggulan-carousel");
        var carouselInner = document.createElement("div");
        carouselInner.className = "carousel-inner pb-2";
        var itemCount = 0;
        var carouselItem;
        var slideCount = 0;
        data.sort((a, b) => b.rating - a.rating);
        data.slice(0, 15).forEach((product, index) => {
            if (itemCount === 0) {
                carouselItem = document.createElement("div");
                carouselItem.className = "carousel-item" + (slideCount === 0 ? " active" : "");
                let rowContainer = document.createElement("div");
                rowContainer.className = "row row-cols-1 row-cols-md-5 g-4";
                carouselItem.appendChild(rowContainer);
                carouselInner.appendChild(carouselItem);
                slideCount++;
            }
            var carouselItemElement = `
                <div class="col">
                    <div class="card position-relative h-100">
                        <img src="${product.url_gambar}" 
                        class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <div class="card-text mb-1">
                                ${product.nama}
                            </div>
                            <div class="card-text fw-bold mb-2">
                                ${product.harga}
                            </div>
                            <div class="card-text small mb-1">
                                <span class="icon-container text-center me-1">
                                    <i class="fas fa-location-dot text-custom"></i>
                                </span>
                                ${product.lokasi}
                            </div>
                            <div class="card-text small mb-1">
                                <span class="icon-container text-center me-1">
                                    <i class="fas fa-star text-warning"></i>
                                </span>${product.rating} | Terjual ${product.jumlah_jual}
                            </div>
                            <a href="javascript:void(0)" class="stretched-link"></a>
                        </div>
                    </div>
                </div>`;
            carouselItem.querySelector(".row").innerHTML += carouselItemElement;
            itemCount = (itemCount + 1) % 5;
        });
        produkCarouselEl.appendChild(carouselInner);

        //Produk Per Kategori
        function createCarousel(kategori, carouselId, containerId) {
            const filteredData = data.filter(product => product.kategori === kategori);
            const produkCarouselEl = document.getElementById(containerId);
            const carouselInner = document.createElement("div");
            carouselInner.className = "carousel-inner pb-2";
            let itemCount = 0;
            let carouselItem;
            let slideCount = 0;
        
            filteredData.forEach((product, index) => {
                if (itemCount === 0) {
                    carouselItem = document.createElement("div");
                    carouselItem.className = "carousel-item" + (slideCount === 0 ? " active" : "");
                    const rowContainer = document.createElement("div");
                    rowContainer.className = "row row-cols-1 row-cols-md-5 g-4";
                    carouselItem.appendChild(rowContainer);
                    carouselInner.appendChild(carouselItem);
                    slideCount++;
                }
                var carouselItemElement = `
                <div class="col">
                    <div class="card position-relative h-100">
                        <img src="${product.url_gambar}" 
                        class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <div class="card-text mb-1">
                                ${product.nama}
                            </div>
                            <div class="card-text fw-bold mb-2">
                                ${product.harga}
                            </div>
                            <div class="card-text small mb-1">
                                <span class="icon-container text-center me-1">
                                    <i class="fas fa-location-dot text-custom"></i>
                                </span>
                                ${product.lokasi}
                            </div>
                            <div class="card-text small mb-1">
                                <span class="icon-container text-center me-1">
                                    <i class="fas fa-star text-warning"></i>
                                </span>${product.rating} | Terjual ${product.jumlah_jual}
                            </div>
                            <a href="javascript:void(0)" class="stretched-link"></a>
                        </div>
                    </div>
                </div>`;
                carouselItem.querySelector(".row").innerHTML += carouselItemElement;
                itemCount = (itemCount + 1) % 5;
            });
        
            produkCarouselEl.appendChild(carouselInner);
            const carouselControls = document.querySelector(`#${carouselId} .carousel-control`);
            const prevButton = carouselControls.querySelector("[data-bs-slide='prev']");
            const nextButton = carouselControls.querySelector("[data-bs-slide='next']");
            if (slideCount > 1) {
                carouselControls.style.display = "flex";
                prevButton.style.display = "block";
                nextButton.style.display = "block";
            } else {
                carouselControls.style.display = "none";
            }
        }
        createCarousel("Elektronik", "elektronikCarousel" ,"electronic-carousel");
        createCarousel("Laptop", "laptopCarousel", "laptop-carousel");
        createCarousel("Handphone", "handphoneCarousel", "handphone-carousel");
        createCarousel("Pakaian", "pakaianCarousel", "pakaian-carousel");
        createCarousel("Sepatu", "sepatuCarousel","sepatu-carousel");
        createCarousel("Tas", "tasCarousel","tas-carousel");
        createCarousel("Aksesoris", "aksesorisCarousel", "aksesoris-carousel");
        createCarousel("Alat Musik", "alatMusikCarousel", "alatMusik-carousel");        
    })
    .catch(error => alert("Terjadi kesalahan saat mengambil data item :", error));

    //Scroll to Divs
    const scrollButtons = document.querySelectorAll(".scroll-button");
    scrollButtons.forEach(button => {
        button.addEventListener("click", function() {
            const targetId = this.getAttribute("data-target");
            const targetDiv = document.querySelector(targetId);
            if (targetDiv) {
                const offset = 250;
                const targetScrollPosition = targetDiv.getBoundingClientRect().top + window.scrollY - offset;
                window.scrollTo({
                    top: targetScrollPosition,
                    behavior: "smooth"
                });
            }
        });
    });

    //Scroll to Top
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            document.querySelector('.scroll-to-top').style.display = 'block';
        } else {
            document.querySelector('.scroll-to-top').style.display = 'none';
        }
    });
    document.querySelector('.scroll-to-top').addEventListener('click', function() {
        var scrollOptions = {
            top: 0,
            behavior: 'smooth'
        };
        window.scrollTo(scrollOptions);
        return false;
    });
};