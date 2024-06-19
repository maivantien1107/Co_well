<template>
  <div class="hidden">
    <vs-sidebar
      absolute
      v-model="active"
      open
      >
      <template #logo>
        <!-- ...img logo -->
      </template>
      <div class="items" v-for="(item, index) in items" :key="index">
      <ItemSideBar @selectItemSidebar="selectItemSidebar" :item="item" :itemSelected="itemSelected" />
    </div>
      
      
      
      <template #footer>
        <vs-row justify="space-between">
          <vs-avatar>
            <img src="/avatars/avatar-5.png" alt="">
          </vs-avatar>

          <vs-avatar badge-color="danger" badge-position="top-right">
            <i class='bx bx-bell' ></i>

            <template #badge>
              28
            </template>
          </vs-avatar>
        </vs-row>
      </template>
    </vs-sidebar>
  </div>
</template>

<script>
import ItemSideBar from './ItemSidebar.vue'
import items from './sidebar-items'
export default {
  name: 'Sidebar',
  data() {
    return {
      active: 'home',
      itemSelected: null,
      items
    }
  },
  components: {
    ItemSideBar,

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
