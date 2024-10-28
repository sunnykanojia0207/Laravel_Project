<template>
  <div class="user-profile">
    <div class="profile-image">
      <img :src="getUserProfileImageUrl(image)" alt="Profile Image"  class="profile-image" width="120"
        height="120">
    </div>
    <div class="profile-details">
      <!-- <div class="profile-detail">
        <strong>ID:</strong> {{ id }}
      </div> -->
      <div class="profile-detail">
        <strong>Full Name:</strong> {{ fullname }}
      </div>
      <div class="profile-detail">
        <strong>Last Name:</strong> {{ lastname }}
      </div>
      <div class="profile-detail">
        <strong>Email:</strong> {{ email }}
      </div>
      <div class="profile-detail">
        <strong>Phone Number:</strong> {{ phoneno }}
      </div>
      <div class="profile-detail">
        <strong>Gender:</strong> {{ gender }}
      </div>
      <div class="profile-detail">
        <strong>City:</strong> {{ city }}
      </div>
      <div class="profile-detail">
        <strong>State:</strong> {{ state }}
      </div>
      <div class="profile-detail">
        <strong>Country:</strong> {{ country }}
      </div>
      <div class="profile-detail">
        <strong>Joining Date:</strong> {{ joindate }}
      </div>
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
      joindate:'',
      image: null,
      userDataLoaded: false
    };
  },
  methods: {
    fetchUserDetails() {
      const userId = this.$route.params.id;
      axios.get(`/api/userdetails/${userId}`)
        .then(response => {
          const userData = response.data;
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
            this.joindate = userData.userinfo.joindate || '';
            if (userData.userinfo.image) {
              this.image = userData.userinfo.image;
            } else {
              this.image = null;
            }
          }
          this.userDataLoaded = true;
        })
        .catch(error => {
          console.error('Error fetching user details:', error);
        });
    },
    getUserProfileImageUrl(imageFileName) {
  console.log("Image FileName:", imageFileName);
  if (!imageFileName || imageFileName.trim() === '' || imageFileName == null) {
    return `${window.baseurl}/images/defaultimage.jpg`;
  } else {
    return `${window.baseurl}/images/${imageFileName}`;
  }
}

  },
  mounted() {
    this.fetchUserDetails();
  }
};
</script>

<style scoped>
.user-profile {
  display: flex;
  align-items: center;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  max-width: 600px;
  background-color: lightblue;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);

}

.profile-image img {
  border-radius: 10%;
  box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.4);
}

.profile-image {
  margin-right: 20px;

}

.profile-details {
  flex-grow: 1;
}

.profile-detail {
  margin-bottom: 10px;
  color: rgb(29, 29, 29);
  font-size: large;
  font-style: normal;
  font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
}

.profile-detail strong {
  margin-right: 5px;
}
</style>