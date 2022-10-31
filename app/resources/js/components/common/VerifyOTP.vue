<template>
    <div class="center">
      <vs-dialog not-close v-model="active">
        <template #header>
          <h4 class="not-margin">
            Xác thực OTP
          </h4>
        </template>


        <div class="con-form">
            <span>Mã xác nhận</span>
          <vs-input  lable="OTP" v-model="otp" placeholder="OTP">
          </vs-input>
        </div>

        <template #footer>
          <div class="footer-dialog">
            <vs-button block @click="handleVerify">
              Xác nhận
            </vs-button>
          </div>
        </template>
      </vs-dialog>
    </div>
  </template>
  <script>
  import { mapActions } from 'vuex'
  export default {
    name: 'Verify',
    data() {
      return {
        active:true,
        otp: null,
        username: JSON.parse(localStorage.getItem('profile')),
      }
    },
    methods: {
      ...mapActions('auth', {
        verifyotp: 'verifyotp',
      }),
      async handleVerify() {
        const { data } = await this.verifyotp({
          otp: this.otp,
          username: this.username,
        })
      },
    },
    created() {
      if (this.$store.state.auth.token || localStorage.getItem('tokenAdmin')) this.$router.push('/admin-user')
    },
  }
  </script>
  <style >

  .not-margin{
    margin: 0px;
    font-weight: normal;
    padding: 10px;

  }
    
  .con-form{
    width: 100%;
  }
  .con-form .flex{
    display: flex;
      align-items: center;
      justify-content: space-between;
  }
      a{
        font-size :.8rem;
        opacity: .7;
      
      }
      a:hover{
        opacity :1;
      }
         
        
    .vs-checkbox-label{
        font-size: .8rem;
    }
     
    .vs-input-content{
        margin: 10px 0px;
      width :calc(100%);
    }
     
    .vs-input-content .vs-input{
        width: 100%;
      }
       
  .footer-dialog{
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    width: calc(100%);
  }
    
    .new{
        margin: 0px;
      margin-top :20px;
      padding: 0px;
      font-size: .7rem;
    }
    .vs-button{
        margin: 0px;
    }
     
  </style>