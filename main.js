// ============================================
// MOCK DATA - Movies Database
// ============================================
// Ganti poster, backdrop, dan video URL dengan data dari API Anda nanti
// Format: { id, title, poster, backdrop, video, year, rating, genre, synopsis, duration }

const moviesData = [
  {
    id: 1,
    title: "The Quantum Paradox",
    poster: "https://placehold.co/400x600?text=The+Quantum+Paradox+Poster+Dark+sci-fi+movie+with+futuristic+elements",
    backdrop:
      "https://placehold.co/1920x1080?text=Epic+sci-fi+movie+scene+with+futuristic+cityscape+at+night+neon+lights+dark+atmosphere",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4", // Sample video
    year: 2024,
    rating: 8.9,
    genre: "Sci-Fi, Thriller",
    synopsis:
      "Dalam dunia di mana realitas dapat dimanipulasi melalui teknologi quantum, seorang ilmuwan jenius harus menghadapi konsekuensi dari eksperimennya yang mengancam keberadaan seluruh umat manusia. Dengan waktu yang terus berpacu, ia harus menemukan cara untuk membalikkan kerusakan sebelum terlambat.",
    duration: "2h 28m",
  },
  {
    id: 2,
    title: "Crimson Horizon",
    poster: "https://placehold.co/400x600?text=Crimson+Horizon+Poster+Action+adventure+film+with+red+dramatic+sky",
    backdrop: "https://placehold.co/1920x1080?text=Dramatic+sunset+landscape+with+silhouettes+cinematic+red+orange+sky",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4",
    year: 2024,
    rating: 8.5,
    genre: "Action, Adventure",
    synopsis:
      "Seorang pilot pesawat tempur elit dipanggil kembali untuk misi terakhir yang tidak mungkin - menghentikan organisasi teroris yang mengancam akan menghancurkan seluruh negara dengan senjata pemusnah massal. Dalam perlombaan melawan waktu, ia harus menghadapi musuh-musuh di langit dan di darat.",
    duration: "2h 15m",
  },
  {
    id: 3,
    title: "Silent Echo",
    poster: "https://placehold.co/400x600?text=Silent+Echo+Poster+Dark+psychological+thriller+mysterious+woman",
    backdrop: "https://placehold.co/1920x1080?text=Dark+mysterious+forest+at+night+with+fog+eerie+atmosphere",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4",
    year: 2023,
    rating: 8.2,
    genre: "Thriller, Mystery",
    synopsis:
      "Seorang psikolog forensik yang kehilangan kemampuan bicara setelah trauma masa lalu harus memecahkan serangkaian pembunuhan berantai yang terhubung dengan rahasianya sendiri. Setiap petunjuk membawanya lebih dekat ke kebenaran yang mengancam akan menghancurkan hidupnya.",
    duration: "1h 58m",
  },
  {
    id: 4,
    title: "Neon Dreams",
    poster: "https://placehold.co/400x600?text=Neon+Dreams+Poster+Cyberpunk+aesthetic+with+vibrant+neon+colors",
    backdrop: "https://placehold.co/1920x1080?text=Futuristic+cyberpunk+city+with+neon+lights+rain+reflections",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
    year: 2024,
    rating: 8.7,
    genre: "Sci-Fi, Drama",
    synopsis:
      "Di kota cyberpunk tahun 2077, seorang hacker jenius mencoba mengungkap konspirasi korporasi yang mengendalikan pikiran jutaan orang melalui implant neural. Dalam dunia di mana realitas dan virtual berbaur, ia harus memilih antara kebebasan atau keselamatan.",
    duration: "2h 32m",
  },
  {
    id: 5,
    title: "The Last Guardian",
    poster: "https://placehold.co/400x600?text=The+Last+Guardian+Poster+Epic+fantasy+warrior+with+sword",
    backdrop: "https://placehold.co/1920x1080?text=Epic+medieval+battle+scene+with+castle+dramatic+sky",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4",
    year: 2023,
    rating: 9.1,
    genre: "Fantasy, Action",
    synopsis:
      "Penjaga terakhir dari dinasti kuno harus melindungi artefak suci dari pasukan gelap yang ingin menguasai dunia. Dalam perjalanan epiknya, ia menemukan bahwa takdirnya jauh lebih besar dari yang pernah dibayangkan, dan pengorbanan tertinggi mungkin diperlukan.",
    duration: "2h 45m",
  },
  {
    id: 6,
    title: "Midnight Train",
    poster: "https://placehold.co/400x600?text=Midnight+Train+Poster+Mystery+thriller+train+at+night",
    backdrop:
      "https://placehold.co/1920x1080?text=Train+traveling+through+dark+landscape+at+night+mysterious+atmosphere",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4",
    year: 2024,
    rating: 7.9,
    genre: "Mystery, Thriller",
    synopsis:
      "Dalam perjalanan kereta malam yang sepi, sekelompok penumpang yang tidak saling kenal menemukan diri mereka terjebak dalam misteri pembunuhan. Setiap orang memiliki rahasia, dan pembunuhnya ada di antara mereka. Waktu terus berjalan dan stasiun berikutnya masih jauh.",
    duration: "1h 52m",
  },
  {
    id: 7,
    title: "Ocean's Fury",
    poster: "https://placehold.co/400x600?text=Oceans+Fury+Poster+Disaster+movie+massive+ocean+waves",
    backdrop: "https://placehold.co/1920x1080?text=Massive+ocean+storm+with+huge+waves+dramatic+disaster+scene",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4",
    year: 2023,
    rating: 8.3,
    genre: "Action, Disaster",
    synopsis:
      "Ketika tsunami raksasa mengancam akan menenggelamkan seluruh pesisir, sekelompok pahlawan improvisasi harus bekerja sama untuk menyelamatkan ribuan nyawa. Di tengah kekacauan dan kehancuran, kemanusiaan diuji hingga batasnya.",
    duration: "2h 08m",
  },
  {
    id: 8,
    title: "Starlight Symphony",
    poster: "https://placehold.co/400x600?text=Starlight+Symphony+Poster+Musical+romance+couple+dancing+under+stars",
    backdrop:
      "https://placehold.co/1920x1080?text=Beautiful+starry+night+sky+with+couple+silhouette+romantic+atmosphere",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4",
    year: 2024,
    rating: 8.6,
    genre: "Romance, Drama",
    synopsis:
      "Seorang pianis berbakat yang kehilangan pendengarannya bertemu dengan penari balet yang berjuang dengan cedera karir-ending. Bersama-sama, mereka menemukan bahwa musik dan cinta dapat melampaui segala keterbatasan fisik dan menciptakan harmoni yang indah.",
    duration: "2h 12m",
  },
  {
    id: 9,
    title: "Shadow Protocol",
    poster: "https://placehold.co/400x600?text=Shadow+Protocol+Poster+Spy+action+film+silhouette+agent",
    backdrop: "https://placehold.co/1920x1080?text=Urban+spy+scene+at+night+silhouettes+action+dark+atmosphere",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4",
    year: 2024,
    rating: 8.4,
    genre: "Action, Spy",
    synopsis:
      "Agen rahasia terbaik di dunia harus menghadapi operasi yang paling berbahaya: mengungkap pengkhianat di dalam organisasinya sendiri. Dengan identitas palsu dan aliansi yang terus berubah, ia harus menyelesaikan misi sebelum protokol pembunuhan diaktifkan.",
    duration: "2h 22m",
  },
  {
    id: 10,
    title: "Desert Mirage",
    poster: "https://placehold.co/400x600?text=Desert+Mirage+Poster+Western+adventure+desert+landscape+lone+rider",
    backdrop: "https://placehold.co/1920x1080?text=Vast+desert+landscape+sunset+dramatic+western+cinematic",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4",
    year: 2023,
    rating: 7.8,
    genre: "Western, Adventure",
    synopsis:
      "Seorang penembak jitu yang pensiun dipaksa kembali ke kehidupan lamanya ketika geng penjahat mengancam kota gurun kecil yang telah menjadi rumahnya. Dalam duel terakhir di bawah terik matahari gurun, masa lalu dan masa depan akan bertabrakan.",
    duration: "1h 58m",
  },
  {
    id: 11,
    title: "Code Black",
    poster: "https://placehold.co/400x600?text=Code+Black+Poster+Medical+drama+doctors+in+emergency+room",
    backdrop: "https://placehold.co/1920x1080?text=Hospital+emergency+room+intense+medical+drama+scene",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4",
    year: 2024,
    rating: 8.1,
    genre: "Drama, Medical",
    synopsis:
      "Dalam 24 jam yang chaos di rumah sakit tersibuk di kota, tim dokter dan perawat harus menghadapi bencana massal, dilema etika, dan kehidupan pribadi yang berantakan. Setiap detik berharga, setiap keputusan bisa berarti hidup atau mati.",
    duration: "2h 05m",
  },
  {
    id: 12,
    title: "Frost & Fire",
    poster: "https://placehold.co/400x600?text=Frost+Fire+Poster+Epic+fantasy+ice+and+fire+elements+battle",
    backdrop: "https://placehold.co/1920x1080?text=Epic+fantasy+landscape+with+ice+fire+contrasting+elements",
    video: "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4",
    year: 2024,
    rating: 9.0,
    genre: "Fantasy, Epic",
    synopsis:
      "Dua kerajaan yang bermusuhan - satu menguasai es dan satu menguasai api - harus bersatu melawan ancaman gelap yang akan menghancurkan kedua dunia. Dua pewaris tahta dari kubu berlawanan harus mengatasi kebencian turun-temurun demi menyelamatkan semua yang mereka cintai.",
    duration: "2h 55m",
  },
]

