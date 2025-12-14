        // Data Film Indonesia
        const moviesData = [{
                id: 1,
                title: "Pengabdi Setan 2: Communion",
                year: 2022,
                rating: 8.5,
                quality: "HD",
                category: "Horror",
                poster: "images/films/pengabdi-setan-2.webp",
                description: "Sebuah keluarga yang masih dibayangi teror masa lalu harus menghadapi kembali kekuatan jahat yang lebih mengerikan.",
                director: "Joko Anwar",
                cast: "Tara Basro, Bront Palarae, Endy Arfian"
            },
            {
                id: 2,
                title: "Miracle in Cell No. 7",
                year: 2022,
                rating: 9.0,
                quality: "4K",
                category: "Drama",
                poster: "images/films/miracle-cell-7.webp",
                description: "Kisah mengharukan tentang seorang ayah dengan disabilitas mental yang dipenjara atas tuduhan pembunuhan.",
                director: "Hanung Bramantyo",
                cast: "Vino G. Bastian, Graciella Abigail, Indro Warkop"
            },
            {
                id: 3,
                title: "Imperfect: Karier, Cinta & Timbangan",
                year: 2024,
                rating: 8.8,
                quality: "HD",
                category: "Drama",
                poster: "images/films/imperfect.webp",
                description: "Perjalanan seorang wanita karir yang berjuang menyeimbangkan kehidupan profesional dan pribadi.",
                director: "Ernest Prakasa",
                cast: "Reza Rahadian, Jessica Mila, Denny Sumargo"
            },
            {
                id: 4,
                title: "Teman Tapi Menikah 2",
                year: 2024,
                rating: 7.9,
                quality: "HD",
                category: "Romance",
                poster: "images/films/teman-tapi-menikah-2.jpg",
                description: "Kelanjutan kisah romansa Ditto dan Ayu yang menghadapi tantangan baru dalam pernikahan mereka.",
                director: "Rako Prijanto",
                cast: "Adipati Dolken, Mawar de Jongh, Marshanda"
            },
            {
                id: 5,
                title: "The Big 4",
                year: 2023,
                rating: 8.3,
                quality: "HD",
                category: "Action",
                poster: "images/films/the-big-4.webp",
                description: "Empat pembunuh bayaran pensiun dipaksa kembali beraksi untuk membalas dendam dan melindungi orang yang mereka cintai.",
                director: "Timo Tjahjanto",
                cast: "Abimana Aryasatya, Putri Marino, Lutesha"
            },
            {
                id: 6,
                title: "Keluarga Cemara",
                year: 2019,
                rating: 8.7,
                quality: "4K",
                category: "Drama",
                poster: "images/films/keluarga-cemara.webp",
                description: "Kisah inspiratif keluarga yang kehilangan segalanya namun menemukan makna kebahagiaan yang sesungguhnya.",
                director: "Yandy Laurens",
                cast: "Ringgo Agus Rahman, Nirina Zubir, Adhisty Zara"
            },
            {
                id: 7,
                title: "Gundala",
                year: 2019,
                rating: 9.2,
                quality: "HD",
                category: "Action",
                poster: "images/films/gundala.webp",
                description: "Superhero Indonesia pertama bangkit untuk melawan kejahatan dan korupsi yang merajalela.",
                director: "Joko Anwar",
                cast: "Abimana Aryasatya, Tara Basro, Bront Palarae"
            },
            {
                id: 8,
                title: "Dilan 1990",
                year: 2018,
                rating: 8.6,
                quality: "HD",
                category: "Romance",
                poster: "images/films/dilan-1990.webp",
                description: "Kisah cinta legendaris antara Dilan dan Milea di tahun 1990 yang penuh kenangan manis.",
                director: "Fajar Bustomi",
                cast: "Iqbaal Ramadhan, Vanesha Prescilla, Sissy Priscillia"
            }
        ];

        const seriesData = [{
                id: 9,
                title: "Layangan Putus",
                year: 2022,
                rating: 9.3,
                quality: "HD",
                category: "Drama",
                poster: "images/series/layangan-putus.jpg",
                description: "Drama rumah tangga yang mengisahkan perjuangan seorang istri menghadapi perselingkuhan suami.",
                director: "Benni Setiawan",
                cast: "Reza Rahadian, Putri Marino, Anya Geraldine"
            },
            {
                id: 10,
                title: "Jingga dan Senja",
                year: 2023,
                rating: 8.9,
                quality: "4K",
                category: "Romance",
                poster: "images/series/jingga-senja.webp",
                description: "Kisah cinta remaja yang manis dan penuh dengan lika-liku kehidupan SMA.",
                director: "Kuntz Agus",
                cast: "Yesaya Abraham, Ochi Rosdiana, Jerome Kurnia"
            },
            {
                id: 11,
                title: "Wedding Agreement The Series",
                year: 2022,
                rating: 8.4,
                quality: "HD",
                category: "Romance",
                poster: "images/series/wedding-agreement.webp",
                description: "Pernikahan kontrak yang berubah menjadi cinta sejati penuh dengan konflik dan drama.",
                director: "Archie Hekagery",
                cast: "Refal Hady, Indah Permatasari, Mathias Muchus"
            },
            {
                id: 12,
                title: "Imperfect The Series",
                year: 2021,
                rating: 8.1,
                quality: "HD",
                category: "Drama",
                poster: "images/series/imperfect-series.jpg",
                description: "Tiga sahabat menghadapi tekanan hidup tentang body image, karier, dan cinta.",
                director: "Ernest Prakasa",
                cast: "Jessica Mila, Denny Sumargo, Marsha Timothy"
            }
        ];

        let allMovies = [...moviesData];
        let currentCategory = 'Semua';

        // Function to create movie card HTML
        function createMovieCard(movie) {
            return `
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="movie-card" onclick="showMovieDetail(${movie.id})">
                        <div class="movie-poster-wrapper">
                            <img src="${movie.poster}" alt="${movie.title}" class="movie-poster">
                            <div class="movie-overlay">
                                <div class="play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                            <span class="badge-quality">${movie.quality}</span>
                        </div>
                        <div class="movie-info">
                            <h5 class="movie-title">${movie.title}</h5>
                            <div class="movie-meta">
                                <span><i class="far fa-calendar"></i> ${movie.year}</span>
                                <span class="movie-rating"><i class="fas fa-star"></i> ${movie.rating}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Load movies on page load
        function loadMovies() {
            const trendingContainer = document.getElementById('trendingMovies');
            const categoryContainer = document.getElementById('categoryMovies');
            const seriesContainer = document.getElementById('seriesContent');

            // Load trending movies (first 4)
            trendingContainer.innerHTML = moviesData.slice(0, 4).map(createMovieCard).join('');

            // Load all movies in category section
            categoryContainer.innerHTML = moviesData.map(createMovieCard).join('');

            // Load series
            seriesContainer.innerHTML = seriesData.map(createMovieCard).join('');
        }

        // Show movie detail modal
        function showMovieDetail(movieId) {
            const movie = [...moviesData, ...seriesData].find(m => m.id === movieId);
            if (!movie) return;

            document.getElementById('modalTitle').textContent = movie.title;
            document.getElementById('modalPoster').src = movie.poster;
            document.getElementById('modalInfo').innerHTML = `
                <p><strong>Tahun:</strong> ${movie.year}</p>
                <p><strong>Rating:</strong> <span class="movie-rating">${movie.rating}/10</span></p>
                <p><strong>Kualitas:</strong> ${movie.quality}</p>
                <p><strong>Kategori:</strong> ${movie.category}</p>
                <p><strong>Sutradara:</strong> ${movie.director}</p>
                <p><strong>Pemeran:</strong> ${movie.cast}</p>
                <p><strong>Deskripsi:</strong></p>
                <p>${movie.description}</p>
            `;

            const modal = new bootstrap.Modal(document.getElementById('movieModal'));
            modal.show();
        }

        // Filter by category
        function filterCategory(category) {
            currentCategory = category;

            // Update active pill
            document.querySelectorAll('.category-pill').forEach(pill => {
                pill.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter movies
            const categoryContainer = document.getElementById('categoryMovies');
            let filteredMovies = category === 'Semua' ? moviesData : moviesData.filter(m => m.category === category);

            categoryContainer.innerHTML = filteredMovies.map(createMovieCard).join('');
        }

        // Search movies
        function searchMovies() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const allContent = [...moviesData, ...seriesData];

            const filteredMovies = allContent.filter(movie =>
                movie.title.toLowerCase().includes(searchTerm)
            );

            const categoryContainer = document.getElementById('categoryMovies');

            if (searchTerm === '') {
                // If search is empty, show all or filtered by category
                let moviesToShow = currentCategory === 'Semua' ? moviesData : moviesData.filter(m => m.category === currentCategory);
                categoryContainer.innerHTML = moviesToShow.map(createMovieCard).join('');
            } else {
                // Show search results
                if (filteredMovies.length > 0) {
                    categoryContainer.innerHTML = filteredMovies.map(createMovieCard).join('');
                } else {
                    categoryContainer.innerHTML = '<div class="col-12 text-center"><p class="text-secondary">Tidak ada film ditemukan</p></div>';
                }
            }
        }

        // Set active nav link
        function setActive(element) {
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            element.classList.add('active');
        }

        // Scroll to section
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            loadMovies();

            // Add smooth scroll to nav links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
