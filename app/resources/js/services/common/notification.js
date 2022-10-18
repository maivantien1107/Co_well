import axios from '@/axios'

export default {
  // ADMIN
  getNotificationsForAdmin() {
    return axios.get('api/personnel-notifications')
  },
  deleteNotificationsByAdmin(id) {
    return axios.get(`api/personnel-notifications/${id}`)
  },
  
}