// ============================================
// STATE MANAGEMENT
// ============================================
const myList = JSON.parse(localStorage.getItem("myList")) || []
let currentMovie = null

// ============================================
// HEADER SCROLL EFFECT
// ============================================
const header = document.getElementById("header")
let lastScroll = 0

window.addEventListener("scroll", () => {
  const currentScroll = window.pageYOffset

  if (currentScroll > 50) {
    header.classList.add("scrolled")
  } else {
    header.classList.remove("scrolled")
  }

  lastScroll = currentScroll
})

// ============================================
// SEARCH FUNCTIONALITY
// ============================================
const searchToggle = document.getElementById("searchToggle")
const searchContainer = document.getElementById("searchContainer")
const searchInput = document.getElementById("searchInput")
const searchResults = document.getElementById("searchResults")

searchToggle.addEventListener("click", () => {
  searchContainer.classList.toggle("active")
  if (searchContainer.classList.contains("active")) {
    searchInput.focus()
  }
})

// Debounce function untuk optimasi performance
function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Search input handler dengan debounce 250ms
const handleSearch = debounce((query) => {
  if (query.length < 2) {
    searchResults.classList.remove("active")
    searchResults.innerHTML = ""
    return
  }

  const filteredMovies = moviesData.filter(
    (movie) =>
      movie.title.toLowerCase().includes(query.toLowerCase()) ||
      movie.genre.toLowerCase().includes(query.toLowerCase()),
  )

  if (filteredMovies.length === 0) {
    searchResults.innerHTML = '<p style="padding: 1rem; color: var(--color-text-muted);">No results found</p>'
    searchResults.classList.add("active")
    return
  }

  searchResults.innerHTML = filteredMovies
    .map(
      (movie) => `
        <div class="search-result-item" data-movie-id="${movie.id}">
            <img src="${movie.poster}" alt="${movie.title} poster" class="search-result-poster" loading="lazy">
            <div class="search-result-info">
                <h3>${movie.title}</h3>
                <p>${movie.year} • ${movie.genre} • ★ ${movie.rating}</p>
            </div>
        </div>
    `,
    )
    .join("")

  searchResults.classList.add("active")

  // Add click handlers to search results
  document.querySelectorAll(".search-result-item").forEach((item) => {
    item.addEventListener("click", () => {
      const movieId = Number.parseInt(item.dataset.movieId)
      const movie = moviesData.find((m) => m.id === movieId)
      openMovieModal(movie)
      searchContainer.classList.remove("active")
      searchInput.value = ""
      searchResults.classList.remove("active")
    })
  })
}, 250)

