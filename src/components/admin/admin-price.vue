<template>

  <!-- Added Navigation Bar -->
  <nav>
  <ul class="sidebar" ref="sidebar">
    <li @click="hideSidebar">
      <a href="#"> 
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="26"
          viewBox="0 -960 960 960"
          width="26"
          fill="#e3e3e3"
        >
        <path
          d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"
        />
        </svg>
        </a>
    </li>
    <li><a href="#" @click.prevent="$router.push('/adminHome')">Home</a></li>
    <li><a href="#" @click.prevent="$router.push('/adminPrice')">Price</a></li>
    <li><a href="#" @click.prevent="$router.push('/adminUser')">Users</a></li>
    <li><a href="#" @click.prevent="$router.push('/adminControl')">Control Panel</a></li>
    <li><a href="#" @click.prevent="logout">Log Out</a></li>
  </ul>

  <ul>
    <li>
      <a href="#" @click.prevent="$router.push('/adminHome')"
        >Market Price Tracker-Admin</a>
    </li>
    <li class="hideMobile">
      <a href="#" @click.prevent="$router.push('/adminHome')">Home</a>
    </li>
    <li class="hideMobile">
      <a href="#" @click.prevent="$router.push('/adminPrice')">Price</a>
    </li>
    <li class="hideMobile">
      <a href="#" @click.prevent="$router.push('/adminUser')">Users</a>
    </li>
    <li class="hideMobile">
      <a href="#" @click.prevent="$router.push('/adminControl')">Control Panel</a>
    </li>
    <li class="hideMobile">
      <a href="#" @click.prevent="logout">Log Out</a>
    </li>
    <li class="menu-btn" @click="showSidebar">
      <a href="#">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          height="26"
          viewBox="0 -960 960 960"
          width="26"
          fill="#e3e3e3"
        >
        <path
          d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"
        />
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


<h1 class="top-text">Admin Price</h1>

<div class="image-container">
  <img src="@/assets/main.jpeg" class="main-image" alt="Blurred Background">
  <div class="img-overlay"></div>
</div>

<div class="main-content">
  <div v-if="$route.path === '/adminPrice'">
    <div class="list-price-container">
      <div class="header-section">
        <h2 class="section-title">Market Prices</h2>

        <button class="btn btn-icon" @click.prevent="$router.push('/addItem')">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" 
          width="24px" 
          fill="#e3e3e3">
          <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/>
        </svg>
        </button>

        <button class="btn btn-icon" @click.prevent="$router.push('/editItem')">
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            height="24px" viewBox="0 -960 960 960" 
            width="24px" 
            fill="#000000" >

            <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z"/>
          </svg>
        </button>

        <button class="btn btn-icon" @click.prevent="$router.push('/deleteItem')">
          <svg xmlns="http://www.w3.org/2000/svg" 
            height="24px" 
            viewBox="0 -960 960 960" 
            width="24px" 
            fill="#000000">

            <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
          </svg>
        </button>
      </div>
      <div class="price-list">
        <input class="input-field" type="text" v-model="searchQuery" placeholder="Search ..." @input="filterPrices"/>
        <div class="scroll">
          <table class="table-content">
            <thead>
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Source</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="price in filteredPrices" :key="price.RiceType + price.Source">
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

  <div v-if="$route.path === '/addItem'">
    <button class="btn" @click.prevent="$router.push('/adminPrice')"> 
        <svg xmlns="http://www.w3.org/2000/svg" 
          height="24px" viewBox="0 -960 960 960" 
          width="24px" fill="#e3e3e3">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg> 
      </button>
    <div class="addItem">
      <div class="form-box">
        <form
          id="newItem"
          class="input-group"
          method="get"
          @submit.prevent="addItem">

          <br>

          <p>Enter new item:</p>
          <br>
          <input
            type="text"
            class="input-field"
            placeholder="Item"
            v-model="FormDataP.newItem"
            required/>

          <br>

          <button type="submit" class="btn" value="GET">Add</button>
        </form>
      </div>
    </div>
  </div>

  <div v-if="$route.path === '/editItem'">
    <button class="btn" @click.prevent="$router.push('/adminPrice')"> 
        <svg xmlns="http://www.w3.org/2000/svg" 
          height="24px" viewBox="0 -960 960 960" 
          width="24px" fill="#e3e3e3">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg> 
      </button>
    <div class="addItem">
      <div class="form-box">

        <form @submit.prevent="editItem">
          <br>
          <p>Enter new price:</p>
          <br>

          <select v-model="selectedItem" class="btn" required>
            <option value="">-- Select an item --</option>
            <option v-for="price in prices" :key="price.RiceType" :value="price.RiceType">
              {{ price.RiceType }}
            </option>
          </select>
          <br>

          <input
            type="number"
            step="0.01"
            class="input-field"
            placeholder="Price"
            v-model.number="FormDataP.newPrice"
            required
          />
          <br>

          <button type="submit" class="btn">Change</button>
        </form>
      </div>
    </div>
  </div>

  <div v-if="$route.path === '/deleteItem'">
    <button class="btn" @click.prevent="$router.push('/adminPrice')"> 
        <svg xmlns="http://www.w3.org/2000/svg" 
          height="24px" viewBox="0 -960 960 960" 
          width="24px" fill="#e3e3e3">
          <path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/>
        </svg> 
      </button>
    <div class="addItem">
      <div class="form-box">

        <form @submit.prevent="delItem">
          <br>
          <p>Delete price of:</p>
          <br>

          <select v-model="selectedItem" class="btn" required>
            <option value="" disabled>-- Select an item --</option>
            <option v-for="price in prices" :key="price.RiceType" :value="price.RiceType">
              {{ price.RiceType }}
            </option>
          </select>
          <br>

          <button type="submit" class="btn">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</template>

