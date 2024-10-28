<template>
  <div class="profile-form">
    <div class="container">
      <h1>Welcome</h1>
      <h3>
        Fullname : {{ user.fullname }} <br>
        Lastname : {{ user.lastname }} <br>
        Email : {{ user.email }}
      </h3>
      <div class="mb-3">
        <label for="phone"><b>Phone No</b></label>
        <input type="text" placeholder="Enter Phone no" v-model="phoneno">
        <ul class="error-list" v-if="errorList.phoneno">
          <li class="error-item" v-for="(error, index) in errorList.phoneno" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <label for="gender"><b>Gender</b></label>
        <input type="text" placeholder="Enter Gender" v-model="gender">
        <ul class="error-list" v-if="errorList.gender">
          <li class="error-item" v-for="(error, index) in errorList.gender" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <label for="City"><b>City</b></label>
        <input type="text" placeholder="Enter City Name" v-model="city">
        <ul class="error-list" v-if="errorList.city">
          <li class="error-item" v-for="(error, index) in errorList.city" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <label for="state"><b>State</b></label>
        <input type="text" placeholder="Enter State Name" v-model="state">
        <ul class="error-list" v-if="errorList.state">
          <li class="error-item" v-for="(error, index) in errorList.state" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <label for="country"><b>Country</b></label>
        <input type="text" placeholder="Enter Country Name" v-model="country">
        <ul class="error-list" v-if="errorList.country">
          <li class="error-item" v-for="(error, index) in errorList.country" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>
      <div class="mb-3">
        <label for="image"><b>Image</b></label>
        <!-- <input type="file" placeholder="Upload Image" @change="handleFileUpload(this)"> -->
        <input type="file" placeholder="Upload Image" @change="handleFileUpload">
        <ul class="error-list" v-if="errorList.image">
          <li class="error-item" v-for="(error, index) in errorList.image" :key="index">
            {{ error }}
          </li>
        </ul>
      </div>

      <div class="mb-3">
        <button type="button" @click="registerInfo">Submit</button>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  data() {
    return {
      errorList:{},
      phoneno: '',
      gender: '',
      city: '',
      state: '',
      country: '',
      image: '',
      user: '',
    };
  },
  methods: {
    registerInfo() {
      var mythis = this;
      const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
      // userid = this.user.id;
      const payload = {};
      const formData = new FormData();
      formData.append("image", this.image);
      payload.image = formData;
      payload.phoneno = this.phoneno;
      payload.gender = this.gender;
      payload.city = this.city;
      payload.state = this.state;
      payload.country = this.country;
      payload.image = this.image;
      payload.userid =  this.user.id;
      axios.post('/api/profile', payload,config )
        .then(response => {
          console.log(response.data);
          alert("data update Successfully");
          this.$router.push('/userlist');
          // Optionally, handle success response
        })
        .catch(function (error) {
          if (error.response) {

            if (error.response.status == 422) {
              mythis.errorList = error.response.data.errors;
            }

            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            console.log(error.request);
          } else {
            console.log('Error', error.message);
          }
          console.error(error.response.data);
          // Optionally, handle error response
        });
    },
    handleFileUpload(event) {
      this.image = event.target.files[0];
    },
    getUser() {
      axios.get('/api/user') // Assuming '/api/fullname' is the endpoint to fetch the full name
        .then(response => {
          this.user = response.data.user;
        })
        .catch(error => {
          console.error('Error fetching user details:', error);
        });
    },
  },
  mounted() {
    this.getUser(); // Call getFullname method when the component is mounted
  }
}
</script>
<style scoped>
body {
  font-family: Arial, Helvetica, sans-serif;
}

.profile-form {
  margin-top: 10px;
  margin-bottom:10px ;
  width: 50%;
  border: 3px solid #f1f1f1;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Add drop shadow */
  text-align: center; /* Center align content */
}

.container {
  padding: 16px;
}

input[type=text],
input[type=file] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  /* Adjust width to fit both buttons */
}

button:hover {
  opacity: 0.8;
}

.error-list {
  list-style-type: none;
  /* Remove bullet points */
  padding: 0;
  /* Remove default padding */
}

.error-item {
  margin-bottom: 1px;
  /* Add margin between error messages */
  color: #721c24;
  /* Custom color for error messages */
  background-color: #f8d7da;
  /* Custom background color for error messages */
  border: 1px solid #f5c6cb;
  /* Custom border color for error messages */
  padding: 10px;
  /* Add padding to the error messages */
  border-radius: 5px;
  /* Add border radius to the error messages */
}

ul {
  margin-top: 5px;
}

input[type=file] {
  border: none;
  /* Remove default border for file input */
  background-color: #f1f1f1;
  /* Add background color */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}
</style>