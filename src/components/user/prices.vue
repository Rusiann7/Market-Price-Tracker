<template>
  <nav>
    <ul class="sidebar" ref="sidebar">
      <li @click="hideSidebar">
        <a href="#">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="26"
            viewBox="0 -960 960 960"
            width="26"
            fill="#e3e3e3">

            <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
          </svg>
        </a>
      </li>
      <li><a href="#" @click.prevent="$router.push('/')">Home</a></li>
      <li><a href="#" @click.prevent="$router.push('/price')">Price</a></li>
      <li><a href="#" @click.prevent="$router.push('/compare')">Compare</a></li>
      <li><a href="#" @click.prevent="$router.push('/newsandupdates')">News and Updates</a></li>
      <li><a href="#" @click.prevent="$router.push('/feedback')">Feedback</a></li>
    </ul>

    <ul>
      <li>
        <a href="#" @click.prevent="$router.push('/')">Market Price Tracker</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/')">Home</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/price')">Price</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/compare')">Compare</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/newsandupdates')">News and Update</a>
      </li>
      <li class="hideMobile">
        <a href="#" @click.prevent="$router.push('/feedback')">Feedback</a>
      </li>
      <li class="menu-btn" @click="showSidebar">
        <a href="#">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            height="26"
            viewBox="0 -960 960 960"
            width="26"
            fill="#e3e3e3">

            <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/>
          </svg>
        </a>
      </li>
    </ul>
  </nav>

  <div v-if="isLoading" class="loading-screen">
    <div class="loading-spinner"></div>
    <p>Loading...</p>
  </div>

  <div v-show="!isLoading"> 

  <div class="image-container">
    <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
    <div class="img-overlay"></div>
  </div>

  <div class="price-container">
    <h1 style="color: white">Prices & Analytics</h1>
    <br>

    <div class="prices-container">
      <!-- left side) -->
      <div class="chart-section">
        <h3>Price Trends</h3>
        <div class="chart-wrapper">
          <Bar v-if="chartData" :data="chartData" :options="chartOptions" />
          <div v-else>No data available</div>
        </div>
        <div class="ai-summarizer">
          <h3>
            <svg xmlns="http://www.w3.org/2000/svg" 
            height="24px" viewBox="0 -960 960 960" 
            width="24px" fill="#e3e3e3">
            <path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/>
          </svg>
          AI Summarizer
        </h3>
          <p>{{ message }}</p>
        </div>
      </div>

      <!-- right side) -->
      <div class="list-price-container">
        <div class="title-section">
          <h2>Market Prices</h2>
        </div>
        <div class="price-list">
          <input class="input-field" type="text" v-model="searchQuery" placeholder="Search ..." @input="filterPrices"/>

          <table class="table-content">
            <thead>
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Source</th>
              </tr>
            </thead>

            <tbody>
              <tr 
                v-for="price in filteredPrices"
                :key="price.RiceType + price.Source" 
                @click="handleRowClick(price)"
                :class="{ 'selected-row': selectedItem && selectedItem.RiceType === price.RiceType }"
              >
                <td>{{ price.RiceType }}</td>
                <td>₱{{ price.Price }}</td>
                <td><a :href="price.Source" target="_blank" rel="noopener noreferrer" @click.stop style="color: black;">Source</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script>
