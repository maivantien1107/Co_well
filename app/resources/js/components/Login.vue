<!-- 
<template>
    <div id="login" class="mx-2 mt-36">
      <div class="flex items-center border-2 rounded-xl py-20 mx-20 bg-gray-100">
        <vs-col vs-w="6" class="flex justify-center items-center">
          <img src="@/assets/img/logo.svg" alt="" class="w-80" />
        </vs-col>
        <vs-col vs-w="4" class="border-l-2 border-gray-200">
          <div class="flex flex-col">
            <div class="title text-center font-bold text-2xl">Đăng nhập quản trị viên</div>
            <div class="flex flex-col justify-center items-center mt-4">
              <vs-input label="Email" class="w-3/4 mt-6" v-model="email" />
              <vs-input type="password" label="Mật khẩu" class="w-3/4 mt-4" v-model="password" />
            </div>
            <div class="flex justify-evenly items-center mt-8">
              <vs-button @click="handleLogin">Đăng nhập</vs-button>
              <span class="cursor-pointer hover:text-gray-600" @click="onResetPassword">Quên mật khẩu ?</span>
            </div>
          </div>
        </vs-col>
      </div>
      <div class="dialog-reset">
        <vs-popup title="Nhận email đă đăng kí trong hệ thống" :active.sync="isResetPassword" button-close-hidden>
          <vs-input placeholder="Nhập địa chỉ email" v-model="email" />
          <p class="text-red-400">
            Chúng tôi sẽ gửi thư để lấy lại mật khẩu cho tài khoản của bạn vào email được nhập, nếu như email đã được đăng
            kí. Hãy vui lòng nhập chính xác !
          </p>
          <div class="flex justify-end items-center mt-4">
            <vs-button color="primary" icon="email" class="mr-6" @click="resetPassword({ email })">Gửi</vs-button>
            <vs-button color="lightgray" @click="isResetPassword = false">Thoát</vs-button>
          </div>
        </vs-popup>
      </div>
    </div>
  </template>
  
  <script>
  import { mapActions } from 'vuex'
  export default {
    name: 'Login',
    data() {
      return {
        email: null,
        password: null,
        isResetPassword: false
      }
    },
    methods: {
      ...mapActions('auth', {
        login: 'login',
        resetPassword: 'resetPassword'
      }),
      async handleLogin() {
        const { data } = await this.login({
          email: this.email,
          password: this.password
        })
        this.$socket.emit('admin', data)
      },
      onResetPassword() {
        this.isResetPassword = true
      }
    },
    created() {
      if (this.$store.state.auth.token || localStorage.getItem('tokenAdmin')) this.$router.push('/dashboard')
    }
  }
  </script> -->
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
<!-- <script>
import axios from 'axios';
export default {
    data:() =>  ({
        'email': '',
        'password': '',
        hasVisiblePassword: false
    }),
    methods: {
        onSubmit() {
            const data = {
                email: this.email,
                password: this.password,
            }
            axios.post('api/login', data, {}).then((res) => {
                this.$vs.notification({
                    flat: true,
                    color:'success',
                    position:'top-center',
                    title: 'Login Successful',
                    // text: res.data.access_token,
                    text: "Welcome to my page !",
                })
            }).catch((err) => {
                this.$vs.notification({
                    flat: true,
                    color:'danger',
                    position:'top-center',
                    title: 'Login Fail',
                    // text: err.response.data.error,
                    text: "Email or Password incorrect !",
                })
            });
        }
    },
    computed: {
        validEmail() {
          return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)
        },
      }
}
</script> -->

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