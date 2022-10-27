<!-- @format

<template>
  <div class="sidebar-container fixed shadow-2xl border-r-2 h-screen w-60 bg-gray-100">
    <div class="flex justify-center items-center">
      <img class="w-2/5 ml-4 mb-4 border-b-4 border-red-200" src="@/assets/img/logo.svg" alt="Logo" />
    </div>
    <div class="items" v-for="(item, index) in items" :key="index">
      <ItemSideBar @selectItemSidebar="selectItemSidebar" :item="item" :itemSelected="itemSelected" />
    </div>
  </div>
</template>

<script>
import ItemSideBar from './ItemSidebar.vue'
import items from './sidebar-items'
export default {
  name: 'Sidebar',
  data() {
    return {
      itemSelected: null,
      items
    }
  },
  components: {
    ItemSideBar
  },
  methods: {
    selectItemSidebar(item) {
      this.itemSelected = item
      this.$router.push(item.slug)
    }
  },
  created() {
    this.itemSelected = this.items.find(item => item.slug === this.$route.name)
    if (!this.itemSelected) this.itemSelected = { slug: this.$route.name }
  }
}
</script>

<style lang="scss" scoped>
.sidebar-container {
  box-sizing: border-box;
}
</style> -->
<template>
  <div class="hidden">
    <vs-sidebar
      absolute
      hover-expand
      reduce
      v-model="active"
      open
      >
      <template #logo>
        <!-- ...img logo -->
      </template>
      <vs-sidebar-item id="home">
        <template #icon>
          <i class='bx bx-home'></i>
        </template>
        Home
      </vs-sidebar-item>
      <vs-sidebar-item id="market">
        <template #icon>
          <i class='bx bx-grid-alt'></i>
        </template>
        Market Overview
      </vs-sidebar-item>
      <vs-sidebar-item id="Music">
        <template #icon>
          <i class='bx bxs-music'></i>
        </template>
        Music
      </vs-sidebar-item>
      <vs-sidebar-group>
        <template #header>
          <vs-sidebar-item arrow>
            <template #icon>
              <i class='bx bx-group'></i>
            </template>
            Social media
          </vs-sidebar-item>
        </template>

        <vs-sidebar-item id="Instagram">
          <template #icon>
            <i class='bx bxl-instagram'></i>
          </template>
          Instagram
        </vs-sidebar-item>
        <vs-sidebar-item id="twitter">
          <template #icon>
            <i class='bx bxl-twitter' ></i>
          </template>
          Twitter
        </vs-sidebar-item>
        <vs-sidebar-item id="Facebook">
          <template #icon>
            <i class='bx bxl-facebook' ></i>
          </template>
          Facebook
        </vs-sidebar-item>
      </vs-sidebar-group>
      <vs-sidebar-group>
        <template #header>
          <vs-sidebar-item arrow>
            <template #icon>
              <i class='bx bx-code-alt' ></i>
            </template>
            Coding
          </vs-sidebar-item>
        </template>

        <vs-sidebar-item id="github">
          <template #icon>
            <i class='bx bxl-github' ></i>
          </template>
          Github
        </vs-sidebar-item>
        <vs-sidebar-item id="codepen">
          <template #icon>
            <i class='bx bxl-codepen'></i>
          </template>
          Codepen
        </vs-sidebar-item>
        <vs-sidebar-item id="discord">
          <template #icon>
            <i class='bx bxl-discord'></i>
          </template>
          Discord
        </vs-sidebar-item>
        <vs-sidebar-item id="Javascript">
          <template #icon>
            <i class='bx bxl-javascript' ></i>
          </template>
          Javascript
        </vs-sidebar-item>
        <vs-sidebar-item id="git">
          <template #icon>
            <i class='bx bxl-git' ></i>
          </template>
          Git
        </vs-sidebar-item>
      </vs-sidebar-group>
      <vs-sidebar-item id="donate">
        <template #icon>
          <i class='bx bxs-donate-heart' ></i>
        </template>
        Donate
      </vs-sidebar-item>
      <vs-sidebar-item id="drink">
        <template #icon>
          <i class='bx bx-drink'></i>
        </template>
        Drink
      </vs-sidebar-item>
      <vs-sidebar-item id="shopping">
        <template #icon>
          <i class='bx bxs-shopping-bags'></i>
        </template>
        Shopping
      </vs-sidebar-item>
      <vs-sidebar-item id="chat">
        <template #icon>
          <i class='bx bx-chat' ></i>
        </template>
        Chat
      </vs-sidebar-item>
      <template #footer>
        <vs-row justify="space-between">
          <vs-avatar badge-color="danger" badge-position="top-right">
            <i class='bx bx-bell' ></i>

            <template #badge>
              28
            </template>
          </vs-avatar>

          <vs-avatar>
            <img src="/avatars/avatar-5.png" alt="">
          </vs-avatar>
        </vs-row>
      </template>
    </vs-sidebar>
  </div>
</template>
<script>
export default {
  data:() => ({
    active: 'home',
  })
}
</script>
