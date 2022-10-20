<template>
    <div class="customer-detail">
      <vs-input class="mb-2 w-1/6" label="ID" placeholder="id" v-if="user.id" :value="user.id" disabled />
      <vs-input class="mb-3 w-full" label="Tên" placeholder="Tên" v-model="user.name" />
      <vs-row>
        <label class="ml-1">Giới tính:</label>
        <vs-radio
          class="mx-2"
          v-for="(name, index) of sexType"
          :key="index"
          v-model="user.sex"
          vs-name="user.sex"
          :vs-value="index"
          >{{ name }}</vs-radio
        >
      </vs-row>
      <vs-input class="mb-4 mt-2 w-full" label="Số điện thoại" placeholder="Số điện thoại" v-model="user.phone" />
      <vs-input class="mb-4 mt-2 w-full" label="Email" placeholder="Email" v-model="user.email" />
      <vs-checkbox class="mb-4" v-if="user.id" v-model="user.is_verified" disabled>Đã xác thực</vs-checkbox>
      <!-- <vs-input
        class="mb-4 w-full"
        label="Mật khẩu"
        placeholder="Mật khẩu"
        v-if="!user.id"
        v-model="user.password"
      /> -->
      <vs-row>
        <label class="ml-1">Loại tài khoản:</label>
        <vs-radio
          class="mx-2"
          v-for="(name, index) of userType"
          :key="index"
          v-model="user.role_id"
          vs-name="user.role_id"
          :vs-value="index"
          >{{ name }}</vs-radio
        >
      </vs-row>
      <div class="mt-4 flex justify-end">
        <vs-button color="success" icon="assignment" v-if="user.id" @click="$emit('actionEdit')">Lưu</vs-button>
        <vs-button color="danger" icon="delete" v-if="user.id" class="mx-4" @click="$emit('actionDelete')"
          >Xoá</vs-button
        >
        <vs-button color="primary" icon="person_add" class="mr-2" v-else @click="$emit('actionCreate')">Thêm mới</vs-button>
        <vs-button color="lightgray" @click="$emit('clearEvent')">Thoát</vs-button>
      </div>
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
        srcPreviewAvatar: null
      }
    },
    methods: {
    }
  }
  </script>
  <style lang="scss" scoped>
  .con-vs-checkbox {
    justify-content: flex-start;
  }
  </style>