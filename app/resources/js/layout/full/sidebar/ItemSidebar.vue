<template>
  <div
    @click="selectItemSidebar"
    class="sidebar-item rounded flex items-center p-2 mb-2 hover:bg-gray-200 cursor-pointer"
    :class="{ 'bg-gray-200': itemSelected.slug === item.slug }"
  >
    <vs-sidebar-item id="home">
        <template #icon>
          <i class='bx bx-home'></i>
        </template>
        {{itemSelected.lable}}
      </vs-sidebar-item>
  </div>
  
</template>


<script>
export default {
  name: 'SidebarItem',
  props: {
    item: Object,
    itemSelected: Object
  },
  methods: {
    selectItemSidebar() {
      if (this.itemSelected !== this.item) this.$emit('selectItemSidebar', this.item)
    }
  }
}
</script>