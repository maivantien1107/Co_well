<template>
  <div class="user-manage">
    <!-- <TitlePage title="Quản lý users" icon="manage_accounts" /> -->
    <div class="user-content">
    </div>
    <template>
  <div class="center">
    <vs-table>
      <template #header>
            <div class="flex justify-between items-center m-2 mb-8 w-full" style="padding-top:50px;">
              <div
                @click="onCreate"
                class="flex items-center justify-center p-2 rounded cursor-pointer bg-gray-100 hover:bg-gray-200 border-blue-400 border-2"
              >
                <span class="material-icons text-green-600 mx-2">person_add</span>
                <span class="font-bold">Thêm người dùng</span>
              </div>
              <div>
                <vs-input type="text" v-model="searchFilter"   @keyup.enter="onSearch" border placeholder="Tìm kiếm theo email">
                  <template #icon>
                    <i class='bx bx-search'></i>
                  </template>
                </vs-input>
              </div>
            </div>
          </template>
      <template #thead>
        <vs-tr>
          <vs-th sort @click="users = $vs.sortData($event ,users, 'id')">
            STT
          </vs-th>
          <vs-th sort @click="users = $vs.sortData($event ,users, 'name')">
            Name
          </vs-th>
          <vs-th sort @click="users = $vs.sortData($event ,users, 'email')">
            Email
          </vs-th>
          <vs-th sort @click="users = $vs.sortData($event ,users, 'phone')">
            Phone
          </vs-th>
          <vs-th sort @click="users = $vs.sortData($event ,users, 'role_name')">
            Role
          </vs-th>
          <vs-th>Thao tác</vs-th>
        </vs-tr>
      </template>
      <template #tbody>
        <vs-tr
          :key="i"
          v-for="(tr, i) in $vs.getPage(users, page, max)"
          :data="tr"
        >
          <vs-td>
          {{ tr.id }}
          </vs-td>
          <vs-td>
            {{ tr.name }}
          </vs-td>
          <vs-td>
          {{ tr.email }}
          </vs-td>
          <vs-td>
          {{ tr.phone }}
          </vs-td>
          <vs-td>
          {{ tr.role_name }}
          </vs-td>
          <vs-td>
            <div class="center">
              <vs-row>
                <vs-button class="mr-2 text-blue-600 hover:text-black" @click="onEdit(tr.id)" flat icon>  
                <i class='bx bx-pencil' ></i>
              </vs-button>
              <vs-button class="text-red-400 hover:text-black" @click="onDelete(tr.id)" flat icon>  
                <i class='bx bxs-trash'></i>
              </vs-button>

              </vs-row>
            
            </div>
          </vs-td>
        </vs-tr>
      </template>
      <template #footer>
        <vs-pagination v-model="page" :length="$vs.getLength(users, max)" />
      </template>
    </vs-table>
  </div>
</template>
<vs-dialog width="550px" not-center v-model="active2">
        <div class="con-content">
          <p>
            Bạn có chắc chắn muốn xóa người dùng này?
          </p>
        </div>

        <template #footer>
          <div class="con-footer">
            <vs-button @click="actionDelete()" transparent>
              Ok
            </vs-button>
            <vs-button  @click="active2=false" dark transparent>
              Cancel
            </vs-button>
          </div>
        </template>
</vs-dialog>
<vs-dialog width="550px" not-center v-model="isShowDialog">
        <template #header>
          <h4 class="not-margin">
            {{isCreate ? 'Thêm người dùng':'Chỉnh sửa người dùng'}}
          </h4>
        </template>
        <UserDetail
        :user="user"
        @clearEvent="clearEvent"
        @actionCreate="actionCreate"
        @actionEdit="actionEdit"
        @actionDelete="onDelete"
      />
</vs-dialog>

  </div>
</template>

<script>
import { mapActions } from 'vuex'
import UserDetail from '@/components/admin/DetailUser.vue'
export default {
  name: 'UserManagePage',
  data() {
    return {
      active2:false,
      search:'',
      page:1,
      max:10,
      isShowDialog: false,
      isEdit: false,
      isCreate: false,
      users: [],
      selected: null,
      user: {},
      searchFilter: null
    }
  },
  components: {
    UserDetail
  },
  methods: {
    ...mapActions('user', {
      getUsers: 'getUsers',
      getUser: 'getUser',
      createUser: 'createUser',
      updateUser: 'updateUser',
      deleteUser: 'deleteUser',
      searchUser: 'searchUser'
    }),
    async onEdit(id) {
      const res = await this.getUser(id)
      this.user = res.data
      this.isEdit = true
      this.isCreate = false
      this.isShowDialog = true
    },
    onDelete(id) {
     this.selected=id
     this.active2=true
    },
    onCreate() {
      this.user = {}
      this.isCreate = true
      this.isEdit = false
      this.isShowDialog = true
    },
    clearEvent() {
      this.user = {}
      this.isCreate = false
      this.isEdit = false
      this.isShowDialog = false
      this.isDelete = false
    },
    async actionCreate() {
      await this.createUser(this.user)
      await this.fetchUsers()
      this.clearEvent()
    },
    async actionEdit() {
      await this.updateUser(this.user)
      await this.fetchUsers()
      this.clearEvent()
    },
    async actionDelete() {
      await this.deleteUser(this.selected)
      await this.fetchUsers()
      this.active2=false
      this.clearEvent()
    },
    async fetchUsers() {
      const users = await this.getUsers()
      this.users = users.data
    },
    async onSearch() {
      await this.searchUser({ email: this.searchFilter })
    }
  },
  async created() {
    await this.fetchUsers()
  }

}
</script>
<style>
  .con-footer{
    display: flex;
    align-items: center;
    justify-content: flex-end;
   
  }
  .con-footer  .vs-button{
    margin: 0px;
  }
      .vs-button__content{
        padding: 10px 30px;
      }
       
</style>