import { Bar } from "vue-chartjs";
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,} from "chart.js";

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  name: "PricePage",
  components: {Bar},
  data() {
    return {
      urlappphp: process.env.VUE_APP_URLAPPPHP,
      selectedItem: null,
      message: '',
      prices: [],
      chartData: {
        labels: [],
        datasets: []
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: true,
        aspectRatio: 2,
        scales: {
          x: { display: false },
          y: { beginAtZero: true },
        },
      },
      searchQuery: '',
      filteredPrices: [],
      isLoading: true
    };
  },

  created() {
    this.filteredPrices = [...this.prices];
  },
  
  methods: {
    showSidebar() {
      this.$refs.sidebar.style.display = "flex";
    },
    hideSidebar() {
      this.$refs.sidebar.style.display = "none";
    },

    async getPrices(){
      try {

        const response = await fetch(this.urlappphp, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ action: "getPrices" }),
        });

        const result = await response.json();

        if (result.success) {
          this.prices = result.data.map(item => ({
          ...item,
          Price: typeof item.Price === 'number' ? item.Price.toFixed(2) : item.Price
          }));
          this.filteredPrices = [...this.prices]; 
        } else {
          console.error("Failed to fetch Prices:", result.error);
        }
      } catch (error) {
        console.error("Error fetching Prices:", error);
      } finally {
        this.isLoading = false;
      }
    },

    handleClickOutside(event) {
      if (this.$refs.sidebar &&this.$refs.sidebar.style.display === "flex") {
        const isClickInsideSidebar = this.$refs.sidebar.contains(event.target);
        const isClickOnMenuButton = event.target.closest(".menu-btn"); 

        if (!isClickInsideSidebar && !isClickOnMenuButton) {
          this.hideSidebar(); 
        }
      }
    },

    async handleRowClick(price){
      this.isLoading = true;
      try {
        const response = await fetch(this.urlappphp, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ action: "chartData", itemName: price.RiceType}),
        });

        const result = await response.json();

        if (result.success) {
          this.chartData = {
            labels: result.data.Prices.map((_, index) => `Price ${index + 1}`),
            datasets: [{
              label: `Price of ${result.data.RiceType}`,
              data: result.data.Prices,
              backgroundColor: "#ffe082",
            }]
          };
          this.selectedItem = price;
          this.message = result.message
        } else {
          console.error("Failed to fetch Prices:", result.error);
        }
      } catch (error) {
        console.error("Error fetching Prices:", error);
      } finally {
        this.isLoading = false;  // Hide loader when done
      }
    },
    
    filterPrices() {
      if (!this.searchQuery) {
        this.filteredPrices = [...this.prices];
        return;
      }
    
      const query = this.searchQuery.toLowerCase();
      this.filteredPrices = this.prices.filter(price => 
        price.RiceType.toLowerCase().includes(query)
      );
    }
  },

  watch: {
    prices(newPrices) {
      this.filteredPrices = [...newPrices];
      this.filterPrices();
    }
  },
  
  mounted(){
    this.getPrices();
    this.priceInterval = setInterval(() => {
      this.getPrices();
    }, 5000);
    document.addEventListener("click", this.handleClickOutside);
  },

  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
    clearInterval(this.priceInterval);
  },

};
</script>

<style scoped>
/* Added navbar styles */
* {
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  box-sizing: border-box;
}

