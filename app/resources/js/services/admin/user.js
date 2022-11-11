import axios from '@/axios'

export default {
  getUsers(data) {
    return axios.get(`api/admin?page=${data.page}&search=${data.search}&sort_direction=${data.sort_direction}&sort_field=${data.sort_field}`)
  },
  getAll(data){
    return axios.get(`api/admin/getAll?search=${data.search}&sort_direction=${data.sort_direction}&sort_field=${data.sort_field}`)
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
    return axios.get(`api/admin/search`, emailFilter)
  },
  changePassword(data) {
    return axios.put(`api/user/change-password-profile`, data)
  },
  exportUser(data){
    return axios.get(`api/admin/export`,data)
  }
}