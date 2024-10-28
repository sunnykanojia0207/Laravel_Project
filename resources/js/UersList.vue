<template>
   <div v-if="!errorOccurred">
  <div class="user-list">
    <h2>User List</h2>
    <!-- Search box -->
    <div class="search-box">
      <input type="text" v-model="searchQuery" placeholder="Search...">
      <!-- Date search -->
      <input type="date" v-model="searchDate">
      <!-- Selection box -->
      <select class="selection-box" v-model="exportFormat">
        <option value="csv">Select Option</option>
        <option value="csv">Export to CSV </option>
      </select>
      <button class="export-btn" @click="exportSelectedUsers">Export</button>
    </div>

    <table>
      <!-- Table header -->
      <thead>
        <tr>
          <th><input type="checkbox" @click="selectAllUsers"></th>
          <th>ID</th>
          <th>Profile Photo</th>
          <th>Fullname</th>
          <th>Lastname</th>
          <th>Email</th>
          <th>Joining Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <!-- Table body -->
      <tbody>
        <tr v-for="user in paginatedUsers" :key="user.id">
          <td><input type="checkbox" v-model="selectedUsers" :value="user.id"></td>
          <td>{{ user.id }}</td>
          <td>
            <img :src="getUserProfileImageUrl(user.userinfo.image)" alt="Profile Image" width="60" height="60"
              @click="openModal(user)">
          </td>
          <td v-html="highlightText(user.fullname)"></td>
          <td v-html="highlightText(user.lastname)"></td>
          <td v-html="highlightText(user.email)"></td>
          <td v-html="highlightText(user.userinfo.joindate)"></td>
          <td>
            <button class="bi bi-pencil-square edit-btn" @click="editUser(user)"></button>
            <button class="bi bi-trash delete-btn" @click="deleteUser(user.id)"></button>
            <button class="bi bi-person-fill view-btn" @click="viewProfile(user.id)"></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div v-if="!errorOccurred">
    <!-- Pagination -->
    <div class="pagination">
      <button class="prev-btn" @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <div class="page-numbers">
        <button v-for="pageNumber in totalPages" :key="pageNumber" :class="{ 'active': pageNumber === currentPage }"
          @click="goToPage(pageNumber)">
          {{ pageNumber }}
        </button>
      </div>
      <button class="next-btn" @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>
</div>
    <Modal :showModal="showModal" :selectedUserPhoto="selectedUserPhoto" @closeModal="showModal = false" />
  </div>
  <div v-if="!errorOccurred">
  <div class="charts-container">
    <div class="chart1">
      <highcharts :options="pieChartOptions"></highcharts>
    </div>
    <div class="chart2">
      <highcharts :options="barChartOptions"></highcharts>
    </div>
  </div>
  </div>

  <div v-else>
    <div class="spinner-border " role="status">
</div>
 <h2 >Loading Data</h2>

  </div>
</template>

<script>
import axios from 'axios';
import * as bootstrap from 'bootstrap';
import Modal from './Modal.vue';
import { Chart } from 'highcharts-vue';
import Highcharts from 'highcharts';

// Radialize the colors
Highcharts.setOptions({
  colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
      radialGradient: {
        cx: 0.5,
        cy: 0.3,
        r: 0.7
      },
      stops: [
        [0, color],
        [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
      ]
    };
  })
});

