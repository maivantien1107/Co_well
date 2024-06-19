<template>
    <div class="customer-detail">
      <vs-input class="mb-3 w-full" label="Tên" placeholder="Tên" v-model="user.name" />
      <vs-row>
        <label class="ml-1">Giới tính:</label>
        <div v-for="(name, index) of sexType" :key="index" >
          <vs-radio
          class="mx-2"
          v-model="user.sex"
          vs-name="user.sex"
          :val="index"
          >{{ name }}
          </vs-radio>
        </div>
      </vs-row>
    <br>
      <vs-input class="mb-4 mt-2 w-full" label="Số điện thoại" placeholder="Số điện thoại" v-model="user.phone"  type="text">
        <template v-if="user.phone !== ''" #message-success>
        </template>
        <template v-if="user.phone === ''" #message-danger>
          Phone Invalid
        </template>
      </vs-input>
      <vs-input class="mb-4 mt-2 w-full" label="Email" placeholder="Email" v-model="user.email">
        <template v-if="validEmail" #message-success>
        </template>  
        <template v-if="!validEmail && user.email !== ''" #message-danger>
          Email Invalid
        </template>
      </vs-input>
      <vs-checkbox class="mb-4" v-if="user.id" v-model="user.is_verified" disabled>Đã xác thực</vs-checkbox>
      <vs-input
        class="mb-4 w-full"
        label="Mật khẩu"
        placeholder="Mật khẩu"
        v-if="!user.id"
        v-model="user.password"
      >
      <template v-if="user.password !== ''" #message-success>
        </template>
      <template v-if="user.password === ''" #message-danger>
          Password Invalid
        </template>
    
    </vs-input>
      <vs-row>
        <label class="ml-1">Loại tài khoản:</label>
        <div v-for="(name, index) of userType" :key="index" >
          <vs-radio
          class="mx-2"
          v-model="user.role_id"
          vs-name="user.role_id"
          :val="index"
          >{{ name }}
          </vs-radio>
        </div>
      </vs-row>
      <template >
        <div class="mt-4 flex justify-end">
          <vs-row>
            <vs-button color="success" v-if="user.id" @click="$emit('actionEdit')">Lưu</vs-button>
        <vs-button color="danger" v-if="user.id" class="mx-4" @click="$emit('actionDelete')"
          >Xoá</vs-button
        >
        <vs-button color="primary"  class="mr-2" v-else @click="$emit('actionCreate')">Thêm mới</vs-button>
        <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
          </vs-row>
       
      </div>
        </template>
    </div>
  </template>
  
  <script>
  import { USER_TYPE, SEX_TYPE } from '@/constants/user'
  export default {
    name: 'UserDetail',
    props: {
      user: Object
    },
    data() {
      return {
        userType: USER_TYPE,
        sexType: SEX_TYPE,
      }
    },
    methods: {
    },
    computed: {
        validEmail() {
          return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.user.email)
        }
      }
  }
  </script>
  <style lang="scss" scoped>
  .con-vs-checkbox {
    justify-content: flex-start;
  }
  </style>