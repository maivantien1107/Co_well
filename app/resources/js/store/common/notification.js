import notificationServices from '@/services/common/notification'

const state = () => ({
  admin: [],
})

const getters = {
  admin: state => state.admin,
  
}

const mutations = {
  SET_NOTIFICATIONS_ADMIN(state, payload) {
    state.admin = payload
  },
 
}

const actions = {
  // ADMIN
  async getNotificationsForAdmin({ commit }) {
    const res = await notificationServices.getNotificationsForAdmin().catch(() => {
      return { data: [] }
    })
    commit('SET_NOTIFICATIONS_ADMIN', res.data)
  },
  deleteNotificationsByAdmin(store, id) {
    return notificationServices.deleteNotificationsByAdmin(id)
  },
  
}

export default {
  namespaced: true,
  actions,
  getters,
  mutations,
  state
}