<template>
  <div class="edit-form">
    <div class="container" v-if="userDataLoaded">
      <!-- ID input -->
      <!-- <div class="mb-3">
        <label for="id"><b>ID</b></label>
        <input type="text" placeholder="ID" v-model="id" readonly>
      </div> -->

      <!-- Full Name input -->
      <div class="mb-3">
        <label for="fullname"><b>Full Name</b></label>
        <input type="text" placeholder="Enter Full Name" v-model="fullname">
      </div>

      <!-- Last Name input -->
      <div class="mb-3">
        <label for="lastname"><b>Last Name</b></label>
        <input type="text" placeholder="Enter Last Name" v-model="lastname">
      </div>

      <!-- Email input -->
      <div class="mb-3">
        <label for="email"><b>Email</b></label>
        <input type="email" placeholder="Enter Email" v-model="email">
      </div>

      <!-- Phone Number input -->
      <div class="mb-3">
        <label for="phoneno"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter Phone Number" v-model="phoneno">
      </div>

      <!-- Gender input -->
      <div class="mb-3">
        <label for="gender"><b>Gender</b></label>
        <input type="text" placeholder="Enter Gender" v-model="gender">
      </div>

      <!-- City input -->
      <div class="mb-3">
        <label for="city"><b>City</b></label>
        <input type="text" placeholder="Enter City" v-model="city">
      </div>

      <!-- State input -->
      <div class="mb-3">
        <label for="state"><b>State</b></label>
        <input type="text" placeholder="Enter State" v-model="state">
      </div>

      <!-- Country input -->
      <div class="mb-3">
        <label for="country"><b>Country</b></label>
        <input type="text" placeholder="Enter Country" v-model="country">
      </div>

      <!-- Profile Photo input -->
      <!-- <div class="mb-3">
        <label for="image"><b>Profile Photo</b></label>
        <input type="file" accept="image/*" @change="onFileChange">
      </div> -->
      <div class="mb-3">
        <label for="image"><b>Profile Photo</b></label>
        <img :src="imagePreview ? imagePreview : getUserProfileImageUrl(image)" v-if="imagePreview || image" alt="Image Preview" class="profile-image" width="60" height="60"><br>
        <input type="file" accept="image/*" @change="onFileChange">
      </div>



      <!-- Update button -->
      <div class="mb-3">
        <button type="button" @click="updateUser">Update</button>
      </div>
    </div>
    <div v-else>
      <!-- Placeholder or loading indicator -->
      <p>Loading user data...</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      id: '',
      fullname: '',
      lastname: '',
      email: '',
      phoneno: '',
      gender: '',
      city: '',
      state: '',
      country: '',
      image: null,
      imagePreview: null,
      userDataLoaded: false // Flag to indicate if user data is loaded
    };
  },
  methods: {
    fetchUserDetails() {
      const userId = this.$route.params.id;
      axios.get(`/api/userdetails/${userId}`)
        .then(response => {
          const userData = response.data;
          console.log('User data:', userData);
          this.id = userData.id || '';
          this.fullname = userData.fullname || '';
          this.lastname = userData.lastname || '';
          this.email = userData.email || '';
          if (userData.userinfo) {
            this.phoneno = userData.userinfo.phoneno || '';
            this.gender = userData.userinfo.Gender || '';
            this.city = userData.userinfo.City || '';
            this.state = userData.userinfo.state || '';
            this.country = userData.userinfo.country || '';
            // this.image = userData.userinfo.image || null;
            // Check if user uploaded a new image, if not, use the previous image
            if (userData.userinfo.image) {
              this.image = userData.userinfo.image;
            } else {
              this.image = this.image || null;
            }
          }
          this.userDataLoaded = true; // Set flag to true once data is loaded
        })
        .catch(error => {
          console.error('Error fetching user details:', error);
        });
    },
    updateUser() {
      const formData = new FormData();
      formData.append('id', this.id);
      formData.append('fullname', this.fullname);
      formData.append('lastname', this.lastname);
      formData.append('email', this.email);
      formData.append('phoneno', this.phoneno);
      formData.append('gender', this.gender);
      formData.append('city', this.city);
      formData.append('state', this.state);
      formData.append('country', this.country);
      formData.append('image', this.image);
      //formData.append('image', this.image);

      axios.post('/api/updateuser', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then(response => {
          console.log('User updated successfully:', response.data);
          alert("Data Updated Successfully");
          // Optionally, redirect the user to another page after successful update
        })
        .catch(error => {
          console.error('Error updating user:', error);
          alert(error);
        });
    },
  
    onFileChange(event) {
  const selectedFile = event.target.files[0];
  if (selectedFile) {
    this.image = selectedFile;
    // Display a preview of the selected image
    const reader = new FileReader();
    reader.onload = (e) => {
      this.imagePreview = e.target.result;
    };
    reader.readAsDataURL(selectedFile);
  } else {
    // If no file is selected, retain the previous image
    this.image = this.image || null;
  }
},

    getUserProfileImageUrl(imageFileName) {
      return `${window.baseurl}/images/${imageFileName}`;
    }
  },
  mounted() {
    this.fetchUserDetails();
  }
}
</script>


<style scoped>
.edit-form {
  margin-top: 10px;
  margin-bottom:10px ;
  width: 70%;
  border: 3px solid #f1f1f1;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Add drop shadow */
  text-align: center; /* Center align content */
}

.container {
  padding: 16px;
}

.mb-3 {
  margin-bottom: 15px;
}

label {
  display: block;
  font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="file"],
select {
  width: 100%;
  padding: 10px;
  margin-top: 5px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); 
}

input[type="file"] {
  margin-top: 5px;
  margin-bottom: 10px;
  background-color: white;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); 
}

button:hover {
  opacity: 0.8;
}

.router-link {
  display: block;
  text-align: center;
  margin-top: 10px;
  color: #04AA6D;
  text-decoration: none;
}

.router-link:hover {
  text-decoration: underline;
}
</style>
