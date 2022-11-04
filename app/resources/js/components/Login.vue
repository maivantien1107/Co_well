<template>
    <!-- new -->
    <div>
      <div class="knd-form">
          <!-- input -->
          <div class="knd-form-x">
              <h3>Login Form</h3>
              <div class="knd-form-field">
                  <vs-input type="string" label="Username" v-model="username" placeholder="YourEmail@gmail.com">
                  </vs-input>
              </div>
  
         <!-- password -->
              <div class="knd-form-field">
                  <vs-input type="password" label="Password" v-model="password" label-placeholder="Password"
  
                  :visiblePassword="hasVisiblePassword"
                  icon-after
                  @click-icon="hasVisiblePassword = !hasVisiblePassword">
                      <template #icon>
                      <i v-if="!hasVisiblePassword" class='bx bx-show-alt'></i>
                      <i v-else class='bx bx-hide'></i>
                      </template>
                  </vs-input>
              </div>
  
              <div class="knd-field-group">
              <vs-button @click="onLogin()" >Login</vs-button>
              </div>
              <span>Don't have an account?<router-link to="/signup"> <a >Sign up</a></router-link></span>
  
          </div>
  
      </div>
      <vs-dialog width="550px" not-center v-model="isEnterCode">
        <div class="con-content">
          <p>
            Ma xac nhan cua ban la?
          </p>
          <vs-input type="string" label="OTP" v-model="otp" placeholder=""></vs-input>
        </div>

        <template #footer>
          <div class="con-footer">
            <vs-button @click="onVerify()" transparent>
              Xac nhan
            </vs-button>
            <vs-button  @click="isEnterCode=false" dark transparent>
              Cancel
            </vs-button>
          </div>
        </template>
</vs-dialog>
    </div>

</template>
<script>
  import { mapActions } from 'vuex'
  export default {
    name: 'Login',
    data() {
      return {
        isEnterCode:false,
        otp:null,
        username: null,
        password: null,
        isResetPassword: false,
        hasVisiblePassword: false
      }
    },
    methods: {
      ...mapActions('auth', {
        login: 'login',
        verifyotp:'verifyotp',
        resetPassword: 'resetPassword'
      }),
      async onLogin() {
        this.isEnterCode=true;
        const customer={
          username:this.username,
          password:this.password
        }
        const res = await this.login(customer)
      if (res) {
        this.isEnterCode = true
      }
     
      },
      async onVerify(){
        const res = await this.verifyotp({
        username: this.username,
       otp:this.otp
      })
      if (res) {
        this.isEnterCode = false
        this.$router.push('/admin-user')
      }
      },
    },
    created() {
      if (this.$store.state.auth.token || localStorage.getItem('tokenAdmin')) this.$router.push('/dashboard')
    },
    computed: {
        validEmail() {
          return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)
        },
      }
  }
  </script> 

<style>
    .knd-form{
        height: 80vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .knd-form-field {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }
    .knd-form-x {
        padding: 1.25rem;
        background-color: aquamarine;
    }
    .vs-input {
        width: 400px !important;
    }
    .knd-field-group {
        display: flex;
        align-items: center;
        flex-direction: row;
        margin-top: 0.50rem;
    }
    .knd-field-group a{
        text-decoration: none !important;
        text-transform: capitalize;
        margin-left: 0.25rem;
        cursor: pointer;
    }
</style>