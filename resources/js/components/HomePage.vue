<template>
    <div class="container py-4">
      <!-- Navigasi Responsif -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow-sm mb-4">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">☕ Kedai Kopi</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" href="#">Beranda</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Ulasan</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
  
      <!-- Judul Section -->
      <div class="text-center mb-4">
        <h1 class="fw-bold">Menu Bestseller</h1>
        <p class="text-muted">Minuman paling favorit pelanggan kami</p>
      </div>
  
      <!-- Daftar Menu -->
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <div class="col" v-for="item in menuList" :key="item.id">
          <div class="card h-100 shadow-sm">
            <img :src="item.image" class="card-img-top" :alt="item.name" style="object-fit: cover; height: 200px;" />
            <div class="card-body">
              <h5 class="card-title">{{ item.name }}</h5>
              <p class="card-text text-muted">{{ item.description }}</p>
              <p class="fw-bold">Rp {{ item.price.toLocaleString() }}</p>
              <button class="btn btn-outline-primary w-100">Pesan Sekarang</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'HomePage',
    data() {
      return {
        menuList: [
          {
            id: 1,
            name: 'Kopi Susu Gula Aren',
            description: 'Kopi klasik dengan sentuhan manis gula aren.',
            price: 18000,
            image: 'https://www.nescafe.com/id/sites/default/files/2023-08/Cobain%20Minuman%20Kekinian%20Ala%20Cafe%20Di%20Rumah%2C%20Yuk%21%20Ini%20Resepnya_desk.jpg',
          },
          {
            id: 2,
            name: 'Cappuccino',
            description: 'Espresso dengan foam susu creamy.',
            price: 22000,
            image: 'https://www.cuisinart.com/dw/image/v2/ABAF_PRD/on/demandware.static/-/Sites-us-cuisinart-sfra-Library/default/dw30047d66/images/recipe-Images/cappuccino1-recipe.jpg?sw=1200&sh=630',
          },
          {
            id: 3,
            name: 'Es Matcha Latte',
            description: 'Matcha premium dengan susu segar.',
            price: 25000,
            image: 'https://www.romylondonuk.com/wp-content/uploads/2025/04/Matcha-Latte-Starbucks-Recipe_THUMB.jpg',
          },
          // Tambahkan menu lain di sini
        ],
      };
    },
  };
  </script>
  
  <style scoped>
  @media (max-width: 768px) {
    h1 {
      font-size: 1.5rem;
    }
  }
  </style>
  