html,
body {
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav {
  background-color: #2d333f;
  box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);
  position: fixed;
  top: 0;
  z-index: 100;
  margin: 0;
  padding: 0;
  width: 100%;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav ul {
  width: 100%;
  list-style: none;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav li {
  height: 50px;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav a {
  height: 100%;
  padding: 0 30px;
  text-decoration: none;
  display: flex;
  align-items: center;
  color: #e3e3e3;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 500;
  font-size: 15px;
  letter-spacing: 0.5px;
}

nav li:first-child a {
  font-size: 18px;
  font-weight: 600;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

nav a:hover {
  background-color: #3a4252;
  color: white;
}

nav a:active {
  background-color: #4a5568; 
}

nav li:first-child {
  margin-right: auto;
}

.sidebar {
  position: fixed;
  top: 0;
  right: 0;
  height: 100vh;
  width: 250px;
  z-index: 999;
  background-color: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  box-shadow: -10px 0 10px rgba(0, 0, 0, 0.1);
  display: none;
  flex-direction: column;
  align-items: flex-start;
  justify-content: flex-start;
  margin: 0;
  padding: 0;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar li {
  width: 100%;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar a {
  width: 100%;
  font-size: 16px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.sidebar a:hover {
    background-color: #3a4252;
}

.menu-btn {
  display: none;
}

.menu-btn:hover {
  background-color: #3a4252;
}

@media (max-width: 800px) {
  .hideMobile {
    display: none;
  }
  .menu-btn {
    display: block;
  }
}

@media (max-width: 400px) {
  .sidebar {
    width: 100%;
  }
}

.price-container {
  position: relative; /* above overlay */
  z-index: 2;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  padding: 80px 40px 40px; /* space */
  border-radius: 12px;
  max-width: 100%;
  min-height: 100vh; 
  margin: 0 auto;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
}

/* two columns */
.prices-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  width: 90%;
  max-width: 1200px;
  margin: 20px auto;
  gap: 40px;
}

/* chart left */
.chart-section {
  width: 30%;
  background-color: #232831;
  color: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: sticky;
  top: 70px; 
}

.chart-section h3 {
  text-align: center;
  margin-bottom: 20px;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.chart-wrapper {
  width: 100%;
  margin: 0 auto;
  height: auto;
  min-height: 200px;
}

/* price right */
.list-price-container {
  width: 65%;
  background-color: #232831;
  color: white;
  max-height: 70vh;
  overflow-y: auto;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.list-price-container::-webkit-scrollbar {
  width: 8px;
}

.list-price-container::-webkit-scrollbar-track {
  background: #232831;
}

.list-price-container::-webkit-scrollbar-thumb {
  background-color: #3a4252;
  border-radius: 4px;
}

.list-price-container h2 {
  margin-bottom: 20px;
  font-size: 1.4rem;
  font-weight: bold;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.price-list {
  padding: 10px;
}

.chart-container {
  width: 300px;
  height: 200px;
  margin: 20px auto;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1200px;
  gap: 40px;
  display: flex;
}

@media (max-width: 1024px) {
  .prices-container {
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  .chart-section,
  .list-price-container {
    width: 90%;
    max-width: 600px;
  }

  .chart-section {
    position: relative;
    top: 0;
  }
}

@media (max-width: 768px) {
  .prices-container {
    gap: 15px;
  }

  .chart-section,
  .list-price-container {
    width: 100%;
  }

  .chart-section h3 {
    font-size: 18px;
  }

  .list-price-container {
    max-height: 350px;
  }

  .chart-container {
    width: 250px;
    height: 150px;
  }
}

@media (max-width: 480px) {
  .chart-section h3 {
    font-size: 16px;
  }

  .chart-container {
    width: 200px;
    height: 120px;
  }

  .table-content {
    min-width: 100%;
    font-size: 0.8rem;
  }

  .table-content th,
  .table-content td {
    padding: 8px 10px;
  }
}

.clickable-row {
  cursor: pointer;
  transition: all 0.3s ease;
}

.clickable-row:hover {
  background-color: #e9f5ff; 
  box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
  transform: translateY(-1px); /* lift effect */
}

.clickable-row:active {
  transform: translateY(0); /* effect when clicked */
  background-color: #d0e7ff; 
}


.table-content {
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 0.9rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-width: 300px;
  width: 100%;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  overflow: hidden;
  border-radius: 15px;
}

.table-content thead tr {
  background-color: #1e1e1e;
  color: #ffffff;
  text-align: left;
  font-weight: bold;
}

.table-content tbody tr {
  background-color: #f9f9f9;
  color: #333;
  border-bottom: 1px solid #dddddd;
}

.table-content tbody tr:nth-of-type(even) {
  background-color: #f3f3f3; 
}

.table-content tbody tr:hover {
  background-color: #e9e9e9; /* hover effect */
  cursor: pointer; 
}

.selected-row {
  background-color: #ffe082 !important; /* selected row */
  color: #333 !important;
}

.table-content th,
.table-content td {
  padding: 12px 15px;
}

.table-content tbody tr:last-of-type {
  border-bottom: #1e1e1e;
}

@media (max-width: 768px) {
  .table-content {
    min-width: 100%;
    font-size: 0.85rem;
  }

  .table-content th,
  .table-content td {
    padding: 10px 12px;
  }

  .main-content {
    padding: 0 10px;
  }
}

@media (max-width: 480px) {
  .table-content {
    font-size: 0.8rem;
  }

  .table-content th,
  .table-content td {
    padding: 8px 10px;
  }

  .compare-content {
    padding: 15px;
  }
}

.title-section {
  display: flex;
  align-items: center;
  justify-content: center;

}

.ai-summarizer {
  margin-top: 20px;
  background-color: rgba(255, 255, 255, 0.05);
  border-radius: 8px;
  padding: 15px;
  justify-content: center;
  text-align: justify;
}

.ai-summarizer h3 {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}

.ai-summarizer p {
  line-height: 1.5;
}

.input-field {
  margin-bottom: 15px; /* space below each input field */
  margin-top: 15px;
  padding: 12px 15px;
  width: 100%;
  font-size: 16px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

.loading-screen {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  color: white;
}

.loading-spinner {
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top: 4px solid #ffffff;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
  margin-bottom: 10px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.image-container {
  position: fixed; 
  width: 100%;
  height: 100vh;
  top: 0;
  left: 0;
  z-index: 1;
}

.main-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: blur(5px);
  position: absolute;
  top: 0;
  left: 0;
}

.img-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(45, 51, 63, 0.452);
}
</style>
