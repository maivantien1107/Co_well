import axios from '@/axios'

export default {
  getUsers() {
    return axios.get('api/admin')
  },
  getUser(userId) {
    return axios.get(`api/admin/${userId}`)
  },
  updateUser(user) {
    return axios.put(`api/admin/update-user/${user.id}`, user)
  },
  createUser(user) {
    return axios.post('api/admin/create-user', user)
  },
  deleteUser(userId) {
    return axios.delete(`api/admin/delete/${userId}`)
  },
  searchUser(emailFilter) {
    return axios.post(`api/admin/search`, emailFilter)
  },
  changePassword(data) {
    return axios.put(`api/user/change-password-profile`, data)
  }
}