<script>
import { getToken, removeToken } from "@/utils/auth";

export default {
name: "adminPrice",
data(){
  return{
    urlappphp: "https://star-panda-literally.ngrok-free.app/app.php",
    prices: [],
    priceColumns: [],
    selectedItem: null,
    FormDataP: {
      newPrice: "",
    },
    searchQuery: '',
    filteredPrices: [],
    isLoading: true
  }
},

created() {
    this.filteredPrices = [...this.prices];
  },

methods: {

  async getPrices(){
    try {

      const token = getToken();
      if (!token) {
        console.error("No token found, redirecting to login.");
        this.$router.replace("/login");
        return;
      }
      
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

  async logout() {
    try {
      removeToken();
      this.localUserData = {};
      this.$router.replace("/");
    } catch (error) {
      console.error("Logout error:", error);
    }
  },

  async addItem(){
    this.isLoading = true;
    try {
      const newItemName = this.FormDataP.newItem.trim();
      if (!newItemName) {
        console.error("Item name cannot be empty.");
        alert("Please enter an item name."); // error feedback
        return;
      }

      const token = getToken();
      if (!token) {
        console.error("No token found, redirecting to login.");
        this.$router.replace("/login");
        return;
      }

      const response = await fetch(this.urlappphp, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ action: "addItems", itemName: newItemName }),
      });

      const result = await response.json();

      if (result.success) {
        this.FormDataP.newPrice = ''; // clear the form
        alert("Successfully added item.");
        this.$router.push('/adminPrice'); // go back to the price list
      } else {
        console.error("Failed to add item:", result.error);
      }
    } catch (error) {
      console.error("Error adding items:", error);
    } finally {
      this.isLoading = false;
    }
  },

  async editItem(){
    this.isLoading = true;
    try {

      if (!this.selectedItem || !this.FormDataP.newPrice) {
          alert("Please select an item and enter a price.");
          return;
      }

      const token = getToken();
      if (!token) {
        console.error("No token found, redirecting to login.");
        this.$router.replace("/login");
        return;
      }

      const response = await fetch(this.urlappphp, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ action: "changePrice", 
              itemName: this.selectedItem, 
              newPrice: this.FormDataP.newPrice,
              sourceUrl: "/addedadmin" }),
      });

      const result = await response.json();

      if (result.success) {
        this.FormDataP.newPrice = ''; // clear the form
        this.selectedItem = null;
        alert("Successfully edited item.");
        this.$router.push('/adminPrice'); // go back to the price list
      } else {
        console.error("Failed to edit item:", result.error);
      }
    } catch (error) {
      console.error("Error editing items:", error);
    } finally {
      this.isLoading = false;
    }
  },

  async delItem(){
    this.isLoading = true;
    try {

      if (!this.selectedItem) {
          alert("Please select an item.");
          return;
      }

      const token = getToken();
      if (!token) {
        console.error("No token found, redirecting to login.");
        this.$router.replace("/login");
        return;
      }

      const response = await fetch(this.urlappphp, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ action: "delItem", 
        itemName: this.selectedItem, }),    
      });

      const result = await response.json();

      if (result.success) {
        this.selectedItem = null;
        alert("Successfully deleted item.");
        this.$router.push('/adminPrice'); // go back to the price list
      } else {
        console.error("Failed to delete item:", result.error);
      }
    } catch (error) {
      console.error("Error deleting items:", error);
    } finally {
      this.isLoading = false;
    }
  },

  showSidebar() {
    this.$refs.sidebar.style.display = "flex";
  },

  hideSidebar() {
    this.$refs.sidebar.style.display = "none";
  },

  handleEditClick(price) {
    console.log('Editing price:', price);
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
}

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
  background-color: #4a5568;  /* pressed state */
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

.clickable-cell {
  cursor: pointer;
  padding: 12px 15px; /* table cell padding */
}

.header-section {
  display: flex;
  align-items: center;      /* vertical alignment */
  justify-content: space-between; /* put title left, button right */
  margin-bottom: 24px;     
  gap: 16px; 
  padding: 8px 0;
  color: #ffffff;
}

.section-title {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
  color: #ffffff; 
}

.btn {
  margin-top: 10px;
  padding: 10px 20px;
  background: #ffe082;
  color: #001821;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.btn:hover {
  background: #ffd448; 
  color: #001821; 
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.btn-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  border-radius: 4px;
}

.btn svg {
  fill: #000000;
}

.list-price-container {
  width: 100%;
  overflow: hidden;
  background-color: #232831;
  border-radius: 15px;  /* rounded corners */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  margin: 15px auto; /* center   */
  padding: 15px;
}

.price-list {
  width: 100%;
  overflow: hidden;
}

.scroll {
  max-height: 500px; 
  overflow-y: auto;
  position: relative;
  border-radius: 4px; /* rounded corners */
}

.main-content {
  position: relative; /* above overlay */
  z-index: 2;
  padding-top: 0;
  margin-top: -180px;
  align-items: center;
  justify-content: center;
  display: flex;
  min-height: calc(100vh - 50px);
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 15px;
  padding-right: 15px;
}

.table-content {
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 0.9rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-width: 900px;
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

.addItem {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 20px;
  margin: 10px ;
  max-width: 400px;
  width: 90%;
  color: white;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #232831;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  box-sizing: border-box;
  min-height: auto;
  max-height: 475px;
  align-self: center;
  margin-top: 125px;
  margin-bottom: 0;
  font-size: 17px;
}

.form-box {
  width: 100%;
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

.top-text {
  position: relative; /* above overlay */
  z-index: 2;
  color: #ffffff;
  text-align: center;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  padding-top: 100px;
  font-size: 2rem;
  margin-bottom: 20px;
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