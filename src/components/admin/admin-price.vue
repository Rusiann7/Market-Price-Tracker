<template>

    <!-- Added Navigation Bar -->
    <nav>
    <ul class="sidebar" ref="sidebar">
      <li @click="hideSidebar">
        <a href="#"
          ><svg
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
          ></a
        >
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
          >Market Price Tracker-Admin</a
        >
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
        <a href="#"
          ><svg
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
          ></a
        >
      </li>
    </ul>
  </nav>

  <br>
  <br>
  <br>
  <br>
  <center><h1>Admin Price</h1></center>
  

<div class="main-content">
  <div v-if="$route.path === '/adminPrice'">
    <div class="list-price-container">
      <div class="header-section">
        <h2 class="section-title">Market Prices</h2>
        <button class="btn" @click.prevent="$router.push('/addItem')">
          <svg 
            width="16" 
            height="16" 
            viewBox="0 0 24 24" 
            fill="#000000" 
            xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4V20M4 12H20" stroke="white" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
        <button class="btn" @click.prevent="$router.push('/editItem')">
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            height="24px" viewBox="0 -960 960 960" 
            width="24px" 
            fill="#000000" 
            >
            <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h357l-80 80H200v560h560v-278l80-80v358q0 33-23.5 56.5T760-120H200Zm280-360ZM360-360v-170l367-367q12-12 27-18t30-6q16 0 30.5 6t26.5 18l56 57q11 12 17 26.5t6 29.5q0 15-5.5 29.5T897-728L530-360H360Zm481-424-56-56 56 56ZM440-440h56l232-232-28-28-29-28-231 231v57Zm260-260-29-28 29 28 28 28-28-28Z"/>
          </svg>
        </button>
      </div>
        <div class="price-list">
          <table class="table-content">
            <thead>
              <tr>
                <th>Products</th>
                <th>Price</th>
                <th>Source</th>
            </tr>
            </thead>
  
            <tbody>
              <tr v-for="price in prices" :key="price.RiceType + price.Source">
                <td>{{ price.RiceType }}</td>
                <td>₱{{ price.Price }}</td>
                <td><a :href="price.Source" target="_blank" rel="noopener noreferrer" @click.stop style="color: black;">Source</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </div>

  <div v-if="$route.path === '/addItem'">
    <div class="addItem">
      <div class="form-box">
      <form
        id="newItem"
        class="input-group"
        method="get"
        @submit.prevent="addItem"
      >
      <br>
        <p>Enter new item:</p>
        <br>
        <input
          type="text"
          class="input-field"
          placeholder="Item"
          v-model="FormDataP.newItem"
          required
        />

        <br>

        <button type="submit" class="btn" value="GET">Add</button>
      </form>
    </div>
  </div>
  </div>

  <div v-if="$route.path === '/editItem'">
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
</div>

</template>

<script>
import { getToken, removeToken } from "@/utils/auth";

export default {
  name: "adminPrice",
  data(){
    return{
      urlappphp: process.env.VUE_APP_URLAPPPHP,
      prices: [],
      priceColumns: [],
      selectedItem: null,
      FormDataP: {
        newPrice: "",
      },
    }
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

        } else {
          console.error("Failed to fetch Prices:", result.error);
        }
      } catch (error) {
        console.error("Error fetching Prices:", error);
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
      try {
        const newItemName = this.FormDataP.newItem.trim();
        if (!newItemName) {
          console.error("Item name cannot be empty.");
          alert("Please enter an item name."); // Simple feedback
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
          },
          body: JSON.stringify({ action: "addItems", itemName: newItemName }),
        });

        const result = await response.json();

        if (result.success) {
          this.FormDataP.newPrice = ''; // Clear the form
          this.$router.push('/adminPrice'); // Navigate back to the price list
        } else {
          console.error("Failed to add item:", result.error);
        }
      } catch (error) {
        console.error("Error adding items:", error);
      }
    },

    async editItem(){
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
          },
          body: JSON.stringify({ action: "changePrice", 
                itemName: this.selectedItem, 
                newPrice: this.FormDataP.newPrice,
                sourceUrl: "Edited by Admin" }),
                
        });

        const result = await response.json();

        if (result.success) {
          this.FormDataP.newPrice = ''; // Clear the form
          this.selectedItem = null;
          this.$router.push('/adminPrice'); // Navigate back to the price list
        } else {
          console.error("Failed to add item:", result.error);
        }
      } catch (error) {
        console.error("Error adding items:", error);
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
      // Implement your edit logic here, for example:
      // this.$router.push(`/edit-price/${price.id}`);
      // Or show a modal:
      // this.showEditModal(price);
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
  },
  mounted(){
    this.getPrices();
    document.addEventListener("click", this.handleClickOutside);
  },

    beforeUnmount() {
      document.removeEventListener("click", this.handleClickOutside);
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
  background-color: white;
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
  color: black;
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
  background-color: #f0f0f0;
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

.menu-btn {
  display: none;
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
  padding: 12px 15px; /* Match your table cell padding */
}

.header-section {
  display: flex;
  align-items: center;      /* Vertical alignment */
  justify-content: space-between; /* Pushes title left, button right */
  margin-bottom: 20px;     /* Spacing below header */
}

.btn {
  margin-top: 10px;
  padding: 10px 20px;
  background: #ffd700;
  color: #000;
  float: right;
  display: flex;
  align-items: center;
  gap: 8px;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background-color 0.2s;
}

.btn:hover {
  background: #ffc107;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.main-content {
  padding-top: 0;
  margin-top: -180px;
  align-items: center;
  justify-content: center;
  display: flex;
  min-height: calc(100vh - 50px);
}

.table-content {
  border-collapse: collapse;
  margin: 25px 0;
  font-size: 0.9rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-width: 750px;
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
  background-color: #f3f3f3; /* Changed to be different from odd rows */
}

.table-content th,
.table-content td {
  padding: 12px 15px;
}

.table-content tbody tr:last-of-type {
  border-bottom: #1e1e1e;
}

.addItem {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 20px;
  margin: 0 auto;
  max-width: 400px;
  width: 90%;
  color: white;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #2c2c2c, #333333);
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

.input-field {
  margin-bottom: 15px; /* Add space below each input field */
  margin-top: 15px;
  padding: 12px 15px;
  width: 100%;
  font-size: 16px;
  border-radius: 6px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}
</style>