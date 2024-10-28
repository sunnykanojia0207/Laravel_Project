<template>
    <div class="modal" :class="{ 'show': showModal }" tabindex="-1" role="dialog" @click="closeModalOutside">
      <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Profile Photo</h5>
            <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex justify-content-center align-items-center">
            <img :src="selectedUserPhoto" alt="Profile Photo" class="modal-image">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      showModal: {
        type: Boolean,
        required: true
      },
      selectedUserPhoto: {
        type: String,
        default: ''
      }
    },
    methods: {
      closeModal() {
        this.$emit('closeModal');
      },
      closeModalOutside(event) {
        if (event.target === this.$el) {
          this.closeModal();
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Your component styles */
  .modal {
    display: none;
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
  }
  
  .modal.show {
    display: block;
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
  </style>
  