searchInput.addEventListener("input", (e) => {
  handleSearch(e.target.value)
})

// Close search when clicking outside
document.addEventListener("click", (e) => {
  if (!searchContainer.contains(e.target) && !searchToggle.contains(e.target)) {
    searchContainer.classList.remove("active")
    searchInput.value = ""
    searchResults.classList.remove("active")
  }
})

// ============================================
// HERO SECTION - Dynamic Content
// ============================================
function initHero() {
  const heroMovie = moviesData[0] // First movie as featured
  document.getElementById("heroTitle").textContent = heroMovie.title
  document.getElementById("heroDescription").textContent = heroMovie.synopsis
}

// Hero buttons
document.getElementById("heroPlayBtn").addEventListener("click", () => {
  openPlayerModal(moviesData[0])
})

document.getElementById("heroInfoBtn").addEventListener("click", () => {
  openMovieModal(moviesData[0])
})

// ============================================
// MOVIE ROWS - Dynamic Rendering
// ============================================
function renderMovieRow(category) {
  const section = document.querySelector(`[data-category="${category}"]`)
  const carousel = section.querySelector(".carousel")

  // Shuffle untuk variasi (atau bisa filtered berdasarkan kategori)
  const movies = [...moviesData].sort(() => Math.random() - 0.5)

  carousel.innerHTML = movies
    .map(
      (movie) => `
        <div class="movie-card" data-movie-id="${movie.id}" role="listitem">
            <img 
                src="${movie.poster}" 
                alt="${movie.title} poster"
                class="movie-card-poster"
                loading="lazy"
            >
            <div class="movie-card-overlay">
                <h3 class="movie-card-title">${movie.title}</h3>
                <p class="movie-card-rating">★ ${movie.rating}</p>
            </div>
            <div class="movie-card-play" aria-label="Play ${movie.title}">
                <svg width="28" height="28" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
            </div>
        </div>
    `,
    )
    .join("")

  // Add click handlers
  carousel.querySelectorAll(".movie-card").forEach((card) => {
    card.addEventListener("click", () => {
      const movieId = Number.parseInt(card.dataset.movieId)
      const movie = moviesData.find((m) => m.id === movieId)
      openMovieModal(movie)
    })
  })

  // Setup carousel controls
  setupCarouselControls(section)
}

