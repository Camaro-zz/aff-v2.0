import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'


Vue.use(VueRouter)

Vue.use(VueResource)

/* eslint-disable no-new */

var router = new VueRouter({
    history: true,
    root: 'admin'
})


router.map({
    '/': {
        component: require('./components/Home.vue')
    },
    '': {
        component: require('./components/Home.vue')
    },
    '/users': {
        component: require('./components/Users.vue')
    },
    '/campaigns': {
        component: require('./components/Campaigns.vue')
    },
    '/campaigns/:hashid/edit': {
        name: 'campaigns',
        component: require('./components/Campinfo.vue')
    },
    '/profile': {
        component: require('./components/Profile.vue')
    },
    '/setting': {
        component: require('./components/Setting.vue')
    },
    '/logs': {
        component: require('./components/Logs.vue')
    },
    '/users/camp/:hashid': {
        name: 'camp_user',
        component: require('./components/CampUsers.vue')
    },
})

router.alias({

    // alias can contain dynamic segments
    // the dynamic segment names must match
    '/posts/:hashid': '/posts/:hashid/edit',
    //'categories/:hashid': '/categories/:hashid/edit'
})

router.start(App, 'body')
