import { createRouter, createWebHashHistory } from 'vue-router'
import { ElMessage } from "element-plus";
const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "",
      redirect: { name: 'overview' }
    },
    {
      path: "/",
      name: "layout",
      component: () => import('../views/LayoutView.vue'),
      children: [
        {
          path: '/not-found',
          name: 'not-found',
          component: () => import('../views/NotFoundView.vue')
        },
      ]
    },
    {
      path: "/overview",
      name: "overview",
      component: () => import('../views/EmptyView.vue'),
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },



    // login
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { title: '登录', noNeedLayout: true }
    },
    // 404

    {
      path: '/:pathMatch(.*)*',
      redirect: { name: 'not-found' }
    }
  ]
})

router.onError((err) => {
  ElMessage.error(err.message)
  router.push({ name: 'not-found' })
})

export default router