// ============================================
// CAROUSEL CONTROLS - Horizontal Scroll
// ============================================
function setupCarouselControls(section) {
  const carousel = section.querySelector(".carousel")
  const btnLeft = section.querySelector(".carousel-btn-left")
  const btnRight = section.querySelector(".carousel-btn-right")

  if (!carousel || !btnLeft || !btnRight) return

  const scrollAmount = 600

  btnLeft.addEventListener("click", () => {
    carousel.scrollBy({
      left: -scrollAmount,
      behavior: "smooth",
    })
  })

  btnRight.addEventListener("click", () => {
    carousel.scrollBy({
      left: scrollAmount,
      behavior: "smooth",
    })
  })

  // Optional: Mouse drag to scroll
  let isDown = false
  let startX
  let scrollLeft

  carousel.addEventListener("mousedown", (e) => {
    isDown = true
    carousel.style.cursor = "grabbing"
    startX = e.pageX - carousel.offsetLeft
    scrollLeft = carousel.scrollLeft
  })

  carousel.addEventListener("mouseleave", () => {
    isDown = false
    carousel.style.cursor = "grab"
  })

  carousel.addEventListener("mouseup", () => {
    isDown = false
    carousel.style.cursor = "grab"
  })

  carousel.addEventListener("mousemove", (e) => {
    if (!isDown) return
    e.preventDefault()
    const x = e.pageX - carousel.offsetLeft
    const walk = (x - startX) * 2
    carousel.scrollLeft = scrollLeft - walk
  })
}

// ============================================
// MODAL - Movie Detail
// ============================================
const movieModal = document.getElementById("movieModal")
const modalBackdrop = document.getElementById("modalBackdrop")
const modalClose = document.getElementById("modalClose")
const modalVideo = document.getElementById("modalVideo")
const modalPlayBtn = document.getElementById("modalPlayBtn")
const modalAddBtn = document.getElementById("modalAddBtn")

function openMovieModal(movie) {
  currentMovie = movie

  // Populate modal content
  document.getElementById("modalTitle").textContent = movie.title
  document.getElementById("modalRating").textContent = `★ ${movie.rating}`
  document.getElementById("modalYear").textContent = movie.year
  document.getElementById("modalDuration").textContent = movie.duration
  document.getElementById("modalGenre").textContent = movie.genre
  document.getElementById("modalSynopsis").textContent = movie.synopsis

  // Set video source
  modalVideo.querySelector("source").src = movie.video
  modalVideo.poster = movie.backdrop
  modalVideo.load()

  // Update Add to List button
  updateAddButton()

  // Show modal
  movieModal.classList.add("active")
  document.body.style.overflow = "hidden"

  // Autoplay video (muted)
  modalVideo.muted = true
  modalVideo.play().catch(() => {
    // Autoplay prevented
  })
}

function closeMovieModal() {
  movieModal.classList.remove("active")
  document.body.style.overflow = ""
  modalVideo.pause()
  modalVideo.currentTime = 0
  currentMovie = null
}