export default {
  components: {
    bootstrap,
    Modal,
    highcharts: Chart
  },
  data() {
    return {
      errorOccurred: true,
      userList: [],
      currentPage: 1,
      perPage: 5,
      searchQuery: '',
      searchDate: '',
      selectedUsers: [],
      exportFormat: 'csv',
      selectedUserPhoto: '',
      showModal: false,
      pieChartOptions: {
        chart: {
          type: 'pie',
          width: 500,
          height: 275,
        },
        title: {
          text: 'Users Joining Date (Pie Chart)'
        },
        series: [{
          name: 'Date',
          data: []
        }]
      },
      barChartOptions: {
        chart: {
          type: 'bar',
          width: 500,
          height: 275,
        },
        title: {
          text: 'Users Joining Date (Bar Chart)'
        },
        xAxis: {
          categories: [],
          title: {
            text: 'Joining Date'
          }
        },
        yAxis: {
          title: {
            text: 'Number of Users'
          }
        },
        series: [{
          name: 'Date',
          data: []
        }]
      }


    };
  },
  computed: {
    paginatedUsers() {
      let filteredUsers = this.userList;
      if (this.searchQuery.trim() !== '') {
        filteredUsers = filteredUsers.filter(user => this.userContainsQuery(user, this.searchQuery));
      }
      if (this.searchDate) {
        filteredUsers = filteredUsers.filter(user => user.userinfo.joindate === this.searchDate);
      }

      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return filteredUsers.slice(startIndex, endIndex);
    },
    totalPages() {
      let filteredUsers = this.userList;
      if (this.searchQuery.trim() !== '') {
        filteredUsers = filteredUsers.filter(user => this.userContainsQuery(user, this.searchQuery));
      }
      if (this.searchDate) {
        filteredUsers = filteredUsers.filter(user => user.userinfo.joindate === this.searchDate);
      }
      return Math.ceil(filteredUsers.length / this.perPage);
    }
  },
  methods: {
    editUser(user) {
      this.$router.push(`/edit/${user.id}`);
      console.log("Editing user:", user);
    },
    deleteUser(userId) {
      if (confirm('Are you sure you want to delete this user?')) {
        axios.delete(`/api/user/${userId}`)
          .then(response => {
            console.log("User deleted successfully:", response.data);
            this.userList = this.userList.filter(user => user.id !== userId);
          })
          .catch(error => {
            console.error('Error deleting user:', error);
          });
      }
    },
    viewProfile(userId) {
      this.$router.push(`/viewprofile/${userId}`);
      console.log("Viewing profile of user with ID:", userId);
    },
    getUserProfileImageUrl(imageFileName) {
      if (!imageFileName || imageFileName.trim() === '' || imageFileName == null) {
        return `${window.baseurl}/images/defaultimage.jpg`;
      } else {
        return `${window.baseurl}/images/${imageFileName}`;
      }
    },
    userContainsQuery(user, query) {
      const userData = Object.values(user).join(' ').toLowerCase();
      return userData.includes(query.toLowerCase());
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    goToPage(pageNumber) {
      this.currentPage = pageNumber;
    },
    fetchUserList() {
      axios.get('/api/userlist')
        .then(response => {
          //console.log(response);
          this.errorOccurred = false;
          this.userList = response.data.users;
          // Count the frequency of each date
          const userCounts = {};
          this.userList.forEach(user => {
            const date = user.userinfo.joindate;// Extract date part
            if (userCounts[date]) {
              userCounts[date]++;
            } else {
              userCounts[date] = 1;
            }
          });

          // // Extract dates and counts from the userCounts object
          // const dates = Object.keys(userCounts);
          // const counts = Object.values(userCounts);


          //  this.chartOptions.name= dates;
          // this.chartOptions.series[0].data = counts;


          // Extract dates and counts from the userCounts object
          const dates = Object.keys(userCounts);
          const counts = Object.values(userCounts);
          const pieChartData = dates.map((date, index) => ({
            name: date,
            y: counts[index]
          }));

          const barChartData = dates.map((date, index) => counts[index]);

          this.pieChartOptions.series[0].data = pieChartData;
          this.barChartOptions.xAxis.categories = dates;
          this.barChartOptions.series[0].data = barChartData;
        })
        .catch(error => {
          // console.error('Error fetching user list:', error);
          if (error.response.status === 403) {
            this.errorOccurred = true;
            alert('You must be 18 or older to access the user list.');
            //this.$router.push(`/`);
          }
         else if (error.response.status === 401) {
           this.errorOccurred = true;
            alert('User not authenticated');
          } 
          else {
            console.error('Error fetching user list:', error);
          }
        });
    },
    highlightText(text) {
      if (!this.searchQuery) return text;
      const pattern = new RegExp(`(${this.searchQuery})`, 'gi');
      return text.replace(pattern, '<span class="highlighted">$1</span>');
    },
    selectAllUsers(event) {
      if (event.target.checked) {
        this.selectedUsers = this.userList.map(user => user.id);
      } else {
        this.selectedUsers = [];
      }
    },
    exportSelectedUsers() {
      if (this.selectedUsers.length === 0) {
        alert('No users selected for export.');
        return;
      }
      if (confirm('Are you sure you want to download the data')) {
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "ID,Fullname,Lastname,Email,Phone Number,Gender,City,State,Country,Image\n";
        this.selectedUsers.forEach(userId => {
          const user = this.userList.find(u => u.id === userId);
          const row = `${user.id},${user.fullname},${user.lastname},${user.email},${user.userinfo.phoneno},${user.userinfo.Gender},${user.userinfo.City},${user.userinfo.state},${user.userinfo.country},${user.userinfo.image}\n`;
          csvContent += row;
        });
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "selected_users.csv");
        document.body.appendChild(link);
        link.click();
      }
    },
    openModal(user) {
      this.selectedUserPhoto = this.getUserProfileImageUrl(user.userinfo.image);
      this.showModal = true;
    }
  },
  mounted() {
    this.fetchUserList();
  }
};
</script>


