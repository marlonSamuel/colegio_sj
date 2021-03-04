import Axios from 'axios'
import router from '../router'
import store from '../store'
import toastr from 'toastr'

export default {

    data_refresh_token: {
        grant_type: 'refresh_token',
        refresh_token: '',
        client_id: '',
        cliente_secret: ''
    },

    permisos: [],
    getRefreshToken() {
        var token_data = JSON.parse(localStorage.getItem('token_data'))
        this.data_refresh_token.refresh_token = token_data.refresh_token
        this.data_refresh_token.client_id = store.state.client_id,
            this.data_refresh_token.client_secret = store.state.client_secret

        return this.data_refresh_token
    },

    saveToken(token) {
        store.dispatch('guardarToken', token)
    },

    noAcceso() {
        store.dispatch('logout')
        router.push('/login')
    },

    getUser() {
        let self = this
        store.state.services.loginService.me()
            .then(r => {
                store.dispatch('setUser', r.data.user)
                store.dispatch('setInstitucion', r.data.institucion)
                store.dispatch('setShowSideBar', r.data.user.rol.rol == 'admin' ? true : false)
                this.getMenus(r.data.user.rol_id)
                if(r.data.user.rol.rol == 'apoderado'){
                    self.getAlumnos(r.data.user.user_info.id)
                }
                self.getCicloActual()
            }).catch(e => {

            })
    },

    getCicloActual() {
        store.state.services.cicloService.actual()
            .then(r => {
                if (r.response) {
                    toastr.error('no existe ciclo actual, por favor active uno', 'error')
                    router.push('/ciclo')
                    return
                }
                store.dispatch('setCiclo', r.data)
            }).catch(e => {

            })
    },
    

    getMenus(id) {
        let self = this
        self.loading = true
        store.state.services.rolService
            .getMenus(id)
            .then(r => {
                self.loading = false
                if (r.response !== undefined) {
                    self.$toastr.error(r.response.data.error, 'error')
                    return
                }

                this.mapMenu(r.data)

            }).catch(e => {

            })
    },

    getAlumnos(id) {
        let self = this
        self.loading = true
        store.state.services.apoderadoService
            .getAlumnos(id)
            .then(r => {
                self.loading = false
                if (r.response !== undefined) {
                    self.$toastr.error(r.response.data.error, 'error')
                    return
                }
                console.log(r.data)
                store.dispatch('setAlumnos', r.data)
            }).catch(e => {

            })
    },

    mapMenu(items) {
        var menu = []
        var permissions = []
        items.forEach(function(item) {
            permissions.push(item.name)
            if (item.father === 0 && !item.hide) {
                var object = new Object
                object.icon = item.icon
                object.text = item.text
                object.path = item.path
                object.name = item.name
                object.model = true
                object.children = []
                items.forEach(function(child, i) {
                    if (item.id === child.father && !child.hide) {
                        var object2 = new Object()
                        object2.icon = child.icon
                        object2.text = child.text
                        object2.path = child.path

                        object.children.push(object2)
                    }
                });
                menu.push(object)
            }
        })
        store.dispatch('setMenu', menu)
        store.dispatch('setPermisos', permissions)
    }
}