modalClose.addEventListener("click", closeMovieModal)
modalBackdrop.addEventListener("click", closeMovieModal)

modalPlayBtn.addEventListener("click", () => {
  if (currentMovie) {
    closeMovieModal()
    openPlayerModal(currentMovie)
  }
})

modalAddBtn.addEventListener("click", () => {
  if (currentMovie) {
    toggleMyList(currentMovie.id)
    updateAddButton()
  }
})

// Close modal with Escape key
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    if (movieModal.classList.contains("active")) {
      closeMovieModal()
    }
    if (playerModal.classList.contains("active")) {
      closePlayerModal()
    }
  }
})

// ============================================
// PLAYER MODAL - Fullscreen Video
// ============================================
const playerModal = document.getElementById("playerModal")
const playerClose = document.getElementById("playerClose")
const playerVideo = document.getElementById("playerVideo")

function openPlayerModal(movie) {
  // Set video source
  playerVideo.querySelector("source").src = movie.video
  playerVideo.load()

  // Show player modal
  playerModal.classList.add("active")
  document.body.style.overflow = "hidden"

  // Play video
  playerVideo.play()
}

function closePlayerModal() {
  playerModal.classList.remove("active")
  document.body.style.overflow = ""
  playerVideo.pause()
  playerVideo.currentTime = 0
}

playerClose.addEventListener("click", closePlayerModal)

// ============================================
// MY LIST - localStorage Integration
// ============================================
function toggleMyList(movieId) {
  const index = myList.indexOf(movieId)

  if (index > -1) {
    myList.splice(index, 1)
  } else {
    myList.push(movieId)
  }

  localStorage.setItem("myList", JSON.stringify(myList))
}

function isInMyList(movieId) {
  return myList.includes(movieId)
}

function updateAddButton() {
  if (currentMovie && isInMyList(currentMovie.id)) {
    modalAddBtn.innerHTML = `
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            In My List
        `
  } else {
    modalAddBtn.innerHTML = `
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add to List
        `
  }
}

// ============================================
// INITIALIZATION
// ============================================
function init() {
  // Render hero
  initHero()

  // Render all movie rows
  const categories = ["trending", "continue", "popular", "new", "recommended"]
  categories.forEach((category) => renderMovieRow(category))

  console.log("CineStream initialized successfully!")
  console.log("Total movies:", moviesData.length)
  console.log("My List:", myList)
}

// Run initialization when DOM is ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", init)
} else {
  init()
}

// ============================================
// INTEGRASI API (Panduan untuk masa depan)
// ============================================
/*
PANDUAN INTEGRASI API:

1. Ganti moviesData dengan fetch dari API:
   
   async function fetchMovies() {
       const response = await fetch('https://your-api.com/movies');
       const data = await response.json();
       return data;
   }

2. Format response API harus sesuai dengan struktur moviesData:
   {
       id: number,
       title: string,
       poster: string (URL),
       backdrop: string (URL),
       video: string (URL video stream atau .mp4),
       year: number,
       rating: number,
       genre: string,
       synopsis: string,
       duration: string
   }

3. Update initialization:
   
   async function init() {
       moviesData = await fetchMovies();
       initHero();
       // ... rest of initialization
   }

4. Untuk streaming video production:
   - Gunakan HLS (HTTP Live Streaming) untuk adaptive bitrate
   - Library seperti hls.js atau video.js
   - Format: .m3u8 playlist files
   
   Example dengan hls.js:
   
   if (Hls.isSupported()) {
       const hls = new Hls();
       hls.loadSource('https://your-cdn.com/video/playlist.m3u8');
       hls.attachMedia(videoElement);
   }

5. Untuk poster images:
   - Gunakan CDN untuk optimasi
   - Implementasi lazy loading (sudah ada: loading="lazy")
   - Gunakan srcset untuk responsive images:
     
     <img 
         src="poster-400.jpg" 
         srcset="poster-400.jpg 400w, poster-800.jpg 800w"
         sizes="(max-width: 768px) 150px, 200px"
     >

6. Authentication (jika diperlukan):
   - Simpan token di localStorage atau cookies
   - Include token di header request:
     
     headers: {
         'Authorization': `Bearer ${token}`
     }

7. Error handling:
   - Tambahkan try-catch di semua async functions
   - Tampilkan error state di UI jika fetch gagal
*/