<style scoped>
.user-list {
  margin: 0 auto;
  max-width: 1000px;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-family: Arial, sans-serif;
  background-color: #fffafa;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

th,
td {
  padding: 12px;
  border: 1px solid #ddd;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}

button {
  padding: 8px 16px;
  margin-right: 5px;
  cursor: pointer;
  border: none;
  border-radius: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

.bi {
  font-size: 1.2rem;
  /* Adjust the size of icons */
}


.bi-pencil-square:hover {
  background-color: #67e84d;
  box-shadow: none;
  /* Remove drop shadow on hover */
}

.bi-trash:hover {
  background-color: rgb(205, 93, 93);
  box-shadow: none;
  /* Remove drop shadow on hover */
}

.bi-person-fill:hover {
  background-color: #54d1df;
  box-shadow: none;
  /* Remove drop shadow on hover */
}

.pagination {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

.page-numbers {
  display: flex;
}

.page-numbers button {
  background-color: transparent;
  border: none;
  cursor: pointer;
  padding: 5px 10px;
  margin: 0 1px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

.page-numbers button.active {
  background-color: #007bff;
  color: white;
  border-radius: 10%;
}

.prev-btn,
.next-btn {
  margin-left: 5px;
  background-color: #4b84c1;
  color: white;
  border-radius: 4px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

.prev-btn:hover,
.next-btn:hover {
  background-color: #1b4875;
}

.search-box {
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

.search-box input {
  padding: 8px;
  margin-right: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  flex: 1;
}

.highlighted {
  background-color: yellow;
}

.export-btn {
  margin-left: 10px;
  padding: 10px 20px;
  /* Adjust padding */
  background-color: #3d95a8;
  color: white;
  border: none;
  border-radius: 1px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  /* Add transition effect */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  /* Add drop shadow */
}

.export-btn:hover {
  background-color: #4dc6e1;
  /* Change background color on hover */
}

.export-btn:active {
  transform: translateY(1px);
  /* Add slight push down effect when button is clicked */
}

.selection-box {
  font-size: 80%;
  margin-left: 1px;
  height: 32px;
  width: 100px;
}

.modal-dialog {
  width: 50%;
  max-width: 50%;
  max-height: 100%;
}

.modal-image {
  max-width: 50%;
  max-height: 50%;
}

.charts-container {
  margin-top: 45px;
  margin-right: auto;
  display: flex;
  flex-direction: column;
}


.chart1,
.chart2 {
  margin-top: 10px;
  margin-left: 20px;
  margin-bottom: 20px;
  /* Adjust spacing between charts */
}
</style>
